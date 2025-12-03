<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exercise;
use App\Models\Reminder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    /**
     * Affiche la page de suivi
     */
    public function index()
    {
        // Statistiques générales
        $stats = [
            'avgScore' => $this->getAverageScore(),
            'sessionsThisWeek' => $this->getSessionsThisWeek(),
            'weeklyGoal' => 4,
            'adherence' => $this->calculateAdherence(),
            'totalSessions' => Exercise::count(),
            'bestScore' => Exercise::max('score') ?? 0,
        ];

        // Progression par type d'exercice
        $progressByType = $this->getProgressByType();

        // Historique des 30 derniers jours
        $history = $this->getHistory(30);

        // Notes des aidants récentes
        $carerNotes = $this->getRecentCarerNotes();

        return view('tracking.index', compact('stats', 'progressByType', 'history', 'carerNotes'));
    }

    /**
     * Calcule le score moyen
     */
    private function getAverageScore(): int
    {
        $avg = Exercise::avg('score');
        return $avg ? round($avg) : 0;
    }

    /**
     * Compte les séances de la semaine
     */
    private function getSessionsThisWeek(): int
    {
        return Exercise::whereBetween('completed_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();
    }

    /**
     * Calcule le taux d'observance des rappels
     */
    private function calculateAdherence(): int
    {
        $last7Days = Reminder::whereBetween('date', [
            now()->subDays(7),
            now()
        ])->get();

        $total = $last7Days->count();

        if ($total === 0) {
            return 0;
        }

        $completed = $last7Days->where('done', true)->count();

        return round(($completed / $total) * 100);
    }

    /**
     * Progression par type d'exercice
     */
    private function getProgressByType(): array
    {
        $types = [
            Exercise::TYPE_MEMORY => 'Mémoire',
            Exercise::TYPE_ATTENTION => 'Attention',
            Exercise::TYPE_LANGUAGE => 'Langage',
            Exercise::TYPE_ORIENTATION => 'Orientation'
        ];

        $progress = [];

        foreach ($types as $type => $label) {
            $exercises = Exercise::where('type', $type)->get();

            $progress[] = [
                'type' => $type,
                'label' => $label,
                'count' => $exercises->count(),
                'avgScore' => $exercises->count() > 0 ? round($exercises->avg('score')) : 0,
                'lastScore' => $exercises->sortByDesc('completed_at')->first()->score ?? 0,
                'trend' => $this->calculateTrend($type)
            ];
        }

        return $progress;
    }

    /**
     * Calcule la tendance (amélioration/baisse)
     */
    private function calculateTrend(string $type): string
    {
        $recent = Exercise::where('type', $type)
            ->orderByDesc('completed_at')
            ->take(5)
            ->pluck('score');

        if ($recent->count() < 3) {
            return 'stable';
        }

        $first = $recent->slice(3, 2)->avg();
        $last = $recent->slice(0, 2)->avg();

        if ($last > $first + 5) {
            return 'up';
        } elseif ($last < $first - 5) {
            return 'down';
        }

        return 'stable';
    }

    /**
     * Historique des scores
     */
    private function getHistory(int $days): array
    {
        $history = Exercise::whereBetween('completed_at', [
                now()->subDays($days),
                now()
            ])
            ->orderBy('completed_at')
            ->get()
            ->groupBy(function($exercise) {
                return $exercise->completed_at->format('Y-m-d');
            })
            ->map(function($dayExercises) {
                return [
                    'avgScore' => round($dayExercises->avg('score')),
                    'count' => $dayExercises->count()
                ];
            });

        return $history->toArray();
    }

    /**
     * Récupère les notes des aidants récentes
     */
    private function getRecentCarerNotes(): array
    {
        // Récupération depuis la session (temporaire)
        $feed = session('feed', []);
        
        // Filtrer les notes visibles par le patient
        $visibleNotes = array_filter($feed, function($note) {
            return $note['visible'] ?? true;
        });

        // Retourner les 3 dernières
        return array_slice($visibleNotes, 0, 3);
    }

    /**
     * Génère un rapport PDF/TXT
     */
    public function generateReport(Request $request)
    {
        $format = $request->input('format', 'txt');

        // Collecte des données
        $stats = [
            'date' => now()->translatedFormat('l j F Y à H:i'),
            'avgScore' => $this->getAverageScore(),
            'totalSessions' => Exercise::count(),
            'sessionsThisWeek' => $this->getSessionsThisWeek(),
            'adherence' => $this->calculateAdherence(),
            'mood' => session('mood', '-'),
            'sleep' => session('sleep', '-'),
        ];

        // Progression par type
        $progressByType = $this->getProgressByType();

        // Dernière note aidant
        $lastNote = session('feed', [])[0]['text'] ?? 'Aucune note';

        if ($format === 'txt') {
            return $this->generateTextReport($stats, $progressByType, $lastNote);
        }

        // Future implémentation PDF
        return $this->generatePdfReport($stats, $progressByType, $lastNote);
    }

    /**
     * Génère un rapport texte
     */
    private function generateTextReport(array $stats, array $progress, string $lastNote)
    {
        $content = "═══════════════════════════════════════════════════\n";
        $content .= "   RAPPORT DE SUIVI - APP AIDE ALZHEIMER\n";
        $content .= "═══════════════════════════════════════════════════\n\n";
        $content .= "Date du rapport : {$stats['date']}\n\n";

        $content .= "─── STATISTIQUES GÉNÉRALES ───\n\n";
        $content .= "• Score moyen : {$stats['avgScore']}/100\n";
        $content .= "• Total de séances : {$stats['totalSessions']}\n";
        $content .= "• Séances cette semaine : {$stats['sessionsThisWeek']}/4\n";
        $content .= "• Observance des rappels : {$stats['adherence']}%\n";
        $content .= "• Humeur actuelle : {$stats['mood']}\n";
        $content .= "• Qualité du sommeil : {$stats['sleep']}\n\n";

        $content .= "─── PROGRESSION PAR EXERCICE ───\n\n";
        foreach ($progress as $p) {
            $trendIcon = match($p['trend']) {
                'up' => '↗',
                'down' => '↘',
                default => '→'
            };
            $content .= "• {$p['label']} : {$p['avgScore']}/100 (x{$p['count']}) {$trendIcon}\n";
        }

        $content .= "\n─── DERNIÈRE NOTE AIDANT ───\n\n";
        $content .= wordwrap($lastNote, 70) . "\n\n";

        $content .= "─── RECOMMANDATIONS ───\n\n";
        $content .= $this->generateRecommendations($stats, $progress);

        $content .= "\n═══════════════════════════════════════════════════\n";
        $content .= "Rapport généré automatiquement\n";
        $content .= "Pour toute question, contactez votre aidant\n";
        $content .= "═══════════════════════════════════════════════════\n";

        // Téléchargement
        $filename = 'rapport-suivi-' . now()->format('Y-m-d') . '.txt';

        return response($content)
            ->header('Content-Type', 'text/plain; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Génère des recommandations personnalisées
     */
    private function generateRecommendations(array $stats, array $progress): string
    {
        $recommendations = [];

        // Régularité
        if ($stats['sessionsThisWeek'] < 3) {
            $recommendations[] = "→ Essayez de faire au moins 3 séances cette semaine";
        } else {
            $recommendations[] = "→ Excellente régularité ! Continuez ainsi";
        }

        // Observance
        if ($stats['adherence'] < 70) {
            $recommendations[] = "→ Pensez à valider vos rappels quotidiens";
        }

        // Exercices faibles
        $weakExercises = array_filter($progress, fn($p) => $p['avgScore'] < 50 && $p['count'] > 2);
        if (count($weakExercises) > 0) {
            $types = array_column($weakExercises, 'label');
            $recommendations[] = "→ Concentrez-vous sur : " . implode(', ', $types);
        }

        // Exercices en baisse
        $declining = array_filter($progress, fn($p) => $p['trend'] === 'down');
        if (count($declining) > 0) {
            $types = array_column($declining, 'label');
            $recommendations[] = "→ Attention à la baisse dans : " . implode(', ', $types);
        }

        // Sommeil
        if ($stats['sleep'] === 'Mauvais') {
            $recommendations[] = "→ Un bon sommeil améliore les performances cognitives";
        }

        if (empty($recommendations)) {
            $recommendations[] = "→ Continuez votre excellent travail !";
        }

        return implode("\n", $recommendations) . "\n";
    }

    /**
     * Génère un rapport PDF (future implémentation)
     */
    private function generatePdfReport(array $stats, array $progress, string $lastNote)
    {
        // TODO: Implémenter avec une bibliothèque comme DOMPDF ou TCPDF
        return response()->json([
            'error' => 'Format PDF non encore disponible'
        ], 501);
    }

    /**
     * Export des données en JSON
     */
    public function exportData()
    {
        $data = [
            'exercises' => Exercise::orderByDesc('completed_at')->get(),
            'reminders' => Reminder::orderByDesc('date')->get(),
            'stats' => [
                'avgScore' => $this->getAverageScore(),
                'totalSessions' => Exercise::count(),
                'adherence' => $this->calculateAdherence(),
            ]
        ];

        $filename = 'export-donnees-' . now()->format('Y-m-d') . '.json';

        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
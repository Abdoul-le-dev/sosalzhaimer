<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exercise;

class ExercisesController extends Controller
{
    /**
     * Page principale des exercices
     */
    public function index()
    {
        return view('exercises.index');
    }

    /**
     * Démarre un exercice spécifique
     */
    public function start(Request $request, string $type)
    {
        // Validation du type
        if (!in_array($type, ['memory', 'attention', 'language', 'orientation'])) {
            return response()->json(['error' => 'Type invalide'], 400);
        }

        // Génération du contenu selon le type
        $exerciseData = match($type) {
            'memory' => $this->generateMemoryExercise(),
            'attention' => $this->generateAttentionExercise(),
            'language' => $this->generateLanguageExercise(),
            'orientation' => $this->generateOrientationExercise(),
        };

        // Stockage en session
        session(['currentExercise' => [
            'type' => $type,
            'data' => $exerciseData,
            'started_at' => now()
        ]]);

        return response()->json([
            'success' => true,
            'type' => $type,
            'data' => $exerciseData
        ]);
    }

    /**
     * Valide et enregistre le résultat
     */
    public function validate(Request $request)
    {
        $current = session('currentExercise');
        
        if (!$current) {
            return response()->json(['error' => 'Aucun exercice en cours'], 400);
        }

        // Calcul du score selon le type
        $score = match($current['type']) {
            'memory' => $this->scoreMemory($request, $current['data']),
            'attention' => $this->scoreAttention($request, $current['data']),
            'language' => $this->scoreLanguage($request, $current['data']),
            'orientation' => $this->scoreOrientation($request, $current['data']),
        };

        // Calcul de la durée
        $duration = now()->diffInSeconds($current['started_at']);

        // Enregistrement en base
        $exercise = Exercise::create([
            'user_id' => 1, // À remplacer par auth()->id() avec authentification
            'type' => $current['type'],
            'score' => $score,
            'duration' => $duration,
            'completed_at' => now()
        ]);

        // Mise à jour des stats en session
        $this->updateSessionStats($score);

        // Nettoyage
        session()->forget('currentExercise');

        return response()->json([
            'success' => true,
            'score' => $score,
            'feedback' => $this->getFeedback($score)
        ]);
    }

    /**
     * Génère un exercice de mémoire
     */
    private function generateMemoryExercise(): array
    {
        $emojis = ['🍎', '🔑', '🌼', '🚗', '🐱', '⚽️', '🎨', '📱', '🌟', '🏠'];
        shuffle($emojis);
        
        $correct = array_slice($emojis, 0, 3);
        $pool = array_slice($emojis, 0, 6);
        shuffle($pool);

        return [
            'correct' => $correct,
            'pool' => $pool,
            'instruction' => 'Mémorisez ces 3 symboles pendant 5 secondes'
        ];
    }

    /**
     * Génère un exercice d'attention
     */
    private function generateAttentionExercise(): array
    {
        $shapes = ['▲', '●', '■'];
        $grid = [];
        
        // Génération d'une grille 3x3
        for ($i = 0; $i < 9; $i++) {
            $grid[] = $shapes[array_rand($shapes)];
        }
        
        // S'assurer qu'il y a au moins 3 triangles
        $grid[0] = '▲';
        $grid[4] = '▲';
        $grid[8] = '▲';
        
        shuffle($grid);

        return [
            'grid' => $grid,
            'target' => '▲',
            'instruction' => 'Cliquez sur tous les triangles (▲)'
        ];
    }

    /**
     * Génère un exercice de langage
     */
    private function generateLanguageExercise(): array
    {
        $wordSets = [
            ['pomme', 'clé', 'fleur'],
            ['chat', 'livre', 'soleil'],
            ['table', 'porte', 'stylo']
        ];

        $words = $wordSets[array_rand($wordSets)];

        return [
            'words' => $words,
            'instruction' => 'Écrivez un des mots suivants : ' . implode(', ', $words)
        ];
    }

    /**
     * Génère un exercice d'orientation
     */
    private function generateOrientationExercise(): array
    {
        $questions = [
            [
                'question' => 'Quel jour sommes-nous ?',
                'answer' => now()->translatedFormat('l'),
                'options' => ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche']
            ],
            [
                'question' => 'En quelle année sommes-nous ?',
                'answer' => now()->year,
                'options' => [now()->year - 1, now()->year, now()->year + 1]
            ],
            [
                'question' => 'En quel mois sommes-nous ?',
                'answer' => now()->translatedFormat('F'),
                'options' => ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 
                             'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre']
            ]
        ];

        $selected = $questions[array_rand($questions)];

        return $selected;
    }

    /**
     * Score l'exercice de mémoire
     */
    private function scoreMemory(Request $request, array $data): int
    {
        $picks = $request->input('picks', []);
        $correct = $data['correct'];
        
        $correctPicks = array_intersect($picks, $correct);
        $incorrectPicks = array_diff($picks, $correct);
        
        // 33 points par bonne réponse, -10 par erreur
        return max(0, (count($correctPicks) * 33) - (count($incorrectPicks) * 10));
    }

    /**
     * Score l'exercice d'attention
     */
    private function scoreAttention(Request $request, array $data): int
    {
        $picks = $request->input('picks', []);
        $grid = $data['grid'];
        $target = $data['target'];
        
        // Indices corrects
        $correctIndices = [];
        foreach ($grid as $i => $shape) {
            if ($shape === $target) {
                $correctIndices[] = $i;
            }
        }
        
        $correctPicks = array_intersect($picks, $correctIndices);
        $total = count($correctIndices);
        
        return $total > 0 ? round((count($correctPicks) / $total) * 100) : 0;
    }

    /**
     * Score l'exercice de langage
     */
    private function scoreLanguage(Request $request, array $data): int
    {
        $answer = strtolower(trim($request->input('answer', '')));
        $words = array_map('strtolower', $data['words']);
        
        return in_array($answer, $words) ? 100 : 0;
    }

    /**
     * Score l'exercice d'orientation
     */
    private function scoreOrientation(Request $request, array $data): int
    {
        $answer = strtolower(trim($request->input('answer', '')));
        $correct = strtolower($data['answer']);
        
        return $answer === $correct ? 100 : 0;
    }

    /**
     * Met à jour les statistiques en session
     */
    private function updateSessionStats(int $score): void
    {
        $currentAvg = session('avgScore', 0);
        $sessions = session('sessions', 0);
        
        // Calcul de la nouvelle moyenne
        $newAvg = round(($currentAvg * $sessions + $score) / ($sessions + 1));
        
        session([
            'avgScore' => $newAvg,
            'sessions' => $sessions + 1
        ]);
    }

    /**
     * Retourne un feedback selon le score
     */
    private function getFeedback(int $score): string
    {
        return match(true) {
            $score >= 80 => "Excellent travail ! Continuez comme ça.",
            $score >= 50 => "Bien joué ! Vous progressez.",
            default => "Pas de souci, on réessaie demain."
        };
    }
}
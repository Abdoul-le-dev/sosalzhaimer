<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarerNote;
use App\Models\Exercise;
use App\Models\Reminder;

class CarersController extends Controller
{
    /**
     * Affiche le fil des aidants
     */
    public function index()
    {
        // Récupération des notes visibles par le patient
        $notes = CarerNote::visibleToPatient()
            ->orderByDesc('created_at')
            ->paginate(10);

        // Statistiques pour les aidants
        $patientStats = [
            'lastExercise' => Exercise::orderByDesc('completed_at')->first(),
            'avgScore' => Exercise::avg('score') ? round(Exercise::avg('score')) : 0,
            'adherence' => $this->calculateAdherence(),
            'mood' => session('mood', null),
            'sleep' => session('sleep', null),
        ];

        return view('carers.index', compact('notes', 'patientStats'));
    }

    /**
     * Affiche la vue complète (aidants uniquement)
     */
    public function dashboard()
    {
        // Toutes les notes (publiques et privées)
        $allNotes = CarerNote::orderByDesc('created_at')
            ->paginate(20);

        // Statistiques détaillées
        $stats = [
            'exercises' => Exercise::orderByDesc('completed_at')->take(10)->get(),
            'reminders' => Reminder::today()->get(),
            'avgScore' => Exercise::avg('score') ? round(Exercise::avg('score')) : 0,
            'adherence' => $this->calculateAdherence(),
            'alertsCount' => $this->countAlerts(),
        ];

        return view('carers.dashboard', compact('allNotes', 'stats'));
    }

    /**
     * Crée une nouvelle note
     */
    public function postNote(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'visible_to_patient' => 'required|boolean',
            'category' => 'required|in:observation,medication,behavior,general'
        ], [
            'content.required' => 'Le contenu est obligatoire',
            'content.max' => 'La note ne peut pas dépasser 1000 caractères',
            'visible_to_patient.required' => 'Veuillez indiquer la visibilité',
            'category.required' => 'La catégorie est obligatoire'
        ]);

        $note = CarerNote::create([
            'user_id' => 1, // À remplacer par le patient concerné
            'carer_id' => null, // À remplacer par auth()->id() quand authentification
            'carer_name' => $request->input('carer_name', 'Aidant'),
            'content' => $validated['content'],
            'visible_to_patient' => $validated['visible_to_patient'],
            'category' => $validated['category']
        ]);

        // Notification (optionnelle)
        if ($validated['visible_to_patient']) {
            // TODO: Envoyer une notification au patient
        }

        return response()->json([
            'success' => true,
            'message' => 'Note publiée avec succès',
            'note' => [
                'id' => $note->id,
                'content' => $note->content,
                'carer_name' => $note->carer_name,
                'date' => $note->getFormattedDate(),
                'visible' => $note->visible_to_patient,
                'category' => $note->getCategoryLabel(),
                'icon' => $note->getCategoryIcon()
            ]
        ]);
    }

    /**
     * Supprime une note
     */
    public function deleteNote(int $id)
    {
        $note = CarerNote::findOrFail($id);
        
        // Vérification : seul l'auteur peut supprimer
        // TODO: Ajouter la vérification auth()->id() === $note->carer_id

        $note->delete();

        return response()->json([
            'success' => true,
            'message' => 'Note supprimée'
        ]);
    }

    /**
     * Marque une note comme lue (future fonctionnalité)
     */
    public function markAsRead(int $id)
    {
        // TODO: Implémenter le système de lecture
        return response()->json([
            'success' => true,
            'message' => 'Note marquée comme lue'
        ]);
    }

    /**
     * Filtre les notes par catégorie
     */
    public function filterByCategory(Request $request, string $category)
    {
        $notes = CarerNote::where('category', $category)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('carers.partials.notes-list', compact('notes'));
    }

    /**
     * Calcule le taux d'observance
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
     * Compte les alertes (rappels manqués, scores faibles)
     */
    private function countAlerts(): int
    {
        $alerts = 0;

        // Rappels en retard aujourd'hui
        $lateReminders = Reminder::today()
            ->where('done', false)
            ->get()
            ->filter(fn($r) => $r->isLate())
            ->count();

        $alerts += $lateReminders;

        // Scores faibles récents (< 40)
        $lowScores = Exercise::where('created_at', '>=', now()->subDays(3))
            ->where('score', '<', 40)
            ->count();

        $alerts += $lowScores;

        return $alerts;
    }

    /**
     * Export du suivi patient (CSV)
     */
    public function exportPatientData(Request $request)
    {
        $data = [
            'exercises' => Exercise::orderByDesc('completed_at')->get()->toArray(),
            'reminders' => Reminder::orderByDesc('date')->get()->toArray(),
            'notes' => CarerNote::orderByDesc('created_at')->get()->toArray(),
        ];

        $filename = 'export-patient-' . now()->format('Y-m-d') . '.json';

        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
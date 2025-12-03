<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reminder;
use Carbon\Carbon;

class RemindersController extends Controller
{
    /**
     * Affiche la page agenda
     */
    public function index()
    {
        // Rappels du jour
        $todayReminders = Reminder::today()
            ->orderBy('time')
            ->get();

        // Rappels à venir (7 prochains jours)
        $upcomingReminders = Reminder::whereBetween('date', [
                today()->addDay(),
                today()->addWeek()
            ])
            ->orderBy('date')
            ->orderBy('time')
            ->get()
            ->groupBy(function($reminder) {
                return $reminder->date->format('Y-m-d');
            });

        return view('reminders.index', compact('todayReminders', 'upcomingReminders'));
    }

    /**
     * Crée un nouveau rappel
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'repeat' => 'required|in:none,daily,weekly'
        ], [
            'title.required' => 'Le titre est obligatoire',
            'date.required' => 'La date est obligatoire',
            'date.after_or_equal' => 'La date doit être aujourd\'hui ou dans le futur',
            'time.required' => 'L\'heure est obligatoire',
            'time.date_format' => 'Format d\'heure invalide',
            'repeat.in' => 'Type de répétition invalide'
        ]);

        $reminder = Reminder::create([
            'user_id' => 1, // À remplacer par auth()->id()
            'title' => $validated['title'],
            'date' => $validated['date'],
            'time' => $validated['time'],
            'repeat' => $validated['repeat'],
            'done' => false
        ]);

        // Générer les répétitions si nécessaire
        if ($validated['repeat'] !== 'none') {
            $this->generateRepeatedReminders($reminder);
        }

        return response()->json([
            'success' => true,
            'message' => 'Rappel créé avec succès',
            'reminder' => $reminder
        ]);
    }

    /**
     * Marque un rappel comme terminé
     */
    public function markDone(Request $request, int $id)
    {
        $reminder = Reminder::findOrFail($id);

        $reminder->update(['done' => true]);

        // Recalcul de l'observance
        $adherence = $this->calculateAdherence();

        return response()->json([
            'success' => true,
            'message' => 'Rappel validé',
            'adherence' => $adherence
        ]);
    }

    /**
     * Supprime un rappel
     */
    public function destroy(int $id)
    {
        $reminder = Reminder::findOrFail($id);
        $reminder->delete();

        return response()->json([
            'success' => true,
            'message' => 'Rappel supprimé'
        ]);
    }

    /**
     * Récupère les rappels du jour (API)
     */
    public function getTodayReminders()
    {
        $reminders = Reminder::today()
            ->orderBy('time')
            ->get()
            ->map(function($reminder) {
                return [
                    'id' => $reminder->id,
                    'title' => $reminder->title,
                    'time' => $reminder->getFormattedTime(),
                    'done' => $reminder->done,
                    'late' => $reminder->isLate()
                ];
            });

        return response()->json($reminders);
    }

    /**
     * Génère les rappels répétés
     */
    private function generateRepeatedReminders(Reminder $reminder): void
    {
        $occurrences = 30; // Générer 30 occurrences

        for ($i = 1; $i <= $occurrences; $i++) {
            $nextDate = match($reminder->repeat) {
                'daily' => Carbon::parse($reminder->date)->addDays($i),
                'weekly' => Carbon::parse($reminder->date)->addWeeks($i),
                default => null
            };

            if ($nextDate) {
                Reminder::create([
                    'user_id' => $reminder->user_id,
                    'title' => $reminder->title,
                    'date' => $nextDate,
                    'time' => $reminder->time,
                    'repeat' => 'none', // Les occurrences ne sont pas elles-mêmes répétées
                    'done' => false
                ]);
            }
        }
    }

    /**
     * Calcule le taux d'observance
     */
    private function calculateAdherence(): int
    {
        $todayReminders = Reminder::today()->get();
        $total = $todayReminders->count();

        if ($total === 0) {
            return 0;
        }

        $completed = $todayReminders->where('done', true)->count();

        return round(($completed / $total) * 100);
    }

    /**
     * Envoie une notification pour un rappel
     */
    public function sendNotification(int $id)
    {
        $reminder = Reminder::findOrFail($id);

        // Marquer comme notifié
        $reminder->update(['notified' => true]);

        // Logique de notification (SMS, email, push, etc.)
        // À implémenter selon les besoins

        return response()->json([
            'success' => true,
            'message' => 'Notification envoyée'
        ]);
    }
}
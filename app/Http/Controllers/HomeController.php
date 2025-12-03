<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reminder;
use App\Models\Exercise;

class HomeController extends Controller
{
    /**
     * Affiche le dashboard principal
     */
    public function index()
    {
        // Récupération des rappels du jour
        $todayReminders = Reminder::whereDate('date', today())
            ->orderBy('time')
            ->take(3)
            ->get();
        
        // Récupération des statistiques de base
        $stats = [
            'avgScore' => session('avgScore', 0),
            'sessions' => session('sessions', 0),
            'adherence' => $this->calculateAdherence(),
            'mood' => session('mood', null),
            'sleep' => session('sleep', null),
        ];
        
        return view('home.index', compact('todayReminders', 'stats'));
    }
    
    /**
     * Calcule le taux d'observance des rappels
     */
    private function calculateAdherence(): int
    {
        $todayReminders = Reminder::whereDate('date', today())->get();
        $total = $todayReminders->count();
        
        if ($total === 0) {
            return 0;
        }
        
        $completed = $todayReminders->where('done', true)->count();
        
        return round(($completed / $total) * 100);
    }
    
    /**
     * Enregistre l'humeur du jour
     */
    public function setMood(Request $request)
    {
        $validated = $request->validate([
            'mood' => 'required|in:🙂,😐,🙁'
        ]);
        
        session(['mood' => $validated['mood']]);
        
        return response()->json([
            'success' => true,
            'message' => 'Humeur enregistrée'
        ]);
    }
    
    /**
     * Enregistre la qualité du sommeil
     */
    public function setSleep(Request $request)
    {
        $validated = $request->validate([
            'sleep' => 'required|in:Très bon,Correct,Mauvais'
        ]);
        
        session(['sleep' => $validated['sleep']]);
        
        return response()->json([
            'success' => true,
            'message' => 'Sommeil enregistré'
        ]);
    }
}
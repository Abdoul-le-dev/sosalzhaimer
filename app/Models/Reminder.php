<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reminder extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'date',
        'time',
        'repeat',
        'done',
        'notified'
    ];

    protected $casts = [
        'date' => 'date',
        'done' => 'boolean',
        'notified' => 'boolean'
    ];

    // Types de répétition
    const REPEAT_NONE = 'none';
    const REPEAT_DAILY = 'daily';
    const REPEAT_WEEKLY = 'weekly';

    /**
     * Scope pour les rappels du jour
     */
    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    /**
     * Scope pour les rappels à venir
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', today())
                    ->where('done', false)
                    ->orderBy('date')
                    ->orderBy('time');
    }

    /**
     * Scope pour les rappels non complétés
     */
    public function scopePending($query)
    {
        return $query->where('done', false);
    }

    /**
     * Vérifie si le rappel est en retard
     */
    public function isLate(): bool
    {
        if ($this->done) {
            return false;
        }

        $reminderDateTime = Carbon::parse($this->date->format('Y-m-d') . ' ' . $this->time);
        return $reminderDateTime->isPast();
    }

    /**
     * Retourne le label de répétition
     */
    public function getRepeatLabel(): string
    {
        return match($this->repeat) {
            self::REPEAT_DAILY => 'Quotidien',
            self::REPEAT_WEEKLY => 'Hebdomadaire',
            default => 'Aucune'
        };
    }

    /**
     * Formate l'heure pour l'affichage
     */
    public function getFormattedTime(): string
    {
        return Carbon::parse($this->time)->format('H:i');
    }
}
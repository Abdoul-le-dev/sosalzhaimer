<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarerNote extends Model
{
    protected $fillable = [
        'user_id',
        'carer_id',
        'carer_name',
        'content',
        'visible_to_patient',
        'category'
    ];

    protected $casts = [
        'visible_to_patient' => 'boolean',
        'created_at' => 'datetime'
    ];

    // Catégories de notes
    const CATEGORY_OBSERVATION = 'observation';
    const CATEGORY_MEDICATION = 'medication';
    const CATEGORY_BEHAVIOR = 'behavior';
    const CATEGORY_GENERAL = 'general';

    /**
     * Scope pour les notes visibles par le patient
     */
    public function scopeVisibleToPatient($query)
    {
        return $query->where('visible_to_patient', true);
    }

    /**
     * Scope pour les notes privées (aidants seulement)
     */
    public function scopePrivate($query)
    {
        return $query->where('visible_to_patient', false);
    }

    /**
     * Scope pour les notes récentes
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days))
                    ->orderByDesc('created_at');
    }

    /**
     * Retourne le label de la catégorie
     */
    public function getCategoryLabel(): string
    {
        return match($this->category) {
            self::CATEGORY_OBSERVATION => 'Observation',
            self::CATEGORY_MEDICATION => 'Médicament',
            self::CATEGORY_BEHAVIOR => 'Comportement',
            default => 'Général'
        };
    }

    /**
     * Retourne l'icône de la catégorie
     */
    public function getCategoryIcon(): string
    {
        return match($this->category) {
            self::CATEGORY_OBSERVATION => '👁️',
            self::CATEGORY_MEDICATION => '💊',
            self::CATEGORY_BEHAVIOR => '🧠',
            default => '📝'
        };
    }

    /**
     * Formate la date de création
     */
    public function getFormattedDate(): string
    {
        return $this->created_at->translatedFormat('j M Y à H:i');
    }
}
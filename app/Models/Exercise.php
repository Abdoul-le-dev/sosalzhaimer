<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'score',
        'duration',
        'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'score' => 'integer',
        'duration' => 'integer'
    ];

    // Types d'exercices disponibles
    const TYPE_MEMORY = 'memory';
    const TYPE_ATTENTION = 'attention';
    const TYPE_LANGUAGE = 'language';
    const TYPE_ORIENTATION = 'orientation';

    /**
     * Récupère le label de l'exercice
     */
    public function getTypeLabel(): string
    {
        return match($this->type) {
            self::TYPE_MEMORY => 'Mémoire',
            self::TYPE_ATTENTION => 'Attention',
            self::TYPE_LANGUAGE => 'Langage',
            self::TYPE_ORIENTATION => 'Orientation',
            default => 'Inconnu'
        };
    }

    /**
     * Calcule le score moyen des exercices
     */
    public static function averageScore(): int
    {
        return static::avg('score') ?? 0;
    }
}
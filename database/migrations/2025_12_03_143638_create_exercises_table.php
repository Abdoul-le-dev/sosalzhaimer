<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Type d'exercice
            $table->enum('type', ['memory', 'attention', 'language', 'orientation']);
            
            // Résultats
            $table->integer('score')->default(0); // 0-100
            $table->integer('duration')->nullable(); // en secondes
            
            // Détails de la session (JSON)
            $table->json('session_data')->nullable(); // stocke les réponses, erreurs, etc.
            
            // Statut
            $table->boolean('completed')->default(true);
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();

            // Index pour performances
            $table->index(['user_id', 'completed_at']);
            $table->index(['user_id', 'type']);
            $table->index('score');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
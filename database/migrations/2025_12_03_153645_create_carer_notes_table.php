<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carer_notes', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Patient concerné
            $table->foreignId('carer_id')->nullable()->constrained('users')->onDelete('set null'); // Aidant auteur
            $table->string('carer_name'); // Nom si pas de compte
            
            // Contenu
            $table->text('content');
            
            // Catégorie
            $table->enum('category', ['observation', 'medication', 'behavior', 'general'])->default('general');
            
            // Visibilité
            $table->boolean('visible_to_patient')->default(true);
            
            // Priorité / Importance
            $table->enum('priority', ['low', 'normal', 'high'])->default('normal');
            
            // Lecture
            $table->boolean('read_by_patient')->default(false);
            $table->timestamp('read_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            // Index pour performances
            $table->index(['user_id', 'created_at']);
            $table->index('visible_to_patient');
            $table->index('category');
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carer_notes');
    }
};
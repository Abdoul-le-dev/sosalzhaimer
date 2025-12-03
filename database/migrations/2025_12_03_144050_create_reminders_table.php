<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Contenu du rappel
            $table->string('title');
            $table->text('description')->nullable();
            
            // Planification
            $table->date('date');
            $table->time('time');
            
            // Répétition
            $table->enum('repeat', ['none', 'daily', 'weekly', 'monthly'])->default('none');
            $table->foreignId('parent_reminder_id')->nullable()->constrained('reminders')->onDelete('cascade'); // Pour les répétitions
            
            // Catégorie
            $table->enum('category', ['medication', 'appointment', 'exercise', 'meal', 'other'])->default('other');
            
            // Statut
            $table->boolean('done')->default(false);
            $table->timestamp('completed_at')->nullable();
            
            // Notifications
            $table->boolean('notified')->default(false);
            $table->timestamp('notified_at')->nullable();
            $table->integer('notification_minutes_before')->default(15); // Notifier X minutes avant
            
            $table->timestamps();
            $table->softDeletes();

            // Index pour performances
            $table->index(['user_id', 'date']);
            $table->index(['user_id', 'done', 'date']);
            $table->index(['date', 'time', 'notified']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
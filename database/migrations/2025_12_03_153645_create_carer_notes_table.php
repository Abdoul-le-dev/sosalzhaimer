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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('carer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('carer_name');
            $table->text('content');
            $table->boolean('visible_to_patient')->default(true);
            $table->enum('category', ['observation', 'medication', 'behavior', 'general'])->default('general');
            $table->timestamps();

            // Index pour performances
            $table->index(['user_id', 'created_at']);
            $table->index('visible_to_patient');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carer_notes');
    }
};
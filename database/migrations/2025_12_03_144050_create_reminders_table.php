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
            $table->string('title');
            $table->date('date');
            $table->time('time');
            $table->enum('repeat', ['none', 'daily', 'weekly'])->default('none');
            $table->boolean('done')->default(false);
            $table->boolean('notified')->default(false);
            $table->timestamps();

            // Index pour performances
            $table->index(['user_id', 'date']);
            $table->index(['done', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
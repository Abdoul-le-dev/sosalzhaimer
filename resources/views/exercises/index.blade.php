@extends('layouts.app')

@section('title', 'Exercices - App Aide Alzheimer')

@section('content')
    {{-- Sélection des exercices --}}
    <x-card title="Exercices disponibles">
        <div class="btnGrid">
            <button class="btn" onclick="startExercise('memory')" aria-label="Exercice de mémoire">
                🧠 Mémoire
            </button>
            <button class="btn secondary" onclick="startExercise('attention')" aria-label="Exercice d'attention">
                👁 Attention
            </button>
            <button class="btn secondary" onclick="startExercise('language')" aria-label="Exercice de langage">
                💬 Langage
            </button>
            <button class="btn secondary" onclick="startExercise('orientation')" aria-label="Exercice d'orientation">
                📍 Orientation
            </button>
        </div>
    </x-card>

    {{-- Zone d'exercice (cachée par défaut) --}}
    <div id="exerciseArea" class="card" style="display:none">
        <h3 id="exerciseTitle">Séance</h3>
        <div id="exerciseContent"></div>
        <div class="btnRow">
            <button class="btn" onclick="validateExercise()" aria-label="Valider l'exercice">
                ✓ Valider
            </button>
            <button class="btn secondary" onclick="cancelExercise()" aria-label="Quitter l'exercice">
                Quitter
            </button>
        </div>
    </div>

    {{-- Zone de résultat (cachée par défaut) --}}
    <div id="exerciseResult" class="card" style="display:none">
        <h3>Résultat</h3>
        <div id="resultContent"></div>
        <button class="btn full" onclick="finishExercise()" aria-label="Terminer l'exercice">
            Terminer
        </button>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('js/modules/exercises.js') }}"></script>
@endpush
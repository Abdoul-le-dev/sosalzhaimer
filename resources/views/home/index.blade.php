@extends('layouts.app')

@section('title', 'Accueil - App Aide Alzheimer')

@section('content')
    {{-- Exercice du jour --}}
    <x-card title="Exercice du jour">
        <div class="muted">Mémoire visuelle • 3–5 min</div>
        <div class="btnRow">
            <a href="{{ route('exercises.index') }}" class="btn">
                ▶ Commencer
            </a>
            <button class="btn secondary" onclick="speak('Exercice du jour')">
                🔊
            </button>
        </div>
    </x-card>

    {{-- Rappels à venir --}}
    <x-card title="Rappels à venir">
        <ul id="nextReminders" class="list">
            @forelse($todayReminders as $reminder)
                <li class="li">
                    <div class="li-left">
                        {{ $reminder->time }} — {{ $reminder->title }}
                    </div>
                    <div class="li-right">
                        {{ $reminder->done ? '✅' : '🕒' }}
                    </div>
                </li>
            @empty
                <li class="li">
                    <span class="muted">Aucun rappel aujourd'hui</span>
                </li>
            @endforelse
        </ul>
        <a href="{{ route('reminders.index') }}" class="btn ghost full">
            Voir l'agenda
        </a>
    </x-card>

    {{-- Humeur et sommeil --}}
    <x-card title="Comment allez-vous ?">
        <div class="muted" style="margin-bottom:8px">
            Humeur actuelle: <strong id="mood">{{ $stats['mood'] ?? '—' }}</strong>
        </div>
        
        <div class="pillRow">
            <button class="pill" onclick="setMood('🙂')" aria-label="Bien">🙂</button>
            <button class="pill" onclick="setMood('😐')" aria-label="Moyen">😐</button>
            <button class="pill" onclick="setMood('🙁')" aria-label="Pas bien">🙁</button>
        </div>
        
        <div class="field" style="margin-top:16px">
            <label for="sleep">Qualité du sommeil</label>
            <select id="sleep" onchange="saveSleep()" aria-label="Qualité du sommeil">
                <option value="">Choisir...</option>
                <option {{ $stats['sleep'] === 'Très bon' ? 'selected' : '' }}>Très bon</option>
                <option {{ $stats['sleep'] === 'Correct' ? 'selected' : '' }}>Correct</option>
                <option {{ $stats['sleep'] === 'Mauvais' ? 'selected' : '' }}>Mauvais</option>
            </select>
        </div>
    </x-card>
@endsection

@push('scripts')
<script src="{{ asset('../js/modules/home.js') }}"></script>
<script src="{{ asset('js/modules/app.js') }}"></script>
@endpush
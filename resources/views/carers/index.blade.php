@extends('layouts.app')

@section('title', 'Aidants - App Aide Alzheimer')

@section('content')
    {{-- Fil des aidants --}}
    <x-card title="Messages de vos aidants">
        <div id="feed">
            @forelse($notes as $note)
                <div class="noteCard" data-id="{{ $note->id }}">
                    <div class="noteHeader">
                        <div>
                            <strong>{{ $note->carer_name }}</strong>
                            <span class="badge">{{ $note->getCategoryIcon() }} {{ $note->getCategoryLabel() }}</span>
                        </div>
                        <div class="muted" style="font-size:12px">
                            {{ $note->getFormattedDate() }}
                        </div>
                    </div>
                    <div class="noteContent">
                        {{ $note->content }}
                    </div>
                </div>
            @empty
                <div class="muted" style="text-align:center; padding:24px">
                    Aucun message pour le moment.
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($notes->hasPages())
            <div style="margin-top:16px">
                {{ $notes->links() }}
            </div>
        @endif
    </x-card>

    {{-- Formulaire nouveau message (pour aidants) --}}
    <x-card title="Envoyer un message">
        <form id="noteForm" onsubmit="postNote(event)">
            <div class="field">
                <label for="carerName">Votre nom</label>
                <input id="carerName" 
                       name="carer_name"
                       class="input" 
                       type="text"
                       placeholder="Ex. Marie, Infirmière..."
                       required
                       aria-required="true">
            </div>

            <div class="field">
                <label for="noteText">Message</label>
                <textarea id="noteText" 
                          name="content"
                          placeholder="Écrire une note..."
                          required
                          aria-required="true"></textarea>
            </div>

            <div class="field">
                <label for="noteCategory">Catégorie</label>
                <select id="noteCategory" name="category" class="input" aria-label="Catégorie de la note">
                    <option value="general">📝 Général</option>
                    <option value="observation">👁️ Observation</option>
                    <option value="medication">💊 Médicament</option>
                    <option value="behavior">🧠 Comportement</option>
                </select>
            </div>

            <label style="display:flex; align-items:center; gap:8px; margin-bottom:12px">
                <input type="checkbox" 
                       id="visiblePatient" 
                       name="visible_to_patient"
                       checked 
                       style="width:20px; height:20px">
                <span>Visible par le patient</span>
            </label>

            <button type="submit" class="btn full" aria-label="Publier la note">
                Publier
            </button>
        </form>
    </x-card>

    {{-- Résumé état patient (pour aidants) --}}
    <x-card title="État du patient">
        <div class="statRow">
            <div class="statBox">
                <div class="statValue">{{ $patientStats['avgScore'] }}</div>
                <div class="statLabel">Score moyen</div>
            </div>
            <div class="statBox">
                <div class="statValue">{{ $patientStats['adherence'] }}%</div>
                <div class="statLabel">Observance</div>
            </div>
        </div>

        <div style="margin-top:16px">
            <div class="infoRow">
                <span class="muted">Humeur actuelle:</span>
                <strong>{{ $patientStats['mood'] ?? '—' }}</strong>
            </div>
            <div class="infoRow">
                <span class="muted">Qualité du sommeil:</span>
                <strong>{{ $patientStats['sleep'] ?? '—' }}</strong>
            </div>
            @if($patientStats['lastExercise'])
                <div class="infoRow">
                    <span class="muted">Dernier exercice:</span>
                    <strong>
                        {{ $patientStats['lastExercise']->getTypeLabel() }} 
                        ({{ $patientStats['lastExercise']->score }})
                    </strong>
                </div>
            @endif
        </div>
    </x-card>
@endsection

@push('styles')
<style>
    .noteCard {
        background: var(--elev);
        border: 1px solid var(--outline);
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
    }

    .noteHeader {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
        gap: 12px;
    }

    .noteContent {
        line-height: 1.6;
        white-space: pre-wrap;
    }

    .badge {
        display: inline-block;
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 6px;
        background: var(--brand);
        color: white;
        margin-left: 8px;
        font-weight: 600;
    }

    .infoRow {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid var(--outline);
    }

    .infoRow:last-child {
        border-bottom: none;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/modules/carers.js') }}"></script>
@endpush
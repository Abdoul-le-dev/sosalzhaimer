@extends('layouts.app')

@section('title', 'Suivi - App Aide Alzheimer')

@section('content')
    {{-- Statistiques principales --}}
    <x-card title="Votre progression">
        <div class="statRow">
            <div class="statBox">
                <div class="statValue">{{ $stats['avgScore'] }}</div>
                <div class="statLabel">Score moyen</div>
            </div>
            <div class="statBox">
                <div class="statValue">{{ $stats['sessionsThisWeek'] }}/{{ $stats['weeklyGoal'] }}</div>
                <div class="statLabel">Séances/semaine</div>
            </div>
        </div>

        {{-- Barre de progression --}}
        <div class="progress">
            <div class="bar" style="width: {{ min(100, $stats['avgScore']) }}%"></div>
        </div>
        <div class="muted" style="text-align:center">
            Observance: <strong>{{ $stats['adherence'] }}%</strong>
        </div>

        {{-- Actions --}}
        <div class="btnRow">
            <button class="btn" onclick="generateReport()" aria-label="Télécharger le rapport">
                📄 Rapport
            </button>
            <button class="btn secondary" onclick="exportData()" aria-label="Exporter les données">
                💾 Export
            </button>
            <button class="btn secondary" onclick="speak('Progression')" aria-label="Écouter les statistiques">
                🔊
            </button>
        </div>
    </x-card>

    {{-- Progression par type d'exercice --}}
    <x-card title="Détails par exercice">
        <div class="exerciseProgress">
            @foreach($progressByType as $exercise)
                <div class="progressItem">
                    <div class="progressHeader">
                        <div>
                            <strong>{{ $exercise['label'] }}</strong>
                            <span class="muted" style="font-size:13px">(x{{ $exercise['count'] }})</span>
                        </div>
                        <div style="display:flex; align-items:center; gap:8px">
                            <span style="font-weight:700; color:var(--brand)">
                                {{ $exercise['avgScore'] }}
                            </span>
                            @if($exercise['trend'] === 'up')
                                <span style="color:var(--brand-2); font-size:20px" aria-label="En amélioration">↗</span>
                            @elseif($exercise['trend'] === 'down')
                                <span style="color:var(--warn); font-size:20px" aria-label="En baisse">↘</span>
                            @else
                                <span style="color:var(--muted); font-size:20px" aria-label="Stable">→</span>
                            @endif
                        </div>
                    </div>
                    <div class="progress" style="margin-top:8px">
                        <div class="bar" style="width: {{ min(100, $exercise['avgScore']) }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </x-card>

    {{-- Graphique d'évolution --}}
    <x-card title="Évolution (30 derniers jours)">
        <div class="chartContainer">
            <canvas id="progressChart" aria-label="Graphique d'évolution des scores"></canvas>
        </div>
        <div class="muted" style="text-align:center; margin-top:12px">
            Total de séances : <strong>{{ $stats['totalSessions'] }}</strong>
        </div>
    </x-card>

    {{-- Notes des aidants --}}
    <x-card title="Notes des aidants">
        <div id="notesArea">
            @forelse($carerNotes as $note)
                <div class="noteItem">
                    <div class="muted" style="font-size:12px; margin-bottom:4px">
                        {{ \Carbon\Carbon::createFromTimestamp($note['ts'] / 1000)->translatedFormat('j M Y à H:i') }}
                    </div>
                    <div>{{ $note['text'] }}</div>
                </div>
                @if(!$loop->last)
                    <hr style="border:none; border-top:1px solid var(--outline); margin:12px 0">
                @endif
            @empty
                <div class="muted">Aucune note récente.</div>
            @endforelse
        </div>
        <a href="{{ route('carers.index') }}" class="btn ghost full" style="margin-top:12px">
            Voir toutes les notes
        </a>
    </x-card>
@endsection

@push('styles')
<style>
    .exerciseProgress {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .progressItem {
        padding: 12px;
        background: var(--elev);
        border-radius: 12px;
        border: 1px solid var(--outline);
    }

    .progressHeader {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .noteItem {
        padding: 12px;
        background: var(--elev);
        border-radius: 8px;
        margin-bottom: 8px;
    }

    .chartContainer {
        position: relative;
        height: 200px;
        margin: 16px 0;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="{{ asset('js/modules/tracking.js') }}"></script>
<script>
    // Données pour le graphique
    const historyData = @json($history);
    initChart(historyData);
</script>
@endpush
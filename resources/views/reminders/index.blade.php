@extends('layouts.app')

@section('title', 'Agenda - App Aide Alzheimer')

@section('content')
    {{-- Rappels d'aujourd'hui --}}
    <x-card title="Aujourd'hui">
        <ul id="todayList" class="list">
            @forelse($todayReminders as $reminder)
                <li class="li" data-id="{{ $reminder->id }}">
                    <div class="li-left">
                        <div style="font-weight:600">
                            {{ $reminder->getFormattedTime() }} — {{ $reminder->title }}
                        </div>
                        @if($reminder->isLate() && !$reminder->done)
                            <div class="muted" style="color:var(--warn)">
                                ⚠️ En retard
                            </div>
                        @endif
                    </div>
                    <div class="li-right">
                        @if($reminder->done)
                            <span style="font-size:24px" aria-label="Terminé">✅</span>
                        @else
                            <button class="btn" 
                                    style="min-height:40px; padding:0 12px; font-size:13px" 
                                    onclick="markDone({{ $reminder->id }})"
                                    aria-label="Marquer comme fait">
                                ✓
                            </button>
                            <button class="btn secondary" 
                                    style="min-height:40px; padding:0 12px; font-size:13px" 
                                    onclick="deleteReminder({{ $reminder->id }})"
                                    aria-label="Supprimer">
                                🗑
                            </button>
                        @endif
                    </div>
                </li>
            @empty
                <li class="li">
                    <span class="muted">Aucun rappel aujourd'hui</span>
                </li>
            @endforelse
        </ul>
        
        <button class="btn full" onclick="openReminderSheet()" aria-label="Ajouter un rappel">
            + Ajouter un rappel
        </button>
    </x-card>

    {{-- Rappels à venir --}}
    @if($upcomingReminders->isNotEmpty())
        <x-card title="Prochains rappels">
            @foreach($upcomingReminders as $date => $reminders)
                <div style="margin-bottom:16px">
                    <div style="font-weight:700; margin-bottom:8px; color:var(--brand)">
                        {{ Carbon\Carbon::parse($date)->translatedFormat('l j F') }}
                    </div>
                    <ul class="list">
                        @foreach($reminders as $reminder)
                            <li class="li">
                                <div class="li-left">
                                    {{ $reminder->getFormattedTime() }} — {{ $reminder->title }}
                                    @if($reminder->repeat !== 'none')
                                        <span class="muted" style="font-size:12px">
                                            🔁 {{ $reminder->getRepeatLabel() }}
                                        </span>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </x-card>
    @endif
@endsection

@push('scripts')
<script src="{{ asset('js/modules/reminders.js') }}"></script>
@endpush
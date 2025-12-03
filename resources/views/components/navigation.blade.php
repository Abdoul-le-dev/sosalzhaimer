<nav>
    <div class="tabs">
        {{-- Accueil --}}
        <a href="{{ route('home') }}" 
           class="tabbtn {{ request()->routeIs('home') ? 'active' : '' }}"
           aria-label="Accueil">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M3 10.5 12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1v-9.5Z" 
                      stroke="currentColor" stroke-width="2"/>
            </svg>
            Accueil
        </a>
        
        {{-- Exercices --}}
        <a href="{{ route('exercises.index') }}" 
           class="tabbtn {{ request()->routeIs('exercises.*') ? 'active' : '' }}"
           aria-label="Exercices">
            <svg viewBox="0 0 24 24" fill="none">
                <circle cx="6" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
                <circle cx="18" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
            </svg>
            Exercices
        </a>
        
        {{-- Agenda --}}
        <a href="{{ route('reminders.index') }}" 
           class="tabbtn {{ request()->routeIs('reminders.*') ? 'active' : '' }}"
           aria-label="Agenda">
            <svg viewBox="0 0 24 24" fill="none">
                <rect x="3" y="5" width="18" height="16" rx="2" stroke="currentColor" stroke-width="2"/>
                <path d="M3 9h18" stroke="currentColor" stroke-width="2"/>
            </svg>
            Agenda
        </a>
        
        {{-- Suivi --}}
        <a href="{{ route('tracking.index') }}" 
           class="tabbtn {{ request()->routeIs('tracking.*') ? 'active' : '' }}"
           aria-label="Suivi">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M4 15l4-5 4 3 4-6 4 8" stroke="currentColor" stroke-width="2"/>
            </svg>
            Suivi
        </a>
        
        {{-- Aidants --}}
        <a href="{{ route('carers.index') }}" 
           class="tabbtn {{ request()->routeIs('carers.*') ? 'active' : '' }}"
           aria-label="Aidants">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M7 7a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm10 0a3 3 0 1 0 0 6 3 3 0 0 0 0-6ZM3 19a4 4 0 0 1 4-4h2a4 4 0 0 1 4 4v2H3v-2Zm10 2v-2a4 4 0 0 1 4-4h0a4 4 0 0 1 4 4v2h-8Z" 
                      stroke="currentColor" stroke-width="2"/>
            </svg>
            Aidants
        </a>
    </div>
</nav>
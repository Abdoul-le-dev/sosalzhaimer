<header>
    <div class="brandRow">
        {{-- Bouton SOS --}}
        <button class="sos" onclick="openSOS()" aria-label="Urgence">SOS</button>
        
        <div class="userInfo">
            <div class="title">Bonjour <span id="username">{{ session('username', 'Marie') }}</span></div>
            <div class="sub" id="today">{{ now()->translatedFormat('l j F') }}</div>
        </div>
    </div>
    
    <div class="headerActions">
        {{-- Changement de thème --}}
        <button class="iconBtn" onclick="toggleTheme()" aria-label="Changer le thème">
            🌓
        </button>
        
        {{-- Agrandissement du texte --}}
        <button class="iconBtn" onclick="biggerText()" aria-label="Agrandir le texte">
            A+
        </button>
    </div>
</header>
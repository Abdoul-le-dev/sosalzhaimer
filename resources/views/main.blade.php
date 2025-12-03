

<div class="app">
  <!-- Header -->
  <header>
    <div class="brandRow">
      <button class="sos" onclick="openSOS()" aria-label="Urgence">SOS</button>
      <div class="userInfo">
        <div class="title">Bonjour <span id="username">Marie</span></div>
        <div class="sub" id="today"></div>
      </div>
    </div>
    <div class="headerActions">
      <button class="iconBtn" onclick="toggleTheme()" aria-label="Changer thème">🌓</button>
      <button class="iconBtn" onclick="biggerText()" aria-label="Agrandir texte">A+</button>
    </div>
  </header>

  <!-- Main Content -->
  <main id="content">
    <!-- Accueil -->
    <section id="tab-home" class="active">
      <div class="card">
        <h3>Exercice du jour</h3>
        <div class="muted">Mémoire visuelle • 3–5 min</div>
        <div class="btnRow">
          <button class="btn" onclick="startMemory()">▶ Commencer</button>
          <button class="btn secondary" onclick="speak('Exercice du jour')">🔊</button>
        </div>
      </div>

      <div class="card">
        <h3>Rappels à venir</h3>
        <ul id="nextReminders" class="list"></ul>
        <button class="btn ghost full" onclick="goTab('tab-agenda')">Voir l'agenda</button>
      </div>

      <div class="card">
        <h3>Comment allez-vous ?</h3>
        <div class="muted" style="margin-bottom:8px">Humeur actuelle: <strong id="mood">—</strong></div>
        <div class="pillRow">
          <button class="pill" onclick="setMood('🙂')">🙂</button>
          <button class="pill" onclick="setMood('😐')">😐</button>
          <button class="pill" onclick="setMood('🙁')">🙁</button>
        </div>
        <div class="field" style="margin-top:16px">
          <label for="sleep">Qualité du sommeil</label>
          <select id="sleep" onchange="saveSleep()">
            <option value="">Choisir...</option>
            <option>Très bon</option>
            <option>Correct</option>
            <option>Mauvais</option>
          </select>
        </div>
      </div>
    </section>

    <!-- Exercices -->
    <section id="tab-exos">
      <div class="card">
        <h3>Exercices disponibles</h3>
        <div class="btnGrid">
          <button class="btn" onclick="startMemory()">🧠 Mémoire</button>
          <button class="btn secondary" onclick="startAttention()">👁 Attention</button>
          <button class="btn secondary" onclick="startLanguage()">💬 Langage</button>
          <button class="btn secondary" onclick="startOrientation()">📍 Orientation</button>
        </div>
      </div>

      <div id="exerciseArea" class="card" style="display:none">
        <h3 id="exerciseTitle">Séance</h3>
        <div id="exerciseContent"></div>
        <div class="btnRow">
          <button class="btn" onclick="validateExercise()">✓ Valider</button>
          <button class="btn secondary" onclick="cancelExercise()">Quitter</button>
        </div>
      </div>

      <div id="exerciseResult" class="card" style="display:none">
        <h3>Résultat</h3>
        <div id="resultText" style="font-size:18px; margin:16px 0"></div>
        <button class="btn full" onclick="cancelExercise()">Terminer</button>
      </div>
    </section>

    <!-- Agenda -->
    <section id="tab-agenda">
      <div class="card">
        <h3>Aujourd'hui</h3>
        <ul id="agendaList" class="list"></ul>
        <button class="btn full" onclick="openReminderSheet()">+ Ajouter un rappel</button>
      </div>
    </section>

    <!-- Suivi -->
    <section id="tab-track">
      <div class="card">
        <h3>Votre progression</h3>
        <div class="statRow">
          <div class="statBox">
            <div class="statValue" id="avgScoreDisplay">0</div>
            <div class="statLabel">Score moyen</div>
          </div>
          <div class="statBox">
            <div class="statValue"><span id="regularity">0</span>/4</div>
            <div class="statLabel">Séances/semaine</div>
          </div>
        </div>
        <div class="progress">
          <div id="bar" class="bar"></div>
        </div>
        <div class="muted" style="text-align:center">Observance: <strong id="adherence">0</strong>%</div>
        <div class="btnRow">
          <button class="btn" onclick="generateReport()">📄 Rapport</button>
          <button class="btn secondary" onclick="speak('Progression stable')">🔊</button>
        </div>
      </div>

      <div class="card">
        <h3>Notes des aidants</h3>
        <div id="notesArea" class="muted">Aucune note.</div>
      </div>
    </section>

    <!-- Aidants -->
    <section id="tab-carers">
      <div class="card">
        <h3>Fil des aidants</h3>
        <div id="feed"></div>
      </div>
      
      <div class="card">
        <h3>Nouvelle note</h3>
        <div class="field">
          <textarea id="noteText" placeholder="Écrire une note..."></textarea>
        </div>
        <label style="display:flex; align-items:center; gap:8px; margin-bottom:12px">
          <input type="checkbox" id="visiblePatient" checked style="width:20px; height:20px">
          <span>Visible par le patient</span>
        </label>
        <button class="btn full" onclick="postNote()">Publier</button>
      </div>
    </section>
  </main>

  <!-- Bottom Navigation -->
  <nav>
    <div class="tabs">
      <button class="tabbtn active" onclick="goTab('tab-home', this)">
        <svg viewBox="0 0 24 24" fill="none"><path d="M3 10.5 12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1v-9.5Z" stroke="currentColor" stroke-width="2"/></svg>
        Accueil
      </button>
      <button class="tabbtn" onclick="goTab('tab-exos', this)">
        <svg viewBox="0 0 24 24" fill="none"><circle cx="6" cy="12" r="3" stroke="currentColor" stroke-width="2"/><circle cx="18" cy="12" r="3" stroke="currentColor" stroke-width="2"/></svg>
        Exercices
      </button>
      <button class="tabbtn" onclick="goTab('tab-agenda', this)">
        <svg viewBox="0 0 24 24" fill="none"><rect x="3" y="5" width="18" height="16" rx="2" stroke="currentColor" stroke-width="2"/><path d="M3 9h18" stroke="currentColor" stroke-width="2"/></svg>
        Agenda
      </button>
      <button class="tabbtn" onclick="goTab('tab-track', this)">
        <svg viewBox="0 0 24 24" fill="none"><path d="M4 15l4-5 4 3 4-6 4 8" stroke="currentColor" stroke-width="2"/></svg>
        Suivi
      </button>
      <button class="tabbtn" onclick="goTab('tab-carers', this)">
        <svg viewBox="0 0 24 24" fill="none"><path d="M7 7a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm10 0a3 3 0 1 0 0 6 3 3 0 0 0 0-6ZM3 19a4 4 0 0 1 4-4h2a4 4 0 0 1 4 4v2H3v-2Zm10 2v-2a4 4 0 0 1 4-4h0a4 4 0 0 1 4 4v2h-8Z" stroke="currentColor" stroke-width="2"/></svg>
        Aidants
      </button>
    </div>
  </nav>
</div>

<!-- Backdrop -->
<div class="backdrop" id="backdrop" onclick="closeAllSheets()"></div>

<!-- Reminder Sheet -->
<div id="remSheet" class="sheet">
  <div class="handle"></div>
  <h4>Nouveau rappel</h4>
  <div class="field">
    <label>Titre</label>
    <input id="remTitleInput" class="input" placeholder="Ex. Médicament, RDV...">
  </div>
  <div class="fieldRow">
    <div class="field">
      <label>Date</label>
      <input id="remDate" type="date" class="input">
    </div>
    <div class="field">
      <label>Heure</label>
      <input id="remTime" type="time" class="input">
    </div>
  </div>
  <div class="field">
    <label>Répétition</label>
    <select id="remRepeat" class="input">
      <option value="none">Aucune</option>
      <option value="daily">Quotidien</option>
      <option value="weekly">Hebdomadaire</option>
    </select>
  </div>
  <div class="btnRow">
    <button class="btn" onclick="saveReminder()">Enregistrer</button>
    <button class="btn secondary" onclick="closeReminderSheet()">Annuler</button>
  </div>
</div>

<!-- SOS Sheet -->
<div id="sosSheet" class="sheet">
  <div class="handle"></div>
  <h4>🚨 Alerte SOS</h4>
  <p style="margin:16px 0; text-align:center">Confirmer l'appel d'urgence vers votre contact enregistré.</p>
  <div class="btnRow">
    <button class="btn" style="background:var(--danger)" onclick="confirmSOS()">📞 Appeler</button>
    <button class="btn secondary" onclick="closeSOS()">Annuler</button>
  </div>
</div>

<!-- Toasts -->
<div class="toasts"></div>


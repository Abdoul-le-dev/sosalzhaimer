/**
 * Module Exercices - Gestion des exercices cognitifs
 */

let currentExercise = null;

/**
 * Démarre un exercice
 */
function startExercise(type) {
    fetch(`/exercises/start/${type}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            currentExercise = data;
            renderExercise(data);
        }
    })
    .catch(err => {
        console.error('Erreur:', err);
        showToast('Erreur lors du chargement de l\'exercice');
    });
}

/**
 * Affiche l'exercice selon son type
 */
function renderExercise(exercise) {
    const area = document.getElementById('exerciseArea');
    const content = document.getElementById('exerciseContent');
    const title = document.getElementById('exerciseTitle');
    
    // Masquer les autres zones
    document.getElementById('exerciseResult').style.display = 'none';
    
    // Affichage selon le type
    switch(exercise.type) {
        case 'memory':
            renderMemoryExercise(exercise.data, title, content);
            break;
        case 'attention':
            renderAttentionExercise(exercise.data, title, content);
            break;
        case 'language':
            renderLanguageExercise(exercise.data, title, content);
            break;
        case 'orientation':
            renderOrientationExercise(exercise.data, title, content);
            break;
    }
    
    area.style.display = 'block';
    area.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

/**
 * Exercice de mémoire
 */
function renderMemoryExercise(data, title, content) {
    title.textContent = 'Mémoire — Mémorisez ces symboles';
    currentExercise.picks = [];
    
    // Phase d'apprentissage (5 secondes)
    content.innerHTML = `
        <div style="font-size:3rem; text-align:center; margin:24px 0">
            ${data.correct.join(' ')}
        </div>
        <div class="muted" style="text-align:center">
            ${data.instruction}
        </div>
    `;
    
    // Après 5 secondes, phase de sélection
    setTimeout(() => {
        title.textContent = 'Mémoire — Sélectionnez les symboles';
        content.innerHTML = `
            <div class="muted" style="margin-bottom:12px">
                Cliquez sur les symboles que vous avez vus :
            </div>
            <div class="emojiGrid">
                ${data.pool.map(emoji => 
                    `<button class="emojiBtn" onclick="pickEmoji('${emoji}')" aria-label="Sélectionner ${emoji}">
                        ${emoji}
                    </button>`
                ).join('')}
            </div>
        `;
    }, 5000);
}

/**
 * Sélection d'un emoji (mémoire)
 */
function pickEmoji(emoji) {
    if (!currentExercise.picks.includes(emoji)) {
        currentExercise.picks.push(emoji);
        event.target.classList.add('selected');
        event.target.setAttribute('aria-pressed', 'true');
    } else {
        // Dé-sélection
        const index = currentExercise.picks.indexOf(emoji);
        currentExercise.picks.splice(index, 1);
        event.target.classList.remove('selected');
        event.target.setAttribute('aria-pressed', 'false');
    }
}

/**
 * Exercice d'attention
 */
function renderAttentionExercise(data, title, content) {
    title.textContent = 'Attention — Repérez les triangles';
    currentExercise.picks = [];
    
    content.innerHTML = `
        <div class="muted" style="margin-bottom:12px">
            ${data.instruction}
        </div>
        <div class="emojiGrid">
            ${data.grid.map((shape, i) => 
                `<button class="emojiBtn" onclick="markCell(${i})" aria-label="Cellule ${i+1}: ${shape}">
                    ${shape}
                </button>`
            ).join('')}
        </div>
    `;
}

/**
 * Marque une cellule (attention)
 */
function markCell(index) {
    if (!currentExercise.picks.includes(index)) {
        currentExercise.picks.push(index);
        event.target.classList.add('selected');
        event.target.setAttribute('aria-pressed', 'true');
    } else {
        const idx = currentExercise.picks.indexOf(index);
        currentExercise.picks.splice(idx, 1);
        event.target.classList.remove('selected');
        event.target.setAttribute('aria-pressed', 'false');
    }
}

/**
 * Exercice de langage
 */
function renderLanguageExercise(data, title, content) {
    title.textContent = 'Langage — Écrire un mot';
    currentExercise.answer = '';
    
    content.innerHTML = `
        <div class="muted" style="margin-bottom:12px">
            ${data.instruction}
        </div>
        <div class="field">
            <input id="langInput" 
                   class="input" 
                   type="text"
                   placeholder="Écrivez un des mots" 
                   oninput="currentExercise.answer = this.value"
                   aria-label="Votre réponse">
        </div>
    `;
    
    // Focus automatique
    setTimeout(() => document.getElementById('langInput')?.focus(), 100);
}

/**
 * Exercice d'orientation
 */
function renderOrientationExercise(data, title, content) {
    title.textContent = 'Orientation — Question temporelle';
    currentExercise.answer = '';
    
    content.innerHTML = `
        <div class="muted" style="margin-bottom:12px; font-size:16px">
            <strong>${data.question}</strong>
        </div>
        <div class="pillRow">
            ${data.options.map(opt => 
                `<button class="pill" 
                         onclick="selectOrientation('${opt}')"
                         aria-label="Sélectionner ${opt}">
                    ${opt}
                </button>`
            ).join('')}
        </div>
        <div class="muted" style="margin-top:12px">
            Choisi : <strong id="picked">—</strong>
        </div>
    `;
}

/**
 * Sélection réponse orientation
 */
function selectOrientation(value) {
    currentExercise.answer = value;
    document.getElementById('picked').textContent = value;
    
    // Retirer la classe selected de tous les boutons
    document.querySelectorAll('.pillRow .pill').forEach(btn => {
        btn.classList.remove('selected');
        btn.setAttribute('aria-pressed', 'false');
    });
    
    // Ajouter au bouton cliqué
    event.target.classList.add('selected');
    event.target.setAttribute('aria-pressed', 'true');
}

/**
 * Valide l'exercice en cours
 */
function validateExercise() {
    if (!currentExercise) {
        showToast('Aucun exercice en cours');
        return;
    }
    
    const payload = {
        picks: currentExercise.picks,
        answer: currentExercise.answer
    };
    
    fetch('/exercises/validate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showResult(data.score, data.feedback);
        }
    })
    .catch(err => {
        console.error('Erreur:', err);
        showToast('Erreur lors de la validation');
    });
}

/**
 * Affiche le résultat
 */
function showResult(score, feedback) {
    document.getElementById('exerciseArea').style.display = 'none';
    
    const resultDiv = document.getElementById('exerciseResult');
    const contentDiv = document.getElementById('resultContent');
    
    contentDiv.innerHTML = `
        <div class="statValue" style="text-align:center">${score}</div>
        <div class="muted" style="text-align:center; font-size:18px; margin-top:12px">
            ${feedback}
        </div>
    `;
    
    resultDiv.style.display = 'block';
    resultDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
    
    showToast('Séance terminée !');
}

/**
 * Annule l'exercice
 */
function cancelExercise() {
    document.getElementById('exerciseArea').style.display = 'none';
    document.getElementById('exerciseResult').style.display = 'none';
    currentExercise = null;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

/**
 * Termine et ferme le résultat
 */
function finishExercise() {
    cancelExercise();
}
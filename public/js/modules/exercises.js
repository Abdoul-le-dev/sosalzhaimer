/**
 * ============================================
 * MODULE EXERCICES - JavaScript complet
 * Gestion de tous les types d'exercices
 * ============================================
 */

// Variables globales
let currentExercise = null;
let exerciseTimer = null;
let intervalTimer = null;
let currentStep = 0;
let draggedElement = null;

/**
 * ===========================
 * DÉMARRAGE D'UN EXERCICE
 * ===========================
 */

alert('js')
function startExercise(type) {
    showToast('Chargement de l\'exercice...');
    
    // Simuler un appel API (à remplacer par fetch réel)
    setTimeout(() => {
        currentExercise = {
            type: type,
            startedAt: Date.now(),
            data: generateExerciseData(type)
        };
        
        renderExercise(type);
        scrollToExercise();
    }, 500);
}

/**
 * Génère les données de l'exercice selon le type
 */
function generateExerciseData(type) {
    switch(type) {
        case 'face_name':
            return {
                faces: [
                    { name: 'Marie', image: 'https://randomuser.me/api/portraits/women/1.jpg' },
                    { name: 'Pierre', image: 'https://randomuser.me/api/portraits/men/2.jpg' },
                    { name: 'Sophie', image: 'https://randomuser.me/api/portraits/women/3.jpg' },
                    { name: 'Jean', image: 'https://randomuser.me/api/portraits/men/4.jpg' }
                ],
                correct: ['Marie', 'Pierre', 'Sophie']
            };
        
        case 'spaced_retrieval':
            return {
                question: 'Où sont les clés de la maison ?',
                answer: 'Dans la boîte bleue près de la porte',
                image: 'https://images.unsplash.com/photo-1582139329536-e7284fece509?w=400'
            };
        
        case 'memory':
            return {
                symbols: ['🍎', '🔑', '🌼', '🚗', '🐱', '⚽️'],
                correct: ['🍎', '🔑', '🌼']
            };
        
        case 'attention':
            return {
                grid: ['▲', '●', '■', '●', '▲', '■', '●', '▲', '■'],
                target: '▲'
            };
        
        case 'orientation':
            return {
                question: 'Quel jour sommes-nous ?',
                answer: new Date().toLocaleDateString('fr-FR', { weekday: 'long' }),
                options: ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche']
            };
        
        case 'reminiscence':
            return {
                image: 'https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?w=600',
                questions: [
                    'Qui voyez-vous sur cette photo ?',
                    'Où cette photo a-t-elle été prise ?',
                    'Vous souvenez-vous de ce jour ?'
                ]
            };
        
        case 'word_games':
            return {
                category: 'fruits',
                required: 5,
                examples: ['pomme', 'poire', 'banane', 'orange', 'fraise']
            };
        
        case 'simple_calc':
            return {
                operations: generateCalculations(5)
            };
        
        case 'daily_task':
            return {
                name: 'Faire du thé',
                steps: 6
            };
        
        case 'montessori_sorting':
            return {
                items: [
                    { name: 'Pomme', category: 'rouge', image: 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?w=200' },
                    { name: 'Citron', category: 'jaune', image: 'https://images.unsplash.com/photo-1587486937314-9c1f33556076?w=200' },
                    { name: 'Tomate', category: 'rouge', image: 'https://images.unsplash.com/photo-1546094096-0df4bcaaa337?w=200' },
                    { name: 'Banane', category: 'jaune', image: 'https://images.unsplash.com/photo-1571771894821-ce9b6c11b08e?w=200' },
                    { name: 'Raisin', category: 'vert', image: 'https://images.unsplash.com/photo-1596363505729-4190a9506133?w=200' },
                    { name: 'Concombre', category: 'vert', image: 'https://images.unsplash.com/photo-1604977042946-1eecc30f269e?w=200' }
                ]
            };
        
        case 'sequence':
            return {
                title: 'Se laver les mains',
                steps: 6
            };
        
        case 'music_therapy':
            return {
                playlist: 'Chansons françaises des années 60',
                songs: [
                    { title: 'La Bohème', artist: 'Charles Aznavour' },
                    { title: 'Non, je ne regrette rien', artist: 'Édith Piaf' },
                    { title: 'Les Champs-Élysées', artist: 'Joe Dassin' }
                ]
            };
        
        default:
            return {};
    }
}

/**
 * ===========================
 * RENDU DE L'EXERCICE
 * ===========================
 */
function renderExercise(type) {
    const area = document.getElementById('exerciseArea');
    const content = document.getElementById('exerciseContent');
    const title = document.getElementById('exerciseTitle');
    
    // Masquer résultat
    document.getElementById('exerciseResult').style.display = 'none';
    
    // Réinitialiser progression
    document.getElementById('exerciseProgressBar').style.width = '0%';
    
    // Charger le template correspondant
    const template = document.getElementById(`template${capitalize(camelCase(type))}`);
    
    if (template) {
        content.innerHTML = template.innerHTML;
    } else {
        content.innerHTML = `<p class="muted">Template non trouvé pour ${type}</p>`;
    }
    
    // Titre selon le type
    const titles = {
        'face_name': '👤 Visage-Nom',
        'spaced_retrieval': '🔑 Récupération espacée',
        'memory': '🧠 Mémoire d\'images',
        'attention': '👁 Attention sélective',
        'orientation': '📅 Orientation temporelle',
        'reminiscence': '📸 Réminiscence',
        'word_games': '💬 Jeux de mots',
        'simple_calc': '🔢 Calculs simples',
        'daily_task': '☕ Tâche quotidienne',
        'montessori_sorting': '🎨 Tri Montessori',
        'sequence': '📋 Séquence d\'actions',
        'music_therapy': '🎵 Musicothérapie'
    };
    
    title.textContent = titles[type] || 'Exercice';
    
    // Initialiser l'exercice spécifique
    initExercise(type);
    
    // Afficher la zone
    area.style.display = 'block';
}

/**
 * Initialise les comportements spécifiques par exercice
 */
function initExercise(type) {
    switch(type) {
        case 'face_name':
            initFaceNameExercise();
            break;
        case 'spaced_retrieval':
            initSpacedRetrievalExercise();
            break;
        case 'memory':
            initMemoryExercise();
            break;
        case 'daily_task':
            initDailyTaskExercise();
            break;
        case 'montessori_sorting':
            initMontessoriSortingExercise();
            break;
        case 'music_therapy':
            initMusicTherapyExercise();
            break;
        default:
            updateProgress(100);
    }
}

/**
 * ===========================
 * EXERCICE VISAGE-NOM
 * ===========================
 */
function initFaceNameExercise() {
    currentExercise.picks = [];
    currentExercise.phase = 'learning';
    
    // Phase d'apprentissage (10 secondes)
    let timeLeft = 10;
    const timerEl = document.getElementById('learningTimer');
    
    exerciseTimer = setInterval(() => {
        timeLeft--;
        if (timerEl) timerEl.textContent = timeLeft;
        
        if (timeLeft <= 0) {
            clearInterval(exerciseTimer);
            showTestPhaseFaceName();
        }
    }, 1000);
}

function showTestPhaseFaceName() {
    currentExercise.phase = 'testing';
    
    const content = document.getElementById('exerciseContent');
    const data = currentExercise.data;
    
    let html = '<div class="exerciseInstructions">';
    html += '<p><strong>Sélectionnez les 3 visages que vous avez vus</strong></p>';
    html += '</div>';
    
    html += '<div class="faceGrid">';
    data.faces.forEach(face => {
        html += `
            <div class="faceCard" onclick="toggleFaceSelection('${face.name}', this)">
                <img src="${face.image}" alt="${face.name}">
                <div class="faceName">${face.name}</div>
            </div>
        `;
    });
    html += '</div>';
    
    html += '<div class="timer">';
    html += '<span class="muted">Sélectionnés:</span> ';
    html += '<strong id="selectedCount">0</strong>';
    html += '<span class="muted">/3</span>';
    html += '</div>';
    
    content.innerHTML = html;
    updateProgress(50);
}

function toggleFaceSelection(name, element) {
    const picks = currentExercise.picks;
    const index = picks.indexOf(name);
    
    if (index > -1) {
        picks.splice(index, 1);
        element.classList.remove('selected');
    } else {
        if (picks.length < 3) {
            picks.push(name);
            element.classList.add('selected');
        } else {
            showToast('Vous avez déjà sélectionné 3 visages');
            return;
        }
    }
    
    const countEl = document.getElementById('selectedCount');
    if (countEl) countEl.textContent = picks.length;
    
    updateProgress(50 + (picks.length / 3) * 50);
}

/**
 * ===========================
 * EXERCICE RÉCUPÉRATION ESPACÉE
 * ===========================
 */
function initSpacedRetrievalExercise() {
    currentExercise.intervals = [5, 10, 15]; // Secondes
    currentExercise.currentInterval = 0;
    
    // Démarrer le premier intervalle
    startIntervalCountdown();
}

function startIntervalCountdown() {
    const data = currentExercise.data;
    const intervals = currentExercise.intervals;
    const currentIdx = currentExercise.currentInterval;
    
    if (currentIdx >= intervals.length) {
        // Tous les intervalles terminés
        updateProgress(100);
        showToast('Exercice terminé !');
        return;
    }
    
    let timeLeft = intervals[currentIdx];
    const timerEl = document.getElementById('intervalTimer');
    
    intervalTimer = setInterval(() => {
        timeLeft--;
        if (timerEl) timerEl.textContent = timeLeft;
        
        if (timeLeft <= 0) {
            clearInterval(intervalTimer);
            promptRecall();
        }
    }, 1000);
    
    updateProgress((currentIdx / intervals.length) * 100);
}

function promptRecall() {
    const data = currentExercise.data;
    const content = document.getElementById('exerciseContent');
    
    let html = '<div class="spacedRetrievalContent">';
    html += `<p class="spacedQuestion">${data.question}</p>`;
    html += '<div class="field">';
    html += '<textarea id="recallAnswer" class="input" placeholder="Écrivez votre réponse..." style="min-height:100px"></textarea>';
    html += '</div>';
    html += '<button class="btn full" onclick="checkRecallAnswer()">Vérifier</button>';
    html += '</div>';
    
    content.innerHTML = html;
    document.getElementById('recallAnswer').focus();
}

function checkRecallAnswer() {
    const userAnswer = document.getElementById('recallAnswer').value.toLowerCase().trim();
    const correctAnswer = currentExercise.data.answer.toLowerCase();
    
    if (userAnswer.includes('boîte') && userAnswer.includes('bleue')) {
        showToast('✓ Correct !');
        currentExercise.currentInterval++;
        
        // Réafficher l'information et passer à l'intervalle suivant
        setTimeout(() => {
            const template = document.getElementById('templateSpacedRetrieval');
            document.getElementById('exerciseContent').innerHTML = template.innerHTML;
            startIntervalCountdown();
        }, 1500);
    } else {
        showToast('Réessayez, voici un indice...');
        document.getElementById('exerciseContent').innerHTML += `
            <div class="muted" style="text-align:center; margin-top:16px; padding:12px; background:var(--elev); border-radius:8px;">
                💡 Indice: ${currentExercise.data.answer}
            </div>
        `;
    }
}

/**
 * ===========================
 * EXERCICE MÉMOIRE D'IMAGES
 * ===========================
 */
function initMemoryExercise() {
    currentExercise.picks = [];
    const data = currentExercise.data;
    
    const content = document.getElementById('exerciseContent');
    
    // Phase 1: Apprentissage
    let html = '<div class="exerciseInstructions">';
    html += '<p><strong>Mémorisez ces 3 symboles pendant 5 secondes</strong></p>';
    html += '</div>';
    
    html += '<div style="font-size:4rem; text-align:center; margin:32px 0;">';
    html += data.correct.join(' ');
    html += '</div>';
    
    html += '<div class="timer">';
    html += '<span class="muted">Temps restant:</span> ';
    html += '<strong id="memoryTimer">5</strong>';
    html += '<span class="muted">secondes</span>';
    html += '</div>';
    
    content.innerHTML = html;
    
    // Timer
    let timeLeft = 5;
    const timerEl = document.getElementById('memoryTimer');
    
    exerciseTimer = setInterval(() => {
        timeLeft--;
        if (timerEl) timerEl.textContent = timeLeft;
        
        if (timeLeft <= 0) {
            clearInterval(exerciseTimer);
            showTestPhaseMemory();
        }
    }, 1000);
}

function showTestPhaseMemory() {
    const content = document.getElementById('exerciseContent');
    const data = currentExercise.data;
    
    let html = '<div class="exerciseInstructions">';
    html += '<p><strong>Cliquez sur les 3 symboles que vous avez vus</strong></p>';
    html += '</div>';
    
    html += '<div class="emojiGrid">';
    data.symbols.forEach(symbol => {
        html += `
            <button class="emojiBtn" onclick="pickSymbol('${symbol}', this)">
                ${symbol}
            </button>
        `;
    });
    html += '</div>';
    
    html += '<div class="timer">';
    html += '<span class="muted">Sélectionnés:</span> ';
    html += '<strong id="symbolCount">0</strong>';
    html += '<span class="muted">/3</span>';
    html += '</div>';
    
    content.innerHTML = html;
    updateProgress(50);
}

function pickSymbol(symbol, element) {
    const picks = currentExercise.picks;
    const index = picks.indexOf(symbol);
    
    if (index > -1) {
        picks.splice(index, 1);
        element.classList.remove('selected');
    } else {
        if (picks.length < 3) {
            picks.push(symbol);
            element.classList.add('selected');
        } else {
            showToast('Vous avez déjà sélectionné 3 symboles');
            return;
        }
    }
    
    const countEl = document.getElementById('symbolCount');
    if (countEl) countEl.textContent = picks.length;
    
    updateProgress(50 + (picks.length / 3) * 50);
}

/**
 * ===========================
 * EXERCICE TÂCHE QUOTIDIENNE
 * ===========================
 */
function initDailyTaskExercise() {
    currentStep = 0;
    updateProgress(0);
}

function completeCurrentStep() {
    const steps = document.querySelectorAll('.taskStep');
    
    if (currentStep < steps.length) {
        steps[currentStep].classList.add('completed');
        steps[currentStep].classList.remove('active');
        
        currentStep++;
        
        if (currentStep < steps.length) {
            steps[currentStep].classList.add('active');
            updateProgress((currentStep / steps.length) * 100);
            showToast(`✓ Étape ${currentStep} terminée`);
            
            // Scroll vers l'étape suivante
            steps[currentStep].scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            updateProgress(100);
            showToast('🎉 Toutes les étapes terminées !');
            setTimeout(() => validateExercise(), 2000);
        }
    }
}

/**
 * ===========================
 * EXERCICE TRI MONTESSORI
 * ===========================
 */
function initMontessoriSortingExercise() {
    currentExercise.sorted = 0;
    currentExercise.total = 6;
    
    const items = document.querySelectorAll('.sortingItem');
    const categories = document.querySelectorAll('.sortingCategory');
    
    // Touch events pour mobile
    items.forEach(item => {
        item.addEventListener('touchstart', handleTouchStart, { passive: false });
        item.addEventListener('touchmove', handleTouchMove, { passive: false });
        item.addEventListener('touchend', handleTouchEnd, { passive: false });
    });
    
    // Drag events pour desktop
    items.forEach(item => {
        item.addEventListener('dragstart', handleDragStart);
        item.addEventListener('dragend', handleDragEnd);
    });
    
    categories.forEach(cat => {
        cat.addEventListener('dragover', handleDragOver);
        cat.addEventListener('drop', handleDrop);
        cat.addEventListener('dragleave', handleDragLeave);
    });
}

let touchStartY = 0;
let touchStartX = 0;

function handleTouchStart(e) {
    draggedElement = e.target.closest('.sortingItem');
    if (draggedElement) {
        draggedElement.classList.add('dragging');
        touchStartY = e.touches[0].clientY;
        touchStartX = e.touches[0].clientX;
    }
}

function handleTouchMove(e) {
    e.preventDefault();
    if (!draggedElement) return;
    
    const touch = e.touches[0];
    draggedElement.style.position = 'fixed';
    draggedElement.style.left = touch.clientX - 60 + 'px';
    draggedElement.style.top = touch.clientY - 60 + 'px';
    draggedElement.style.zIndex = '1000';
}

function handleTouchEnd(e) {
    if (!draggedElement) return;
    
    const touch = e.changedTouches[0];
    const dropTarget = document.elementFromPoint(touch.clientX, touch.clientY);
    const category = dropTarget?.closest('.sortingCategory');
    
    // Réinitialiser le style
    draggedElement.style.position = '';
    draggedElement.style.left = '';
    draggedElement.style.top = '';
    draggedElement.style.zIndex = '';
    draggedElement.classList.remove('dragging');
    
    if (category) {
        const categoryName = category.dataset.category;
        const itemCategory = draggedElement.dataset.category;
        
        if (categoryName === itemCategory) {
            const categoryItems = category.querySelector('.categoryItems');
            categoryItems.appendChild(draggedElement);
            showToast('✓ Correct !');
            currentExercise.sorted++;
            updateProgress((currentExercise.sorted / currentExercise.total) * 100);
            
            if (currentExercise.sorted === currentExercise.total) {
                setTimeout(() => {
                    showToast('🎉 Tri terminé !');
                    setTimeout(() => validateExercise(), 1500);
                }, 500);
            }
        } else {
            showToast('Essayez une autre catégorie');
        }
    }
    
    draggedElement = null;
}

function handleDragStart(e) {
    draggedElement = e.target;
    draggedElement.classList.add('dragging');
    e.dataTransfer.effectAllowed = 'move';
}

function handleDragEnd(e) {
    draggedElement.classList.remove('dragging');
}

function handleDragOver(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
    e.currentTarget.classList.add('dragover');
}

function handleDragLeave(e) {
    e.currentTarget.classList.remove('dragover');
}

function handleDrop(e) {
    e.preventDefault();
    e.currentTarget.classList.remove('dragover');
    
    const category = e.currentTarget;
    const categoryName = category.dataset.category;
    const itemCategory = draggedElement.dataset.category;
    
    if (categoryName === itemCategory) {
        const categoryItems = category.querySelector('.categoryItems');
        categoryItems.appendChild(draggedElement);
        showToast('✓ Correct !');
        currentExercise.sorted++;
        updateProgress((currentExercise.sorted / currentExercise.total) * 100);
        
        if (currentExercise.sorted === currentExercise.total) {
            setTimeout(() => {
                showToast('🎉 Tri terminé !');
                setTimeout(() => validateExercise(), 1500);
            }, 500);
        }
    } else {
        showToast('Essayez une autre catégorie');
    }
}

/**
 * ===========================
 * EXERCICE MUSICOTHÉRAPIE
 * ===========================
 */
function initMusicTherapyExercise() {
    currentExercise.moodBefore = null;
    currentExercise.moodAfter = null;
    updateProgress(50);
}

function selectMood(element) {
    const allMoods = document.querySelectorAll('.moodOption');
    allMoods.forEach(m => m.classList.remove('selected'));
    
    element.classList.add('selected');
    
    if (!currentExercise.moodBefore) {
        currentExercise.moodBefore = element.textContent;
        showToast('Humeur enregistrée');
        updateProgress(100);
    } else {
        currentExercise.moodAfter = element.textContent;
        showToast('Merci !');
    }
}

/**
 * ===========================
 * VALIDATION & RÉSULTAT
 * ===========================
 */
function validateExercise() {
    if (!currentExercise) {
        showToast('Aucun exercice en cours');
        return;
    }
    
    // Calculer le score selon le type
    let score = calculateScore();
    
    // Afficher le résultat
    showResult(score);
    
    // Nettoyer les timers
    clearInterval(exerciseTimer);
    clearInterval(intervalTimer);
}

function calculateScore() {
    const type = currentExercise.type;
    
    switch(type) {
        case 'face_name':
            const correctPicks = currentExercise.picks.filter(p => 
                currentExercise.data.correct.includes(p)
            );
            return Math.round((correctPicks.length / 3) * 100);
        
        case 'memory':
            const correctSymbols = currentExercise.picks.filter(p => 
                currentExercise.data.correct.includes(p)
            );
            return Math.round((correctSymbols.length / 3) * 100);
        
        case 'daily_task':
            return currentStep === 6 ? 100 : Math.round((currentStep / 6) * 100);
        
        case 'montessori_sorting':
            return Math.round((currentExercise.sorted / currentExercise.total) * 100);
        
        default:
            // Score aléatoire entre 70-100 pour les autres exercices
            return Math.floor(Math.random() * 30) + 70;
    }
}

function showResult(score) {
    // Masquer la zone d'exercice
    document.getElementById('exerciseArea').style.display = 'none';
    
    // Afficher le résultat
    const resultDiv = document.getElementById('exerciseResult');
    const contentDiv = document.getElementById('resultContent');
    
    const stars = score >= 90 ? '⭐⭐⭐' : score >= 70 ? '⭐⭐' : '⭐';
    const feedback = getFeedback(score);
    
    let html = `<div class="resultScore">${score}</div>`;
    html += `<div class="resultStars">${stars}</div>`;
    html += `<div class="resultFeedback">${feedback}</div>`;
    html += `<p class="muted">Votre progression est enregistrée</p>`;
    
    contentDiv.innerHTML = html;
    resultDiv.style.display = 'block';
    resultDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function getFeedback(score) {
    if (score >= 90) return "Excellent travail ! Vous êtes formidable ! 🎉";
    if (score >= 70) return "Très bien ! Vous progressez chaque jour ! 👏";
    if (score >= 50) return "Bien joué ! Continuez comme ça ! 💪";
    return "Pas de souci, on réessaie demain ! 🌟";
}

/**
 * ===========================
 * UTILITAIRES
 * ===========================
 */
function updateProgress(percent) {
    const bar = document.getElementById('exerciseProgressBar');
    if (bar) {
        bar.style.width = Math.min(100, Math.max(0, percent)) + '%';
    }
}

function scrollToExercise() {
    setTimeout(() => {
        const area = document.getElementById('exerciseArea');
        if (area) {
            area.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }, 100);
}

function cancelExercise() {
    if (confirm('Voulez-vous vraiment quitter cet exercice ?')) {
        document.getElementById('exerciseArea').style.display = 'none';
        document.getElementById('exerciseResult').style.display = 'none';
        
        // Nettoyer
        clearInterval(exerciseTimer);
        clearInterval(intervalTimer);
        currentExercise = null;
        currentStep = 0;
        draggedElement = null;
        
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

function finishExercise() {
    document.getElementById('exerciseResult').style.display = 'none';
    currentExercise = null;
    window.scrollTo({ top: 0, behavior: 'smooth' });
    showToast('À demain pour le prochain exercice ! 👋');
}

function speakText(text) {
    try {
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'fr-FR';
        utterance.rate = 0.85;
        utterance.pitch = 1;
        speechSynthesis.speak(utterance);
    } catch (e) {
        showToast('Synthèse vocale non disponible');
    }
}

// Helpers
function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function camelCase(str) {
    return str.replace(/_([a-z])/g, (g) => g[1].toUpperCase());
}

function generateCalculations(count) {
    const operations = [];
    for (let i = 0; i < count; i++) {
        const a = Math.floor(Math.random() * 15) + 2;
        const b = Math.floor(Math.random() * 10) + 1;
        const type = Math.random() > 0.5 ? '+' : '-';
        
        if (type === '+') {
            operations.push({
                question: `${a} + ${b} = ?`,
                answer: a + b
            });
        } else {
            const max = Math.max(a, b);
            const min = Math.min(a, b);
            operations.push({
                question: `${max} - ${min} = ?`,
                answer: max - min
            });
        }
    }
    return operations;
}

/**
 * ===========================
 * INITIALISATION
 * ===========================
 */
document.addEventListener('DOMContentLoaded', () => {
    console.log('✓ Module Exercices chargé');
    
    // Nettoyer au chargement
    currentExercise = null;
    
    // Gestion Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const exerciseArea = document.getElementById('exerciseArea');
            if (exerciseArea && exerciseArea.style.display !== 'none') {
                cancelExercise();
            }
        }
    });
});
@extends('layouts.app')

@section('title', 'Exercices - App Aide Alzheimer')

@section('content')

{{-- ============================================
     INDICATEUR DU STADE
     ============================================ --}}
<div class="stageIndicator">
    <span class="muted">Stade actuel:</span>
    <strong>Prodromal (MCI)</strong>
</div>

{{-- ============================================
     EXERCICE DU JOUR
     ============================================ --}}
<x-card title="✨ Exercice du jour">
    <div class="dailyExercise">
        <div class="exerciseIcon">🧠</div>
        <div class="exerciseInfo">
            <h4>Mémoire visuelle</h4>
            <p class="muted">Mémoriser des images et les retrouver</p>
            <div class="exerciseMeta">
                <span>⏱ 3-5 min</span>
                <span>📊 Facile</span>
            </div>
        </div>
    </div>
    <div class="btnRow">
        <button class="btn" onclick="startExercise('memory')">
            ▶ Commencer
        </button>
        <button class="btn secondary" onclick="speak('Exercice du jour : Mémoire visuelle')">
            🔊 Écouter
        </button>
    </div>
</x-card>

{{-- ============================================
     LISTE DES EXERCICES DISPONIBLES
     ============================================ --}}
<x-card title="Tous les exercices disponibles">
    <div class="exerciseList">

        {{-- Exercice 1: Visage-Nom --}}
        <div class="exerciseCard" onclick="startExercise('face_name')">
            <div class="exerciseCardIcon">👤</div>
            <div class="exerciseCardContent">
                <h5>Visage-Nom</h5>
                <p class="muted">Associer des visages à des prénoms</p>
                <div class="exerciseMeta">
                    <span>⏱ 5 min</span>
                    <span>📊 Facile</span>
                </div>
            </div>
            <div class="exerciseCardArrow">→</div>
        </div>

        {{-- Exercice 2: Récupération espacée --}}
        <div class="exerciseCard" onclick="startExercise('spaced_retrieval')">
            <div class="exerciseCardIcon">🔑</div>
            <div class="exerciseCardContent">
                <h5>Récupération espacée</h5>
                <p class="muted">Se rappeler où sont les objets importants</p>
                <div class="exerciseMeta">
                    <span>⏱ 4 min</span>
                    <span>📊 Facile</span>
                </div>
            </div>
            <div class="exerciseCardArrow">→</div>
        </div>

        {{-- Exercice 3: Mémoire d'images --}}
        <div class="exerciseCard" onclick="startExercise('memory')">
            <div class="exerciseCardIcon">🧠</div>
            <div class="exerciseCardContent">
                <h5>Mémoire d'images</h5>
                <p class="muted">Mémoriser et retrouver des symboles</p>
                <div class="exerciseMeta">
                    <span>⏱ 3 min</span>
                    <span>📊 Moyen</span>
                </div>
            </div>
            <div class="exerciseCardArrow">→</div>
        </div>

        {{-- Exercice 4: Attention sélective --}}
        <div class="exerciseCard" onclick="startExercise('attention')">
            <div class="exerciseCardIcon">👁</div>
            <div class="exerciseCardContent">
                <h5>Attention sélective</h5>
                <p class="muted">Repérer des symboles spécifiques</p>
                <div class="exerciseMeta">
                    <span>⏱ 3 min</span>
                    <span>📊 Facile</span>
                </div>
            </div>
            <div class="exerciseCardArrow">→</div>
        </div>

        {{-- Exercice 5: Orientation temporelle --}}
        <div class="exerciseCard" onclick="startExercise('orientation')">
            <div class="exerciseCardIcon">📅</div>
            <div class="exerciseCardContent">
                <h5>Orientation temporelle</h5>
                <p class="muted">Jour, date, saison actuelle</p>
                <div class="exerciseMeta">
                    <span>⏱ 2 min</span>
                    <span>📊 Facile</span>
                </div>
            </div>
            <div class="exerciseCardArrow">→</div>
        </div>

        {{-- Exercice 6: Réminiscence --}}
        <div class="exerciseCard" onclick="startExercise('reminiscence')">
            <div class="exerciseCardIcon">📸</div>
            <div class="exerciseCardContent">
                <h5>Réminiscence</h5>
                <p class="muted">Photos et souvenirs personnels</p>
                <div class="exerciseMeta">
                    <span>⏱ 10 min</span>
                    <span>📊 Facile</span>
                </div>
            </div>
            <div class="exerciseCardArrow">→</div>
        </div>

        {{-- Exercice 7: Jeux de mots --}}
        <div class="exerciseCard" onclick="startExercise('word_games')">
            <div class="exerciseCardIcon">💬</div>
            <div class="exerciseCardContent">
                <h5>Jeux de mots</h5>
                <p class="muted">Trouver des mots par catégorie</p>
                <div class="exerciseMeta">
                    <span>⏱ 4 min</span>
                    <span>📊 Moyen</span>
                </div>
            </div>
            <div class="exerciseCardArrow">→</div>
        </div>

        {{-- Exercice 8: Calculs simples --}}
        <div class="exerciseCard" onclick="startExercise('simple_calc')">
            <div class="exerciseCardIcon">🔢</div>
            <div class="exerciseCardContent">
                <h5>Calculs simples</h5>
                <p class="muted">Additions et soustractions faciles</p>
                <div class="exerciseMeta">
                    <span>⏱ 5 min</span>
                    <span>📊 Moyen</span>
                </div>
            </div>
            <div class="exerciseCardArrow">→</div>
        </div>

        {{-- Exercice 9: Tâche quotidienne --}}
        <div class="exerciseCard" onclick="startExercise('daily_task')">
            <div class="exerciseCardIcon">☕</div>
            <div class="exerciseCardContent">
                <h5>Tâche quotidienne</h5>
                <p class="muted">Faire du thé étape par étape</p>
                <div class="exerciseMeta">
                    <span>⏱ 8 min</span>
                    <span>📊 Guidé</span>
                </div>
            </div>
            <div class="exerciseCardArrow">→</div>
        </div>

        {{-- Exercice 10: Tri Montessori --}}
        <div class="exerciseCard" onclick="startExercise('montessori_sorting')">
            <div class="exerciseCardIcon">🎨</div>
            <div class="exerciseCardContent">
                <h5>Tri Montessori</h5>
                <p class="muted">Trier objets par couleur ou usage</p>
                <div class="exerciseMeta">
                    <span>⏱ 5 min</span>
                    <span>📊 Facile</span>
                </div>
            </div>
            <div class="exerciseCardArrow">→</div>
        </div>

        {{-- Exercice 11: Séquence d'actions --}}
        <div class="exerciseCard" onclick="startExercise('sequence')">
            <div class="exerciseCardIcon">📋</div>
            <div class="exerciseCardContent">
                <h5>Séquence d'actions</h5>
                <p class="muted">Remettre les étapes dans l'ordre</p>
                <div class="exerciseMeta">
                    <span>⏱ 6 min</span>
                    <span>📊 Guidé</span>
                </div>
            </div>
            <div class="exerciseCardArrow">→</div>
        </div>

        {{-- Exercice 12: Musicothérapie --}}
        <div class="exerciseCard" onclick="startExercise('music_therapy')">
            <div class="exerciseCardIcon">🎵</div>
            <div class="exerciseCardContent">
                <h5>Musicothérapie</h5>
                <p class="muted">Écouter et réagir à la musique</p>
                <div class="exerciseMeta">
                    <span>⏱ 15 min</span>
                    <span>📊 Plaisir</span>
                </div>
            </div>
            <div class="exerciseCardArrow">→</div>
        </div>

    </div>
</x-card>

{{-- ============================================
     ZONE D'EXERCICE (S'affiche EN BAS après clic)
     ============================================ --}}
<div id="exerciseArea" class="card" style="display:none;">
    <button class="closeBtn" onclick="cancelExercise()" aria-label="Fermer">✕</button>
    
    <h3 id="exerciseTitle">Exercice en cours</h3>
    
    <div class="exerciseProgress">
        <div class="progress">
            <div id="exerciseProgressBar" class="bar"></div>
        </div>
    </div>
    
    <div id="exerciseContent"></div>
    
    <div class="btnRow">
        <button class="btn" onclick="validateExercise()">✓ Valider</button>
        <button class="btn secondary" onclick="cancelExercise()">Quitter</button>
    </div>
</div>

{{-- ============================================
     ZONE DE RÉSULTAT (S'affiche EN BAS après validation)
     ============================================ --}}
<div id="exerciseResult" class="card" style="display:none;">
    <div class="resultAnimation">🎉</div>
    <h3>Résultat</h3>
    <div id="resultContent"></div>
    <button class="btn full" onclick="finishExercise()">Terminer</button>
</div>

@endsection

{{-- ============================================
     TEMPLATES HTML (EN BAS DU FICHIER)
     ============================================ --}}
@section('templates')

{{-- Template: Exercice Visage-Nom --}}
<template id="templateFaceName">
    <div class="exerciseInstructions">
        <p><strong>Mémorisez ces 3 visages et leurs prénoms pendant 10 secondes</strong></p>
    </div>
    
    <div class="faceGrid">
        <div class="faceCard">
            <img src="https://randomuser.me/api/portraits/women/1.jpg" alt="Marie">
            <div class="faceName">Marie</div>
            <div class="faceHint">Cheveux courts, lunettes</div>
        </div>
        
        <div class="faceCard">
            <img src="https://randomuser.me/api/portraits/men/2.jpg" alt="Pierre">
            <div class="faceName">Pierre</div>
            <div class="faceHint">Barbe blanche, souriant</div>
        </div>
        
        <div class="faceCard">
            <img src="https://randomuser.me/api/portraits/women/3.jpg" alt="Sophie">
            <div class="faceName">Sophie</div>
            <div class="faceHint">Yeux verts, écharpe</div>
        </div>
        
        <div class="faceCard">
            <img src="https://randomuser.me/api/portraits/men/4.jpg" alt="Jean">
            <div class="faceName">Jean</div>
            <div class="faceHint">Casquette bleue</div>
        </div>
    </div>
    
    <div class="timer">
        <span class="muted">Temps restant:</span>
        <strong id="learningTimer">10</strong>
        <span class="muted">secondes</span>
    </div>
</template>

{{-- Template: Exercice Récupération espacée --}}
<template id="templateSpacedRetrieval">
    <div class="spacedRetrievalContent">
        <p class="spacedQuestion">Où sont les clés de la maison ?</p>
        
        <img src="https://images.unsplash.com/photo-1582139329536-e7284fece509?w=400" 
             alt="Boîte bleue" 
             class="spacedImage">
        
        <div class="spacedAnswer">
            Dans la boîte bleue près de la porte
        </div>
        
        <p class="muted">Mémorisez bien cette information !</p>
        
        <div class="intervalTimer" id="intervalTimer">5</div>
    </div>
</template>

{{-- Template: Exercice Mémoire --}}
<template id="templateMemory">
    <div class="exerciseInstructions">
        <p><strong>Mémorisez ces 3 symboles pendant 5 secondes</strong></p>
    </div>
    
    <div style="font-size:4rem; text-align:center; margin:32px 0;">
        🍎 🔑 🌼
    </div>
    
    <div class="timer">
        <span class="muted">Temps restant:</span>
        <strong id="memoryTimer">5</strong>
        <span class="muted">secondes</span>
    </div>
</template>

{{-- Template: Exercice Attention --}}
<template id="templateAttention">
    <div class="exerciseInstructions">
        <p><strong>Cliquez sur tous les triangles (▲)</strong></p>
    </div>
    
    <div class="emojiGrid">
        <button class="emojiBtn" onclick="pickSymbol('▲', this)">▲</button>
        <button class="emojiBtn" onclick="pickSymbol('●', this)">●</button>
        <button class="emojiBtn" onclick="pickSymbol('■', this)">■</button>
        <button class="emojiBtn" onclick="pickSymbol('●', this)">●</button>
        <button class="emojiBtn" onclick="pickSymbol('▲', this)">▲</button>
        <button class="emojiBtn" onclick="pickSymbol('■', this)">■</button>
        <button class="emojiBtn" onclick="pickSymbol('●', this)">●</button>
        <button class="emojiBtn" onclick="pickSymbol('▲', this)">▲</button>
        <button class="emojiBtn" onclick="pickSymbol('■', this)">■</button>
    </div>
</template>

{{-- Template: Exercice Orientation --}}
<template id="templateOrientation">
    <div class="exerciseInstructions">
        <p><strong>Quel jour sommes-nous ?</strong></p>
    </div>
    
    <div class="pillRow">
        <button class="pill" onclick="selectOption('lundi', this)">Lundi</button>
        <button class="pill" onclick="selectOption('mardi', this)">Mardi</button>
        <button class="pill" onclick="selectOption('mercredi', this)">Mercredi</button>
        <button class="pill" onclick="selectOption('jeudi', this)">Jeudi</button>
        <button class="pill" onclick="selectOption('vendredi', this)">Vendredi</button>
        <button class="pill" onclick="selectOption('samedi', this)">Samedi</button>
        <button class="pill" onclick="selectOption('dimanche', this)">Dimanche</button>
    </div>
    
    <div class="timer">
        <span class="muted">Réponse choisie:</span>
        <strong id="selectedAnswer">—</strong>
    </div>
</template>

{{-- Template: Exercice Réminiscence --}}
<template id="templateReminiscence">
    <div class="reminiscenceContent">
        <img src="https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?w=600" 
             alt="Photo mariage" 
             class="reminiscenceImage">
        
        <div class="reminiscenceQuestions">
            <div class="reminiscenceQuestion">Qui voyez-vous sur cette photo ?</div>
            <div class="reminiscenceQuestion">Où cette photo a-t-elle été prise ?</div>
            <div class="reminiscenceQuestion">Vous souvenez-vous de ce jour ?</div>
        </div>
        
        <div class="reminiscencePrompts">
            <h5>💡 Indices pour vous aider :</h5>
            <p>• C'était votre mariage</p>
            <p>• C'était en 1985</p>
            <p>• À l'église Saint-Pierre</p>
        </div>
        
        <p class="muted">Prenez le temps de discuter de ces souvenirs avec votre aidant</p>
    </div>
</template>

{{-- Template: Exercice Jeux de mots --}}
<template id="templateWordGames">
    <div class="exerciseInstructions">
        <p><strong>Trouvez 5 fruits</strong></p>
    </div>
    
    <div class="field">
        <label>Fruit 1:</label>
        <input type="text" class="input wordInput" placeholder="Ex: pomme">
    </div>
    <div class="field">
        <label>Fruit 2:</label>
        <input type="text" class="input wordInput" placeholder="Ex: banane">
    </div>
    <div class="field">
        <label>Fruit 3:</label>
        <input type="text" class="input wordInput" placeholder="Ex: orange">
    </div>
    <div class="field">
        <label>Fruit 4:</label>
        <input type="text" class="input wordInput" placeholder="Ex: fraise">
    </div>
    <div class="field">
        <label>Fruit 5:</label>
        <input type="text" class="input wordInput" placeholder="Ex: raisin">
    </div>
</template>

{{-- Template: Exercice Calculs simples --}}
<template id="templateSimpleCalc">
    <div class="exerciseInstructions">
        <p><strong>Répondez à ces calculs simples</strong></p>
    </div>
    
    <div class="field">
        <label>5 + 3 = ?</label>
        <input type="number" class="input calcInput" placeholder="Votre réponse">
    </div>
    <div class="field">
        <label>12 - 4 = ?</label>
        <input type="number" class="input calcInput" placeholder="Votre réponse">
    </div>
    <div class="field">
        <label>7 + 6 = ?</label>
        <input type="number" class="input calcInput" placeholder="Votre réponse">
    </div>
    <div class="field">
        <label>15 - 8 = ?</label>
        <input type="number" class="input calcInput" placeholder="Votre réponse">
    </div>
    <div class="field">
        <label>9 + 4 = ?</label>
        <input type="number" class="input calcInput" placeholder="Votre réponse">
    </div>
</template>

{{-- Template: Exercice Tâche quotidienne --}}
<template id="templateDailyTask">
    <div class="taskSteps">
        <div class="taskStep active" data-step="0">
            <div class="taskStepNumber">1</div>
            <div class="taskStepContent">
                <strong>Remplir la bouilloire d'eau</strong>
                <img src="https://images.unsplash.com/photo-1595981234058-7d31f7bb5071?w=400" alt="Bouilloire">
            </div>
        </div>
        
        <div class="taskStep" data-step="1">
            <div class="taskStepNumber">2</div>
            <div class="taskStepContent">
                <strong>Allumer la bouilloire</strong>
                <img src="https://images.unsplash.com/photo-1556881286-fc6915169721?w=400" alt="Bouilloire allumée">
            </div>
        </div>
        
        <div class="taskStep" data-step="2">
            <div class="taskStepNumber">3</div>
            <div class="taskStepContent">
                <strong>Prendre une tasse</strong>
                <img src="https://images.unsplash.com/photo-1514228742587-6b1558fcca3d?w=400" alt="Tasse">
            </div>
        </div>
        
        <div class="taskStep" data-step="3">
            <div class="taskStepNumber">4</div>
            <div class="taskStepContent">
                <strong>Mettre un sachet de thé dans la tasse</strong>
                <img src="https://images.unsplash.com/photo-1564890369478-c89ca6d9cde9?w=400" alt="Sachet de thé">
            </div>
        </div>
        
        <div class="taskStep" data-step="4">
            <div class="taskStepNumber">5</div>
            <div class="taskStepContent">
                <strong>Verser l'eau chaude</strong>
                <img src="https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=400" alt="Verser eau">
            </div>
        </div>
        
        <div class="taskStep" data-step="5">
            <div class="taskStepNumber">6</div>
            <div class="taskStepContent">
                <strong>Laisser infuser 3 minutes</strong>
                <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400" alt="Infusion">
            </div>
        </div>
    </div>
    
    <button class="btn full" onclick="completeCurrentStep()">✓ Étape terminée</button>
</template>

{{-- Template: Exercice Tri Montessori --}}
<template id="templateMontessoriSorting">
    <div class="sortingContainer">
        <p><strong>Triez les objets par couleur (rouge, jaune, vert)</strong></p>
        
        <div class="sortingItems">
            <div class="sortingItem" draggable="true" data-category="rouge">
                <img src="https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?w=200" alt="Pomme">
                <div>Pomme</div>
            </div>
            
            <div class="sortingItem" draggable="true" data-category="jaune">
                <img src="https://images.unsplash.com/photo-1587486937314-9c1f33556076?w=200" alt="Citron">
                <div>Citron</div>
            </div>
            
            <div class="sortingItem" draggable="true" data-category="rouge">
                <img src="https://images.unsplash.com/photo-1546094096-0df4bcaaa337?w=200" alt="Tomate">
                <div>Tomate</div>
            </div>
            
            <div class="sortingItem" draggable="true" data-category="jaune">
                <img src="https://images.unsplash.com/photo-1571771894821-ce9b6c11b08e?w=200" alt="Banane">
                <div>Banane</div>
            </div>
            
            <div class="sortingItem" draggable="true" data-category="vert">
                <img src="https://images.unsplash.com/photo-1596363505729-4190a9506133?w=200" alt="Raisin">
                <div>Raisin</div>
            </div>
            
            <div class="sortingItem" draggable="true" data-category="vert">
                <img src="https://images.unsplash.com/photo-1604977042946-1eecc30f269e?w=200" alt="Concombre">
                <div>Concombre</div>
            </div>
        </div>
        
        <div class="sortingCategories">
            <div class="sortingCategory" data-category="rouge">
                <div class="categoryLabel">🔴 Rouge</div>
                <div class="categoryItems"></div>
            </div>
            
            <div class="sortingCategory" data-category="jaune">
                <div class="categoryLabel">🟡 Jaune</div>
                <div class="categoryItems"></div>
            </div>
            
            <div class="sortingCategory" data-category="vert">
                <div class="categoryLabel">🟢 Vert</div>
                <div class="categoryItems"></div>
            </div>
        </div>
    </div>
</template>

{{-- Template: Exercice Séquence --}}
<template id="templateSequence">
    <div class="exerciseInstructions">
        <p><strong>Remettez les étapes dans le bon ordre</strong></p>
    </div>
    
    <div class="sequenceItems">
        <div class="sequenceItem" draggable="true" data-order="3">
            <span class="sequenceHandle">☰</span>
            <img src="https://images.unsplash.com/photo-1556909212-d5b604d0c90d?w=80&h=60&fit=crop" alt="Étape 3">
            <span>Mettre du savon</span>
        </div>
        
        <div class="sequenceItem" draggable="true" data-order="1">
            <span class="sequenceHandle">☰</span>
            <img src="https://images.unsplash.com/photo-1556909172-54557c7e4fb7?w=80&h=60&fit=crop" alt="Étape 1">
            <span>Ouvrir le robinet</span>
        </div>
        
        <div class="sequenceItem" draggable="true" data-order="5">
            <span class="sequenceHandle">☰</span>
            <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=80&h=60&fit=crop" alt="Étape 5">
            <span>Rincer</span>
        </div>
        
        <div class="sequenceItem" draggable="true" data-order="2">
            <span class="sequenceHandle">☰</span>
            <img src="https://images.unsplash.com/photo-1556909212-92e7b5be72e0?w=80&h=60&fit=crop" alt="Étape 2">
            <span>Mouiller les mains</span>
        </div>
    </div>
</template>

{{-- Template: Exercice Musicothérapie --}}
<template id="templateMusicTherapy">
    <div class="musicTherapyContent">
        <div class="musicPlayer">
            <h4>🎵 Chansons françaises des années 60</h4>
            
            <div class="playlistSongs">
                <div class="songItem">
                    <div class="songInfo">
                        <strong>La Bohème</strong>
                        <span>Charles Aznavour</span>
                    </div>
                    <button class="playButton">▶</button>
                </div>
                
                <div class="songItem">
                    <div class="songInfo">
                        <strong>Non, je ne regrette rien</strong>
                        <span>Édith Piaf</span>
                    </div>
                    <button class="playButton">▶</button>
                </div>
                
                <div class="songItem">
                    <div class="songInfo">
                        <strong>Les Champs-Élysées</strong>
                        <span>Joe Dassin</span>
                    </div>
                    <button class="playButton">▶</button>
                </div>
            </div>
        </div>
        
        <div class="moodTracking">
            <p><strong>Comment vous sentez-vous maintenant ?</strong></p>
            <div class="moodTracker">
                <span class="moodOption" onclick="selectMood(this)">😊</span>
                <span class="moodOption" onclick="selectMood(this)">😐</span>
                <span class="moodOption" onclick="selectMood(this)">😔</span>
            </div>
        </div>
        
        <p class="muted">Écoutez la musique et bougez au rythme si vous le souhaitez</p>
    </div>
</template>




@push('styles')
<link rel="stylesheet" href="{{ asset('css/exo.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/modules/exercises.js') }}"></script>
@endpush

@endsection

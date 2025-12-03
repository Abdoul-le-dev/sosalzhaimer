/**
 * Module Accueil - Gestion humeur et sommeil
 */

// Définir l'humeur
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker
            .register('/service-worker.js')
            .then(reg => console.log('Service worker registered', reg))
            .catch(err => console.log('Service worker registration failed', err));
    });
}


function setMood(emoji) {
    fetch('/api/mood', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ mood: emoji })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('mood').textContent = emoji;
        showToast('Humeur enregistrée');
    })
    .catch(err => {
        console.error('Erreur:', err);
        showToast('Erreur lors de l\'enregistrement');
    });
}

// Enregistrer le sommeil
function saveSleep() {
    const sleepValue = document.getElementById('sleep').value;
    
    if (!sleepValue) return;
    
    fetch('/api/sleep', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ sleep: sleepValue })
    })
    .then(res => res.json())
    .then(data => {
        showToast('Sommeil enregistré');
    })
    .catch(err => {
        console.error('Erreur:', err);
        showToast('Erreur lors de l\'enregistrement');
    });
}
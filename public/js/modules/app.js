/**
 * ============================================
 * APP.JS - Fonctions globales
 * ============================================
 */

/**
 * Affiche un toast de notification
 */
function showToast(message, duration = 3000) {
    const toastsContainer = document.querySelector('.toasts');
    
    if (!toastsContainer) {
        console.error('Container .toasts introuvable');
        return;
    }
    
    // Créer le toast
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.textContent = message;
    toast.style.animation = 'slideUp 0.3s ease';
    
    // Ajouter au container
    toastsContainer.appendChild(toast);
    
    // Retirer après la durée
    setTimeout(() => {
        toast.style.animation = 'fadeOut 0.3s ease';
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, duration);
}

/**
 * Synthèse vocale
 */
function speak(text) {
    try {
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'fr-FR';
        utterance.rate = 0.85;
        utterance.pitch = 1;
        speechSynthesis.speak(utterance);
    } catch (e) {
        console.error('Synthèse vocale non disponible:', e);
        showToast('Synthèse vocale non disponible');
    }
}

/**
 * Changement de thème
 */
function toggleTheme() {
    const body = document.body;
    const currentTheme = body.dataset.theme || 'dark';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    body.dataset.theme = newTheme;
    
    // Sauvegarder en session
    sessionStorage.setItem('theme', newTheme);
    
    showToast(`Thème ${newTheme === 'dark' ? 'sombre' : 'clair'} activé`);
}

/**
 * Agrandir le texte
 */
function biggerText() {
    const body = document.body;
    const currentSize = parseFloat(getComputedStyle(body).fontSize);
    const newSize = Math.min(currentSize + 1, 22);
    
    body.style.fontSize = newSize + 'px';
    
    showToast(`Taille de texte: ${newSize}px`);
}

/**
 * Fermer toutes les modales
 */
function closeAllSheets() {
    const sheets = document.querySelectorAll('.sheet');
    sheets.forEach(sheet => sheet.classList.remove('open'));
    
    const backdrop = document.getElementById('backdrop');
    if (backdrop) backdrop.classList.remove('show');
}

/**
 * Initialisation au chargement
 */
document.addEventListener('DOMContentLoaded', () => {
    console.log('✓ App.js chargé');
    
    // Restaurer le thème
    const savedTheme = sessionStorage.getItem('theme');
    if (savedTheme) {
        document.body.dataset.theme = savedTheme;
    }
    
    // Gestion Escape globale
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeAllSheets();
        }
    });
});

// Animation fadeOut pour les toasts
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-10px);
        }
    }
`;
document.head.appendChild(style);
/**
 * Module Rappels - Gestion de l'agenda
 */

const backdrop = document.getElementById('backdrop');
const remSheet = document.getElementById('remSheet');

/**
 * Ouvre le sheet de création
 */
function openReminderSheet() {
    // Valeurs par défaut
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('remDate').value = today;
    document.getElementById('remTime').value = '18:00';
    document.getElementById('remTitleInput').value = '';
    document.getElementById('remRepeat').value = 'none';
    
    remSheet.classList.add('open');
    backdrop.classList.add('show');
    
    // Focus sur le champ titre
    setTimeout(() => document.getElementById('remTitleInput')?.focus(), 300);
}

/**
 * Ferme le sheet
 */
function closeReminderSheet() {
    remSheet.classList.remove('open');
    backdrop.classList.remove('show');
}

/**
 * Enregistre un nouveau rappel
 */
function saveReminder(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());
    
    fetch('/reminders/store', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(response => {
        if (response.success) {
            showToast(response.message);
            closeReminderSheet();
            
            // Recharger la page pour voir le nouveau rappel
            setTimeout(() => window.location.reload(), 500);
        }
    })
    .catch(err => {
        console.error('Erreur:', err);
        showToast('Erreur lors de la création du rappel');
    });
}

/**
 * Marque un rappel comme fait
 */
function markDone(id) {
    fetch(`/reminders/${id}/done`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(response => {
        if (response.success) {
            showToast(response.message);
            
            // Mise à jour visuelle
            const li = document.querySelector(`li[data-id="${id}"]`);
            if (li) {
                const rightDiv = li.querySelector('.li-right');
                rightDiv.innerHTML = '<span style="font-size:24px" aria-label="Terminé">✅</span>';
            }
            
            // Mise à jour du taux d'observance si affiché
            if (document.getElementById('adherence')) {
                document.getElementById('adherence').textContent = response.adherence;
            }
        }
    })
    .catch(err => {
        console.error('Erreur:', err);
        showToast('Erreur lors de la validation');
    });
}

/**
 * Supprime un rappel
 */
function deleteReminder(id) {
    if (!confirm('Voulez-vous vraiment supprimer ce rappel ?')) {
        return;
    }
    
    fetch(`/reminders/${id}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(response => {
        if (response.success) {
            showToast(response.message);
            
            // Retirer visuellement
            const li = document.querySelector(`li[data-id="${id}"]`);
            if (li) {
                li.style.opacity = '0';
                setTimeout(() => li.remove(), 300);
            }
        }
    })
    .catch(err => {
        console.error('Erreur:', err);
        showToast('Erreur lors de la suppression');
    });
}

/**
 * Fermeture avec Escape
 */
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeReminderSheet();
    }
});
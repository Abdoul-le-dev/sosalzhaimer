/**
 * Module Aidants - Gestion du fil de communication
 */

/**
 * Publie une nouvelle note
 */
function postNote(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = {
        carer_name: formData.get('carer_name'),
        content: formData.get('content'),
        category: formData.get('category'),
        visible_to_patient: formData.get('visible_to_patient') ? true : false
    };
    
    fetch('/carers/note', {
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
            
            // Ajouter la note au feed
            addNoteToFeed(response.note);
            
            // Réinitialiser le formulaire
            event.target.reset();
            document.getElementById('visiblePatient').checked = true;
        }
    })
    .catch(err => {
        console.error('Erreur:', err);
        showToast('Erreur lors de la publication');
    });
}

/**
 * Ajoute une note au feed (en haut)
 */
function addNoteToFeed(note) {
    const feed = document.getElementById('feed');
    
    const noteCard = document.createElement('div');
    noteCard.className = 'noteCard';
    noteCard.dataset.id = note.id;
    noteCard.style.animation = 'slideUp 0.3s ease';
    
    noteCard.innerHTML = `
        <div class="noteHeader">
            <div>
                <strong>${note.carer_name}</strong>
                <span class="badge">${note.icon} ${note.category}</span>
            </div>
            <div class="muted" style="font-size:12px">
                ${note.date}
            </div>
        </div>
        <div class="noteContent">
            ${note.content}
        </div>
    `;
    
    // Insérer en premier
    if (feed.firstChild) {
        feed.insertBefore(noteCard, feed.firstChild);
    } else {
        feed.appendChild(noteCard);
    }
}

/**
 * Supprime une note
 */
function deleteNote(noteId) {
    if (!confirm('Voulez-vous vraiment supprimer cette note ?')) {
        return;
    }
    
    fetch(`/carers/note/${noteId}`, {
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
            const noteCard = document.querySelector(`.noteCard[data-id="${noteId}"]`);
            if (noteCard) {
                noteCard.style.opacity = '0';
                noteCard.style.transform = 'translateX(-20px)';
                setTimeout(() => noteCard.remove(), 300);
            }
        }
    })
    .catch(err => {
        console.error('Erreur:', err);
        showToast('Erreur lors de la suppression');
    });
}

/**
 * Filtre les notes par catégorie
 */
function filterByCategory(category) {
    const url = category === 'all' 
        ? '/carers' 
        : `/carers/filter/${category}`;
    
    window.location.href = url;
}

/**
 * Lecture audio d'une note
 */
function readNote(noteId) {
    const noteCard = document.querySelector(`.noteCard[data-id="${noteId}"]`);
    if (!noteCard) return;
    
    const content = noteCard.querySelector('.noteContent').textContent;
    
    try {
        const utterance = new SpeechSynthesisUtterance(content);
        utterance.lang = 'fr-FR';
        utterance.rate = 0.9;
        speechSynthesis.speak(utterance);
    } catch (e) {
        showToast('Synthèse vocale non disponible');
    }
}
/**
 * Module Suivi - Graphiques et rapports
 */

let progressChart = null;

/**
 * Initialise le graphique d'évolution
 */
function initChart(historyData) {
    const ctx = document.getElementById('progressChart');
    
    if (!ctx) return;

    // Préparation des données
    const dates = Object.keys(historyData);
    const scores = dates.map(date => historyData[date].avgScore);
    
    // Configuration du graphique
    progressChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates.map(date => {
                const d = new Date(date);
                return d.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' });
            }),
            datasets: [{
                label: 'Score moyen',
                data: scores,
                borderColor: 'rgb(14, 165, 233)',
                backgroundColor: 'rgba(14, 165, 233, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            return 'Score: ' + context.parsed.y + '/100';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        color: getComputedStyle(document.body).getPropertyValue('--muted'),
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        color: getComputedStyle(document.body).getPropertyValue('--outline')
                    }
                },
                x: {
                    ticks: {
                        color: getComputedStyle(document.body).getPropertyValue('--muted'),
                        font: {
                            size: 11
                        },
                        maxRotation: 45,
                        minRotation: 45
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

/**
 * Génère et télécharge le rapport
 */
function generateReport() {
    showToast('Génération du rapport...');
    
    fetch('/tracking/report?format=txt', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'rapport-suivi-' + new Date().toISOString().split('T')[0] + '.txt';
        document.body.appendChild(a);
        a.click();
        a.remove();
        window.URL.revokeObjectURL(url);
        
        showToast('Rapport téléchargé !');
    })
    .catch(err => {
        console.error('Erreur:', err);
        showToast('Erreur lors de la génération du rapport');
    });
}

/**
 * Export des données en JSON
 */
function exportData() {
    showToast('Export en cours...');
    
    fetch('/tracking/export', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'export-donnees-' + new Date().toISOString().split('T')[0] + '.json';
        document.body.appendChild(a);
        a.click();
        a.remove();
        window.URL.revokeObjectURL(url);
        
        showToast('Données exportées !');
    })
    .catch(err => {
        console.error('Erreur:', err);
        showToast('Erreur lors de l\'export');
    });
}

/**
 * Lecture audio des statistiques
 */
function speak(context) {
    const avgScore = document.querySelector('.statValue')?.textContent || '0';
    const sessions = document.querySelectorAll('.statValue')[1]?.textContent || '0';
    
    const text = `Votre score moyen est de ${avgScore} sur 100. Vous avez fait ${sessions} séances cette semaine.`;
    
    try {
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'fr-FR';
        utterance.rate = 0.9;
        speechSynthesis.speak(utterance);
    } catch (e) {
        showToast('Synthèse vocale non disponible');
    }
}

/**
 * Mise à jour du thème pour le graphique
 */
document.addEventListener('themeChanged', () => {
    if (progressChart) {
        progressChart.options.scales.y.ticks.color = getComputedStyle(document.body).getPropertyValue('--muted');
        progressChart.options.scales.x.ticks.color = getComputedStyle(document.body).getPropertyValue('--muted');
        progressChart.options.scales.y.grid.color = getComputedStyle(document.body).getPropertyValue('--outline');
        progressChart.update();
    }
});
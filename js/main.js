// =============================================
// main.js — Gestion des Salles
// =============================================

// Fermer les alertes automatiquement
document.addEventListener('DOMContentLoaded', function () {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s';
            setTimeout(() => alert.remove(), 500);
        }, 4000);
    });

    // Confirmation avant suppression
    document.querySelectorAll('[data-confirm]').forEach(btn => {
        btn.addEventListener('click', function (e) {
            if (!confirm(this.dataset.confirm || 'Êtes-vous sûr ?')) {
                e.preventDefault();
            }
        });
    });

    // Activer le nav item courant
    const path = window.location.pathname;
    document.querySelectorAll('.nav-item').forEach(item => {
        if (item.getAttribute('href') && path.endsWith(item.getAttribute('href').split('/').pop())) {
            item.classList.add('active');
        }
    });
});

// Ouvrir / fermer modal
function openModal(id) {
    document.getElementById(id).classList.add('active');
}
function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}

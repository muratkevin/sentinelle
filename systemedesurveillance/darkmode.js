document.addEventListener("DOMContentLoaded", function() {
    // Récupérer la case à cocher
    const darkModeToggle = document.getElementById('dark-mode-toggle');

    // Vérifier s'il y a une valeur stockée dans le stockage local pour le mode sombre
    const isDarkMode = localStorage.getItem('darkMode') === 'true';

    // Mettre à jour l'état de la case à cocher
    darkModeToggle.checked = isDarkMode;

    // Fonction pour basculer le mode sombre
    function toggleDarkMode() {
        const isDarkMode = darkModeToggle.checked;
        document.body.classList.toggle('dark-mode', isDarkMode);
        localStorage.setItem('darkMode', isDarkMode);
    }

    // Ajouter un écouteur d'événements pour détecter les changements de la case à cocher
    darkModeToggle.addEventListener('change', toggleDarkMode);
    
    // Appliquer le mode sombre au chargement de la page
    toggleDarkMode();
});

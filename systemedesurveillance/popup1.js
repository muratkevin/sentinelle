// Attend que la page soit entièrement chargée
window.addEventListener("load", function() {
    // Déclenche l'ouverture de le popup après un délai de 2000 millisecondes (2 secondes)
    setTimeout(function open(event) {
        // Affiche le popup en changeant son style pour "block"
        document.querySelector(".popup1").style.display = "block";
    }, 2000); // Délai de 2000 millisecondes (2 secondes)
});

// Ajoute un gestionnaire d'événement au bouton de fermeture de la popup
document.querySelector("#close").addEventListener("click", function() {
    // Cache le popup en changeant son style pour "none"
    document.querySelector(".popup1").style.display = "none";
});

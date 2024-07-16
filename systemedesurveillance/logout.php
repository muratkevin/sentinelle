<?php
// Démarrage de la session
session_start();
// Destruction de la session en cours
session_destroy();
// Redirection vers la page d'index
header('Location: index.html');
?>

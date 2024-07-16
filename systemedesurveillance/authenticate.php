<?php

// Démarre ou reprend une session existante
session_start(); 

// L'hôte de la base de données
$DATABASE_HOST = 'localhost'; 
// Nom d'utilisateur de la base de données
$DATABASE_USER = 'Admin'; 
// Mot de passe de la base de données
$DATABASE_PASS = 'sentinelle'; 
// Nom de la base de données
$DATABASE_NAME = 'phplogin'; 

// Connexion à la base de données
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
// Vérifie s'il y a une erreur de connexion 
if (mysqli_connect_errno()) { 
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Vérifie si les champs 'username' et 'password' ont été envoyés via la méthode POST
if (!isset($_POST['username'], $_POST['password'])) {
    exit('Please fill both the username and password fields!');
}

// Prépare une requête SQL pour sélectionner l'identifiant et le mot de passe correspondant à l'utilisateur fourni
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
    // Lie le paramètre 'username' à la requête SQL
    $stmt->bind_param('s', $_POST['username']); 
    // Exécute la requête
    $stmt->execute(); 
    // Stocke le résultat
    $stmt->store_result(); 

    // Vérifie s'il y a des résultats
    if ($stmt->num_rows > 0) {
         // Lie les résultats à des variables
        $stmt->bind_result($id, $password);
        // Récupère les valeurs des résultats
        $stmt->fetch(); 

        // Vérifie si le mot de passe fourni correspond au mot de passe stocké dans la base de données
        if (password_verify($_POST['password'], $password)) {
            // Régénère l'ID de session pour des raisons de sécurité
            session_regenerate_id();
            // Définit une variable de session pour indiquer que l'utilisateur est connecté
            $_SESSION['loggedin'] = TRUE;
            // Stocke le nom d'utilisateur dans une variable de session
            $_SESSION['name'] = $_POST['username'];
            // Stocke l'identifiant dans une variable de session
            $_SESSION['id'] = $id; 
            // Redirige l'utilisateur vers la page d'accueil
            header('Location: home.php');
        } else {
            // Affiche un message d'erreur si le mot de passe est incorrect
            echo 'ID incorrect et/ou mot de passe !'; 
        }
    } else {
        // Affiche un message d'erreur si aucun utilisateur correspondant n'est trouvé
        echo 'ID incorrect et/ou mot de passe !'; 
    }
    echo '<meta http-equiv="refresh" content="3;url=index.html" />';
    $stmt->close(); 
}
?>

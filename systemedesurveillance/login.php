<?php
// Récupération des données envoyées via la méthode GET
$username = $_GET['fname'];
$password = $_GET['fpass'];

// Connexion à la base de données MySQL
$con=mysqli_connect("mysql","database_user","database_password","mydatabase");
// Vérification de la connexion
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Requête SQL pour sélectionner l'ID des membres correspondant au nom d'utilisateur et au mot de passe fournis
$qz = "SELECT id FROM members where username='".$username."' and password='".$password."'" ;
// Suppression des guillemets pour éviter les problèmes de sécurité (vulnérabilité aux injections SQL)
$qz = str_replace("\'","",$qz);
// Exécution de la requête SQL
$result = mysqli_query($con,$qz);

// Parcours des résultats et affichage de l'ID
while($row = mysqli_fetch_array($result)) {
    echo $row['id'];
}

// Fermeture de la connexion à la base de données
mysqli_close($con);
?>

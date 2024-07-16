<?php
// Démarrage de la session
session_start();
// Vérification de la connexion de l'utilisateur
if (!isset($_SESSION['loggedin'])) {
    // Redirection vers la page d'index si l'utilisateur n'est pas connecté
    header('Location: index.html');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="refresh" content="3">
    <title>Données Arduino</title>
    <!-- Import des fichiers CSS -->
    <link href="styletemperature.css" rel="stylesheet" type="text/css">
    <link href="styledata.css" rel="stylesheet" type="text/css">
    <link href="style.css" rel="stylesheet" type="text/css">
    <link href="popup.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="darkmode.css">
    <!-- Import de la bibliothèque d'icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
</head>

<body class="loggedin">

<!-- Barre de navigation -->
<nav class="navtop">
    <div>
        <div>
            <h1>Historique</h1>
            <!-- Liens de navigation -->
            <a href="home.php"><i class="fa-solid fa-table-columns"></i>Tableau de Bord</a>
            <a href="esp-data.php"><i class="fa-solid fa-database"></i>Historique</a>
            <a href="profile.php"><i class="fas fa-user-circle"></i>Compte</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Déconnexion</a> 
            <!-- Case à cocher pour le mode sombre -->
            <input type="checkbox" id="dark-mode-toggle" class="darkmode-checkbox">
        </div>
    </div>
</nav>

<!-- Contenu principal -->
<div class="content tablee-container">
        <table>
        <!-- En-têtes du tableau -->
        <tr>
            <th>Horaire</th>
            <th>IDrobot</th>
            <th>Emplacement</th>
            <th>Direction</th>
            <th>Point Chaud</th> 
            <th>Température</th> 
            <th>Fumée</th> 
            <th>Obstacle</th>
        </tr>
</div>

<?php
// Informations de connexion à la base de données
$servername = "localhost";
$dbname = "Sentinelle";
$username = "Admin";
$password = "sentinelle";

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Requête SQL pour récupérer les données
$sql = "SELECT Obstacle, IDrobot, Point_chaud, Fumee, Direction, Emplacement, Horaire, Temperature FROM Donnees_zumo";

// Exécution de la requête SQLw
if ($result = $conn->query($sql)) {
    // Boucle à travers les résultats
    while ($row = $result->fetch_assoc()) {
        // Formatage des données
        $row_Horaire = date("d/m/y H:i:s", strtotime($row["Horaire"]));
        $row_IDrobot = $row['IDrobot'];
        $row_Emplacement = $row["Emplacement"];
        $row_Direction = $row["Direction"];
        $row_Point_chaud = $row["Point_chaud"]; 
        $row_Temperature = $row["Temperature"];
        $row_Fumee = $row["Fumee"];
        $row_Obstacle = $row["Obstacle"];

        // Détermination des classes CSS en fonction des valeurs
        $point_chaud_class = '';
        if ($row_Point_chaud > 50) {
            $point_chaud_class = 'blink red';
        } elseif ($row_Point_chaud >= -10 && $row_Point_chaud <= 10) {
            $point_chaud_class = 'blue';
        } elseif ($row_Point_chaud > 10.01 && $row_Point_chaud <= 30) {
            $point_chaud_class = 'green';
        } elseif ($row_Point_chaud >= 30.01 && $row_Point_chaud <= 50) {
            $point_chaud_class = 'orange';
        }

        $temperature_class = '';
        if ($row_Temperature > 50) {
            $temperature_class = 'blink red';
        } elseif ($row_Temperature >= -10 && $row_Temperature <= 10) {
            $temperature_class = 'blue';
        } elseif ($row_Temperature > 10.01 && $row_Temperature <= 30) {
            $temperature_class = 'green';
        } elseif ($row_Temperature >= 30.01 && $row_Temperature <= 49.99) {
            $temperature_class = 'orange';
        }

        $temperature_class = ($row_Temperature >= 50.01) ? 'blink ' . $temperature_class : $temperature_class;

        // Affichage des données dans le tableau
        echo '<tr> 
                <td> ' .$row_Horaire . '</td>
                <td> ' .$row_IDrobot . '</td>
                <td> ' . $row_Emplacement . '</td>
                <td>' . $row_Direction . '</td>
                <td class="' . $point_chaud_class . '">' . $row_Point_chaud . '°C</td> 
                <td class="' . $temperature_class . '">' . $row_Temperature . '°C</td> 
                <td>' . $row_Fumee . '</td> 
                <td> ' .$row_Obstacle . '</td>
                
              </tr>';
    }
    // Libération des résultats de la mémoire
    $result->free();
}

// Fermeture de la connexion à la base de données
$conn->close();
?> 
</table>

<!-- Scripts JavaScript -->
<script src="darkmode.js"></script>
</body>
</html>

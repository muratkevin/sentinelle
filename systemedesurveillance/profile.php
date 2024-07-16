<?php
// Démarrage de la session
session_start();

// Vérification de la connexion de l'utilisateur
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html'); // Redirection vers la page de connexion si l'utilisateur n'est pas connecté
    exit;
}

// Vérification si l'utilisateur est "Admin"
if ($_SESSION['name'] !== 'Admin') {
    // Redirection vers une autre page ou affichage d'un message d'erreur
    echo "Accès non autorisé. Seul l'Admin peut accéder à cette page.";
    echo '<meta http-equiv="refresh" content="4;url=home.php" />';
    exit;
}

// Informations de connexion à la base de données
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'Admin';
$DATABASE_PASS = 'sentinelle';
$DATABASE_NAME = 'phplogin';

// Connexion à la base de données
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Insérer une nouvelle entrée dans la table connection_history
$currentTime = date("Y-m-d H:i:s");
$insertQuery = "INSERT INTO connection_history (username, login_time) VALUES (?, ?)";
$stmt = $con->prepare($insertQuery);
$stmt->bind_param("ss", $_SESSION['name'], $currentTime);
$stmt->execute();
$stmt->close();


// Préparation et exécution de la requête pour récupérer le mot de passe et l'email de l'utilisateur
$stmt = $con->prepare('SELECT password, email FROM accounts WHERE id = ?');
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $email);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Compte Administrateur</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="darkmode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
</head>
<body class="loggedin">
<nav class="navtop">
    <div>
        <div>
            <h1>Compte</h1>
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

<div class="content">
    <h2>Page de compte</h2>
    <div>
        <p>Vous trouverez ci-dessous les détails de votre compte :</p>
        <table>
            <tr>
                <td>Nom d'utilisateur :</td>
                <!-- Affichage du nom d'utilisateur récupéré depuis la session -->
                <td><?=$_SESSION['name']?></td> 
            </tr>
            <tr>
                <td>Mot de passe :</td>
                <!-- Affichage du mot de passe récupéré depuis la base de données -->
                <td><?=$password?></td> 
            </tr>
        </table>
    </div>
</div>

<div class="content">
    <h2>Historique des connexions</h2>
    <div>
        <table>
            <thead>
                <tr>
                    <th>Nom d'Utilisateur</th>
                    <th></th>
                    <th>Heure de Connexion</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Connexion à la base de données
                $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
                if (mysqli_connect_errno()) {
                    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
                }

                // Récupération de l'historique des connexions depuis la base de données
                $query = "SELECT username, login_time FROM connection_history ORDER BY login_time DESC";
                $result = mysqli_query($con, $query);

                // Affichage des données dans le tableau
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row[''] . "</td>";
                    echo "<td>" . $row['login_time'] . "</td>";
                    echo "</tr>";
                }

                // Fermeture de la connexion à la base de données
                mysqli_close($con);
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Inclure le script JavaScript -->
<script src="darkmode.js"></script>
</body>
</html>

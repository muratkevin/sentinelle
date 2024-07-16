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
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="refresh" content="5">
    <title>Tableau de Bord</title>
    <!-- Import des fichiers CSS -->
    <link href="style.css" rel="stylesheet" type="text/css">
    <link href="styletemperature.css" rel="stylesheet" type="text/css">
    <link href="styledata.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="darkmode.css">
    <link rel="stylesheet" href="popup.css">
    <link rel="stylesheet" href="popup1.css">
    <link rel="stylesheet" href="alerte.css">
    <link rel="stylesheet" href="courbe.css">
    <!-- Import de la bibliothèque d'icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
</head>
<body class="loggedin">
<!-- Barre de navigation -->
<nav class="navtop">
    <div>
        <div>
            <h1>Tableau de bord</h1>
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

<div class="welcome-message">
    <?php
    session_start();
    if(isset($_SESSION['name'])) {
        echo 'Bonjour, ' . htmlspecialchars($_SESSION['name'], ENT_QUOTES) . ' ! Bienvenue sur le tableau de bord.';
    } else {
        echo 'Bienvenue sur le tableau de bord.';
    }
    ?>
</div>

<div class="containerx">
    <div class="chart-container">
    <b><p class="graph"> Analyses Environnementales <p></b>
        <canvas id="realtimeChart"></canvas>
    </div>

    <b><p class="sys"> Plan du système <p></b>
    
    <div class="content">
    <div class="containerx">
        <img src="" class= "image" alt="Mon Image" id="monImage">
    </div>
</div>

<script>
    // Fonction pour détecter la taille de l'écran
    function detectScreenSize() {
        // Obtenir la largeur de l'écran
        var screenWidth = window.innerWidth;

        // Vérifier la largeur de l'écran et changer l'image en conséquence
        if (screenWidth >= 1920) {
            document.getElementById("monImage").src = "Capture2.PNG";
        } else {
            document.getElementById("monImage").src = "Capture1.PNG";
        }
    }

    // Appeler la fonction de détection de la taille de l'écran lors du chargement de la page
    window.onload = detectScreenSize;
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Affichage du contenu récupéré depuis la base de données -->
    <div class="content">
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
    $sql = "SELECT Obstacle, IDrobot, Point_chaud, Fumee, Direction, Emplacement, Horaire, Temperature FROM Donnees_zumo WHERE Point_chaud > 50 OR Temperature > 50";

    // Affichage du tableau
    echo '<div class="table-container">';
    echo '<table>
          <tr>
            <th>Emplacement</th>
            <th>Horaire</th>
            <th>IDrobot</th>
            <th>Point Chaud</th> 
            <th>Température</th>
          </tr>';

    if ($result = $conn->query($sql)) {
        // Boucle à travers les résultats
        while ($row = $result->fetch_assoc()) {
            // Formatage des données
            $row_Emplacement = $row["Emplacement"];
            $row_Horaire = date("d/m/y H:i:s", strtotime($row["Horaire"]));
            $row_IDrobot = $row['IDrobot'];
            $row_Point_chaud = $row["Point_chaud"]; 
            $row_Temperature = $row["Temperature"];
            $row_Obstacle = $row["Obstacle"];

            // Détermination des classes CSS en fonction des valeurs
            $point_chaud_class = '';
            if ($row_Point_chaud > 50) {
                $point_chaud_class = 'blink red';
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

            // Affichage des données dans le tableau
            echo '<tr> 
                    <td>' . $row_Emplacement . '</td>
                    <td>' . $row_Horaire . '</td>
                    <td>' . $row_IDrobot . '</td>
                    <td class="' . $point_chaud_class . '">' . $row_Point_chaud . '°C</td> 
                    <td class="' . $temperature_class . '">' . $row_Temperature . '°C</td> 
                  </tr>';
        }
        // Libération du résultat
        $result->free();
    }

    // Fermeture de la connexion à la base de données
    $conn->close();
?> 

</table>
</div>
</div>
<?php
// Connexion à la base de données pour vérifier l'indicateur de popup
$servername = "localhost";
$dbname = "Sentinelle";
$username = "Admin";
$password = "sentinelle";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Requête SQL pour récupérer la dernière valeur de point_chaud
$sql = "SELECT Point_chaud FROM Donnees_zumo ORDER BY Horaire DESC LIMIT 1";
$result = $conn->query($sql);

$popup_enabled = false;
$last_point_chaud = null;

if ($result->num_rows > 0) {
    // Récupération de la dernière valeur de point_chaud
    $row = $result->fetch_assoc();
    $last_point_chaud = $row["Point_chaud"];
    
    // Vérification si la dernière valeur de point_chaud est supérieure à 50 degrés
    if ($last_point_chaud > 50) {
        $popup_enabled = true;
    }
}

// Fermeture de la connexion à la base de données
$conn->close();
?>

<!-- Structure HTML du popup -->
<?php if ($popup_enabled): ?>
<div class="popup" id="myPopup">
    <button id="close">&times;</button>
    <h2 style="margin-top: -20px;">Alerte majeur détecté</h2>
    <h1 style="color: white; margin-bottom: 10px;">
    La température est élevée dans la zone du <?php echo $row_Emplacement; ?>. La température est de <?php echo $last_point_chaud; ?> °C.
</h1>
</div>
<?php endif; ?>

<script>
    // Récupérer les données du fichier PHP
    fetch('courbe.php')
    .then(response => response.json())
    .then(data => {
        // Créer le graphique avec les données récupérées
        var ctx = document.getElementById('realtimeChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>

<?php
// Connexion à la base de données pour vérifier l'indicateur de popup
$servername = "localhost";
$dbname = "Sentinelle";
$username = "Admin";
$password = "sentinelle";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Requête SQL pour récupérer la dernière valeur de point_chaud
$sql = "SELECT Point_chaud, Temperature FROM Donnees_zumo ORDER BY Horaire DESC LIMIT 1";
$result = $conn->query($sql);

$popup_enabled = false;
$last_point_chaud = null;
$last_Temperature = null;

if ($result->num_rows > 0) {
    // Récupération de la dernière valeur de point_chaud
    $row = $result->fetch_assoc();
    $last_point_chaud = $row["Point_chaud"];
    $last_Temperature = $row["Temperature"];
    
    // Vérification si la dernière valeur de point_chaud est supérieure à 50 degrés
    if ($last_point_chaud > 50) {
        $popup_enabled = true;
    }

        // Vérification si la dernière valeur de Temperature est supérieure à 50 degrés
        if ($last_Temperature > 50) {
            $popup_enabled = true;
        }
}

// Fermeture de la connexion à la base de données
$conn->close();
?>

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

    // Requête SQL pour récupérer les données de la colonne Point_chaud2 uniquement
    $sql = "SELECT Point_chaudd, Emplacementt FROM Donnees_zumo";


    if ($result = $conn->query($sql)) {
        // Boucle à travers les résultats
        while ($row = $result->fetch_assoc()) {
            // Récupération de la valeur de Point_chaud2
            $row_Point_chaudd = $row["Point_chaudd"];
            $row_Emplacementt = $row["Emplacementt"];

            // Affichage de la valeur de Point_chaud2 dans le tableau
            echo '<tr> 
                  </tr>';
        }
        // Libération du résultat
        $result->free();
    }

    // Fermeture de la connexion à la base de données
    $conn->close();
?> 

<style>
        #bot1 {
            position: absolute;
            top: 100px; 
            right: 521px;
        }

        #bot2 {
            position: absolute;
            top: 217px;
            right: 521px;
        }

        #bot3 {
            position: absolute;
            top: 333px;
            right: 521px;
        }

        #bot4 {
            position: absolute;
            top: 460px;
            right: 522px;
        }
    </style>

<!-- Ajoutez ces identifiants aux images -->
<img src="bot1.PNG" alt="MonImage1" id="bot1">
<img src="bot2.PNG" alt="MonImage2" id="bot2">
<img src="bot3.PNG" alt="MonImage3" id="bot3">
<img src="bot4.PNG" alt="MonImage4" id="bot4">

<script>
    // Récupérez la valeur de $row_Emplacement de PHP
    var row_Emplacementt = "<?php echo $row_Emplacementt; ?>";

    // Vérifiez si $row_Emplacement contient une valeur avant d'afficher/masquer les images
    if (row_Emplacementt !== "") {
        if (row_Emplacementt === "Point d&#039;arrêt 1") {
            document.getElementById('bot1').style.display = 'block';
            document.getElementById('bot2').style.display = 'none';
            document.getElementById('bot3').style.display = 'none';
            document.getElementById('bot4').style.display = 'none';
        } else if (row_Emplacementt === "Point d&#039;arrêt 2") {
            document.getElementById('bot1').style.display = 'none';
            document.getElementById('bot2').style.display = 'block';
            document.getElementById('bot3').style.display = 'none';
            document.getElementById('bot4').style.display = 'none';
        } else if (row_Emplacementt === "Point d&#039;arrêt 3") {
            document.getElementById('bot1').style.display = 'none';
            document.getElementById('bot2').style.display = 'none';
            document.getElementById('bot3').style.display = 'block';
            document.getElementById('bot4').style.display = 'none';
        } else if (row_Emplacementt === "Point d&#039;arrêt 4") {
            document.getElementById('bot1').style.display = 'none';
            document.getElementById('bot2').style.display = 'none';
            document.getElementById('bot3').style.display = 'none';
            document.getElementById('bot4').style.display = 'block';
        }
    } else {
        // Si $row_Emplacement ne contient aucune valeur, masquer toutes les images
        document.getElementById('bot1').style.display = 'none';
        document.getElementById('bot2').style.display = 'none';
        document.getElementById('bot3').style.display = 'none';
        document.getElementById('bot4').style.display = 'none';
    }
</script>

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

// Requête SQL pour récupérer les données de la colonne Point_chaud2 uniquement
$sql = "SELECT Temperature, Emplacementt FROM Donnees_zumo";

// Exécution de la requête
if ($result = $conn->query($sql)) {
    // Initialisation du compteur de valeurs
    $count = 0;

    // Boucle à travers les résultats
    while ($row = $result->fetch_assoc()) {
        // Récupération de la valeur de Temperature
        $row_Temperature = $row["Temperature"];

        if ($row_Temperature > -40) {
            $count++;
        }
    }

    // Libération du résultat
    $result->free();
}

// Fermeture de la connexion à la base de données
$conn->close();
?>
<b><div class="info-message"><?php echo"Le robot a détecté $count valeurs."; ?></div> </b> 

<div class="carre A1" id="carreA1" style="display: none;"></div>
<div class="carre B1" id="carreB1" style="display: none;"></div>
<div class="carre C1" id="carreC1" style="display: none;"></div>
<div class="carre D1" id="carreD1" style="display: none;"></div>

<script>
    // Récupérez la valeur de $row_Point_chaud de PHP
    var row_Point_chaudd = <?php echo $row_Point_chaudd; ?>;
        
    // Variable pour indiquer si une valeur de point chaud supérieure à 50°C existe
    var pointChaudSup50 = <?php echo ($row_Point_chaudd > 50) ? 'true' : 'false'; ?>;
    
    // Variable pour stocker l'état précédent de la température
    var temperaturePrecedenteSup50 = pointChaudSup50;
        
    // Récupérez la valeur de $row_Emplacement de PHP
    var row_Emplacementt = "<?php echo $row_Emplacementt; ?>";

    // Fonction pour afficher ou masquer les carrés en fonction de la température
    function afficherOuMasquerCarre(elementId, afficher) {
        document.getElementById(elementId).style.display = afficher ? 'block' : 'none';
    }

    // Vérifiez la valeur de row_Emplacement et affichez/masquez les carrés en conséquence
    switch (row_Emplacementt) {
        case "Point d&#039;arrêt 1":
            if (pointChaudSup50) {
                afficherOuMasquerCarre('carreA1', true);
            } else if (!pointChaudSup50 && temperaturePrecedenteSup50) {
                afficherOuMasquerCarre('carreA1', false);
            }
            break;
        case "Point d&#039;arrêt 2":
            if (pointChaudSup50) {
                afficherOuMasquerCarre('carreB1', true);
            } else if (!pointChaudSup50 && temperaturePrecedenteSup50) {
                afficherOuMasquerCarre('carreB1', false);
            }
            break;
        case "Point d&#039;arrêt 3":
            if (pointChaudSup50) {
                afficherOuMasquerCarre('carreC1', true);
            } else if (!pointChaudSup50 && temperaturePrecedenteSup50) {
                afficherOuMasquerCarre('carreC1', false);
            }
            break;
        case "Point d&#039;arrêt 4":
            if (pointChaudSup50) {
                afficherOuMasquerCarre('carreD1', true);
            } else if (!pointChaudSup50 && temperaturePrecedenteSup50) {
                afficherOuMasquerCarre('carreD1', false);
            }
            break;
        default:
            // Si l'emplacement n'est pas reconnu, ne rien afficher
            break;
    }

    // Mettre à jour l'état précédent de la température
    temperaturePrecedenteSup50 = pointChaudSup50;
</script>

<?php
// Connexion à la base de données pour vérifier l'indicateur de popup
$servername = "localhost";
$dbname = "Sentinelle";
$username = "Admin";
$password = "sentinelle";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Requête SQL pour récupérer la dernière valeur de Obstacle
$sql = "SELECT Obstacle FROM Donnees_zumo ORDER BY Horaire DESC LIMIT 1";
$result = $conn->query($sql);

$popup1_enabled = false;
$last_Obstacle = null;

if ($result->num_rows > 0) {
    // Récupération de la dernière valeur de Obstacle
    $row = $result->fetch_assoc();
    $last_Obstacle = $row["Obstacle"];
    
    // Vérification si la dernière valeur de Obstacle est = 1
    if ($last_Obstacle == 1) {
        $popup1_enabled = true;
    }
}

// Fermeture de la connexion à la base de données
$conn->close();
?>

<!-- Structure HTML du popup -->
<?php if ($popup1_enabled): ?>
<div class="popup1" id="myPopup1">
    <button id="close">&times;</button>
    <h2 style="margin-top: -20px;">Alerte mineur détecté</h2>
    <h1 style="color: white; margin-bottom: 10px;">
    Un colis est repéré après le <?php echo $row_Emplacement; ?>.
</h1>
</div>
<?php endif; ?>

<!-- Inclure le script JavaScript -->
<script src="darkmode.js"></script>
<script src="popup.js"></script>
<script src="popup1.js"></script>
</body>
</html>

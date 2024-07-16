<?php
// Connexion à la base de données
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
$sql = "SELECT Horaire, Point_chaud, Temperature FROM Donnees_zumo";

// Exécution de la requête SQL
if ($result = $conn->query($sql)) {
    // Initialisation des tableaux de données
    $row_horaire = [];
    $row_point_chaud = [];
    $row_temperature = []; // Nouveau tableau pour stocker les températures

    // Boucle à travers les résultats
    while ($row = $result->fetch_assoc()) {
        // Ajout des données dans les tableaux
        $row_horaire[] = $row['Horaire'];
        $row_point_chaud[] = $row['Point_chaud'];
        $row_temperature[] = $row['Temperature']; 
    }

    // Libération des résultats de la mémoire
    $result->free();
}

// Fermeture de la connexion à la base de données
$conn->close();

// Encodage des données au format JSON
$data = [
    'labels' => $row_horaire,
    'datasets' => [
        [
            'label' => 'Point Chaud',
            'data' => $row_point_chaud,
            'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
            'borderColor' => 'rgba(255, 99, 132, 1)',
            'borderWidth' => 1
        ],
        [
            'label' => 'Température',
            'data' => $row_temperature,
            'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
            'borderColor' => 'rgba(54, 162, 235, 1)',
            'borderWidth' => 1
        ]
    ]
];

// Envoi des données encodées au format JSON
header('Content-Type: application/json');
echo json_encode($data);
?>

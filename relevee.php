<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "station_meteo";

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifie la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Vérifie si les données POST sont bien reçues
if (isset($_POST["temperature"]) && isset($_POST["humidite"])) {
    $temperature = $_POST["temperature"];
    $humidite = $_POST["humidite"];

    // Prépare la requête pour éviter les injections SQL
    $stmt = $conn->prepare("INSERT INTO mesures (temperature, humidity) VALUES (?, ?)");
    $stmt->bind_param("dd", $temperature, $humidite);

    if ($stmt->execute()) {
        echo "Données enregistrées avec succès !";
    } else {
        echo "Erreur lors de l'enregistrement : " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Données manquantes !";
}

$conn->close();
?>

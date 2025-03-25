<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "station_meteo";

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

$sql = "SELECT temperature, humidite, DATE_FORMAT(horodatage, '%d-%m-%Y %H:%i:%s') AS horodatage FROM mesures ORDER BY horodatage DESC LIMIT 10";
$result = $conn->query($sql);

if (!$result) {
    die("Erreur SQL : " . $conn->error);
}

$temperatures = [];
$humidities = [];
$horodatages = [];

while ($row = $result->fetch_assoc()) {
    $temperatures[] = $row['temperature'];
    $humidities[] = $row['humidite'];
    $horodatages[] = $row['horodatage'];
}

$conn->close();


$token = "8101236750:AAFw9kf1KQq885QJyofY52m58Rb46ZkXmyI"; 
$chat_id = "8162309199"; 


$last_temperature = $temperatures[0];
$last_humidity = $humidities[0];


$temperature_threshold = 30;  
$humidity_threshold = 80;     


if ($last_temperature > $temperature_threshold || $last_humidity > $humidity_threshold) {
    $message = "🚨 *Alerte Météo !* 🚨\n";
    $message .= "🌡 *Température:* " . $last_temperature . "°C\n";
    $message .= "💧 *Humidité:* " . $last_humidity . "%";
    
  
    $url = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=" . urlencode($message) . "&parse_mode=Markdown";

   
    file_get_contents($url);
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Station Météo RTGT</title>
    <link rel="stylesheet" type="text/css" href="meteo1.css">

    <!-- Inclusion de Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        var temperatures = <?php echo json_encode($temperatures); ?>;
        var humidities = <?php echo json_encode($humidities); ?>;
        var horodatages = <?php echo json_encode($horodatages); ?>;
        
        function reloadPage() {
            location.reload(); 
        }
        setInterval(reloadPage, 10000); 
    </script>
</head>
<body>

    <div id="badweather">
        <div id="raining">
            <div id="shiningobj" class='sun'></div>
            <h1><p>Station Météo</p></h1>
            <br><br>
            <div id="MyClockDisplay" class="clock" onload="showTime()"></div>
            <div class="list_horizontale">
                
                <div class="temperature">
                    <a id="warmth">🌡️ <?php echo $temperatures[0]; ?> °C</a> 
                </div>
                <div>
                    <a class="logo" onclick="nightmode(), toggleActive(this), sun(), RAIN()">
                        <img src="/sun.png" onclick="logomode()">
                    </a>
                </div>
                <div class="humidité">
                    <a id="wet">💧 <?php echo $humidities[0]; ?> %</a> 
                </div>
            </div>

            <div class="graph">
                <canvas id="myChart"></canvas>
            </div>
            <div id="graph">
                <script>
                    if (typeof Chart === 'undefined') {
                        console.error("Chart.js n'est pas chargé correctement !");
                    }

                    var temperatures = <?php echo json_encode($temperatures); ?>;
                    var humidities = <?php echo json_encode($humidities); ?>;
                    console.log("Données reçues :", { temperatures, humidities});

                    if (temperatures.length === 0 || humidities.length === 0) {
                        console.warn("Les données sont vides !");
                    }

                    var ctx = document.getElementById('myChart').getContext('2d');
                    var myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: horodatages,
                            datasets: [{
                                label: 'Température (°C)',
                                data: temperatures,
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 2,
                                fill: false
                            },
                            {
                                label: 'Humidité (%)',
                                data: humidities,
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 2,
                                fill: false
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: {
                                    ticks: {
                                        autoSkip: true, // Saute des labels si trop serrés
                                        maxRotation: 90,  // Rotation maximale des labels (0° = horizontal)
                                        minRotation: 90   // Rotation minimale (0° = horizontal)
                                    }
                                },
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                </script>
            </div>
        </div>
    </div>

    <script src="/main.js" defer></script>
</body>
</html>

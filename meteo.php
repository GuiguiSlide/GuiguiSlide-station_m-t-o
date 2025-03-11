<?php  
    $verz = "1.0";
    $comPort = "/station.ino";
    define('DEBUG', false);

    date_default_timezone_set('Europe/Paris');

    if (constant('DEBUG')) {
        $data = [
            'Humidity' => 45.5,
            'Temperature' => 23.1
        ];
        file_put_contents('headers.txt', var_export($_SERVER, true), FILE_APPEND);
    } else {
        $data = &$_POST;
    }

    if (constant('DEBUG') || (!empty($_SERVER['HTTP_X_APIKEY']) && $_SERVER['HTTP_X_APIKEY'] == '4Jrt39dj')) {

        $whiteList = [
            'Temperature',
            'Humidity'
        ];

        $whiteList = array_fill_keys($whiteList, NULL);
        
        $storeData['DateTime'] = date('Y-m-d H:i:s');

        $storeData = array_merge($storeData, $whiteList, array_intersect_key($data, $whiteList));

        // Database connection
        $servername = "localhost";
        $username = "your_username";
        $password = "your_password";
        $dbname = "weather_station";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if (!empty($storeData['Temperature']) && !empty($storeData['Humidity'])) {
            $temperature = $storeData['Temperature'];
            $humidity = $storeData['Humidity'];
            $datetime = $storeData['DateTime'];

            $sql = "INSERT INTO sensor_data (temperature, humidity, timestamp) 
                    VALUES ('$temperature', '$humidity', '$datetime')";

            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        // Close connection
        $conn->close();

        if (constant('DEBUG')) {
            print_r($storeData);
        }
    }

    include 'index.html';
?>
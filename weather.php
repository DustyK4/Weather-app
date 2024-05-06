<?php
header("Access-Control-Allow-Origin: * ");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

header('Content-Type: application/json');

header('Content-Type: application/json');
$city = 'Gandhinagar';

function fetch_weather_data()
{
    global $city;
    $api_key = '5e3c61d27c6844b92094a838749fd79a';
    $url = 'https://api.openweathermap.org/data/2.5/weather?q=' .
        $city .
        '&units=metric&appid=' .
        $api_key;
    $json_data = file_get_contents($url);
    $response_data = json_decode($json_data);
    if ($response_data == null || isset($response_data->cod) && $response_data->cod != 200) {
        return false;
    }
    if ($response_data->cod == 200) {
        $day_of_week = date('D');
        $day_and_date = date('M j, Y');
        $weather_condition = $response_data->weather[0]->description;
        $temperature = $response_data->main->temp;
        $wind_speed = $response_data->wind->speed;
        $humidity = $response_data->main->humidity;

        return [$day_of_week, $day_and_date, $weather_condition, $temperature, $wind_speed, $humidity];
    } else {
        echo 'Error';
    }
}

function createDB($servername, $username, $password, $dbname)
{
    $conn = new mysqli($servername, $username, $password);
    if ($conn->connect_error) {
        die('failed' . $conn->connect_error);
    }
    $sql = 'CREATE DATABASE IF NOT EXISTS ' . $dbname; // Fixed variable interpolation
    if ($conn->query($sql) !== true) {
        echo 'error' . $conn->error;
    }
    $conn->close();
}

function create_table($servername, $username, $password, $dbname)
{
    global $city;
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die('error' . $conn->connect_error);
    }
    $sql = 'CREATE TABLE if not exists ' . $city . '(
    id INT AUTO_INCREMENT PRIMARY KEY,
    Day_of_week VARCHAR(15),
    Day_and_Date VARCHAR(20),
    Weather_Condition VARCHAR(20),
    Temperature INT(5),
    Wind_Speed DECIMAL(6),
    Humidity INT(6)
)';
    if ($conn->query($sql) !== true) {
        echo 'error' . $conn->error;
    }
    $conn->close();
}

function insert_update_data($servername, $username, $password, $dbname)
{
    global $city;
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die('error' . $conn->connect_error);
    }
    list($day_of_week, $day_and_date, $weather_condition, $temperature, $wind_speed, $humidity) = fetch_weather_data(); // Added fetch_weather_data()
    $existing_sql = "SELECT * FROM $city WHERE Day_of_week ='$day_of_week'";
    $existing_result = $conn->query($existing_sql);
    if ($existing_result->num_rows === 0) {
        $insert_sql = "INSERT INTO $city ( Day_of_week , Day_and_Date , Weather_Condition , Temperature, Wind_Speed , Humidity ) 
        VALUES ('$day_of_week','$day_and_date','$weather_condition','$temperature','$wind_speed','$humidity')";
        if ($conn->query($insert_sql) !== true) {
            echo "error" . $insert_sql . '<br>' . $conn->error;
        }
    } else {
        $update_sql = "UPDATE $city
                SET
                Weather_Condition = '$weather_condition',
                Temperature = $temperature,
                Wind_Speed = $wind_speed,
                Humidity = $humidity,
                Day_and_Date = '$day_and_date'
                WHERE Day_of_week = '$day_of_week'
    ";
        if ($conn->query($update_sql) !== true) {
            echo "error" . $update_sql . "<br>" . $conn->error;
        }
    }
    $conn->close();
}

function display_data($servername, $username, $password, $dbname)
{
    global $city;
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("error" . $conn->connect_error);
    }
    $sql = "SELECT * FROM $city ORDER BY id ASC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $all_data = array();
        while ($row = $result->fetch_assoc()) {
            array_push($all_data, $row);
        }
        return json_encode($all_data); // Fixed json_encode
    } else {
        echo "0 results";
    }
}

function connect_DB()
{
    global $city;
    $servername = "sql211.infinityfree.com";
    $username = "if0_35967444";
    $password = "i6NlbcJgXKc6Q";
    $dbname = "if0_35967444_city_weather"; // Fixed dbname

    createDB($servername, $username, $password, $dbname);
    create_table($servername, $username, $password, $dbname);
    insert_update_data($servername, $username, $password, $dbname);
    $json_data = display_data($servername, $username, $password, $dbname);
    return $json_data;
}

echo connect_DB();
?>

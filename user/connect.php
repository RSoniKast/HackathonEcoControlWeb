<?php
// URL of the website to fetch data from
$url = '192.168.1.50';

// Use cURL to fetch the data
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// Check if the fetch was successful
if ($response === false) {
    die('Error fetching data from the website.');
}

// Parse the HTML content to extract the required variables
$dom = new DOMDocument();
@$dom->loadHTML($response);

// Use DOMXPath to find the relevant <p> tags
$xpath = new DOMXPath($dom);
$pTags = $xpath->query('//p');

// Initialize variables
$temperature = $humidity = $tension = null;

foreach ($pTags as $pTag) {
    $text = $pTag -> textContent;
    
    if(strpos($text, 'Temperature:') !== false) {
        $temperature = trim(str_replace('Temperature:', '', $text));
    } elseif (strpos($text, 'Humidite:') !== false) {
        $humidity = trim(str_replace('Humidite:', '', $text));
    } elseif (strpos($text, 'Ampere:') !== false) {
        $tension = trim(str_replace('Ampere:', '', $text));
    }
}

// Get the current date
$currentDate = date('Y-m-d H:i:s');

// Database credentials
$host = 'localhost';
$dbname = 'mesure';
$username = 'root';
$password = '';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Prepare the SQL statement with placeholders
    $sql = "INSERT INTO mesure (Temperature, Humidite, Tension, DateHeure) VALUES (:temperature, :humidite, :tension, :date)";
    $stmt = $pdo->prepare($sql);
    
    // Bind the data to the placeholders
    $stmt->bindParam(':temperature', $temperature);
    $stmt->bindParam(':humidite', $humidity);
    $stmt->bindParam(':tension', $tension);
    $stmt->bindParam(':date', $currentDate);
    
    // Execute the statement
    $stmt->execute();

    echo "Data successfully inserted into the database.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
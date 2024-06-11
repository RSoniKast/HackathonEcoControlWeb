<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mesure";

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT Humidité, Température, Tension, Courant FROM mesurestable LIMIT 1";
$result = $conn->query($sql);

$data = array(); // Ajouté cette ligne

if ($result->num_rows > 0) {
  // Récupérer les données de chaque ligne
  while($row = $result->fetch_assoc()) {
    $data = $row; // Modifié cette ligne
  }
} else {
  echo "0 results";
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoControl</title>
    <link rel="stylesheet" type="text/css" href="css/index.css">
</head>
<body>
    <header>
        <h1>EcoControl</h1>
    </header>
    <main>
        <section>
            <h2>Energie / Température / Humidité</h2>
            <div class="circle-container">
                <div id="kwh" class="circle">
                    <svg width="120" height="120">
                        <circle r="50" cx="60" cy="60" />
                    </svg>
                    <span class="circle-text"></span>
                </div>
                <div id="temperature" class="circle">
                    <svg width="120" height="120">
                        <circle r="50" cx="60" cy="60" />
                    </svg>
                    <span class="circle-text"></span>
                </div>
                <div id="humidity" class="circle">
                    <svg width="120" height="120">
                        <circle r="50" cx="60" cy="60" />
                    </svg>
                    <span class="circle-text"></span>
                </div>
            </div>
        </section>
    </main>
    <script>
    var data = <?php echo json_encode($data); ?>; // Ajouté cette ligne
    </script>
    <script src="js/index.js"></script>
</body>
</html>

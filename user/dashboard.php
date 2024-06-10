<?php
if (isset($_GET['nbrLettre'])) {
    $nbrLettre = $_GET['nbrLettre'];
    // Process the value of nbrLettre as needed, e.g., save to a database, log to a file, etc.
    // For this example, let's just write it to a file.
    file_put_contents('nbrLettre.txt', $nbrLettre . PHP_EOL, FILE_APPEND);
    echo "Received: " . $nbrLettre;
} else {
    echo "No nbrLettre value received.";
}

// $client = new MongoDB\Client('mongodb://mongodb-deployment:27017');
?>
<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=hackathonecocontrolweb', 'root', '');


if(isset($_SESSION['username'])) {
    // Récupérer les informations de l'utilisateur
    $query = $bdd->prepare("SELECT pseudo, photo_profil FROM users WHERE pseudo = ?");
    $query->execute([$_SESSION['username']]);
    $user = $query->fetch(PDO::FETCH_ASSOC);
    
}


// Afficher le message d'erreur s'il existe
$error_message = "";
if(isset($_SESSION['error'])) {
    $error_message = $_SESSION['error'];
    unset($_SESSION['error']);
}

// Gérer la déconnexion
if (isset($_GET['logout'])) {
  session_unset();
  session_destroy();
  header("Location: index.php");
  exit();
}



?>

<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>EcoControl - Page d'accueil</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>
  <nav class="navbar fixed-top navbar-light bg-light">
    <div class="container">
        <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
          <span class="fs-4">
              <bold><span class="text-primary">Eco</span><span class="text-success">Control</bold></span>
          </span>
        </a>
        <ul class="nav nav-pills">
          <li class="nav-item"><a href="#" class="nav-link link-secondary" aria-current="page">Accueil</a></li>
          <li class="nav-item"><a href="#" class="nav-link text-success">À Propos</a></li>
          <li class="nav-item"><a href="#" class="nav-link text-success">FAQs</a></li>
          <?php
        if (isset($_SESSION['username'])) {
            // Afficher le lien vers la page de profil avec le pseudo de l'utilisateur
            echo "<nav><a href='?logout=true' class='btn btn-success' style='text-decoration:none'>Se déconnecter</a></nav>";
        } else {
            echo "<nav><a href='html/login.php' class='btn btn-success' style='text-decoration:none'>Connexion</a></nav>";
        }
        ?>
          <a class="btn btn-success" href="html/register.php" role="button">S'inscrire</a>
        </ul>
    </div>
  </nav>

  <main>
  <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-body-secondary" style="background-image: url('img/ecocontrolbg.jpg');">
    <div class="col-md-6 p-lg-5 mx-auto my-5">
      <h1 class="display-1 text-white">Jouez, économisez, gagnez</h1>
      <h4 class="fw-normal text-white">Avec EcoControl, l'économie d'énergie n'a jamais été aussi ludique</h4>
      <div class="d-flex gap-3 justify-content-center lead fw-normal">
      <a class="btn btn-success" href="html/register.php" role="button">Inscrivez-vous</a>  
      </div>
    </div>
  </div>

  <footer class="container py-5">
  <div class="row">
    <div class="col-12 col-md">
      EcoControl © 2024
    </div>
    <div class="col-6 col-md">
      <h5>Features</h5>
      <ul class="list-unstyled text-small">
        <li><a class="link-secondary text-decoration-none" href="#">Cool stuff</a></li>
        <li><a class="link-secondary text-decoration-none" href="#">Random feature</a></li>
        <li><a class="link-secondary text-decoration-none" href="#">Team feature</a></li>
      </ul>
    </div>
    <div class="col-6 col-md">
      <h5>Resources</h5>
      <ul class="list-unstyled text-small">
        <li><a class="link-secondary text-decoration-none" href="#">Resource name</a></li>
        <li><a class="link-secondary text-decoration-none" href="#">Resource</a></li>
        <li><a class="link-secondary text-decoration-none" href="#">Another resource</a></li>
        <li><a class="link-secondary text-decoration-none" href="#">Final resource</a></li>
      </ul>
    </div>
    <div class="col-6 col-md">
      <h5>À Propos</h5>
      <ul class="list-unstyled text-small">
        <li><a class="link-secondary text-decoration-none" href="#">Contactez-nous</a></li>
        <li><a class="link-secondary text-decoration-none" href="#">Conditions générales d'utilisation</a></li>
      </ul>
    </div>
  </div>
</footer>
</html>

<?php 
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=mesure', 'root', '');

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

// Vérifier si l'utilisateur a cliqué sur le lien de déconnexion
if (isset($_GET['logout'])) {
    // Terminer la session
    session_unset();
    session_destroy();
    // Rediriger vers la page de connexion
    header("Location: login.php");
    exit();
}

?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>EcoControl - À Propos</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>

  <nav class="navbar fixed-top navbar-light bg-light">
    <div class="container">
        <a href="index" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
          <span class="fs-4">
              <bold><span class="text-primary">Eco</span><span class="text-success">Control</bold></span>
          </span>
        </a>
        <ul class="nav nav-pills">
          <li class="nav-item"><a href="index" class="nav-link text-success" aria-current="page">Accueil</a></li>
          <li class="nav-item"><a href="#" class="nav-link link-secondary">À Propos</a></li>
          <li class="nav-item"><a href="contact" class="nav-link text-success">Contact</a></li>
          <?php
          if (isset($_SESSION['username']))   {
              // Afficher le lien vers la page de profil avec le pseudo de l'utilisateur
              $username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
              echo "<li class='nav-item'><a href='user.php' class='nav-link text-success'>{$username}</a></li>";
              echo "<li class='nav-item'><a class='btn btn-success' href='user.php?logout=true'>Déconnexion</a></li>";
          } else {
              echo "<li><a href='login.php' class='btn btn-outline-success me-2' style='text-decoration:none'>Connexion</a></li>";
              echo "<li><a href='register.php' class='btn btn-success'>S'inscrire</a></li>";
          }
          ?>
        </ul>
    </div>
  </nav>

    <div class="px-4 py-5 my-5 mx-5 text-justify">
        <h1 class="display-4 text-center">À Propos</h1>
        <h3>Qui sommes-nous ?</h3>
        EcoControl est une initiative innovante dédiée à simplifier la gestion de la consommation énergétique pour les foyers et les entreprises. Notre mission est de vous offrir des outils 
        efficaces pour surveiller et contrôler votre consommation d'énergie en temps réel, vous permettant ainsi d'optimiser votre utilisation des ressources, de réduire vos coûts et de minimiser votre 
        impact environnemental.
        <h3>Nos solutions</h3>
        Nous proposons des versions spécialisées pour l'électricité, le gaz et l'eau, afin de répondre précisément aux besoins uniques de chaque utilisateur. EcoControl s'intègre facilement dans 
        les réseaux existants, offrant des solutions sur mesure pour une gestion énergétique optimale.
        <h3>Notre objectif</h3>
        Notre principal objectif est d'identifier et de réduire le gaspillage énergétique, contribuant ainsi à une utilisation plus efficace des ressources. En plus de fournir des outils de gestion 
        avancés, EcoControl vise également à sensibiliser les utilisateurs à l'importance de la réduction de la consommation énergétique à domicile.
        <h3>Pourquoi choisir EcoControl ?</h3>
        <li>Intégration facile : Compatible avec les infrastructures existantes.</li>
        <li>Surveillance en temps réel : Suivi précis de votre consommation énergétique.</li>
        <li>Solutions personnalisées : Adaptées à vos besoins spécifiques en électricité, gaz et eau.</li>
        <li>Impact positif : Réduction des coûts et diminution de l'empreinte environnementale.</li>
        Rejoignez-nous dans notre démarche pour un avenir plus vert et économisez sur vos factures énergétiques grâce à EcoControl !
    </div>

    <footer class="container py-5">
        <div class="row">
            <div class="col-12 col-md">
            EcoControl © 2024
            </div>
            <div class="col-6 col-md">
            <h5>Pages</h5>
            <ul class="list-unstyled text-small">
                <li><a class="link-secondary text-decoration-none" href="#">Accueil</a></li>
                <li><a class="link-secondary text-decoration-none" href="questions">F.A.Q.</a></li>
            </ul>
            </div>
            <div class="col-6 col-md">
            <h5>Autres</h5>
            <ul class="list-unstyled text-small">
                <li><a class="link-secondary text-decoration-none" href="about">À Propos</a></li>
                <li><a class="link-secondary text-decoration-none" href="contact">Contactez-nous</a></li>
                <li><a class="link-secondary text-decoration-none" href="terms">Conditions générales d'utilisation</a></li>
            </ul>
            </div>
        </div>
    </footer>
</html>
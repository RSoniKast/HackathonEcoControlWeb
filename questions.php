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
        <title>EcoControl - F.A.Q.</title>
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
          <li class="nav-item"><a href="about" class="nav-link text-success">À Propos</a></li>
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
        <h1 class="display-4 text-center">F.A.Q.</h1>
        <h3>Q: Qu'est-ce qu'EcoControl ?</h3>
        EcoControl est une plateforme innovante qui facilite la gestion de la consommation énergétique pour les foyers et les entreprises. Elle permet de surveiller et de contrôler en temps réel la consommation d'électricité, de gaz et d'eau, offrant des solutions personnalisées pour chaque utilisateur.
        <h3>Q: Comment EcoControl fonctionne-t-il ?</h3>
        EcoControl s'intègre facilement aux réseaux existants de votre domicile ou entreprise. Une fois installé, il surveille en temps réel votre consommation énergétique et vous fournit des données précises et exploitables pour optimiser l'utilisation de vos ressources.
        <h3>Q: Quels types d'énergie EcoControl peut-il surveiller ?</h3>
        EcoControl peut surveiller et gérer la consommation d'électricité, de gaz et d'eau. Nous proposons des versions spécialisées pour chaque type de ressource afin de répondre aux besoins spécifiques de nos utilisateurs.
        <h3>Q: Comment EcoControl peut-il aider à réduire les coûts énergétiques ?</h3>
        En vous fournissant une surveillance en temps réel et des analyses détaillées de votre consommation énergétique, EcoControl vous aide à identifier les zones de gaspillage. Vous pouvez ainsi prendre des mesures pour réduire votre consommation, optimisant ainsi votre utilisation d'énergie et réduisant vos factures.
        <h3>Q: EcoControl est-il facile à installer ?</h3>
        Oui, EcoControl est conçu pour s'intégrer facilement aux infrastructures existantes. L'installation est simple et rapide, et nos guides et support technique sont à votre disposition pour vous aider en cas de besoin.
        <h3>Q: Est-ce que je peux utiliser EcoControl pour sensibiliser ma famille ou mes employés à la consommation énergétique ?</h3>
        Absolument. EcoControl ne se contente pas de surveiller la consommation énergétique, il vise également à sensibiliser les utilisateurs à l'importance de réduire leur consommation. Les données et rapports fournis peuvent être utilisés pour éduquer et encourager des pratiques de consommation plus responsables.
        <h3>Q: Est-ce que mes données sont sécurisées avec EcoControl ?</h3>
        Oui, la sécurité de vos données est une priorité pour nous. EcoControl utilise des protocoles de sécurité avancés pour garantir la confidentialité et l'intégrité de vos informations personnelles et de vos données de consommation.
        <h3>Q: Comment puis-je commencer avec EcoControl ?</h3>
        Pour commencer avec EcoControl, il vous suffit de visiter notre site web, de choisir la solution qui correspond à vos besoins (électricité, gaz, eau) et de suivre les instructions d'installation. Notre équipe est également disponible pour vous assister tout au long du processus.
        <h3>Q: Puis-je obtenir de l'aide si j'ai des problèmes avec EcoControl ?</h3>
        Oui, notre service client est toujours prêt à vous aider. Vous pouvez nous contacter via notre site web, par email ou par téléphone, et nous serons heureux de répondre à vos questions et de résoudre vos problèmes.
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
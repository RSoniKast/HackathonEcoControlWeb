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

// Gérer la déconnexion
if (isset($_GET['logout'])) {
  session_unset();
  session_destroy();
  header("Location: index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>EcoControl - Connexion</title>
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
                <li class="nav-item"><a href="#" class="nav-link link-secondary" aria-current="page">Accueil</a></li>
                <li class="nav-item"><a href="about" class="nav-link text-success">À Propos</a></li>
                <li class="nav-item"><a href="contact" class="nav-link text-success">Contact</a></li>
                <li><a class="btn btn-outline-success me-2" href="login" role="button">Connexion</a></li>
                <li><button type="button" class="btn btn-success">S'inscrire</button></li>
            </ul>
        </div>
    </nav>

<body class="flex align-items-center py-4 bg-body-tertiary">

<?php
    // Vérifier si le formulaire a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Connexion à la base de données
        try {
            $pdo = new PDO('mysql:host=127.0.0.1;dbname=mesure', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Récupérer les données du formulaire
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Préparer et exécuter la requête SQL pour récupérer l'utilisateur
            $stmt = $pdo->prepare("SELECT * FROM users WHERE pseudo = :pseudo");
            $stmt->bindParam(':pseudo', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérifier si l'utilisateur existe et si le mot de passe est correct
            if ($user && password_verify($password, $user['mot_de_passe'])) {
                // Stocker les informations de l'utilisateur dans la session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['pseudo'];

                // Rediriger l'utilisateur vers la page principale ou une page de succès
                header("Location: user/index.php");
                exit();
            } else {
                $error_message = "Nom d'utilisateur ou mot de passe incorrect.";
            }
        } catch (PDOException $e) {
            $error_message = "Erreur de connexion à la base de données : " . $e->getMessage();
        }
    }
?>

        <main class="flex-grow-1 d-flex align-items-center justify-content-center py-5">
            <div class="form-container">
                <h1 class="h3 mb-3 fw-normal text-center">Connexion</h1>
                <?php
                if (isset($error_message)) {
                    echo "<p style='color: red;'>$error_message</p>";
                }
                ?>
                <form action="" method="post">
                    <div class="form-floating mb-3">
                        <input type="text" name="username" class="form-control" id="username" placeholder="username1234" required>
                        <label for="username">Nom d'utilisateur</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" name="password" class="form-control" id="password" placeholder="Mot de passe" required>
                        <label for="password">Mot de passe</label>
                    </div>
                    <button class="btn btn-success w-100 py-2" type="submit">Se Connecter</button>
                </form>
                <p class="mt-3 text-center">Vous n'avez pas de compte ? <a href="register.php">Inscrivez-vous ici</a>.</p>
            </div>
        </main>

</body>
    <footer class="container py-5">
        <div class="row">
            <div class="col-12 col-md">
            EcoControl © 2024
            </div>
            <div class="col-6 col-md">
            <h5>Pages</h5>
            <ul class="list-unstyled text-small">
                <li><a class="link-secondary text-decoration-none" href="index">Accueil</a></li>
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
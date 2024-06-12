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
        <title>TrackBase - Créer un compte</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>
    <body class="flex align-items-center py-4 bg-body-tertiary">

<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connexion à la base de données
    try {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=mesure', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Récupérer les données du formulaire
        $pseudo = $_POST['pseudo'];
        $email = $_POST['email'];
        $password = $_POST['mot_de_passe'];
        $date_naissance = $_POST['date_naissance'];

        // Vérifier que les champs ne sont pas vides
        if (!empty($pseudo) && !empty($email) && !empty($password) && !empty($date_naissance)) {
            // Vérifier la validité de l'email
            if (filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
                // Calculer l'âge de l'utilisateur
                $birthDate = new DateTime($date_naissance);
                $today = new DateTime('today');
                $age = $today->diff($birthDate)->y;

                // Vérifier si l'utilisateur a au moins 15 ans
                if ($age < 15) {
                    $error_message = "Vous devez avoir au moins 15 ans pour créer un compte.";
                } else {
                    // Vérifier si le pseudo ou l'email est déjà utilisé
                    $stmt_check = $pdo->prepare("SELECT * FROM users WHERE pseudo = :pseudo OR email = :email");
                    $stmt_check->bindParam(':pseudo', $pseudo);
                    $stmt_check->bindParam(':email', $email);
                    $stmt_check->execute();

                    if ($stmt_check->rowCount() > 0) {
                        $error_message = "Le pseudo ou l'email est déjà utilisé. Veuillez choisir un autre.";
                    } else {
                        // Vérifier la longueur du mot de passe
                        if (strlen($password) < 6) {
                            $error_message = "Le mot de passe doit contenir au moins 6 caractères.";
                        } else {
                            // Hachage du mot de passe
                            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                            // Préparer et exécuter la requête SQL pour insérer le nouvel utilisateur dans la base de données
                            $stmt_insert = $pdo->prepare("INSERT INTO users (pseudo, email, mot_de_passe, Date_naissance) VALUES (:pseudo, :email, :mot_de_passe, :date_naissance)");
                            $stmt_insert->bindParam(':pseudo', $pseudo);
                            $stmt_insert->bindParam(':email', $email);
                            $stmt_insert->bindParam(':mot_de_passe', $hashed_password);
                            $stmt_insert->bindParam(':date_naissance', $date_naissance);

                            if ($stmt_insert->execute()) {
                                // Rediriger l'utilisateur vers une page de succès ou une autre page de ton choix
                                header("Location: login.php");
                                exit();
                            } else {
                                $error_message = "Une erreur est survenue lors de la création de votre compte. Veuillez réessayer.";
                            }
                        }
                    }
                }
            } else {
                $error_message = "L'adresse e-mail est invalide.";
            }
        } else {
            $error_message = "Veuillez remplir tous les champs.";
        }
    } catch (PDOException $e) {
        $error_message = "Erreur de connexion à la base de données : " . $e->getMessage();
    }
}
?>

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
    
    <main class="flex-grow-1 d-flex align-items-center justify-content-center py-5">
        <div class="form-container">
            <h1 class="h3 mb-3 fw-normal text-center">Créer un compte</h1>
            <?php
            if (isset($error_message)) {
                echo "<p style='color: red;'>$error_message</p>";
            }
            ?>
            <form action="" method="post">
                <div class="form-floating mb-3">
                    <input type="text" name="pseudo" class="form-control" id="pseudo" placeholder="Pseudo" required>
                    <label for="pseudo">Pseudo</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="email" name="email" class="form-control" id="email" placeholder="Adresse email" required>
                    <label for="email">Adresse email</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" name="mot_de_passe" class="form-control" id="mot_de_passe" placeholder="Mot de passe" required>
                    <label for="mot_de_passe">Mot de passe</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="date" name="date_naissance" class="form-control" id="date_naissance" placeholder="Date de naissance" required>
                    <label for="date_naissance">Date de naissance</label>
                </div>
                <button class="btn btn-success w-100 py-2" type="submit">Créer un compte</button>
            </form>
            <p class="mt-3 text-center">Vous avez déjà un compte ? <a href="login.php">Connectez-vous ici</a>.</p>
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
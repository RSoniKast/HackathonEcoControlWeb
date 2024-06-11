<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=mesure', 'root', '');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
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

// Récupérer le pseudo de l'utilisateur connecté
$username = $_SESSION['username'];

// Connexion à la base de données
try {
    $bdd = new PDO('mysql:host=localhost;dbname=mesure', 'root', '');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}

// Traiter le formulaire de changement de pseudo
if (isset($_POST['change_pseudo'])) {
    $new_pseudo = trim($_POST['new_pseudo']);

    // Vérifier si le nouveau pseudo est valide
    if (!empty($new_pseudo) && strlen($new_pseudo) <= 50) {
        // Vérifier si le nouveau pseudo est déjà pris
        $stmt = $bdd->prepare("SELECT COUNT(*) FROM users WHERE pseudo = :new_pseudo");
        $stmt->bindParam(':new_pseudo', $new_pseudo);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            // Mettre à jour le pseudo dans la base de données
            $stmt = $bdd->prepare("UPDATE users SET pseudo = :new_pseudo WHERE pseudo = :username");
            $stmt->bindParam(':new_pseudo', $new_pseudo);
            $stmt->bindParam(':username', $username);

            if ($stmt->execute()) {
                // Mettre à jour le pseudo dans la session
                $_SESSION['username'] = $new_pseudo;
                $username = $new_pseudo;

                // Rediriger pour éviter la resoumission du formulaire
                header("Location: user.php");
                exit();
            } else {
                echo "Erreur lors du changement de pseudo.";
            }
        } else {
            echo "Le pseudo est déjà pris.";
        }
    } else {
        echo "Le nouveau pseudo est invalide.";
    }
}

// Traiter le formulaire de changement d'e-mail
if (isset($_POST['change_email'])) {
    $new_email = trim($_POST['new_email']);

    // Vérifier si le nouvel e-mail est valide
    if (!empty($new_email) && filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        // Vérifier si le nouvel e-mail est déjà pris
        $stmt = $bdd->prepare("SELECT COUNT(*) FROM users WHERE email = :new_email");
        $stmt->bindParam(':new_email', $new_email);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            // Mettre à jour l'e-mail dans la base de données
            $stmt = $bdd->prepare("UPDATE users SET email = :new_email WHERE pseudo = :username");
            $stmt->bindParam(':new_email', $new_email);
            $stmt->bindParam(':username', $username);

            if ($stmt->execute()) {
                echo "Adresse e-mail mise à jour avec succès.";
            } else {
                echo "Erreur lors de la mise à jour de l'adresse e-mail.";
            }
        } else {
            echo "L'adresse e-mail est déjà associée à un compte.";
        }
    } else {
        echo "L'adresse e-mail est invalide.";
    }
}

// Traiter le formulaire de changement de mot de passe
if (isset($_POST['change_password'])) {
    $old_password = trim($_POST['old_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Récupérer l'utilisateur depuis la base de données
    $stmt = $bdd->prepare("SELECT * FROM users WHERE pseudo = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si l'utilisateur existe
    if ($user) {
        // Vérifier l'ancien mot de passe
        if (password_verify($old_password, $user['mot_de_passe'])) { // Remplacer 'mot_de_passe' par le nom réel de la colonne du mot de passe
            // Vérifier que le nouveau mot de passe et la confirmation correspondent
            if ($new_password === $confirm_password) {
                // Vérifier la longueur du nouveau mot de passe
                if (strlen($new_password) >= 6) {
                    // Hacher le nouveau mot de passe
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                    // Mettre à jour le mot de passe dans la base de données
                    $stmt = $bdd->prepare("UPDATE users SET mot_de_passe = :new_password WHERE pseudo = :username"); // Remplacer 'mot_de_passe' par le nom réel de la colonne du mot de passe
                    $stmt->bindParam(':new_password', $hashed_password);
                    $stmt->bindParam(':username', $username);

                    if ($stmt->execute()) {
                        echo "Mot de passe mis à jour avec succès.";
                    } else {
                        echo "Erreur lors de la mise à jour du mot de passe.";
                    }
                } else {
                    echo "Le nouveau mot de passe doit contenir au moins 6 caractères.";
                }
            } else {
                echo "Le nouveau mot de passe et la confirmation ne correspondent pas.";
            }
        } else {
            echo "L'ancien mot de passe est incorrect.";
        }
    } else {
        echo "Utilisateur introuvable.";
    }
}

// Vérifier si le formulaire a été soumis pour ajouter ou mettre à jour la description
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['description'])) {
    // Récupérer la description depuis le formulaire
    $description = $_POST['description'];

    // Mettre à jour la description dans la base de données
    $stmt = $bdd->prepare("UPDATE users SET description = :description WHERE pseudo = :username");
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Rediriger pour éviter la soumission multiple du formulaire
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}



// Récupérer les informations de l'utilisateur depuis la base de données
$stmt = $bdd->prepare("SELECT * FROM users WHERE pseudo = :username");
$stmt->bindParam(':username', $username);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'utilisateur existe dans la base de données
if (!$user) {
    // Rediriger vers une page d'erreur ou afficher un message d'erreur
    echo "Utilisateur introuvable.";
    exit();
}

// Définir les deux dates
$date1 = new DateTime('now');
$date2 = new DateTime($user['date_creation_compte']);

// Calculer la différence entre les deux dates
$interval = $date1->diff($date2);

// Obtenir la différence absolue en années
$diffInYears = abs($interval->y);




// Les informations de l'utilisateur sont maintenant stockées dans $user
?>

<!-------------------------------------------------------------------------------------------------------------------------------------------->

<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>EcoControl - Accueil Utilisateur</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/index.css" type="text/css">
    </head>
    <style>
        .nav-link svg {
            fill: white;
        }
    </style>

    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
        <symbol id="home" viewBox="0 0 16 16">
            <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5z"/>
        </symbol>
        <symbol id="speedometer2" viewBox="0 0 16 16">
            <path d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4zM3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 10a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 10zm9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5zm.754-4.246a.389.389 0 0 0-.527-.02L7.547 9.31a.91.91 0 1 0 1.302 1.258l3.434-4.297a.389.389 0 0 0-.029-.518z"/>
            <path fill-rule="evenodd" d="M0 10a8 8 0 1 1 15.547 2.661c-.442 1.253-1.845 1.602-2.932 1.25C11.309 13.488 9.475 13 8 13c-1.474 0-3.31.488-4.615.911-1.087.352-2.49.003-2.932-1.25A7.988 7.988 0 0 1 0 10zm8-7a7 7 0 0 0-6.603 9.329c.203.575.923.876 1.68.63C4.397 12.533 6.358 12 8 12s3.604.532 4.923.96c.757.245 1.477-.056 1.68-.631A7 7 0 0 0 8 3z"/>
        </symbol>
        <symbol id="table" viewBox="0 0 16 16">
            <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm15 2h-4v3h4V4zm0 4h-4v3h4V8zm0 4h-4v3h3a1 1 0 0 0 1-1v-2zm-5 3v-3H6v3h4zm-5 0v-3H1v2a1 1 0 0 0 1 1h3zm-4-4h4V8H1v3zm0-4h4V4H1v3zm5-3v3h4V4H6zm4 4H6v3h4V8z"/>
        </symbol>
        <symbol id="people-circle" viewBox="0 0 16 16">
            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
        </symbol>
        <symbol id="box-arrow-right2" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
            <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
        </symbol>
    </svg>

    <header>
        <div class="px-3 py-2 text-bg-dark border-bottom">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="../index" class="d-flex align-items-center my-3 my-lg-0 me-lg-auto text-white text-decoration-none">
                <bold><span class="text-primary" style="font-size: 26px;">Eco</span><span class="text-success" style="font-size: 26px;">Control</span></bold>
            </a>

            <ul class="nav col-12 col-lg-auto my-2 justify-content-center my-md-0 text-small">
                <li>
                <a href="user/" class="nav-link text-white">
                    <svg class="bi d-block mx-auto mb-1" width="24" height="24"><use xlink:href="#home"/></svg>
                    Accueil
                </a>
                </li>
                <li>
                <a href="user/dashboard" class="nav-link text-white">
                    <svg class="bi d-block mx-auto mb-1" width="24" height="24"><use xlink:href="#speedometer2"/></svg>
                    Dashboard
                </a>
                </li>
                <li>
                <a href="../user.php" class="nav-link text-white">
                    <svg class="bi d-block mx-auto mb-1" width="24" height="24"><use xlink:href="#people-circle"/></svg>
                    <?php 
                        if (isset($_SESSION['username'])) {
                            $username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
                             echo "{$username}";
                    }
                    ?>
                </a>
                </li>
                <li>
                <a href="?logout=true" class="nav-link text-white">
                    <svg class="bi d-block mx-auto mb-1" width="24" height="24"><use xlink:href="#box-arrow-right2"/></svg>
                    Déconnexion
                </a>
                </li>
            </ul>
            </div>
        </div>
    </div>
</header>

<div class="container py-5 mt-5">
    <header class="mb-4 text-center">
        <h3 class="fidelite">
            <?php 
                if ($diffInYears > 1) {
                    echo "Utilisateur fidèle depuis plus d'un an";
                } else {
                    echo "Utilisateur depuis moins d'un an";
                }
            ?>
        </h3>
        <p>Bienvenue sur la page utilisateur. Vous pouvez modifier vos informations personnelles ici.</p>
    </header>

    <div class="row">
        <div class="col-md-6">
            <h2>Informations personnelles</h2>
            <p><strong>Pseudo :</strong> <?php echo htmlspecialchars($user['pseudo']); ?></p>
            <p><strong>Email :</strong> <?php echo htmlspecialchars($user['email']); ?></p>

            <h2>Changer de pseudo</h2>
            <form method="post" action="">
                <div class="mb-3">
                    <label for="new_pseudo" class="form-label">Nouveau pseudo</label>
                    <input type="text" class="form-control" id="new_pseudo" name="new_pseudo" required>
                </div>
                <button type="submit" class="btn btn-success" name="change_pseudo">Changer de pseudo</button>
            </form>

            <h2>Changer d'email</h2>
            <form method="post" action="">
                <div class="mb-3">
                    <label for="new_email" class="form-label">Nouvel email</label>
                    <input type="email" class="form-control" id="new_email" name="new_email" required>
                </div>
                <button type="submit" class="btn btn-success" name="change_email">Changer d'email</button>
            </form>

            <h2>Changer de mot de passe</h2>
            <form method="post" action="">
                <div class="mb-3">
                    <label for="old_password" class="form-label">Ancien mot de passe</label>
                    <input type="password" class="form-control" id="old_password" name="old_password" required>
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label">Nouveau mot de passe</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-success" name="change_password">Changer de mot de passe</button>
            </form>
        </div>
    </div>
</div>

</article>

<script>
        function toggleForm() {
            var form1 = document.getElementById("changePseudoForm");
            var form2 = document.getElementById("changeEmailForm");
            var form3 = document.getElementById("changePasswordForm");
            var form4 = document.getElementById("changePPForm");
            var form5 = document.getElementById("changeDescriptionForm");
            var button = document.getElementById("optionButton");

            var forms = [form1, form2, form3, form4, form5];
            var isAnyFormVisible = forms.some(form => form.style.display === "block");

            forms.forEach(form => {
                form.style.display = isAnyFormVisible ? "none" : "block";
            });

            button.classList.toggle("active-button", !isAnyFormVisible);
        }

        document.addEventListener("DOMContentLoaded", function() {
            var form1 = document.getElementById("changePseudoForm");
            var form2 = document.getElementById("changeEmailForm");
            var form3 = document.getElementById("changePasswordForm");
            var form4 = document.getElementById("changePPForm");
            var form5 = document.getElementById("changeDescriptionForm");
            
            // Ensure all forms are hidden initially
            form1.style.display = "none";
            form2.style.display = "none";
            form3.style.display = "none";
            form4.style.display = "none";
            form5.style.display = "none";
        });
    </script>

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
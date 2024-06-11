<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>TrackBase - Créer un compte</title>
    <link rel="stylesheet" href=../css/style.css>
</head>
<body>

<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connexion à la base de données
    try {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=hackathonecocontrolweb', 'root', '');
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


<section>
<nav><a href="../index.php" style="text-decoration:none"><img id="imgpetit" src="../image/logopetit.png"></a></nav>
    <nav><a href="main.php" style="text-decoration:none">TrackBase</a></nav>
</section>

<div class="login-container">
<div class="login-box">
    <h2>Créer un compte</h2>
    <?php
    if (isset($error_message)) {
        echo "<p style='color: red;'>$error_message</p>";
    }
    ?>
    <form action="" method="post">
        <input type="text" name="pseudo" placeholder="Pseudo" required>
        <input type="email" name="email" placeholder="Adresse email" required>
        <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
        <input type="date" name="date_naissance" placeholder="Date de naissance" required>
        <input type="submit" value="Créer un compte">
    </form>
    <p>Vous avez déjà un compte ? <a href="login.php">Connectez-vous ici</a>.</p>
</div>
</div>
</body>
</html>
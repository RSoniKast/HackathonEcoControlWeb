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

if (isset($_SESSION['username'])) {
    // Récupérer les informations de l'utilisateur
    $query = $bdd->prepare("SELECT pseudo, photo_profil FROM users WHERE pseudo = ?");
    $query->execute([$_SESSION['username']]);
    $user = $query->fetch(PDO::FETCH_ASSOC);
}

// Afficher le message d'erreur s'il existe
$error_message = "";
if (isset($_SESSION['error'])) {
    $error_message = $_SESSION['error'];
    unset($_SESSION['error']);
}

// Gérer la déconnexion
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit();
}
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>EcoControl - Dashboard</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <style>
        .nav-link svg {
            fill: white;
        }
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100; /* Behind the navbar */
            padding: 48px 0 0; /* Height of navbar */
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
        }
        .main-content {
            margin-left: 15%; /* Same as sidebar width */
        }
        .header {
            height: 56px; /* Height of the header */
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1030; /* Higher than sidebar */
        }
    </style>
    </head>
    <body>
        <header>
            <div class="px-3 py-3 text-bg-dark border-bottom">
                <div class="container">
                    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                        <a href="/" class="d-flex align-items-center my-3 my-lg-0 me-lg-auto text-white text-decoration-none">
                            <bold><span class="text-primary" style="font-size: 26px;">Eco</span><span class="text-success" style="font-size: 26px;">Control</span></bold>
                        </a>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width: 15%;">
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <a href="#" class="nav-link active" aria-current="page">
                            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"/></svg>Accueil
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link text-white">
                            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"/></svg>Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link text-white">
                            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#people-circle"/></svg>Utilisateurs
                        </a>
                    </li>
                </ul>
                <hr>
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php 
                        if (isset($_SESSION['username'])) {
                            $username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
                            // echo "<img src='{$avatar}' alt='Avatar' width='32' height='32' class='rounded-circle me-2'>
                             echo   "<strong>{$username}</strong>";
                        } else {
                            echo "<img src='https://github.com/mdo.png' alt='Default Avatar' width='32' height='32' class='rounded-circle me-2'>
                                  <strong>Guest</strong>";
                        }
                        ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                        <li><a class="dropdown-item" href="#">New project...</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Sign out</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <h2 class="mb-4">Data Grid</h2>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data as $row): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-wh+J1HAshLqqdE4c7csEcGVahmjG5ZG7fb/XQTPgWSKkZjbQvdlQjEl19wIY4G7q" crossorigin="anonymous"></script>
    </body>
</html>

<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>EcoControl - Contact</title>
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
          <li class="nav-item"><a href="#" class="nav-link link-secondary">Contact</a></li>
          <li><a class="btn btn-outline-success me-2" href="user/dashboard" role="button">Connexion</a></li>
          <li><button type="button" class="btn btn-success">S'inscrire</button></li>
        
    </div>
  </nav>

    <div class="px-4 py-5 my-5 mx-5 text-justify">
        <h1 class="display-4 text-center">Contactez-nous</h1>
        <p class="text-center">Vous pouvez nous contacter à partir de l'adresse "contact@ecocontrol.fr", mais vous pouvez aussi renseigner votre
            message dans le champ ci-dessous.</p>
        <form id="contact-form">
            <div class="form-group">
                <label for="Adresse mail">Adresse mail</label>
                <input type="email" class="form-control" id="email-input" aria-describedby="emailHelp" placeholder="Veuillez rentrer votre adresse mail.">
                <small id="emailHelp" class="form-text text-muted">Nous ne partagerons pas votre email avec qui que ce soit.</small>
            </div>
            <div class="form-group">
                <label for="Message">Message</label>
                <textarea class="form-control" id="message-input" rows="3"></textarea>
            </div>
            <br>
            <button type="submit" class="btn btn-success">Envoyer</button>
        </form>
        <div id="confirmation-message" class="alert alert-success mt-3" style="display: none;">
            Votre message a été envoyé.
        </div>
    </div>

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
                <li><a class="link-secondary text-decoration-none" href="login">Connexion</a></li>
                <li><a class="link-secondary text-decoration-none" href="signup">Inscription</a></li>
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

        <script>
            document.getElementById('contact-form').addEventListener('submit', function(event) {
                event.preventDefault();
                // Valider l'email et le message
                var email = document.getElementById('email-input').value;
                var message = document.getElementById('message-input').value;

                if (email && message) {
                    // Afficher la confirmation
                    document.getElementById('confirmation-message').style.display = 'block';
                    // Vider les champs
                    document.getElementById('contact-form').reset();
                } else {
                    alert('Veuillez remplir tous les champs.');
                }
            });
        </script>
    </footer>
</html>
<?php
include "./navbar.php";

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Le Palais des saveurs</title>
    <link rel="stylesheet" href="css/disconnect.css">
    <link rel="stylesheet" href="css/contact.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <main class="page-content">
        <div class="contact-flex">
            <aside class="contact-socials" aria-label="Réseaux sociaux">
                <a href="https://facebook.com/" target="_blank" title="Facebook">
                    <img src="img/feuille-africaine1.png" alt="Facebook" class="social-icon"> Facebook
                </a>
                <a href="https://twitter.com/" target="_blank" title="Twitter">
                    <img src="img/feuille-africaine2.png" alt="Twitter" class="social-icon"> Twitter
                </a>
                <a href="https://instagram.com/" target="_blank" title="Instagram">
                    <img src="img/feuille-africaine3.png" alt="Instagram" class="social-icon"> Instagram
                </a>
            </aside>

            <section class="contact-container" role="region" aria-label="Formulaire de contact">
                <h2>Contactez-nous</h2>
                <form class="contact-form" action="#" method="post">
                    <div class="form-group">
                        <label for="name">Nom</label>
                        <input type="text" id="name" name="name" required placeholder="Votre nom">
                    </div>
                    <div class="form-group">
                        <label for="email">Adresse e-mail</label>
                        <input type="email" id="email" name="email" required placeholder="Votre e-mail">
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="6" required placeholder="Votre message"></textarea>
                    </div>
                    <div class="form-action">
                        <button type="submit">Envoyer</button>
                    </div>
                </form>

                <div class="contact-info">
                    <p>Ou contactez-nous par e-mail : <a href="mailto:contact@appresto.fr">contact@appresto.fr</a></p>
                </div>
            </section>
        </div>
    </main>

<?php
  include "./footer.php";
?>

</body>
</html>

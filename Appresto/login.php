<?php
// Affichage des erreurs pour debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once "functions/db_functions.php";

$reponse = "";

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? null);
    $password = trim($_POST['password'] ?? null);

    if ($email && $password) {
        try {
            $dbh = db_connect();

            $sql = "SELECT * FROM Utilisateur WHERE email = :email";
            $sth = $dbh->prepare($sql);
            $sth->execute([":email" => $email]);
            $user = $sth->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['mot_de_passe'])) {
                $_SESSION['id_utilisateur'] = $user['id_utilisateur'];
                $_SESSION['identifiant'] = $user['identifiant'];
                $_SESSION['email'] = $user['email'];

                // Redirection vers la page principale
                header("Location: index.php");
                exit;
            } else {
                $reponse = "Email ou mot de passe incorrect.";
            }
        } catch (PDOException $e) {
            die("Erreur SQL : " . $e->getMessage());
        }
    } else {
        $reponse = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <title>Connexion - APPRESTO</title>
</head>
<body>

<div class="login-container">
    <h2>Connexion</h2>
    <form class="login-form" action="login.php" method="POST">
        <label for="email">Adresse e-mail</label>
        <input type="email" id="email" name="email" placeholder="Entrez votre email" required>

        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required>

        <button type="submit">Se connecter</button>

        <!-- Affichage des erreurs -->
        <?php if (!empty($reponse)) : ?>
            <div class="error-message"><?= htmlspecialchars($reponse) ?></div>
        <?php endif; ?>

        <div class="register-link">
            <span>Pas encore de compte ? <a href="register.php">Inscrivez-vous</a></span>
        </div>
    </form>
</div>

</body>
</html>

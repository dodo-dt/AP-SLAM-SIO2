<?php
session_start();
include "functions/db_functions.php";

$dbh = db_connect();

$reponse = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        
        $stmt = $dbh->prepare("SELECT * FROM Utilisateur WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            
            $_SESSION['id_utilisateur'] = $user['id_utilisateur'];
            $_SESSION['identifiant']    = $user['identifiant']; 
            $_SESSION['email']          = $user['email'];

            header("Location: index.php");
            exit;
            
        } else {
            $reponse = "Email ou mot de passe incorrect.";
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
    
    <form class="login-form" method="POST">
        <label for="email">Adresse e-mail</label>
        <input type="email" id="email" name="email" placeholder="Entrez votre email" required>

        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required>

        <button type="submit">Se connecter</button>

        <?php if (!empty($reponse)) : ?>
            <div class="error-message" style="color: red; margin-top: 10px;">
                <?= $reponse ?>
            </div>
        <?php endif; ?>

        <div class="register-link">
            <br>
            <span>Pas encore de compte ? <a href="register.php">Inscrivez-vous</a></span>
        </div>
    </form>
</div>

</body>
</html>
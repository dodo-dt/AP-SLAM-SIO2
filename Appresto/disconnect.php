<?php
session_start();

$_SESSION = [];

session_destroy();

header("Location: index.php");
exit;
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Déconnexion - APPRESTO</title>
    <link rel="stylesheet" href="css/disconnect.css">
    <link rel="stylesheet" href="css/login.css"><!-- Pour garder le style harmonisé -->
</head>
<body>
    <div class="login-container">
        <h2>Déconnexion</h2>
        <p style="color:white; text-align:center; margin-bottom:24px;">
            Vous avez bien été déconnecté.
        </p>
        <div class="form-action">
            <a href="login.php">
                <button type="button">Se reconnecter</button>
            </a>
        </div>
    </div>
</body>
</html>

<?php
session_start();
include "functions/db_functions.php";

$reponse = "";

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $identifiant = trim($_POST['identifiant'] ?? null);
    $email = trim($_POST['email'] ?? null);
    $password = trim($_POST['password'] ?? null);
    $confirm_password = trim($_POST['confirm_password'] ?? null);

    if ($identifiant && $email && $password && $confirm_password) {
        if ($password !== $confirm_password) {
            $reponse = "Les mots de passe ne correspondent pas.";
        } else {
            try {
                $dbh = db_connect();

                // Vérifier si identifiant ou email existe déjà
                $sql = "SELECT * FROM Utilisateur WHERE identifiant = :identifiant OR email = :email";
                $sth = $dbh->prepare($sql);
                $sth->execute([
                    ":identifiant" => $identifiant,
                    ":email" => $email
                ]);
                $existing = $sth->fetch(PDO::FETCH_ASSOC);

                if ($existing) {
                    $reponse = "Identifiant ou email déjà utilisé.";
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    $sql = "INSERT INTO Utilisateur (identifiant, email, mot_de_passe) 
                            VALUES (:identifiant, :email, :password)";
                    $sth = $dbh->prepare($sql);
                    $sth->execute([
                        ":identifiant" => $identifiant,
                        ":email" => $email,
                        ":password" => $hashed_password
                    ]);

                    header("Location: login.php?success=1");
                    exit;
                }
            } catch (PDOException $e) {
                die("Erreur SQL : " . $e->getMessage());
            }
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
    <title>Inscription - APPRESTO</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/register.css">
</head>
<body>

<div class="register-container">
    <h2>Créer un compte</h2>
    <form action="register.php" method="POST">
        <div class="form-group">
            <label for="identifiant">Nom d'utilisateur</label>
            <input type="text" id="identifiant" name="identifiant" required>
        </div>
        <div class="form-group">
            <label for="email">Adresse e-mail</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirmer le mot de passe</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn">S'inscrire</button>
        </div>

        <!-- Affichage des erreurs -->
        <?php if (!empty($reponse)) : ?>
            <div class="error-message"><?= $reponse ?></div>
        <?php endif; ?>

        <div class="login-link">
            <span>Déjà un compte ? <a href="login.php">Se connecter</a></span>
        </div>
    </form>
</div>

</body>
</html>

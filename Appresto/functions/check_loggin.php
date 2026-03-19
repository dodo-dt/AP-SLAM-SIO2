<?php
// Dans check_loggin.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: login.php");
    exit;
}

$identifiant = $_SESSION['identifiant'];
$id_utilisateur = $_SESSION['id_utilisateur'];

?>
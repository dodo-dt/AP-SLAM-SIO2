<?php

session_start();
include "functions/db_functions.php";

if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: login.php");
    exit;
}

$identifiant = $_SESSION['identifiant'];

?>
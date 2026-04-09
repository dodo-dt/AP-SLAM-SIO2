<?php

// Connexion à la BD
function db_connect() {
  $dsn = 'mysql:host=localhost;dbname=APPRESTO;charset=utf8';
  try {
    $dbh = new PDO($dsn, 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
  } catch (PDOException $ex) {
    die("Erreur lors de la connexion SQL : " . $ex->getMessage());
  }
}

function getPDO() {
  return db_connect();
}



?>
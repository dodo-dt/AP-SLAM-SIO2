<?php
// api/commande_terminer.php
require_once __DIR__ . '/../functions/db_functions.php';

header('Content-Type: application/json');

try {
    if (!isset($_GET['id_commande']) || !is_numeric($_GET['id_commande'])) {
        echo json_encode([]);
        exit;
    }

    $id_commande = $_GET['id_commande'];

    $pdo = db_connect();

    $sql = "UPDATE 
                Commande
            SET 
                id_etat = 5
            WHERE 
                id_commande = :id_commande
            AND 
                id_etat = 4"; // 4 = en préparation

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_commande' => $id_commande]);

    if ($stmt->rowCount() === 0) {
        echo json_encode([]);
        exit;
    }

    echo json_encode([]);

} catch (Exception $e) {
    echo json_encode([]);
}

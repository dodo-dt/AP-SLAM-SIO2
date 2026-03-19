<?php
// api/commandes_en_attente.php
require_once __DIR__ . '/../functions/db_functions.php';

try {
    $pdo = db_connect();

    $sql = "SELECT 
                *
            FROM 
                Commande
            WHERE 
                id_etat = 4"; // 4 = en cours de validation

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
    header('Content-Type: application/json');
    echo json_encode($commandes);

} catch (Exception $e) {

    header('Content-Type: application/json');
    echo json_encode([]);

}

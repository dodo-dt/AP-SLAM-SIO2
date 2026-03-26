<?php
ob_start();
header('Content-Type: application/json; charset=UTF-8');
ini_set('display_errors', 0);
error_reporting(0);

require_once __DIR__ . '/../functions/db_functions.php';

try {
    $rawInput = file_get_contents('php://input');
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    $isJsonRequest = stripos($contentType, 'application/json') !== false;
    $payload = [];

    if ($isJsonRequest) {
        $payload = json_decode($rawInput, true);

        if (trim($rawInput) !== '' && !is_array($payload)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'JSON invalide',
                'count' => 0,
                'data' => []
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    $id_commande = $_GET['id_commande'] ?? $_POST['id_commande'] ?? ($payload['id_commande'] ?? null);

    if (!is_numeric($id_commande)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'id_commande invalide',
            'count' => 0,
            'data' => []
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $pdo = getPDO();

    $sql = "SELECT
                lc.id_commande,
                lc.id_produit,
                p.lib_produit,
                lc.quantite,
                lc.montant_unitaire_HT
            FROM LigneCommande lc
            INNER JOIN Produit p ON p.id_produit = lc.id_produit
            WHERE lc.id_commande = :id_commande
            ORDER BY p.lib_produit ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_commande' => (int)$id_commande]);

    $lignes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'id_commande' => (int)$id_commande,
        'count' => count($lignes),
        'data' => $lignes
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur serveur',
        'count' => 0,
        'data' => []
    ], JSON_UNESCAPED_UNICODE);
}

ob_end_flush();
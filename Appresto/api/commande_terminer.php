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
                'message' => 'JSON invalide'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    $id_commande = $_GET['id_commande'] ?? $_POST['id_commande'] ?? ($payload['id_commande'] ?? null);

    if (!is_numeric($id_commande)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'id_commande invalide'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $pdo = getPDO();

    $stmtEtat = $pdo->prepare('SELECT id_etat FROM Commande WHERE id_commande = :id_commande');
    $stmtEtat->execute(['id_commande' => (int)$id_commande]);
    $etatActuel = $stmtEtat->fetchColumn();

    if ($etatActuel === false) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Commande introuvable'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ((int)$etatActuel === 7) {
        echo json_encode([
            'success' => true,
            'message' => 'Commande deja terminee'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE Commande SET id_etat = 7 WHERE id_commande = :id_commande");
    $stmt->execute(['id_commande' => (int)$id_commande]);

    echo json_encode([
        'success' => true,
        'message' => 'Commande terminee'
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur serveur'
    ], JSON_UNESCAPED_UNICODE);
}

ob_end_flush();
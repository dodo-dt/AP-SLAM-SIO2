<?php
// api/commandes_en_attente.php
require_once __DIR__ . '/../functions/db_functions.php';

header('Content-Type: application/json; charset=UTF-8');

try {
    // Compatible client Java: GET, POST x-www-form-urlencoded et POST JSON
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

    $id_etat_input = $_GET['id_etat'] ?? $_POST['id_etat'] ?? ($payload['id_etat'] ?? null);
    $hasEtatFilter = $id_etat_input !== null && $id_etat_input !== '';

    if ($hasEtatFilter && !is_numeric($id_etat_input)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'id_etat invalide',
            'count' => 0,
            'data' => []
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $id_etat = $hasEtatFilter ? (int) $id_etat_input : null;

    $pdo = getPDO();

    // ✅ Sous-requête pour agréger quantite_totale depuis LigneCommande
    $subQuery = "(
        SELECT SUM(lc.quantite)
        FROM LigneCommande lc
        WHERE lc.id_commande = c.id_commande
    ) AS quantite_totale";

    $baseSelect = "SELECT 
                c.id_commande,
                c.lib_commande,
                c.type_commande,
                c.total_TTC,
                c.date_commande,
                c.id_utilisateur,
                c.id_etat,
                e.lib_etat,
                $subQuery
            FROM 
                Commande c
            INNER JOIN Etat e ON e.id_etat = c.id_etat";

    if ($hasEtatFilter) {
        $sql = "$baseSelect
            WHERE c.id_etat = :id_etat
            ORDER BY c.date_commande DESC";
    } else {
        $sql = "$baseSelect
            ORDER BY c.date_commande DESC";
    }

    $stmt = $pdo->prepare($sql);
    if ($hasEtatFilter) {
        $stmt->execute(['id_etat' => $id_etat]);
    } else {
        $stmt->execute();
    }

    $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($commandes as &$commande) {
        $commande['quantite_totale'] = isset($commande['quantite_totale'])
            ? (int) $commande['quantite_totale']
            : 0;
    }
    unset($commande);

    echo json_encode([
        'success' => true,
        'id_etat' => $id_etat,
        'count' => count($commandes),
        'data' => $commandes
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur serveur',
        'error' => (isset($_GET['debug']) && $_GET['debug'] == '1') ? $e->getMessage() : null,
        'count' => 0,
        'data' => []
    ], JSON_UNESCAPED_UNICODE);
}
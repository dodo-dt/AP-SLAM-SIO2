<?php
// Traitement minimal sécurisé du paiement (stub). Valide montant TTC serveur-side,
// met à jour l'état de la commande et affiche confirmation / erreur.
// Nécessite functions/check_loggin.php (démarre la session) et functions/db_functions.php

if (session_status() === PHP_SESSION_NONE) session_start();
include 'functions/check_loggin.php';
include 'functions/db_functions.php';

$dbh = db_connect();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: payment.php');
    exit;
}

function payment_error_redirect(int $id_commande, array $errors, array $old): void
{
    $_SESSION['payment_errors'] = $errors;
    $_SESSION['payment_old'] = $old;
    $target = 'payment.php';
    if ($id_commande > 0) {
        $target .= '?id_commande=' . $id_commande;
    }
    header('Location: ' . $target);
    exit;
}

function validate_expiry(string $expiry): bool
{
    if (!preg_match('/^(0[1-9]|1[0-2])\/(\d{2})$/', $expiry, $m)) {
        return false;
    }

    $month = (int) substr($expiry, 0, 2);
    $year = 2000 + (int) $m[2];
    $now = new DateTimeImmutable('now');
    $expiryDate = DateTimeImmutable::createFromFormat('Y-n-j H:i:s', $year . '-' . $month . '-1 23:59:59');
    if (!$expiryDate) {
        return false;
    }
    $expiryEndOfMonth = $expiryDate->modify('last day of this month');

    return $expiryEndOfMonth >= $now;
}

// Récupération et validation des entrées
$id_commande = isset($_POST['id_commande']) ? (int) $_POST['id_commande'] : 0;
$posted_amount = isset($_POST['amount_ttc']) ? (float) str_replace(',', '.', $_POST['amount_ttc']) : 0.0;

$cardName = trim((string) ($_POST['cardName'] ?? ''));
$cardNumberRaw = (string) ($_POST['cardNumber'] ?? '');
$cardNumber = preg_replace('/\D+/', '', $cardNumberRaw);
$expiry = trim((string) ($_POST['expiry'] ?? ''));
$cvcRaw = (string) ($_POST['cvc'] ?? '');
$cvc = preg_replace('/\D+/', '', $cvcRaw);

$paymentErrors = [];
if ($cardName === '') {
    $paymentErrors[] = 'Le nom du titulaire est obligatoire.';
}
if ($cardNumber === '' || strlen($cardNumber) < 13 || strlen($cardNumber) > 19) {
    $paymentErrors[] = 'Le numero de carte est invalide.';
}
if (!validate_expiry($expiry)) {
    $paymentErrors[] = "La date d'expiration est invalide ou la carte est expiree.";
}
if ($cvc === '' || !preg_match('/^\d{3,4}$/', $cvc)) {
    $paymentErrors[] = 'Le code CVC est invalide.';
}

if (!empty($paymentErrors)) {
    payment_error_redirect(
        $id_commande,
        $paymentErrors,
        [
            'cardName' => $cardName,
            'cardNumber' => $cardNumberRaw,
            'expiry' => $expiry,
            'cvc' => $cvcRaw,
        ]
    );
}

if ($id_commande <= 0 || $posted_amount <= 0) {
    http_response_code(400);
    echo "Données de paiement invalides.";
    exit;
}

// Récupérer montant TTC stocké en base si présent
$expected_ttc = 0.0;
try {
    $stmt = $dbh->prepare("SELECT total_TTC, type_commande FROM Commande WHERE id_commande = :id LIMIT 1");
    $stmt->execute([':id' => $id_commande]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $expected_ttc = isset($row['total_TTC']) && $row['total_TTC'] !== null ? (float)$row['total_TTC'] : 0.0;
        $type_commande_db = $row['type_commande'] ?? '';
    } else {
        echo "Commande introuvable.";
        exit;
    }
} catch (PDOException $e) {
    error_log("process_payment.php DB error(select Commande): " . $e->getMessage());
    echo "Erreur serveur.";
    exit;
}

// Si pas de total_TTC en base, recompute à partir des lignes + taux session/default
if ($expected_ttc <= 0) {
    try {
        $stmt = $dbh->prepare("SELECT COALESCE(SUM(quantite * montant_unitaire_HT),0) AS total_ht
                               FROM LigneCommande WHERE id_commande = :id");
        $stmt->execute([':id' => $id_commande]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        $total_ht = isset($r['total_ht']) ? (float)$r['total_ht'] : 0.0;
        $total_ht = round($total_ht, 2);
    } catch (PDOException $e) {
        error_log("process_payment.php DB error(calc HT): " . $e->getMessage());
        echo "Erreur serveur.";
        exit;
    }

    // récupérer taux en session si disponible
    $taxRate = 10.0;
    if (!empty($_SESSION['last_tax_rate'])) {
        $taxRate = (float) $_SESSION['last_tax_rate'];
    } else {
        // deviner taux par type_commande (si présent)
        $type_norm = mb_strtolower($type_commande_db ?? '', 'UTF-8');
        if (strpos($type_norm, 'emporter') !== false) $taxRate = 5.5;
        else $taxRate = 10.0;
    }

    $expected_ttc = round($total_ht * (1 + $taxRate / 100), 2);
}

// Comparaison sécurisée (tolérance centimes)
if (abs($expected_ttc - $posted_amount) > 0.02) {
    error_log("process_payment.php: Montant envoyé ({$posted_amount}) différent du montant attendu ({$expected_ttc}) pour commande {$id_commande}");
    ?>
    <!doctype html>
    <html lang="fr">
    <head><meta charset="utf-8"><title>Paiement - Erreur</title></head>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/process_payment.css">
    <body>
      <h1>Erreur de paiement</h1>
      <p>Le montant transmis ne correspond pas au montant attendu pour la commande.</p>
      <p>Montant attendu : <?php echo number_format($expected_ttc,2,'.',''); ?>€</p>
      <p>Montant transmis : <?php echo number_format($posted_amount,2,'.',''); ?>€</p>
      <p><a href="payment.php?id_commande=<?php echo (int)$id_commande; ?>">Retour au paiement</a></p>
    </body>
    </html>
    <?php
    exit;
}

// Simuler traitement de la carte (ici on n'intègre pas de gateway). On considère paiement OK.
$payment_ok = true;

// Mise à jour de la commande : total_TTC si absent, et état (id_etat = 4 => en attente de préparation)
try {
    if ($expected_ttc > 0) {
        // Mettre à jour total_TTC (au cas où il n'était pas enregistré) et id_etat
        $stmt = $dbh->prepare("UPDATE Commande SET total_TTC = :ttc, id_etat = 4 WHERE id_commande = :id");
        $stmt->execute([':ttc' => $expected_ttc, ':id' => $id_commande]);
    } else {
        // fallback simple
        $stmt = $dbh->prepare("UPDATE Commande SET id_etat = 4 WHERE id_commande = :id");
        $stmt->execute([':id' => $id_commande]);
    }
} catch (PDOException $e) {
    error_log("process_payment.php DB error(update Commande): " . $e->getMessage());
}

// Vider sessions temporaires liées au paiement
unset($_SESSION['last_ttc_amount'], $_SESSION['last_tax_rate'], $_SESSION['final_payment_amount']);

// Afficher confirmation propre
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Paiement réussi</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/process_payment.css">
</head>
<body>
<?php include "navbar.php"; ?>
<main class="container" style="padding-top:28px;">
  <div class="confirmation-box">
    <h2>Paiement accepté!</h2>
    <p>Merci! Votre paiement de <strong><span style="color:#ff6b35;"><?php echo number_format($expected_ttc,2,'.',''); ?>€</span></strong> 
    pour la commande<span style="color:#ff6b35;"> <strong>#<?php echo (int)$id_commande; ?></span></strong> a bien été enregistré.</p></p>
    <p>Vous pouvez revenir à l'accueil.</p>
    <a class="btn" href="index.php">Retour à l'accueil</a>
  </div>
</main>
<?php include "footer.php"; ?>
</body>
</html>
<?php
?>
</body>
</html>
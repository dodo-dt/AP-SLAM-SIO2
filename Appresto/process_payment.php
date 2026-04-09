<?php
include 'functions/check_loggin.php';
include 'functions/db_functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: commande.php');
    exit;
}

$dbh = db_connect();

$id_commande = (int)($_POST['id_commande'] ?? 0);
if ($id_commande <= 0) {
    header('Location: commande.php');
    exit;
}

// Validation des champs carte
$cardName   = trim($_POST['cardName'] ?? '');
$cardNumber = preg_replace('/\D+/', '', $_POST['cardNumber'] ?? '');
$expiry     = trim($_POST['expiry'] ?? '');
$cvc        = preg_replace('/\D+/', '', $_POST['cvc'] ?? '');

$errors = [];
if ($cardName === '') {
    $errors[] = 'Le nom du titulaire est obligatoire.';
}
if (strlen($cardNumber) < 13 || strlen($cardNumber) > 19) {
    $errors[] = 'Le numéro de carte est invalide.';
}
if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $expiry)) {
    $errors[] = "La date d'expiration est invalide.";
}else {
    [$mois, $annee] = explode('/', $expiry);
    if (mktime(0, 0, 0, (int)$mois + 1, 1, 2000 + (int)$annee) < time()) {
        $errors[] = "La carte est expirée.";
    }
}
if (!preg_match('/^\d{3,4}$/', $cvc)) {
    $errors[] = 'Le code CVC est invalide.';
}

if (!empty($errors)) {
    $_SESSION['payment_errors'] = $errors;
    header('Location: payment.php?id_commande=' . $id_commande);
    exit;
}

// Récupération du montant depuis la BDD (source de vérité)
$stmt = $dbh->prepare("SELECT total_TTC FROM Commande WHERE id_commande = :id LIMIT 1");
$stmt->execute([':id' => $id_commande]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) {
    header('Location: commande.php');
    exit;
}
$montantTTC = (float)$row['total_TTC'];

// Mise à jour du statut de la commande
$stmt = $dbh->prepare("UPDATE Commande SET id_etat = 4 WHERE id_commande = :id");
$stmt->execute([':id' => $id_commande]);
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
      <h2>Paiement accepté !</h2>
      <p>Merci ! Votre paiement de <strong><span style="color:#ff6b35;"><?php echo number_format($montantTTC, 2, '.', ''); ?>€</span></strong>
         pour la commande <strong><span style="color:#ff6b35;">#<?php echo $id_commande; ?></span></strong> a bien été enregistré.</p>
      <a class="btn" href="index.php">Retour à l'accueil</a>
    </div>
  </main>
  <?php include "footer.php"; ?>
</body>
</html>
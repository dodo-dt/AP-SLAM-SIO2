<?php
include "functions/check_loggin.php";
include "functions/db_functions.php";

$dbh = db_connect();

$id_commande = (int)($_GET['id_commande'] ?? 0);
if ($id_commande <= 0) {
    header("Location: commande.php");
    exit;
}

$stmt = $dbh->prepare("SELECT type_commande, total_TTC FROM Commande WHERE id_commande = :id LIMIT 1");
$stmt->execute([':id' => $id_commande]);
$commande = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$commande) {
    header("Location: commande.php");
    exit;
}

$type_commande = $commande['type_commande'];
$total_ttc     = (float)$commande['total_TTC'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Location: payment.php?id_commande=' . $id_commande);
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/TTC.css" />
  <title>Montant TTC</title>
</head>
<body>
<?php include "./navbar.php"; ?>

<main class="container" style="padding-top:28px;">
  <section id="ttc">
    <div class="ttc-container">
      <h2>Montant TTC</h2>

      <?php if ($total_ttc <= 0): ?>
        <div class="notice">Montant TTC nul pour la commande #<?php echo $id_commande; ?>.</div>
      <?php else: ?>
        <p>Commande #<?php echo $id_commande; ?> — Mode : <?php echo $type_commande; ?></p>

        <form method="post" autocomplete="off">
          <div class="form-group">
            <label for="ttc-amount">Montant TTC (€) :</label>
            <input id="ttc-amount" type="text" value="<?php echo $total_ttc; ?>" readonly>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn">Continuer vers le paiement</button>
          </div>
        </form>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php include "./footer.php"; ?>
</body>
</html>
<?php
include "functions/check_loggin.php";
include "functions/db_functions.php";

$dbh = db_connect();

$id_commande = isset($_GET['id_commande']) ? $_GET['id_commande'] : 0;

if ($id_commande <= 0) {
    header("Location: commande.php");
    exit;
}

$_SESSION['id_commande'] = $id_commande;

/* --- Récupérer le type de commande et le total TTC depuis la table Commande --- */
try {
    $stmt = $dbh->prepare("SELECT type_commande, total_TTC FROM Commande WHERE id_commande = :id_commande LIMIT 1");
    $stmt->execute([':id_commande' => $id_commande]);
    $commande = $stmt->fetch(PDO::FETCH_ASSOC);

    $type_commande_db = $commande['type_commande'] ?? '';
    $total_ttc = $commande['total_TTC'] ?? 0;

} catch (PDOException $e) {
    error_log("DB error TTC.php: " . $e->getMessage());
    header("Location: commande.php");
    exit;
}

/* --- Traitement POST : redirection vers payment.php --- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Location: payment.php?id_commande=' . $id_commande . '&amount=' . $total_ttc);
    exit;
}

/* --- Préparer valeur pour affichage --- */
$display_ttc = $total_ttc;?>

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
        <div class="notice">Montant TTC nul pour la commande #<?php echo htmlspecialchars($id_commande, ENT_QUOTES); ?>.</div>
      <?php else: ?>
        <p>Commande #<?php echo htmlspecialchars($id_commande, ENT_QUOTES); ?> — Mode : <?php echo htmlspecialchars($type_commande_db, ENT_QUOTES); ?></p>

        <form action="" method="post" autocomplete="off">
          <input type="hidden" name="id_commande" value="<?php echo htmlspecialchars($id_commande, ENT_QUOTES); ?>">

          <div class="form-group">
            <label for="ttc-amount">Montant TTC (€):</label>
            <input id="ttc-amount" type="text" value="<?php echo $display_ttc; ?>" readonly aria-readonly="true">
          </div>

          <br>

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

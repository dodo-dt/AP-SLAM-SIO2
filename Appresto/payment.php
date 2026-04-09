<?php
include "functions/db_functions.php";
include "functions/check_loggin.php"; 

$dbh = db_connect();

$id_commande = $_GET['id_commande'] ?? 0;
if (empty($id_commande)) {
  header("Location: commande.php");
  exit;
}
$_SESSION['id_commande'] = $id_commande;

$montantTTC = 0.0;
try {
  $stmt = $dbh->prepare("SELECT total_TTC FROM Commande WHERE id_commande = :id_commande LIMIT 1");
  $stmt->execute([':id_commande' => $id_commande]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($row && $row['total_TTC'] !== null) {
    $montantTTC = $row['total_TTC'];
  }
} catch (PDOException $e) {
  error_log("DB error in payment.php (select total_TTC): " . $e->getMessage());
}

$paymentErrors = $_SESSION['payment_errors'] ?? [];
$paymentOld = $_SESSION['payment_old'] ?? [];
unset($_SESSION['payment_errors'], $_SESSION['payment_old']);
?>
<!doctype html>
<html lang="fr">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Paiement - Le Palais des Saveurs</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/payment.css">
</head>

<body>
  <?php include "./navbar.php"; ?>

  <main class="container" style="padding-top:28px;">
    <header class="menu-header">
      <h1>Paiement sécurisé</h1>
      <p>Interface de paiement d'exemple — n'envoyez pas de données réelles ici en production.</p>
    </header>

    <div class="payment-grid" role="main">
      <section class="preview" aria-hidden="false">
        <div class="card-visual" id="cardVisual" aria-hidden="true">
          <div class="card-top">
            <div class="chip" aria-hidden="true"></div>
            <div id="brandTag" class="card-type">VISA</div>
          </div>

          <div class="card-center">
            <div class="card-number" id="visualNumber">•••• •••• •••• ••••</div>
            <div class="card-meta">
              <div>
                <div class="card-label">Titulaire</div>
                <div id="visualName">NOM PRÉNOM</div>
              </div>
              <div style="text-align:right">
                <div class="card-label">Valable</div>
                <div id="visualExpiry">MM/AA</div>
              </div>
            </div>
          </div>
        </div>

        <div class="summary">
          <h3>Récapitulatif</h3>
          <p>Commande #<?php echo htmlspecialchars($id_commande, ENT_QUOTES); ?></p>
          <p>Montant : <strong id="summaryAmount"><?php echo number_format($montantTTC, 2, '.', ''); ?>€</strong></p>
        </div>
      </section>

      <section class="form">
        <h2>Informations de la carte</h2>
        <p class="small">Exemple d'interface. Pour la production utilisez un prestataire PCI (Stripe, etc.).</p>

        <form id="paymentForm" novalidate method="post" action="process_payment.php">
          <input type="hidden" name="id_commande" value="<?php echo htmlspecialchars($id_commande, ENT_QUOTES); ?>">
          <input type="hidden" name="amount_ttc" value="<?php echo number_format($montantTTC, 2, '.', ''); ?>">

          <div class="form-group">
            <label for="cardName">Titulaire (comme sur la carte)</label>
            <input id="cardName" name="cardName" type="text" autocomplete="cc-name" placeholder="NOM PRÉNOM" required value="<?php echo htmlspecialchars((string)($paymentOld['cardName'] ?? ''), ENT_QUOTES); ?>">
          </div>

          <div class="form-group">
            <label for="cardNumber">Numéro de carte</label>
            <input id="cardNumber" name="cardNumber" inputmode="numeric" type="tel" maxlength="23" autocomplete="cc-number" placeholder="•••• •••• •••• ••••" required value="<?php echo htmlspecialchars((string)($paymentOld['cardNumber'] ?? ''), ENT_QUOTES); ?>">
            <div class="helper">N'entrez pas d'informations réelles si vous testez en local.</div>
          </div>

          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="expiry">Date d'expiration (MM/AA)</label>
                <input id="expiry" name="expiry" type="text" inputmode="numeric" placeholder="MM/AA" maxlength="5" autocomplete="cc-exp" required pattern="^(0[1-9]|1[0-2])\/\d{2}$" value="<?php echo htmlspecialchars((string)($paymentOld['expiry'] ?? ''), ENT_QUOTES); ?>">
                <div class="helper">Format : MM/AA</div>
              </div>
            </div>
            <div style="width:140px">
              <div class="form-group">
                <label for="cvc">CVC</label>
                <input id="cvc" name="cvc" type="tel" inputmode="numeric" maxlength="4" placeholder="123" autocomplete="cc-csc" required value="<?php echo htmlspecialchars((string)($paymentOld['cvc'] ?? ''), ENT_QUOTES); ?>">
              </div>
            </div>
          </div>

          <div id="errorArea" class="errors" role="alert" aria-live="assertive">
            <?php if (!empty($paymentErrors)): ?>
              <?php foreach ($paymentErrors as $err): ?>
                <div><?php echo htmlspecialchars((string)$err, ENT_QUOTES); ?></div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>

          <button class="btn" id="submitBtn" type="submit"><?php echo "Payer " . number_format($montantTTC, 2, '.', '') . "€"; ?></button>

          <p class="notice">Intégration recommandée : Stripe Elements ou SDK conforme PCI.</p>
        </form>
      </section>
    </div>
  </main>

  <?php include "./footer.php"; ?>

  <div class="bubbles">
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
  </div>

  <script src="js/payment.js"></script>
</body>

</html>
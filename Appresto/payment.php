<?php
include "functions/db_functions.php";
include "functions/check_loggin.php";

$dbh = db_connect();

$id_commande = (int)($_GET['id_commande'] ?? 0);
if ($id_commande <= 0) {
    header("Location: commande.php");
    exit;
}

$stmt = $dbh->prepare("SELECT total_TTC FROM Commande WHERE id_commande = :id LIMIT 1");
$stmt->execute([':id' => $id_commande]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) {
    header("Location: commande.php");
    exit;
}
$montantTTC = number_format((float)$row['total_TTC'], 2, '.', '');

$paymentErrors = $_SESSION['payment_errors'] ?? [];
unset($_SESSION['payment_errors']);
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

    <div class="payment-grid">
      <section class="preview">
        <div class="card-visual" aria-hidden="true">
          <div class="card-top">
            <div class="chip"></div>
            <div class="card-type">VISA</div>
          </div>
          <div class="card-center">
            <div class="card-number">•••• •••• •••• ••••</div>
            <div class="card-meta">
              <div>
                <div class="card-label">Titulaire</div>
                <div>NOM PRÉNOM</div>
              </div>
              <div style="text-align:right">
                <div class="card-label">Valable</div>
                <div>MM/AA</div>
              </div>
            </div>
          </div>
        </div>

        <div class="summary">
          <h3>Récapitulatif</h3>
          <p>Commande #<?php echo $id_commande; ?></p>
          <p>Montant : <strong><?php echo $montantTTC; ?>€</strong></p>
        </div>
      </section>

      <section class="form">
        <h2>Informations de la carte</h2>
        <p class="small">Exemple d'interface. Pour la production utilisez un prestataire PCI (Stripe, etc.).</p>

        <form method="post" action="process_payment.php" novalidate>
          <input type="hidden" name="id_commande" value="<?php echo $id_commande; ?>">

          <div class="form-group">
            <label for="cardName">Titulaire (comme sur la carte)</label>
            <input id="cardName" name="cardName" type="text" autocomplete="cc-name" placeholder="NOM PRÉNOM" required>
          </div>

          <div class="form-group">
            <label for="cardNumber">Numéro de carte</label>
            <input id="cardNumber" name="cardNumber" type="tel" inputmode="numeric"
                   autocomplete="cc-number" placeholder="•••• •••• •••• ••••" required>
            <div class="helper">N'entrez pas d'informations réelles si vous testez en local.</div>
          </div>

          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="expiry">Date d'expiration (MM/AA)</label>
                <input id="expiry" name="expiry" type="text" inputmode="numeric"
                       placeholder="MM/AA" maxlength="5" autocomplete="cc-exp" required>
              </div>
            </div>
            <div style="width:140px">
              <div class="form-group">
                <label for="cvc">CVC</label>
                <input id="cvc" name="cvc" type="tel" inputmode="numeric"
                       maxlength="3" placeholder="123" autocomplete="cc-csc" required>
              </div>
            </div>
          </div>

          <button class="btn" type="submit">Payer <?php echo $montantTTC; ?>€</button>
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

  <script>
  document.getElementById('cardNumber').addEventListener('input', function () {
    let v = this.value.replace(/\D/g, '').substring(0, 16);
    this.value = v.match(/.{1,4}/g)?.join(' ') ?? v;
  });

  document.getElementById('expiry').addEventListener('input', function () {
    let v = this.value.replace(/\D/g, '').substring(0, 4);
    if (v.length >= 3) v = v.substring(0, 2) + '/' + v.substring(2);
    this.value = v;
  });
</script>
</body>
</html>
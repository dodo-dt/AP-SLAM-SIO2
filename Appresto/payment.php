<?php
include "functions/db_functions.php";


$sql = "SELECT SUM(prix_unitaire_HT) AS montant_total FROM Produit WHERE prix_unitaire_HT = :prix_unitaire_HT";
$dbh = db_connect();
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':prix_unitaire_HT', $prix_unitaire_HT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$montantTotal = $result['montant_total'] ?? 0;

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
<?php
include "./navbar.php";

?>

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
          <p>Article : Commande en ligne</p>
          <p>Montant : <strong id="summaryAmount"><?php echo number_format($montantTotal, 2); ?>€</strong></p>
        </div>
      </section>

      <section class="form">
        <h2>Informations de la carte</h2>
        <p class="small">Exemple d'interface. Pour la production utilisez un prestataire PCI (Stripe, etc.).</p>

        <form id="paymentForm" novalidate method="post" action="TTC.php">
          <div class="form-group">
            <label for="cardName">Titulaire (comme sur la carte)</label>
            <input id="cardName" name="cardName" type="text" autocomplete="cc-name" placeholder="NOM PRÉNOM" required>
          </div>

          <div class="form-group">
            <label for="cardNumber">Numéro de carte</label>
            <input id="cardNumber" name="cardNumber" inputmode="numeric" type="tel" maxlength="23" autocomplete="cc-number" placeholder="•••• •••• •••• ••••" required>
            <div class="helper">N'entrez pas d'informations réelles si vous testez en local.</div>
          </div>

          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="expiry">Date d'expiration (MM/AA)</label>
                <input id="expiry" name="expiry" type="text" inputmode="numeric" placeholder="MM/AA" maxlength="5" autocomplete="cc-exp" required>
              </div>
            </div>
            <div style="width:140px">
              <div class="form-group">
                <label for="cvc">CVC</label>
                <input id="cvc" name="cvc" type="tel" inputmode="numeric" maxlength="4" placeholder="123" autocomplete="cc-csc" required>
              </div>
            </div>
          </div>

          <div id="errorArea" class="errors" role="alert" aria-live="assertive"></div>

      <button class="btn" id="submitBtn" type="submit"><?php echo "Payer " . number_format($montantTotal, 2) . "€"; ?></button>


          <p class="notice">Intégration recommandée : Stripe Elements ou SDK conforme PCI.</p>
        </form>
      </section>
    </div>
  </main>

<?php
  include "./footer.php";
?>

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

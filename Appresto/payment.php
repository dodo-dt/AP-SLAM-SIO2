<?php
// Récupère id_commande (GET > POST > session), récupère ou calcule le montant TTC et affiche la page de paiement
require_once "functions/db_functions.php";
require_once "functions/check_loggin.php"; // doit démarrer la session

$dbh = db_connect();

// Résolution id_commande
$id_commande = 0;
if (!empty($_GET['id_commande'])) {
  $id_commande = (int) $_GET['id_commande'];
} elseif (!empty($_POST['id_commande'])) {
  $id_commande = (int) $_POST['id_commande'];
} elseif (!empty($_SESSION['id_commande'])) {
  $id_commande = (int) $_SESSION['id_commande'];
}

if ($id_commande <= 0) {
  header("Location: commande.php");
  exit;
}
$_SESSION['id_commande'] = $id_commande;

// 1) Essayer de récupérer total_TTC depuis la table Commande
$montantTTC = 0.0;
try {
  $stmt = $dbh->prepare("SELECT total_TTC FROM Commande WHERE id_commande = :id_commande LIMIT 1");
  $stmt->execute([':id_commande' => $id_commande]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($row && $row['total_TTC'] !== null) {
    $montantTTC = (float) $row['total_TTC'];
  }
} catch (PDOException $e) {
  error_log("DB error in payment.php (select total_TTC): " . $e->getMessage());
  // continue avec autres sources de vérité
}

// 2) Si pas de total_TTC en base, vérifier param amount (GET) puis session (PRG)
if ($montantTTC <= 0) {
  if (!empty($_GET['amount'])) {
    $montantTTC = (float) str_replace(',', '.', $_GET['amount']);
  } elseif (!empty($_SESSION['last_ttc_amount'])) {
    $montantTTC = (float) $_SESSION['last_ttc_amount'];
  }
}

// 3) Si toujours pas de TTC, calculer à partir des lignes (HT) + tax rate connu/session/default
if ($montantTTC <= 0) {
  try {
    // calcul HT total
    $sql = "SELECT COALESCE(SUM(quantite * montant_unitaire_HT), 0) AS montant_ht
                FROM LigneCommande
                WHERE id_commande = :id_commande";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([':id_commande' => $id_commande]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $montantHT = (float) ($row['montant_ht'] ?? 0.0);
    $montantHT = round($montantHT, 2);
  } catch (PDOException $e) {
    error_log("DB error in payment.php (calc HT): " . $e->getMessage());
    header("Location: commande.php");
    exit;
  }

  // tenter de récupérer le dernier taux connu en session ou dans la commande, sinon fallback 10%
  $taxRate = 10.0;
  if (!empty($_SESSION['last_tax_rate'])) {
    $taxRate = (float) $_SESSION['last_tax_rate'];
  } else {
    try {
      $stmt = $dbh->prepare("SELECT total_TTC FROM Commande WHERE id_commande = :id_commande LIMIT 1");
      $stmt->execute([':id_commande' => $id_commande]);
      $stored = $stmt->fetchColumn();
      if ($stored !== false && $stored !== null && $montantHT > 0) {
        $raw = ((float)$stored / $montantHT - 1) * 100;
        if (is_finite($raw) && $raw > 0) $taxRate = (float) round($raw, 2);
      }
    } catch (PDOException $e) {
      // ignore
    }
  }

  $montantTTC = round($montantHT * (1 + $taxRate / 100), 2);
}

// stocker en session le montant final TTC pour étape suivante si besoin
$_SESSION['final_payment_amount'] = $montantTTC;
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
  <?php require_once "./navbar.php"; ?>

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
                <input id="expiry" name="expiry" type="text" inputmode="numeric" placeholder="MM/AA" maxlength="5" autocomplete="cc-exp" required pattern="^(0[1-9]|1[0-2])\/\d{2}$">
                <div class="helper">Format : MM/AA</div>
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

          <button class="btn" id="submitBtn" type="submit"><?php echo "Payer " . number_format($montantTTC, 2, '.', '') . "€"; ?></button>

          <p class="notice">Intégration recommandée : Stripe Elements ou SDK conforme PCI.</p>
        </form>
      </section>
    </div>
  </main>

  <?php require_once "./footer.php"; ?>

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
<?php
include "functions/db_functions.php";

?>


<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/ttc.css" />
    <title>Calcul du TTC total</title>
  </head>
  <body>
  <?php
include "./navbar.php";

?>
    <section id="ttc">
        <div class="ttc-container">
            <h2>Prix</h2>
            <form action="" method="post">
            <div class="form-group">
                <label for="ht-amount">Montant HT (€):</label>
            </div>
            <div class="form-group">
                <label for="tax-rate">Taux de TVA (%):</label>
            </div>
            <!-- Résultat -->
            <div class="result">
            <h3>Montant TTC:</h3>
            <p id="ttc-amount">-- €</p>
            </div>
            <br>
            <div class="form-actions">
                <button type="submit" class="btn">Payer</button>
            </div>
            </form>
        </div>
    </section>

<?php
  include "./footer.php";
?>
  </body>
</html>

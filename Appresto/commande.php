<?php
include "functions/db_functions.php";

$sql = "SELECT * FROM Produit WHERE lib_produit = :lib_produit";
$dbh = db_connect();
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':lib_produit', $lib_produit);
$stmt->execute();
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Commander en ligne</title>
  <link rel="stylesheet" href="./css/commande.css">
  <link rel="stylesheet" href="./css/style.css">

</head>
<body>

<?php
include "./navbar.php";

?>
  <!-- CONTENU -->
  <main class="container">

    <!-- Catégories -->
    <aside class="categories-container">
      <h3>Menu</h3>
      <hr>
      <ul>
        <li><a href="#entrees">🥗 <u><b>Entrées</b></u></a></li>
        <p>• Kachumbari</p>
        <p>• Soupe d'arachide</p>
        <li><a href="#plats">🍝 <u><b>Plats</b></u></a></li>
        <p>• Attieke</p>
        <p>• Foutou</p>
        <p>• Mafé</p>
        <p>• Yassa</p>
        <p>• Tikka</p>
        <p>• Tiep</p>
        <p>• Alloco</p>
        <li><a href="#desserts">🍰 <u><b>Desserts</b></u></a></li>
        <p>• Thiakry</p>
        <p>• Malva Pudding</p>
        <li><a href="#boissons">🥤 <u><b>Boissons</b></u></a></li>
        <p>• Bissap</p>
      </ul>
    </aside>
  
    <!-- Menu -->
    <section class="menu-container">

      <!-- ENTRÉES -->
      <div class="menu-category" id="entrees">
        <h2>🥗 Entrées</h2>
        <div class="plats">

          <div class="card-plat" data-nom="Kachumbari" data-prix="9.99">
            <img src="img/kachumbari.jpg" alt="Kachumbari">
            <h3>Kachumbari</h3>
            <p>9.99 €</p>
          </div>

          <div class="card-plat" data-nom="Soupe d'arachide" data-prix="8.99">
            <img src="img/soupe d_arachide.jpg" alt="Soupe d'arachide">
            <h3>Soupe d'arachide</h3>
            <p>8.99 €</p>
          </div>

        </div>
      </div>

      <!-- PLATS -->
      <div class="menu-category" id="plats">
        <h2>🍝 Plats</h2>
        <div class="plats">
          
          <div class="card-plat" data-nom="Attieke" data-prix="12.99">
            <img src="img/attieke.webp" alt="Attieke">
            <h3>Attieke</h3>
            <p>12.99 €</p>
          </div>
          
          <div class="card-plat" data-nom="Alloco" data-prix="17.99">
            <img src="img/Alloco.webp" alt="Alloco">
            <h3>Alloco</h3>
            <p>17.99 €</p>
          </div>

          <div class="card-plat" data-nom="Foutou" data-prix="15.99">
            <img src="img/foutou.webp" alt="Foutou">
            <h3>Foutou</h3>
            <p>15.99 €</p>
          </div>

          <div class="card-plat" data-nom="mafé" data-prix="19.99">
            <img src="img/mafé.webp"mafé">
            <h3>Mafé</h3>
            <p>19.99 €</p>
          </div>

          <div class="card-plat" data-nom="Yassa" data-prix="22.99">
            <img src="img/yassa.webp" alt="Yassa">
            <h3>Yassa</h3>
            <p>22.99 €</p>
          </div>

          <div class="card-plat" data-nom="Tikka" data-prix="14.99">
            <img src="img/tikka.webp" alt="Tikka">
            <h3>Tikka</h3>
            <p>14.99 €</p>
          </div>

          <div class="card-plat" data-nom="Tiep" data-prix="15.99">
            <img src="img/tiep.jpg" alt="Tiep">
            <h3>Tiep</h3>
            <p>15.99 €</p>
          </div>

        </div>
      </div>

      <!-- DESSERTS -->
      <div class="menu-category" id="desserts">
        <h2>🍰 Desserts</h2>
        <div class="plats">

          <div class="card-plat" data-nom="Thiakry" data-prix="9.99">
            <img src="img/Thiakry.webp" alt="Thiakry">
            <h3>Thiakry</h3>
            <p>9.99 €</p>
          </div>

          <div class="card-plat" data-nom="Malva Pudding" data-prix="8.99">
            <img src="img/Malva-Pudding.jpg" alt="Malva Pudding">
            <h3>Malva Pudding</h3>
            <p>8.99 €</p>
          </div>


        </div>
      </div>

      <!-- BOISSONS -->
      <div class="menu-category" id="boissons">
        <h2>🥤 Boissons</h2>
        <div class="plats">

          <div class="card-plat" data-nom="Bissap" data-prix="8.99">
            <img src="img/bissap.webp" alt="Bissap">
            <h3>Bissap</h3>
            <p>8.99 €</p>
          </div>


        </div>
      </div>

    </section>
  
    <!-- Panier -->
    <aside class="cart-container">
      <h2>🛒 Panier</h2>
      <div class="cart-items"></div>
      <div class="total">
        <?php $sql = "SELECT SUM(prix_unitaire_HT) 
                      FROM Produit 
                      WHERE prix_unitaire_HT = :prix_unitaire_HT"; 
      $stmt = $dbh->prepare($sql); 
      $stmt->bindParam(':prix_unitaire_HT', $prix_unitaire_HT); 
      $stmt->execute(); $total = $stmt->fetchColumn(); 
      echo $total; ?> 0,00€
      </div>

        <h3>Options de commande :</h3>
        <form action="type_commande.php" method="post">
          <input type="radio" id="retrait" name="option" value="retrait">
          <label for="retrait">A emporter</label><br>
          <input type="radio" id="surplace" name="option" value="surplace">
          <label for="surplace">Sur place</label><br>
        </form>
        <br>
      <a href="./payment.php">
        <button >Valider la commande</button>
      </a>
    </aside>
    
  
  </main>

  <!-- FOOTER -->
<?php
  include "./footer.php";
?>

  <script src="./js/commande.js"></script>
</body>
</html>

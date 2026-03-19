<?php
// Démarrage session si nécessaire et includes
if (session_status() === PHP_SESSION_NONE) session_start();
include "functions/db_functions.php";
include "functions/check_loggin.php";

$dbh = db_connect();

// Résolution et mémorisation de l'id_commande (GET > POST > SESSION)
$id_commande = 0;
if (!empty($_GET['id_commande'])) {
  $id_commande = (int) $_GET['id_commande'];
} elseif (!empty($_POST['id_commande'])) {
  $id_commande = (int) $_POST['id_commande'];
} elseif (!empty($_SESSION['id_commande'])) {
  $id_commande = (int) $_SESSION['id_commande'];
}
if ($id_commande > 0) {
  $_SESSION['id_commande'] = $id_commande;
}

// Initialiser le panier
if (!isset($_SESSION['panier'])) $_SESSION['panier'] = [];

// helper normalization (lowercase, trim, collapse spaces, remove accents & non-alnum)
function normalize_name(string $s): string {
    $s = trim(mb_strtolower($s, 'UTF-8'));
    // enlever contenu entre parenthèses (ex: "Tiep (Poisson)" -> "Tiep")
    $s = preg_replace('/\([^)]*\)/u', ' ', $s);
    // remplacer les apostrophes, underscores et séparateurs par un espace
    $s = str_replace(["'", "’", "_", "-", "/", "\\", ","], ' ', $s);
    $s = preg_replace('/\s+/', ' ', $s);

    // translitérer accents -> ASCII si possible (intl preferred), sinon iconv fallback
    if (function_exists('transliterator_transliterate')) {
        $trans = @transliterator_transliterate('Any-Latin; Latin-ASCII; [\u0080-\u7fff] remove', $s);
        if ($trans !== false) $s = $trans;
    } else {
        $trans = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $s);
        if ($trans !== false) $s = $trans;
    }

    // conserver lettres/nombres et espaces uniquement
    $s = preg_replace('/[^a-z0-9 ]+/', '', $s);
    $s = trim($s);
    return $s;
}

// Construire une map nom_normalisé => id_produit pour éviter comparaisons fragiles en SQL
$prodMap = [];
try {
    $stmtAll = $dbh->query("SELECT id_produit, lib_produit FROM Produit");
    $allProds = $stmtAll->fetchAll(PDO::FETCH_ASSOC);
    foreach ($allProds as $p) {
        $key = normalize_name((string)$p['lib_produit']);
        if ($key !== '') $prodMap[$key] = (int)$p['id_produit'];
    }
} catch (PDOException $e) {
    error_log("DB error building product map: " . $e->getMessage());
}

// Traitement POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';

  switch ($action) {
    case 'ajouter':
      // priorité à l'id_produit envoyé
      $prodId = isset($_POST['id_produit']) && $_POST['id_produit'] !== '' ? (int)$_POST['id_produit'] : null;
      $key = $prodId ? 'prod_' . $prodId : md5((string)($_POST['nom'] ?? ''));
      if (isset($_SESSION['panier'][$key])) {
        $_SESSION['panier'][$key]['quantite']++;
      } else {
        $prix = floatval(str_replace(',', '.', $_POST['prix'] ?? 0));
        $_SESSION['panier'][$key] = [
          'key' => $key,
          'id_produit' => $prodId,            // <-- stocker l'id produit si disponible
          'nom' => $_POST['nom'] ?? '',
          'prix_unitaire_HT' => $prix,
          'montant_unitaire_HT' => $prix,
          'quantite' => 1
        ];
      }
      break;

    case 'supprimer':
      if (!empty($_POST['id_item']) && isset($_SESSION['panier'][$_POST['id_item']])) {
        unset($_SESSION['panier'][$_POST['id_item']]);
      }
      break;

    case 'modifier_quantite':
      if (!empty($_POST['id_item']) && isset($_SESSION['panier'][$_POST['id_item']])) {
        $_SESSION['panier'][$_POST['id_item']]['quantite'] = max(1, intval($_POST['quantite']));
      }
      break;

    // Valider la commande : INSERT dans Commande puis LigneCommande
    case 'valider_commande':
      if (empty($_SESSION['panier'])) {
          $_SESSION['message_erreur'] = "Votre panier est vide. Impossible de valider la commande.";
          break; // Arrête le traitement de ce 'case' et permet à la redirection de se faire
      }

      // sécuriser le type
      $type = in_array($_POST['type_commande'] ?? '', ['emporter', 'surplace']) ? $_POST['type_commande'] : 'surplace';

      // Insertion commande (corrigée : on enregistre id_utilisateur, pas id_commande)
      // vérifier que l'utilisateur est identifié en session
      if (empty($_SESSION['id_utilisateur'])) {
          header('Location: login.php');
          exit;
      }
      $stmt = $dbh->prepare("INSERT INTO Commande (date_commande, type_commande, id_utilisateur, id_etat, lib_commande)
                                      VALUES (NOW(), :type, :user, 1, :lib)");
      $stmt->execute([
        ':type' => $type,
        ':user' => (int) $_SESSION['id_utilisateur'],
        ':lib' => "Commande du " . date("Y-m-d H:i:s")
      ]);

      // Récupérer id inséré de façon sûre
      $id_commande = (int) $dbh->lastInsertId();
      $_SESSION['id_commande'] = $id_commande;

      // Préparer insertion des lignes
      $stmt_ligne = $dbh->prepare("INSERT INTO LigneCommande (id_commande, id_produit, quantite, montant_unitaire_HT)
                                             VALUES (:cmd, :prod, :qty, :price)");

      foreach ($_SESSION['panier'] as $item) {
        $prodId = isset($item['id_produit']) && $item['id_produit'] ? (int)$item['id_produit'] : null;
        $price = isset($item['montant_unitaire_HT']) ? (float)$item['montant_unitaire_HT'] : 0.0;
        $qty = isset($item['quantite']) ? (int)$item['quantite'] : 1;

        if ($prodId) {
          // insérer directement si on a l'id produit
          $stmt_ligne->execute([
            ':cmd' => $id_commande,
            ':prod' => $prodId,
            ':qty' => $qty,
            ':price' => $price
          ]);
        } else {
          // fallback : tenter la recherche par nom via la map PHP (plus robuste)
          $nom = $item['nom'] ?? '';
          $keyNorm = normalize_name($nom);

          $foundId = null;

          // 1) recherche exacte dans la map
          if ($keyNorm !== '' && isset($prodMap[$keyNorm])) {
              $foundId = $prodMap[$keyNorm];
          } else {
              // 2) recherche par inclusion (ex: "riztikka" vs "tikka")
              foreach ($prodMap as $k => $pid) {
                  if ($keyNorm !== '' && (strpos($k, $keyNorm) !== false || strpos($keyNorm, $k) !== false)) {
                      $foundId = $pid;
                      break;
                  }
              }
          }

          // 3) si toujours pas trouvé, tentative fuzzy (similar_text) -> prendre meilleur score
          if (!$foundId) {
              $bestId = null;
              $bestScore = 0.0;
              foreach ($prodMap as $k => $pid) {
                  similar_text($keyNorm, $k, $percent);
                  if ($percent > $bestScore) {
                      $bestScore = $percent;
                      $bestId = $pid;
                  }
              }
              // seuil: 70% (ajuster si trop permissif/strict)
              if ($bestScore >= 70) {
                  $foundId = $bestId;
              }
          }

          if ($foundId) {
            $stmt_ligne->execute([
              ':cmd' => $id_commande,
              ':prod' => $foundId,
              ':qty' => $qty,
              ':price' => $price
            ]);
          } else {
            error_log("Produit introuvable lors de la validation (nom normalisé: {$keyNorm}): " . $nom);
            // fallback optionnel: insérer ligne avec id_produit = NULL
            // $stmt_ligne->execute([':cmd'=>$id_commande, ':prod'=>null, ':qty'=>$qty, ':price'=>$price]);
          }
        }
      }

      // Vider le panier et rediriger vers TTC.php pour calcul TVA / paiement
      $_SESSION['panier'] = [];
      header("Location: TTC.php?id_commande=" . $id_commande);
      exit;
  }
  // Redirection pour éviter re-POST (PRG) — conserve id_commande si present
  $redirect = $_SERVER['PHP_SELF'];
  if (!empty($id_commande)) $redirect .= '?id_commande=' . (int)$id_commande;
  header("Location: " . $redirect);
  exit;
}

// Calcul du total (utilise prix_unitaire_HT si présent, fallback sur montant_unitaire_HT)
$total = 0;
foreach ($_SESSION['panier'] as $item) {
  $unit = isset($item['prix_unitaire_HT']) ? (float)$item['prix_unitaire_HT'] : (float)($item['montant_unitaire_HT'] ?? 0);
  $qty = isset($item['quantite']) ? (int)$item['quantite'] : 1;
  $total += $unit * $qty;
}
$total = round($total, 2);

// Type de commande par défaut pour la vue
$type_commande_param = in_array($_GET['type_commande'] ?? '', ['emporter', 'surplace']) ? $_GET['type_commande'] : 'emporter';
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


  <?php include "./navbar.php"; ?>


  <main class="container">


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

    <section class="menu-container">


      <div class="menu-category" id="entrees">
        <h2>🥗 Entrées</h2>
        <div class="plats">


          <div class="card-plat">
            <form method="post">
              <input type="hidden" name="action" value="ajouter">
              <?php $mapKey = normalize_name('Kachumbari'); if (isset($prodMap[$mapKey])): ?>
                <input type="hidden" name="id_produit" value="<?= $prodMap[$mapKey] ?>">
              <?php endif; ?>
              <input type="hidden" name="nom" value="Kachumbari">
              <input type="hidden" name="prix" value="9.99">
              <button type="submit" style="all: unset; width: 100%; cursor: pointer; display: block;">
                <img src="img/kachumbari.jpg" alt="Kachumbari">
                <h3>Kachumbari</h3>
                <p>9.99 €</p>
              </button>
            </form>
          </div>


          <div class="card-plat">
            <form method="post">
              <input type="hidden" name="action" value="ajouter">
              <?php $mapKey = normalize_name("Soupe d'arachide"); if (isset($prodMap[$mapKey])): ?>
                <input type="hidden" name="id_produit" value="<?= $prodMap[$mapKey] ?>">
              <?php endif; ?>
              <input type="hidden" name="nom" value="Soupe d'arachide">
              <input type="hidden" name="prix" value="8.99">
              <button type="submit" style="all: unset; width: 100%; cursor: pointer; display: block;">
                <img src="img/soupe d_arachide.jpg" alt="Soupe d'arachide">
                <h3>Soupe d'arachide</h3>
                <p>8.99 €</p>
              </button>
            </form>
          </div>


        </div>
      </div>


      <div class="menu-category" id="plats">
        <h2>🍝 Plats</h2>
        <div class="plats">

          <div class="card-plat">
            <form method="post">
              <input type="hidden" name="action" value="ajouter">
              <?php $mapKey = normalize_name('Attieke'); if (isset($prodMap[$mapKey])): ?>
                <input type="hidden" name="id_produit" value="<?= $prodMap[$mapKey] ?>">
              <?php endif; ?>
              <input type="hidden" name="nom" value="Attieke">
              <input type="hidden" name="prix" value="12.99">
              <button type="submit" style="all: unset; width: 100%; cursor: pointer; display: block;">
                <img src="img/attieke.webp" alt="Attieke">
                <h3>Attieke</h3>
                <p>12.99 €</p>
              </button>
            </form>
          </div>

          <div class="card-plat">
            <form method="post">
              <input type="hidden" name="action" value="ajouter">
              <?php $mapKey = normalize_name('Alloco'); if (isset($prodMap[$mapKey])): ?>
                <input type="hidden" name="id_produit" value="<?= $prodMap[$mapKey] ?>">
              <?php endif; ?>
              <input type="hidden" name="nom" value="Alloco">
              <input type="hidden" name="prix" value="17.99">
              <button type="submit" style="all: unset; width: 100%; cursor: pointer; display: block;">
                <img src="img/Alloco.webp" alt="Alloco">
                <h3>Alloco</h3>
                <p>17.99 €</p>
              </button>
            </form>
          </div>


          <div class="card-plat">
            <form method="post">
              <input type="hidden" name="action" value="ajouter">
              <?php $mapKey = normalize_name('Foutou'); if (isset($prodMap[$mapKey])): ?>
                <input type="hidden" name="id_produit" value="<?= $prodMap[$mapKey] ?>">
              <?php endif; ?>
              <input type="hidden" name="nom" value="Foutou">
              <input type="hidden" name="prix" value="15.99">
              <button type="submit" style="all: unset; width: 100%; cursor: pointer; display: block;">
                <img src="img/foutou.webp" alt="Foutou">
                <h3>Foutou</h3>
                <p>15.99 €</p>
              </button>
            </form>
          </div>


          <div class="card-plat">
            <form method="post">
              <input type="hidden" name="action" value="ajouter">
              <?php $mapKey = normalize_name('Mafé'); if (isset($prodMap[$mapKey])): ?>
                <input type="hidden" name="id_produit" value="<?= $prodMap[$mapKey] ?>">
              <?php endif; ?>
              <input type="hidden" name="nom" value="Mafé">
              <input type="hidden" name="prix" value="19.99">
              <button type="submit" style="all: unset; width: 100%; cursor: pointer; display: block;">
                <img src="img/mafé.webp" alt="Mafé">
                <h3>Mafé</h3>
                <p>19.99 €</p>
              </button>
            </form>
          </div>


          <div class="card-plat">
            <form method="post">
              <input type="hidden" name="action" value="ajouter">
              <?php $mapKey = normalize_name('Yassa'); if (isset($prodMap[$mapKey])): ?>
                <input type="hidden" name="id_produit" value="<?= $prodMap[$mapKey] ?>">
              <?php endif; ?>
              <input type="hidden" name="nom" value="Yassa">
              <input type="hidden" name="prix" value="22.99">
              <button type="submit" style="all: unset; width: 100%; cursor: pointer; display: block;">
                <img src="img/yassa.webp" alt="Yassa">
                <h3>Yassa</h3>
                <p>22.99 €</p>
              </button>
            </form>
          </div>


          <div class="card-plat">
            <form method="post">
              <input type="hidden" name="action" value="ajouter">
              <?php $mapKey = normalize_name('Tikka'); if (isset($prodMap[$mapKey])): ?>
                <input type="hidden" name="id_produit" value="<?= $prodMap[$mapKey] ?>">
              <?php endif; ?>
              <input type="hidden" name="nom" value="Tikka">
              <input type="hidden" name="prix" value="14.99">
              <button type="submit" style="all: unset; width: 100%; cursor: pointer; display: block;">
                <img src="img/tikka.webp" alt="Tikka">
                <h3>Tikka</h3>
                <p>14.99 €</p>
              </button>
            </form>
          </div>


          <div class="card-plat">
            <form method="post">
              <input type="hidden" name="action" value="ajouter">
              <?php $mapKey = normalize_name('Tiep'); if (isset($prodMap[$mapKey])): ?>
                <input type="hidden" name="id_produit" value="<?= $prodMap[$mapKey] ?>">
              <?php endif; ?>
              <input type="hidden" name="nom" value="Tiep">
              <input type="hidden" name="prix" value="15.99">
              <button type="submit" style="all: unset; width: 100%; cursor: pointer; display: block;">
                <img src="img/tiep.jpg" alt="Tiep">
                <h3>Tiep</h3>
                <p>15.99 €</p>
              </button>
            </form>
          </div>


        </div>
      </div>


      <div class="menu-category" id="desserts">
        <h2>🍰 Desserts</h2>
        <div class="plats">


          <div class="card-plat">
            <form method="post">
              <input type="hidden" name="action" value="ajouter">
              <?php $mapKey = normalize_name('Thiakry'); if (isset($prodMap[$mapKey])): ?>
                <input type="hidden" name="id_produit" value="<?= $prodMap[$mapKey] ?>">
              <?php endif; ?>
              <input type="hidden" name="nom" value="Thiakry">
              <input type="hidden" name="prix" value="9.99">
              <button type="submit" style="all: unset; width: 100%; cursor: pointer; display: block;">
                <img src="img/Thiakry.webp" alt="Thiakry">
                <h3>Thiakry</h3>
                <p>9.99 €</p>
              </button>
            </form>
          </div>


          <div class="card-plat">
            <form method="post">
              <input type="hidden" name="action" value="ajouter">
              <?php $mapKey = normalize_name('Malva Pudding'); if (isset($prodMap[$mapKey])): ?>
                <input type="hidden" name="id_produit" value="<?= $prodMap[$mapKey] ?>">
              <?php endif; ?>
              <input type="hidden" name="nom" value="Malva Pudding">
              <input type="hidden" name="prix" value="8.99">
              <button type="submit" style="all: unset; width: 100%; cursor: pointer; display: block;">
                <img src="img/Malva-Pudding.jpg" alt="Malva Pudding">
                <h3>Malva Pudding</h3>
                <p>8.99 €</p>
              </button>
            </form>
          </div>


        </div>
      </div>


      <div class="menu-category" id="boissons">
        <h2>🥤 Boissons</h2>
        <div class="plats">


          <div class="card-plat">
            <form method="post">
              <input type="hidden" name="action" value="ajouter">
              <?php $mapKey = normalize_name('Bissap'); if (isset($prodMap[$mapKey])): ?>
                <input type="hidden" name="id_produit" value="<?= $prodMap[$mapKey] ?>">
              <?php endif; ?>
              <input type="hidden" name="nom" value="Bissap">
              <input type="hidden" name="prix" value="8.99">
              <button type="submit" style="all: unset; width: 100%; cursor: pointer; display: block;">
                <img src="img/bissap.webp" alt="Bissap">
                <h3>Bissap</h3>
                <p>8.99 €</p>
              </button>
            </form>
          </div>


        </div>
      </div>


    </section>

    <aside class="cart-container">
      <h2>🛒 Panier</h2>
      <div class="cart-items">
        <?php foreach ($_SESSION['panier'] as $id => $item): ?>
          <div class="cart-item">
            <span class="name"><?= htmlspecialchars($item['nom'], ENT_QUOTES) ?></span>
            <div class="right">
              <span class="price"><?= number_format((isset($item['prix_unitaire_HT']) ? $item['prix_unitaire_HT'] : $item['montant_unitaire_HT']) * $item['quantite'], 2, '.', '') ?> €</span>

              <form method="post" style="display: inline;">
                <input type="hidden" name="action" value="modifier_quantite">
                <input type="hidden" name="id_item" value="<?= htmlspecialchars($id, ENT_QUOTES) ?>">
                <input type="number" name="quantite" class="quantity"
                  value="<?= (int)$item['quantite'] ?>"
                  min="1"
                  onchange="this.form.submit()">
              </form>

              <form method="post" style="display: inline;">
                <input type="hidden" name="action" value="supprimer">
                <input type="hidden" name="id_item" value="<?= htmlspecialchars($id, ENT_QUOTES) ?>">
                <button type="submit" class="remove">❌</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="total">Total : <?= number_format($total, 2, '.', '') ?> €</div>


      <h3>Options de commande :</h3>
      <form action="" method="post">
        <input type="hidden" name="action" value="valider_commande">
        <input type="radio" id="retrait" name="type_commande" value="emporter" <?= $type_commande_param === 'emporter' ? 'checked' : '' ?>>
        <label for="retrait">A emporter</label><br>

        <input type="radio" id="surplace" name="type_commande" value="surplace" <?= $type_commande_param === 'surplace' ? 'checked' : '' ?>>
        <label for="surplace">Sur place</label><br>

        <br>
        
        <?php if (isset($_SESSION['message_erreur'])): ?>
            <p style="color: red; font-weight: bold; margin-bottom: 10px;">
                <?= htmlspecialchars($_SESSION['message_erreur']) ?>
            </p>
            <?php unset($_SESSION['message_erreur']); // On supprime le message pour ne pas l'afficher à nouveau ?>
        <?php endif; ?>
        
        <button type="submit" class="btn">Valider la commande</button>
      </form>
    </aside>

  </main>


  <?php include "./footer.php"; ?>


</body>

</html>
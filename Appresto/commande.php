<?php
session_start();
include "functions/db_functions.php";
include "functions/check_loggin.php";

$dbh = db_connect();

if (!isset($_SESSION['panier'])) $_SESSION['panier'] = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action == 'ajouter') {
        $key = 'prod_' . $_POST['id_produit'];
        if (isset($_SESSION['panier'][$key])) {
            $_SESSION['panier'][$key]['quantite']++;
        } else {
            $stmt = $dbh->prepare("SELECT lib_produit, prix_unitaire_HT FROM Produit WHERE id_produit = :id");
            $stmt->execute([':id' => $_POST['id_produit']]);
            $produit = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $_SESSION['panier'][$key] = [
                'id_produit'          => $_POST['id_produit'],
                'nom'                 => $produit['lib_produit'],
                'montant_unitaire_HT' => $produit['prix_unitaire_HT'], 
                'quantite'            => 1,
            ];
        }

    } else if ($action == 'supprimer') {
        unset($_SESSION['panier'][$_POST['id_item']]);

    } else if ($action == 'modifier_quantite') {
        $_SESSION['panier'][$_POST['id_item']]['quantite'] = max(1, $_POST['quantite']);

    } else if ($action == 'valider_commande') {
        if (empty($_SESSION['panier'])) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $stmt = $dbh->prepare("INSERT INTO Commande (date_commande, type_commande, id_utilisateur, id_etat, lib_commande)
                                   VALUES (NOW(), :type, :user, 1, :lib)");
            $stmt->execute([
                ':type' => $_POST['type_commande'],
                ':user' => $_SESSION['id_utilisateur'],
                ':lib'  => "Commande du " . date("Y-m-d H:i:s"),
            ]);

            $id_commande = $dbh->lastInsertId();

            $stmtLigne = $dbh->prepare("INSERT INTO LigneCommande (id_commande, id_produit, quantite, montant_unitaire_HT)
                                        VALUES (:cmd, :prod, :qty, :price)");
            foreach ($_SESSION['panier'] as $item) {
                $stmtLigne->execute([
                    ':cmd'   => $id_commande,
                    ':prod'  => $item['id_produit'],
                    ':qty'   => $item['quantite'],
                    ':price' => $item['montant_unitaire_HT'],
                ]);
            }

            $_SESSION['panier'] = [];
            header("Location: TTC.php?id_commande=" . $id_commande);
            exit;
        }
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$total = 0;
foreach ($_SESSION['panier'] as $item) {
    $total += $item['montant_unitaire_HT'] * $item['quantite'];
}
$total = round($total, 2);
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
                        <input type="hidden" name="action"     value="ajouter">
                        <input type="hidden" name="id_produit" value="11"> <button type="submit">
                            <img src="img/kachumbari.jpg" alt="Kachumbari">
                            <h3>Kachumbari</h3>
                            <p>9.99 €</p>
                        </button>
                    </form>
                </div>

                <div class="card-plat">
                    <form method="post">
                        <input type="hidden" name="action"     value="ajouter">
                        <input type="hidden" name="id_produit" value="12"> <button type="submit">
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
                        <input type="hidden" name="action"     value="ajouter">
                        <input type="hidden" name="id_produit" value="2"> <button type="submit">
                            <img src="img/attieke.webp" alt="Attieke">
                            <h3>Attieke</h3>
                            <p>12.99 €</p>
                        </button>
                    </form>
                </div>

                <div class="card-plat">
                    <form method="post">
                        <input type="hidden" name="action"     value="ajouter">
                        <input type="hidden" name="id_produit" value="5"> <button type="submit">
                            <img src="img/Alloco.webp" alt="Alloco">
                            <h3>Alloco</h3>
                            <p>17.99 €</p>
                        </button>
                    </form>
                </div>

                <div class="card-plat">
                    <form method="post">
                        <input type="hidden" name="action"     value="ajouter">
                        <input type="hidden" name="id_produit" value="3"> <button type="submit">
                            <img src="img/foutou.webp" alt="Foutou">
                            <h3>Foutou</h3>
                            <p>15.99 €</p>
                        </button>
                    </form>
                </div>

                <div class="card-plat">
                    <form method="post">
                        <input type="hidden" name="action"     value="ajouter">
                        <input type="hidden" name="id_produit" value="1"> <button type="submit">
                            <img src="img/mafé.webp" alt="Mafé">
                            <h3>Mafé</h3>
                            <p>19.99 €</p>
                        </button>
                    </form>
                </div>

                <div class="card-plat">
                    <form method="post">
                        <input type="hidden" name="action"     value="ajouter">
                        <input type="hidden" name="id_produit" value="4"> <button type="submit">
                            <img src="img/yassa.webp" alt="Yassa">
                            <h3>Yassa</h3>
                            <p>22.99 €</p>
                        </button>
                    </form>
                </div>

                <div class="card-plat">
                    <form method="post">
                        <input type="hidden" name="action"     value="ajouter">
                        <input type="hidden" name="id_produit" value="6"> <button type="submit">
                            <img src="img/tikka.webp" alt="Tikka">
                            <h3>Tikka</h3>
                            <p>14.99 €</p>
                        </button>
                    </form>
                </div>

                <div class="card-plat">
                    <form method="post">
                        <input type="hidden" name="action"     value="ajouter">
                        <input type="hidden" name="id_produit" value="7"> <button type="submit">
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
                        <input type="hidden" name="action"     value="ajouter">
                        <input type="hidden" name="id_produit" value="9"> <button type="submit">
                            <img src="img/Thiakry.webp" alt="Thiakry">
                            <h3>Thiakry</h3>
                            <p>9.99 €</p>
                        </button>
                    </form>
                </div>

                <div class="card-plat">
                    <form method="post">
                        <input type="hidden" name="action"     value="ajouter">
                        <input type="hidden" name="id_produit" value="10"> <button type="submit">
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
                        <input type="hidden" name="action"     value="ajouter">
                        <input type="hidden" name="id_produit" value="8"> <button type="submit">
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
            <?php foreach ($_SESSION['panier'] as $key => $item): ?>
                <div class="cart-item">
                    <span class="name"><?= $item['nom'] ?></span>
                    <div class="right">
                        <span class="price"><?= number_format($item['montant_unitaire_HT'] * $item['quantite'], 2, '.', '') ?> €</span>

                        <form method="post" style="display:inline;">
                            <input type="hidden" name="action"  value="modifier_quantite">
                            <input type="hidden" name="id_item" value="<?= $key ?>">
                            <input type="number" name="quantite" class="quantity"
                                   value="<?= $item['quantite'] ?>" min="1"
                                   onchange="this.form.submit()">
                        </form>

                        <form method="post" style="display:inline;">
                            <input type="hidden" name="action"  value="supprimer">
                            <input type="hidden" name="id_item" value="<?= $key ?>">
                            <button type="submit" class="remove">❌</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="total">Total : <?= number_format($total, 2, '.', '') ?> €</div>

        <h3>Options de commande :</h3>
        <form method="post">
            <input type="hidden" name="action" value="valider_commande">

            <input type="radio" id="retrait"  name="type_commande" value="emporter" checked>
            <label for="retrait">À emporter</label><br>

            <input type="radio" id="surplace" name="type_commande" value="surplace">
            <label for="surplace">Sur place</label><br><br>

            <?php if (!empty($_SESSION['message_erreur'])): ?>
                <p style="color:red;"><?= $_SESSION['message_erreur'] ?></p>
                <?php unset($_SESSION['message_erreur']); ?>
            <?php endif; ?>

            <button type="submit" class="btn">Valider la commande</button>
        </form>
    </aside>

</main>

<?php include "./footer.php"; ?>
</body>
</html>
<?php

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/menu.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <title>Menu - Palais des saveurs</title>
</head>
<body>
<?php
include "./navbar.php";

?>

    <header class="menu-header">
        <h1>Notre Menu</h1>
        <p>Découvrez nos spécialités préparées avec passion !</p>
    </header>
    <main class="menu-main">
        <section class="menu-category">
            <h2>Entrées</h2>
            <div class="menu-cards">
                <div class="menu-card">
                    <img src="img/kachumbari.jpg" alt="Kachumbari">
                    <h3>Kachumbari</h3>
                    <p>Coriande, oignon, citron, chili (optionnel), tomates.</p>
                    <span class="price">9,99€</span>
                </div>
                <div class="menu-card">
                    <img src="img/soupe d_arachide.jpg" alt="Soupe d'arachide">
                    <h3>Soupe d'arachide</h3>
                    <p>Pâte d'arachide, oignons, carottes, patates douces, légumes, tomates.</p>
                    <span class="price">8,99€</span>
                </div>
            </div>
        </section>
        <section class="menu-category">
            <h2>Plats</h2>
            <div class="menu-cards">
                <div class="menu-card">
                    <img src="img/attieke.webp" alt="Attieke">
                    <h3>Attieke</h3>
                    <p>Attieke, concombres, tomates, oignons, bouillon de poulet.</p>
                    <span class="price">12,99€</span>
                </div>
                <div class="menu-card">
                    <img src="img/foutou.webp" alt="Foutou">
                    <h3>Foutou</h3>
                    <p>Bananes plantain, Manioc, Farine de Manioc.</p>
                    <span class="price">15,99€</span>
                </div>
                <div class="menu-card">
                    <img src="img/mafé.webp" alt="Mafé">
                    <h3>Mafé</h3>
                    <p>Piments antillais, patates douces, oignons, poulet, patates.</p>
                    <span class="price">19,99€</span>
                </div>
                <div class="menu-card">
                    <img src="img/yassa.webp" alt="Yassa">
                    <h3>Yassa</h3>
                    <p>Poulet, oignons, gousses d'ail, citrons, piments, laurier.</p>
                    <span class="price">22,99€</span>
                </div>
                <div class="menu-card">
                    <img src="img/tikka.webp" alt="Tikka">
                    <h3>Tikka</h3>
                    <p>Oignons, tomates, gingembre, yaourt, citron, poulet.</p>
                    <span class="price">14,99€</span>
                </div>
                <div class="menu-card">
                    <img src="img/tiep.jpg" alt="Tiep">
                    <h3>Tiep</h3>
                    <p>riz, oignons blancs, carottes, aubergine, piment rouge, poisson.</p>
                    <span class="price">15,99€</span>
                </div>
                <div class="menu-card">
                    <img src="img/Alloco.webp" alt="Alloco">
                    <h3>Allocco</h3>
                    <p>Poulet, aubergine, gingembre, gousses d'ail, poivron vert, bananes plantains.</p>
                    <span class="price">17,99€</span>
                </div>
            </div>
        </section>
        <section class="menu-category">
            <h2>Desserts</h2>
            <div class="menu-cards">
                <div class="menu-card">
                    <img src="img/Thiakry.webp" alt="Thiakry">
                    <h3>Thiakry</h3>
                    <p>Semoule, noix de coco, raisin secs, mangue.</p>
                    <span class="price">9,99€</span>
                </div>
                <div class="menu-card">
                    <img src="img/Malva-Pudding.jpg" alt="Malva Pudding">
                    <h3>Malva Pudding</h3>
                    <p>Lait, œufs, touche de crème, confiture d'abricot.</p>
                    <span class="price">8,99€</span>
                </div>
            </div>
        </section>
        <section class="menu-category">
            <h2>Boissons</h2>
            <div class="menu-cards">
                <div class="menu-card">
                    <img src="img/bissap.webp" alt="Bissap">
                    <h3>Bissap</h3>
                    <p>Fleurs d'hibiscus, eau, sucre, menthe (optionnel).</p>
                    <span class="price">8,99€</span>
                </div>
            </div>
        </section>
    </main>
<?php
  include "./footer.php";
?>
</body>
</html>
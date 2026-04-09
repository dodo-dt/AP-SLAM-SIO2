<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Le Palais des saveurs</title>
  <link rel="stylesheet" href="css/accueil.css">
  <link rel="stylesheet" href="css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
</head>
<body>

<?php include "./navbar.php"; ?>

  <section class="hero">
    <input type="radio" name="carousel" id="slide1" checked>
    <input type="radio" name="carousel" id="slide2">
    <input type="radio" name="carousel" id="slide3">
    
    <div class="hero-texture"></div>
    
    <div class="cooking-bubbles">
      <div class="bubble"></div>
      <div class="bubble"></div>
      <div class="bubble"></div>
      <div class="bubble"></div>
      <div class="bubble"></div>
      <div class="bubble"></div>
    </div>
    
    <div class="african-leaf leaf-1"></div>
    <div class="african-leaf leaf-2"></div>
    <div class="african-leaf leaf-3"></div>
    <div class="african-leaf leaf-4"></div>
    <div class="african-leaf leaf-5"></div>
    <div class="african-leaf leaf-6"></div>
    <div class="african-leaf leaf-7"></div>
    
    <div class="hero-visual">
      <div class="carousel-glow"></div>
      <div class="rotating-circle"></div>
      <div class="image-carousel">
        <div class="carousel-container">
          <div class="carousel-slide">
            <img src="img/soupe d_arachide.jpg" alt="Soupe d'Arachide">
            <div class="slide-overlay">
              <h3>Soupe d'Arachide</h3>
              <p>Soupe d'arachide africaine</p>
            </div>
          </div>
          <div class="carousel-slide">
            <img src="img/yassa.webp" alt="Yassa">
            <div class="slide-overlay">
              <h3>Yassa</h3>
              <p>Poulet, oignons, olives...</p>
            </div>
          </div>
          <div class="carousel-slide">
            <img src="img/Thiakry.webp" alt="Thiakry">
            <div class="slide-overlay">
              <h3>Thiakry</h3>
              <p>Dessert africain</p>
            </div>
          </div>
          
          <div class="carousel-controls">
            <label class="carousel-prev" for="slide3">‹</label>
            <label class="carousel-next" for="slide2">›</label>
          </div>
          
          <label class="carousel-prev" for="slide1" id="prev-slide2">‹</label>
          <label class="carousel-next" for="slide3" id="next-slide2">›</label>
          
          <label class="carousel-prev" for="slide2" id="prev-slide3">‹</label>
          <label class="carousel-next" for="slide1" id="next-slide3">›</label>
        </div>
        
        <div class="carousel-dots">
          <label for="slide1" class="dot"></label>
          <label for="slide2" class="dot"></label>
          <label for="slide3" class="dot"></label>
        </div>
      </div>
    </div>
    
    <div class="hero-container">
      <div class="hero-content">
        <div class="hero-text">
          <h1 class="hero-title">Le Palais des Saveurs</h1>
          <p class="hero-tagline">Chaque épice raconte une histoire.</p>
          <p class="hero-subtitle">Cuisine africaine authentique</p>
          <a href="menu.php" class="menu-button">Découvrir nos plats</a>
          <div class="hero-stats">
            <div class="stat">
              <span class="stat-number">10+</span>
              <span class="stat-label">Spécialités</span>
            </div>
            <div class="stat">
              <span class="stat-number">100%</span>
              <span class="stat-label">Fait maison</span>
            </div>
            <div class="stat">
              <span class="stat-number">Toulouse</span>
              <span class="stat-label">Depuis 2020</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="section-separator">
    <svg viewBox="0 0 1200 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
      <defs>
        <linearGradient id="waveGradient" x1="0%" y1="0%" x2="0%" y2="100%">
          <stop offset="0%" style="stop-color:rgba(0, 0, 0, 0.4);stop-opacity:1" />
          <stop offset="100%" style="stop-color:rgba(26, 26, 26, 0.95);stop-opacity:1" />
        </linearGradient>
      </defs>
      <path d="M0,0 C150,80 350,80 600,40 C850,0 1050,0 1200,40 L1200,120 L0,120 Z" fill="url(#waveGradient)"/>
    </svg>
  </div>

  <section class="about-section">
    <div class="about-container">
      <div class="about-content">
        <h2 class="about-title">À propos de nous</h2>
        <div class="about-text">
          <p>Situé au cœur de Toulouse, Le Palais des Saveurs vous invite à découvrir l'authenticité de la cuisine africaine dans un cadre chaleureux et convivial. Depuis 2020, nous perpétuons les traditions culinaires transmises de génération en génération.</p>
          <p>Chaque plat est préparé avec amour dans nos cuisines, en utilisant exclusivement des ingrédients frais et des épices importées directement d'Afrique. Notre chef, originaire du Sénégal, met un point d'honneur à vous faire vivre une expérience gustative unique.</p>
          <p>Que ce soit pour un déjeuner entre amis, un dîner en famille ou une découverte culinaire, notre équipe vous accueille avec le sourire pour partager ensemble les saveurs et célébrer la vie.</p>
        </div>
        <div class="about-highlights">
          <div class="highlight">
            <span class="highlight-icon">👨‍🍳</span>
            <span class="highlight-text">Chef authentique</span>
          </div>
          <div class="highlight">
            <span class="highlight-icon">🌿</span>
            <span class="highlight-text">Ingrédients frais</span>
          </div>
          <div class="highlight">
            <span class="highlight-icon">🏠</span>
            <span class="highlight-text">Fait maison</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="process-section">
    <div class="process-container">
      <div class="process-header">
        <h2 class="process-title">Notre processus</h2>
        <p class="process-subtitle">Fait avec amour & Améliore votre humeur</p>
      </div>
      <div class="process-steps">
        <div class="process-step">
          <div class="step-number">01</div>
          <h3 class="step-title">Mise en place</h3>
          <p class="step-description">Avant votre arrivée, nous prévoyons tout, l'aménagement de la salle et la préparation finale.</p>
        </div>
        <div class="process-step">
          <div class="step-number">02</div>
          <h3 class="step-title">Cuisine</h3>
          <p class="step-description">Tous les jours, nous cuisinons des plats délicieux et sains avec des produits frais que nous recevons.</p>
        </div>
        <div class="process-step">
          <div class="step-number">03</div>
          <h3 class="step-title">Service</h3>
          <p class="step-description">Nous avons pour mission de vous accueillir et de vous servir avec le plus grand plaisir.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="best-rated-dishes">
    <div class="dishes-container">
      <div class="dishes-header">
        <h2 class="dishes-title">Nos spécialités</h2>
        <p class="dishes-subtitle">Les plats les mieux notés</p>
      </div>
      <div class="dishes-grid">
        <div class="dish-card">
          <div class="dish-rating">★★★★★</div>
          <h3 class="dish-name">Poulet Yassa</h3>
          <p class="dish-description">Notre spécialité la plus appréciée, poulet mariné aux oignons et citron</p>
          <div class="dish-price">22,99€</div>
        </div>
        <div class="dish-card">
          <div class="dish-rating">★★★★★</div>
          <h3 class="dish-name">Mafé Traditionnel</h3>
          <p class="dish-description">Sauté de bœuf dans une délicieuse sauce au beurre de cacahuète</p>
          <div class="dish-price">19,99€</div>
        </div>
        <div class="dish-card">
          <div class="dish-rating">★★★★☆</div>
          <h3 class="dish-name">Attieke Daurade</h3>
          <p class="dish-description">Daurade royale accompagnée de semoule de manioc et sauce froide</p>
          <div class="dish-price">12.99€</div>
        </div>
      </div>
    </div>
  </section>

  <section class="stats-section">
    <div class="stats-container">
      <h2 class="stats-title">Nos chiffres</h2>
      <div class="stats-grid">
        <div class="stat-item">
          <div class="stat-value">6+</div>
          <div class="stat-description">Années d'existence</div>
        </div>
        <div class="stat-item">
          <div class="stat-value">20k</div>
          <div class="stat-description">Clients servis</div>
        </div>
        <div class="stat-item">
          <div class="stat-value">195+</div>
          <div class="stat-description">Recommandations</div>
        </div>
      </div>
    </div>
  </section>

  <section class="location-section">
    <div class="location-container">
      <h2 class="location-title">
        <img src="img/Logo.png" alt="Le Palais des Saveurs" class="logo">
        <br>Retrouvez-nous
      </h2>
      <p class="location-subtitle">Nous sommes basés uniquement à Toulouse pour l'instant !</p>
      <div class="location-content">
        <div class="location-info">
          <h3 class="restaurant-name">Le Palais des Saveurs</h3>
          <div class="address">
            <p>15 rue du tralala <br>31000 Toulouse</p>
          </div>
        </div>
        <div class="location-description">
          <p>Le Palais des Saveurs est un restaurant Sénégalais mais aussi Français, vous pouvez y savourer les spécialités Franco-Africaines qui y sont proposées. Nous vous attendons du lundi au samedi, pour un café, un thé ou un chocolat chaud, une bière ou un coca, un mafé ou une salade mais aussi d'autres plats disponibles.</p>
          <div class="opening-hours">
            <p>Restaurant ouvert du <strong>Lundi au Samedi</strong> de <strong>10:00h à 21:00h</strong></p>
          </div>
        </div>
      </div>
    </div>
  </section>

<?php include "./footer.php"; ?>

<script>
  let current = 1;
  const total = 3;
  let paused = false;

  setInterval(() => {
    if (!paused) {
      current = current === total ? 1 : current + 1;
      document.getElementById('slide' + current).checked = true;
    }
  }, 4000);

  const carousel = document.querySelector('.image-carousel');
  carousel.addEventListener('mouseenter', () => paused = true);
  carousel.addEventListener('mouseleave', () => paused = false);
</script>

</body>
</html>
```
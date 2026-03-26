// Gestion du carrousel avec bouton central interactif
document.addEventListener('DOMContentLoaded', function() {
  const centerButtons = document.querySelectorAll('.center-dish-info');
  
  // Tableau des plats avec leurs informations
  const dishes = [
    {
      id: 1,
      name: 'Soupe d\'Arachide',
      link: 'menu.php#soupe-arachide'
    },
    {
      id: 2,
      name: 'Yassa',
      link: 'menu.php#yassa'
    },
    {
      id: 3,
      name: 'Thiakry',
      link: 'menu.php#thiakry'
    }
  ];
  
  // Ajouter un événement de clic sur chaque bouton central
  centerButtons.forEach(button => {
    button.addEventListener('click', function() {
      const dishId = parseInt(this.getAttribute('data-dish'));
      const dish = dishes.find(d => d.id === dishId);
      
      if (dish && dish.link) {
        // Rediriger vers la page du menu avec l'ancre du plat
        window.location.href = dish.link;
      }
    });
    
    // Effet visuel au survol
    button.addEventListener('mouseenter', function() {
      this.style.transform = 'translate(-50%, -50%) scale(1.1) rotate(2deg)';
    });
    
    button.addEventListener('mouseleave', function() {
      this.style.transform = 'translate(-50%, -50%) scale(1)';
    });
  });
  
  // Navigation automatique du carrousel (optionnel)
  let currentSlide = 1;
  const totalSlides = 3;
  let autoPlayInterval;
  
  function nextSlide() {
    currentSlide = currentSlide === totalSlides ? 1 : currentSlide + 1;
    document.getElementById('slide' + currentSlide).checked = true;
  }
  
  // Démarrer l'auto-play (décommentez si souhaité)
  // autoPlayInterval = setInterval(nextSlide, 5000);
  
  // Arrêter l'auto-play au survol du carrousel
  const carousel = document.querySelector('.image-carousel');
  if (carousel) {
    carousel.addEventListener('mouseenter', function() {
      if (autoPlayInterval) {
        clearInterval(autoPlayInterval);
      }
    });
    
    carousel.addEventListener('mouseleave', function() {
      // Redémarrer l'auto-play (décommentez si souhaité)
      // autoPlayInterval = setInterval(nextSlide, 5000);
    });
  }
});
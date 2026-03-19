// test.js
document.addEventListener('DOMContentLoaded', () => {
  const menu = document.querySelector('.menu-container');
  const panier = document.querySelector('.cart-items');
  const totalEl = document.querySelector('.total');

  if (!menu) {
    console.error('Élément introuvable : .menu-container');
    return;
  }
  if (!panier) {
    console.error('Élément introuvable : .cart-items');
    return;
  }
  if (!totalEl) {
    console.error('Élément introuvable : .total');
    return;
  }

  let total = 0;

  // Ajout au panier via delegation (plus fiable si éléments enfants sont cliqués)
  menu.addEventListener('click', (e) => {
    const plat = e.target.closest('.card-plat');
    if (!plat) return; // on a cliqué en dehors d'une carte

    // Lecture du nom et du prix (fallback vers le texte s'il manque dataset)
    const nom = plat.dataset.nom?.trim() || plat.querySelector('h3')?.textContent?.trim() || 'Plat';
    const prixRaw = plat.dataset.prix ?? plat.querySelector('p')?.textContent ?? '0';
    // Normaliser le prix (supporte "8.50" ou "8,50 €")
    const prix = parseFloat(String(prixRaw).replace(/[^0-9,.-]/g, '').replace(',', '.'));
    if (isNaN(prix)) {
      console.error('Prix invalide pour', nom, prixRaw);
      return;
    }

    // Crée l'élément panier et stocke le prix dans dataset (utile pour la suppression)
    const item = document.createElement('div');
    item.className = 'cart-item';
    item.dataset.prix = prix; // stockage pour retrait fiable
    item.innerHTML = `
      <span class="name">${nom}</span>
      <div class="right">
        <span class="price">${prix.toFixed(2)} €</span>
        <button type="button" class="remove" aria-label="Supprimer ${nom}">❌</button>
      </div>
    `;
    panier.appendChild(item);

    // Mise à jour du total (arrondi pour éviter les erreurs flottantes)
    total = Math.round((total + prix) * 100) / 100;
    totalEl.textContent = "Total : " + total.toFixed(2) + " €";
  });

  // Gestion des suppressions via delegation sur le conteneur du panier
  panier.addEventListener('click', (e) => {
    const btn = e.target.closest('.remove');
    if (!btn) return;

    const item = btn.closest('.cart-item');
    if (!item) return;

    const prixItem = parseFloat(item.dataset.prix ?? item.querySelector('.price')?.textContent?.replace(/[^0-9,.-]/g, '').replace(',', '.') ?? '0');
    if (!isNaN(prixItem)) {
      total = Math.round((total - prixItem) * 100) / 100;
      if (total < 0) total = 0;
    } else {
      console.warn('Impossible de lire le prix de l\'article à supprimer', item);
    }

    item.remove();
    totalEl.textContent = "Total : " + total.toFixed(2) + " €";
  });
});

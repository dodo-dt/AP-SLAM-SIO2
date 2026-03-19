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

  // helper : format montant
  const fmt = n => (Math.round(n * 100) / 100).toFixed(2) + ' €';
  const updateTotalDisplay = () => totalEl.textContent = "Total : " + fmt(total);

  // Ajout au panier via delegation
  menu.addEventListener('click', (e) => {
    const plat = e.target.closest('.card-plat');
    if (!plat) return;

    const nom = plat.dataset.nom?.trim() || plat.querySelector('h3')?.textContent?.trim() || 'Plat';
    const prixRaw = plat.dataset.prix ?? plat.querySelector('p')?.textContent ?? '0';
    const prix = parseFloat(String(prixRaw).replace(/[^0-9,.-]/g, '').replace(',', '.'));
    if (isNaN(prix)) {
      console.error('Prix invalide pour', nom, prixRaw);
      return;
    }

    const qty = 1;
    const lineTotal = Math.round(prix * qty * 100) / 100;

    const item = document.createElement('div');
    item.className = 'cart-item';
    // stocker prix unitaire et quantité actuelle
    item.dataset.prixUnit = prix;
    item.dataset.qty = qty;
    item.innerHTML = `
      <span class="name">${nom}</span>
      <div class="right">
        <span class="price">${lineTotal.toFixed(2)} €</span>
        <input type="number" class="quantity" value="${qty}" min="1" aria-label="Quantité ${nom}">
        <button type="button" class="remove" aria-label="Supprimer ${nom}">❌</button>
      </div>
    `;
    panier.appendChild(item);

    total = Math.round((total + lineTotal) * 100) / 100;
    updateTotalDisplay();
  });

  // Gestion des suppressions et changements de quantité via delegation
  panier.addEventListener('click', (e) => {
    const btn = e.target.closest('.remove');
    if (!btn) return;

    const item = btn.closest('.cart-item');
    if (!item) return;

    const unit = parseFloat(item.dataset.prixUnit ?? item.querySelector('.price')?.textContent?.replace(/[^0-9,.-]/g, '').replace(',', '.') ?? '0');
    const qty = parseInt(item.dataset.qty ?? item.querySelector('.quantity')?.value ?? '1', 10) || 1;
    const lineTotal = Math.round(unit * qty * 100) / 100;

    if (!isNaN(lineTotal)) {
      total = Math.round((total - lineTotal) * 100) / 100;
      if (total < 0) total = 0;
    } else {
      console.warn('Impossible de lire le prix de l\'article à supprimer', item);
    }

    item.remove();
    updateTotalDisplay();
  });

  // écoute des changements de quantité (delegation)
  panier.addEventListener('input', (e) => {
    const input = e.target.closest('.quantity');
    if (!input) return;

    const item = input.closest('.cart-item');
    if (!item) return;

    // clamp valeur
    let newQty = parseInt(input.value, 10) || 1;
    if (newQty < 1) newQty = 1;
    input.value = newQty;

    const unit = parseFloat(item.dataset.prixUnit ?? item.querySelector('.price')?.textContent?.replace(/[^0-9,.-]/g, '').replace(',', '.') ?? '0');
    const prevQty = parseInt(item.dataset.qty ?? '1', 10) || 1;

    // mettre à jour total selon la différence de quantité
    const diff = newQty - prevQty;
    total = Math.round((total + unit * diff) * 100) / 100;
    if (total < 0) total = 0;

    // mettre à jour dataset qty et affichage du prix de la ligne (unit * qty)
    item.dataset.qty = newQty;
    const lineTotal = Math.round(unit * newQty * 100) / 100;
    const priceEl = item.querySelector('.price');
    if (priceEl) priceEl.textContent = lineTotal.toFixed(2) + ' €';

    updateTotalDisplay();
  });

  // initialisation affichage total
  updateTotalDisplay();
});

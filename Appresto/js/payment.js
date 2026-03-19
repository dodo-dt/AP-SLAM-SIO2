// Prévisualisation du numéro, titulaire, expiry et mise à jour du montant
(function () { // IIFE : crée un scope privé et exécute immédiatement le script
  const el = id => document.getElementById(id); // raccourci pour récupérer un élément par id

  const cardNumber = el('cardNumber'); // input du numéro de carte
  const cardName = el('cardName'); // input du nom du titulaire
  const expiry = el('expiry'); // input de la date d'expiration
  const cvc = el('cvc'); // input du code CVC

  const visualNumber = el('visualNumber'); // élément affichant le numéro sur la carte visuelle
  const visualName = el('visualName'); // élément affichant le nom sur la carte visuelle
  const visualExpiry = el('visualExpiry'); // élément affichant l'expiration sur la carte visuelle
  const brandTag = el('brandTag'); // élément affichant la marque détectée (VISA, etc.)
  const submitBtn = el('submitBtn'); // bouton de soumission (peut être null si absent)
  const errorArea = el('errorArea'); // zone d'affichage des erreurs
  const form = el('paymentForm'); // formulaire de paiement
  const amountEl = el('amount'); // input montant (peut être null)

  function formatCardNumber(v) { // formate un string en groupes de 4 chiffres
    const digits = v.replace(/\D/g, '').slice(0, 16); // garde uniquement les chiffres et limite à 16
    return digits.match(/.{1,4}/g)?.join(' ') || ''; // regroupe par 4 et joint par des espaces, ou retourne vide
  }

  function formatCVC(v) { // formate le CVC en maximum 4 chiffres
    return v.replace(/\D/g, '').slice(0, 3); // garde uniquement les chiffres et limite à 3
  }

  function detectBrand(digits) { // détecte une marque simple à partir des premiers chiffres
    if (!digits) return 'CARD'; // par défaut si rien
    if (/^4/.test(digits)) return 'VISA'; // commence par 4 => VISA
    if (/^5[1-5]/.test(digits)) return 'MASTERCARD'; // commence par 51-55 => Mastercard
    if (/^3[47]/.test(digits)) return 'AMEX'; // commence par 34 ou 37 => American Express
    return 'CARD'; // fallback générique
  }

  function updateNumberDisplay() { // met à jour le visuel du numéro et la marque
    const raw = cardNumber.value.replace(/\D/g, ''); // numéro brut sans séparateurs
    const formatted = formatCardNumber(cardNumber.value); // numéro formaté
    cardNumber.value = formatted; // applique le format dans l'input (corrige la saisie)
    visualNumber.textContent = formatted || '•••• •••• •••• ••••'; // met à jour l'affichage visuel
    brandTag.textContent = detectBrand(raw); // met à jour la marque détectée
  }

  function updateCVCDisplay() { // met à jour le CVC sur la carte visuelle
    const v = formatCVC(cvc.value);
    visualCVC.textContent = v || '•••';
  }

  function updateNameDisplay() { // met à jour le nom du titulaire sur la carte visuelle
    const v = (cardName.value || '').toUpperCase(); // majuscules ou valeur par défaut
    visualName.textContent = v || 'NOM PRÉNOM'; // applique ou placeholder
  }

  function formatExpiry(v) { // formate la date d'expiration en MM/AA
    const digits = v.replace(/\D/g, '').slice(0,4); // garde jusqu'à 4 chiffres
    if (digits.length >= 3) return digits.slice(0,2) + '/' + digits.slice(2); // insère '/'
    if (digits.length >= 1 && v.length === 2 && !v.includes('/')) return digits + '/'; // aide lors de la saisie
    return digits; // sinon retourne les chiffres (partiels)
  }

  function updateExpiryDisplay() { // applique format et met à jour le visuel
    expiry.value = formatExpiry(expiry.value); // corrige la valeur de l'input
    visualExpiry.textContent = expiry.value || 'MM/AA'; // met à jour visuel ou placeholder
  }

  // retourne une date d'expiration valide non expirée au format "MM/AA"
  function getValidExpiryDate(addMonths = 12) {
    const d = new Date();
    d.setMonth(d.getMonth() + addMonths);
    const mm = String(d.getMonth() + 1).padStart(2, '0'); // mois 1-12
    const yy = String(d.getFullYear() % 100).padStart(2, '0'); // deux derniers chiffres de l'année
    return `${mm}/${yy}`;
  }

  // validation stricte de l'expiry : format MM/AA, mois entre 01 et 12 et non expirée
  function validateExpiry(exp) {
    if (!exp || typeof exp !== 'string') return { valid: false, message: "Date d'expiration manquante." };
    const m = exp.trim();
    if (!/^\d{2}\/\d{2}$/.test(m)) {
      return { valid: false, message: "Date d'expiration invalide (format MM/AA)." };
    }
    const parts = m.split('/');
    const mm = parseInt(parts[0], 10);
    const yy = parseInt(parts[1], 10);
    if (isNaN(mm) || isNaN(yy) || mm < 1 || mm > 12) {
      return { valid: false, message: "Date d'expiration invalide (format MM/AA)." };
    }
    // Construire la date de fin du mois d'expiration (dernier instant du mois)
    const fullYear = 2000 + yy;
    // JS: jour 0 du mois suivant = dernier jour du mois courant
    const expiryLastMs = new Date(fullYear, mm, 0, 23, 59, 59, 999).getTime();
    if (expiryLastMs < Date.now()) {
      return { valid: false, message: "La carte est expirée." };
    }
    return { valid: true };
  }

  // Events
  if (cardNumber) cardNumber.addEventListener('input', updateNumberDisplay); // écoute saisie numéro
  if (cardName) cardName.addEventListener('input', updateNameDisplay); // écoute saisie nom
  if (expiry) expiry.addEventListener('input', updateExpiryDisplay); // écoute saisie expiry
  if (cvc) cvc.addEventListener('input', updateCVCDisplay); // écoute saisie CVC

  // pré-remplir l'expiry avec une date valide au focus si vide
  if (expiry) {
    expiry.addEventListener('focus', function () {
      if (!expiry.value.trim()) {
        expiry.value = getValidExpiryDate(12); // par défaut +12 mois
        updateExpiryDisplay();
      }
    });
  }

  // Basic client-side validation on submit
  if (form) {
    form.addEventListener('submit', function (e) { // validation avant envoi
      errorArea.textContent = ''; // efface les erreurs précédentes
      const digits = (cardNumber.value || '').replace(/\D/g, ''); // numéro brut
      const exp = expiry.value || ''; // expiry saisi
      const name = (cardName.value || '').trim(); // nom saisi
      const amountVal = amountEl ? amountEl.value.replace(',', '.').trim() : ''; // montant saisi (optionnel)

      if (digits.length < 13 || digits.length > 19) { // contrôle longueur carte
        e.preventDefault(); // stop envoi
        errorArea.textContent = 'Numéro de carte invalide.';
        cardNumber.focus(); // focus sur le champ
        return;
      }

      // validation stricte expiry : format + non expirée
      const expiryCheck = validateExpiry(exp);
      if (!expiryCheck.valid) {
        e.preventDefault();
        errorArea.textContent = expiryCheck.message;
        expiry.focus();
        return;
      }

      if (!name) { // nom obligatoire
        e.preventDefault();
        errorArea.textContent = 'Indiquez le nom du titulaire.'; // message erreur
        cardName.focus();
        return;
      }

      if (amountEl) {
        const num = parseFloat(amountVal);
        if (isNaN(num) || num <= 0) {
          e.preventDefault();
          errorArea.textContent = 'Montant invalide.';
          amountEl.focus();
          return;
        }
      }

      // en local, empêcher envoi réel par sécurité (optionnel)
      // e.preventDefault();
      // errorArea.textContent = 'Test local : soumission bloquée.';
    });
  }

  // initialisation visuelle
  updateNumberDisplay(); // initialise affichage numéro
  updateNameDisplay(); // initialise affichage nom
  updateExpiryDisplay(); // initialise affichage expiry
})(); // fin IIFE : exécution immédiate et isolation des variables
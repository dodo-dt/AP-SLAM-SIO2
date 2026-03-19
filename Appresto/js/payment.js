// Prévisualisation du numéro, titulaire, expiry et mise à jour du montant
(function () {
  const el = id => document.getElementById(id);

  const cardNumber = el('cardNumber');
  const cardName = el('cardName');
  const expiry = el('expiry');
  const amount = el('amount');
  const cvc = el('cvc');

  const visualNumber = el('visualNumber');
  const visualName = el('visualName');
  const visualExpiry = el('visualExpiry');
  const brandTag = el('brandTag');
  const summaryAmount = el('summaryAmount');
  const submitBtn = el('submitBtn');
  const errorArea = el('errorArea');
  const form = el('paymentForm');

  function formatCardNumber(v) {
    const digits = v.replace(/\D/g, '').slice(0, 19);
    return digits.match(/.{1,4}/g)?.join(' ') || '';
  }

  function detectBrand(digits) {
    if (!digits) return 'CARD';
    if (/^4/.test(digits)) return 'VISA';
    if (/^5[1-5]/.test(digits)) return 'MASTERCARD';
    if (/^3[47]/.test(digits)) return 'AMEX';
    return 'CARD';
  }

  function updateNumberDisplay() {
    const raw = cardNumber.value.replace(/\D/g, '');
    const formatted = formatCardNumber(cardNumber.value);
    cardNumber.value = formatted;
    visualNumber.textContent = formatted || '•••• •••• •••• ••••';
    brandTag.textContent = detectBrand(raw);
  }

  function updateNameDisplay() {
    const v = (cardName.value || '').toUpperCase();
    visualName.textContent = v || 'NOM PRÉNOM';
  }

  function formatExpiry(v) {
    const digits = v.replace(/\D/g, '').slice(0,4);
    if (digits.length >= 3) return digits.slice(0,2) + '/' + digits.slice(2);
    if (digits.length >= 1 && v.length === 2 && !v.includes('/')) return digits + '/';
    return digits;
  }

  function updateExpiryDisplay() {
    expiry.value = formatExpiry(expiry.value);
    visualExpiry.textContent = expiry.value || 'MM/AA';
  }

  function updateAmountDisplay() {
    const val = amount.value.trim() || '0.00';
    const n = parseFloat(val.replace(',', '.')) || 0;
    const s = n.toFixed(2) + '€';
    summaryAmount.textContent = s;
    if (submitBtn) submitBtn.textContent = 'Payer ' + s;
  }

  // Events
  if (cardNumber) cardNumber.addEventListener('input', updateNumberDisplay);
  if (cardName) cardName.addEventListener('input', updateNameDisplay);
  if (expiry) expiry.addEventListener('input', updateExpiryDisplay);
  if (amount) amount.addEventListener('input', updateAmountDisplay);

  // Basic client-side validation on submit
  if (form) {
    form.addEventListener('submit', function (e) {
      errorArea.textContent = '';
      const digits = (cardNumber.value || '').replace(/\D/g, '');
      const exp = expiry.value || '';
      const name = (cardName.value || '').trim();
      const amt = parseFloat((amount.value || '').replace(',', '.')) || 0;

      if (digits.length < 13 || digits.length > 19) {
        e.preventDefault();
        errorArea.textContent = 'Numéro de carte invalide.';
        cardNumber.focus();
        return;
      }
      if (!/^\d{2}\/\d{2}$/.test(exp)) {
        e.preventDefault();
        errorArea.textContent = "Date d'expiration invalide (format MM/AA).";
        expiry.focus();
        return;
      }
      if (!name) {
        e.preventDefault();
        errorArea.textContent = 'Indiquez le nom du titulaire.';
        cardName.focus();
        return;
      }
      if (amt <= 0) {
        e.preventDefault();
        errorArea.textContent = 'Montant invalide.';
        amount.focus();
        return;
      }
      // en local, empêcher envoi réel par sécurité (optionnel)
      // e.preventDefault();
      // errorArea.textContent = 'Test local : soumission bloquée.';
    });
  }

  // initialisation visuelle
  updateNumberDisplay();
  updateNameDisplay();
  updateExpiryDisplay();
  updateAmountDisplay();
})();
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

  function formatCardNumber(v) { // formate un string en groupes de 4 chiffres
    const digits = v.replace(/\D/g, '').slice(0, 19); // garde uniquement les chiffres et limite à 19
    return digits.match(/.{1,4}/g)?.join(' ') || ''; // regroupe par 4 et joint par des espaces, ou retourne vide
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

  // Events
  if (cardNumber) cardNumber.addEventListener('input', updateNumberDisplay); // écoute saisie numéro
  if (cardName) cardName.addEventListener('input', updateNameDisplay); // écoute saisie nom
  if (expiry) expiry.addEventListener('input', updateExpiryDisplay); // écoute saisie expiry

  // Basic client-side validation on submit
  if (form) {
    form.addEventListener('submit', function (e) { // validation avant envoi
      errorArea.textContent = ''; // efface les erreurs précédentes
      const digits = (cardNumber.value || '').replace(/\D/g, ''); // numéro brut
      const exp = expiry.value || ''; // expiry saisi
      const name = (cardName.value || '').trim(); // nom saisi

      if (digits.length < 13 || digits.length > 19) { // contrôle longueur carte
        e.preventDefault(); // stop envoi
        errorArea.textContent = 'Numéro de carte invalide.'; // message erreur
        cardNumber.focus(); // focus sur le champ
        return;
      }
      if (!/^\d{2}\/\d{2}$/.test(exp)) { // vérifie format MM/AA
        e.preventDefault(); // stop envoi
        errorArea.textContent = "Date d'expiration invalide (format MM/AA)."; // message erreur
        expiry.focus(); // focus expiry
        return;
      }
      if (!name) { // nom obligatoire
        e.preventDefault();
        errorArea.textContent = 'Indiquez le nom du titulaire.'; // message erreur
        cardName.focus();
        return;
      }
      if (amt <= 0) { // montant positif requis
        e.preventDefault();
        errorArea.textContent = 'Montant invalide.'; // message erreur
        amount.focus();
        return;
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
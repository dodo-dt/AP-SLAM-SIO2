// Validation et logique de paiement gerees cote PHP (process_payment.php).
// Ce fichier ne contient plus d'animations ni de previsualisation dynamique.
(function () {
  const form = document.getElementById('paymentForm');
  if (!form) return;

  const onlyDigits = function (value, maxLen) {
    return (value || '').replace(/\D/g, '').slice(0, maxLen);
  };

  const cardNumber = document.getElementById('cardNumber');
  const expiry = document.getElementById('expiry');
  const cvc = document.getElementById('cvc');

  if (cardNumber) {
    cardNumber.addEventListener('input', function () {
      const digits = onlyDigits(cardNumber.value, 19);
      cardNumber.value = digits.replace(/(.{4})/g, '$1 ').trim();
    });
  }

  if (expiry) {
    expiry.addEventListener('input', function () {
      const digits = onlyDigits(expiry.value, 4);
      expiry.value = digits.length > 2 ? digits.slice(0, 2) + '/' + digits.slice(2) : digits;
    });
  }

  if (cvc) {
    cvc.addEventListener('input', function () {
      cvc.value = onlyDigits(cvc.value, 4);
    });
  }
})();
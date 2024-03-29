// Récupérer la clé Stripe depuis la balise meta
var stripeKey = document.querySelector('meta[name="stripe-key"]').getAttribute('content');
// Initialiser l'objet Stripe avec la clé récupérée
var stripe = Stripe(stripeKey);

var elements = stripe.elements();
var cardElement = elements.create('card');

cardElement.mount('#card-element');

var form = document.getElementById('payment-form');

form.addEventListener('submit', function(event) {
    event.preventDefault();
    
    stripe.createPaymentMethod({
        type: 'card',
        card: cardElement
    }).then(function(result) {
        if (result.error) {
            var errorElement = document.getElementById('card-errors');
            errorElement.textContent = result.error.message;
        } else {
            var paymentMethodId = result.paymentMethod.id;
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'payment_method_id');
            hiddenInput.setAttribute('value', paymentMethodId);
            form.appendChild(hiddenInput);
            form.submit();
        }
    });
});

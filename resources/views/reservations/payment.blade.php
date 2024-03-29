<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture de réservation</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Intégration de Stripe Elements -->
    <script src="https://js.stripe.com/v3/"></script>
    <link rel="stylesheet" href="{{ asset('css/utilisateur.css') }}">
    <!-- Clé Stripe -->
    <meta name="stripe-key" content="{{ config('services.stripe.key') }}">
</head>

<body style="background-image: url('/images/mercedes2.jpeg');">
    <div class="form_container">
        <h3 class="text-center">Payer</h3>

        <div class="form login_form">
            <div class="input_box">
                <p style="font-weight: bold; color:red;">Lieu de départ: </p>
                <p>{{ $reservation->lieu_depart }}</p>
            </div>
            <div class="input_box">
                <p style="font-weight: bold; color:red;">Lieu d'arrivée: </p>
                <p>{{ $reservation->lieu_arrivee }}</p>
            </div>
            <div class="input_box">
                <p style="font-weight: bold; color:red;">Heure de début: </p>
                <p>{{ $reservation->heure_debut }}</p>
            </div>
            <div class="input_box">
                <p style="font-weight: bold; color:red;">Heure de fin: </p>
                <p>{{ $reservation->heure_fin }}</p>
            </div>
            <div class="input_box">
                <p style="font-weight: bold; color:red;">Prix total: </p>
                <p>{{ $reservation->prix }} FCFA</p>
            </div>
            <br>
           
                <form id="payment-form" action="{{ route('payment.process') }}" method="post">
                    @csrf
                    <div id="card-element" class="input_box">
                        <!-- Element de carte de Stripe -->
                    </div>
                    <button id="submit-payment" class="button">Payer maintenant</button>
                    <div id="card-errors" role="alert" class="text-danger mt-2"></div>
                </form>
            
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Script JavaScript pour gérer le formulaire de paiement -->
    <script src="{{ asset('js/payment.js') }}"></script>
    <script>
        // Récupérer la clé Stripe depuis la balise meta
        var stripeKey = document.querySelector('meta[name="stripe-key"]').getAttribute('content');
        // Initialiser l'objet Stripe avec la clé récupérée
        var stripe = Stripe(stripeKey);
    </script>
</body>

</html>
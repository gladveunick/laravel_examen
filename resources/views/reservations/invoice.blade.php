<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Document</title>
</head>

<body style="background-image: url('/images/mercedes2.jpeg');">

    <!-- <header class="header">
        <nav class="nav">
            <a href="#" class="nav_logo">Yobalema</a>

            <ul class="nav_items">
                <li class="nav_item">
                    <a href="#" class="nav_link">Home</a>
                    <a href="#" class="nav_link">Tarification</a>
                    <a href="#" class="nav_link">Vehicule</a>
                </li>
            </ul>

            <a href="{{ route('login') }}" class="button">Login</a>
        </nav>
    </header> -->



    <br>
    <div class="form_container">

        <!-- login formulaire -->

        <div class="form login_form">
            <h2>Facture de réservation</h2>
            <br>

            <div class="input_box">
                <p style="font-weight: bold; color:red;">Lieu de départ :</p>
                <p>{{ $reservation->lieu_depart }}</p>
            </div>

            <div class="input_box">
                <p style="font-weight: bold; color:red;">Lieu d'arrivée : </p>
                <p>{{ $reservation->lieu_arrivee }}</p>
            </div>

            <div class="input_box">
                <p style="font-weight: bold; color:red;">Heure de début : </p>
                <p>{{ $reservation->heure_debut }}</p>
            </div>

            <div class="input_box">
                <p style="font-weight: bold; color:red;">Heure de fin :</p>
                <p>{{ $reservation->heure_fin }}</p>
            </div>

            <div class="input_box">
                <p style="font-weight: bold; color:red;">Prix total :</p>
                <p>{{ $reservation->prix }} FCFA</p>
            </div>

            <div class="input_box">
                <a href="{{ route('reservations.edit', ['reservation' => $reservation->id]) }}" class="btn btn-warning">Modifier</a>
            </div>

            <!-- {{ route('reservation.processPayment', ['id' => $reservation->id]) }} -->

            <form action="{{ route('reservation.checkout', ['id' => $reservation->id]) }}" method="get">
                @csrf
                <button type="submit" class="button">Payer</button>
            </form>




        </div>
    </div>


    <!-- <form method="POST" action="{{ route('register') }}">
        @csrf
            <div>
                <label for="nom">Nom</label>
                <input id="nom" type="text" class="form-control" name="nom" value="{{ old('nom') }}" required autofocus>
            </div>

            <div>
                <label for="prenom">Prenom</label>
                <input id="prenom" type="text" class="form-control" name="prenom" value="{{ old('prenom') }}" required >
            </div>

            <div>
                <label for="email">Email</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
            </div>

            <div>
                <label for="password">Password</label>
                <input id="password" type="password" class="form-control" name="password" required>
            </div>

            <div>
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
            </div>

            <div>
                <button type="submit">Register</button>
            </div>
        </form> -->
</body>

</html>
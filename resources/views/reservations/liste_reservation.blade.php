<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <title>Mes reservations</title>
</head>

<body>


    <div class="tabular--wrapper">
        <h3 class="main--title">
            Mes Réservations
        </h3>
        <div class="table-container">

            <br>
            <br>
            @if ($reservations->isEmpty())
            <p>Vous n'avez pas encore effectué de réservation.</p>
            <a href="{{route("user-accueil")}}" class="button">Retour</a>
            @else
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Lieu de Départ</th>
                        <th>Lieu d'Arrivée</th>
                        <th>Date de Début</th>
                        <th>Date de Fin</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservations as $reservation)
                    <tr>
                        <td>{{ $reservation->id }}</td>
                        <td>{{ $reservation->lieu_depart }}</td>
                        <td>{{ $reservation->lieu_arrivee }}</td>
                        <td>{{ $reservation->heure_debut }}</td>
                        <td>{{ $reservation->heure_fin }}</td>
                        <td class="btn-boutton">

                            <a href="{{ route('chauffeur.showRatingForm', ['chauffeur' => $reservation->chauffeur_id]) }}" class="btn btn-primary">Noter le Chauffeur</a>


                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modifier la réservation</title>
    <!-- Lien css -->
    <link rel="stylesheet" href="{{ asset('css/utilisateur.css') }}">
</head>

<body style="background-image: url('img/car1.png');">

    <section class="home">
        <div class="form_container">
            <i class="uil uil-times form_close"></i>
            <!-- Formulaire de modification -->
            <div class="form edit_form">
                <form method="POST" action="{{ route('reservations.update', $reservation->id) }}">
                    @csrf
                    @method('PUT')
                    <h2>Modifier la réservation</h2>
                    <br>
                    @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <div class="erreur">{{ session('error') }}</div>
                    <div class="input_box">
                        <input type="text" name="lieu_depart" id="lieu_depart" class="form-control" placeholder="Lieu de départ" value="{{ $reservation->lieu_depart }}" required>
                    </div>
                    <br>
                    <div class="input_box">
                        <input type="text" name="lieu_arrivee" id="lieu_arrivee" class="form-control" placeholder="Lieu d'arrivée" value="{{ $reservation->lieu_arrivee }}" required>
                    </div>
                    <br>
                    <div class="input_box">
                        <label for="heure_debut">Heure de début:</label>
                        <input type="datetime-local" name="heure_debut" id="heure_debut" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime($reservation->heure_debut)) }}" required>
                    </div>
                    <br>
                    <div class="input_box">
                        <label for="heure_fin">Heure de fin:</label>
                        <input type="datetime-local" name="heure_fin" id="heure_fin" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime($reservation->heure_fin)) }}" required>
                    </div>
                    <br>
                    <div class="input_box">
                        <label for="vehicule_id">Véhicule:</label>
                        <select name="vehicule_id" id="vehicule_id" class="form-control" required>
                            @foreach ($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" @if($vehicle->id == $reservation->vehicule_id) selected @endif>{{ $vehicle->model }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Ajout du champ prix -->
                    <button type="submit" class="button">Enregistrer</button>
                    <input type="hidden" name="prix" value="{{ $reservation->prix }}">
                </form>
            </div>
        </div>
    </section>

</body>

</html>

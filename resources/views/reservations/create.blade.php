<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réserver un véhicule</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/utilisateur.css') }}">
</head>

<body style="background-image: url('img/car1.png');">

    <section class="home">
        <div class="form_container">
            <i class="uil uil-times form_close"></i>
            <div class="form login_form">
                <form id="reservationForm" method="POST" action="{{ route('reservations.store') }}">
                    @csrf
                    <h2>Réserver un véhicule</h2>
                    <br>
                    @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    <div class="alert alert-success">
                        <p style="color:red;">{{ session('error') }}</p>
                    </div>
                    @endif
                    <div class="erreur">{{ session('error') }}</div>
                    <div class="input_box">
                        <input type="text" name="lieu_depart" id="lieu_depart" class="form-control" placeholder="Lieu de départ" required>
                    </div>
                    <div class="input_box">
                        <input type="text" name="lieu_arrivee" id="lieu_arrivee" class="form-control" placeholder="Lieu d'arrivée" required>
                    </div>

                    <div class="input_box">
                        <label for="heure_debut">Heure de début </label>
                        <input type="datetime-local" name="heure_debut" id="heure_debut" class="form-control" required>
                    </div>
                    <br>
                    <div class="input_box">
                        <label for="heure_fin">Heure de fin </label>
                        <input type="datetime-local" name="heure_fin" id="heure_fin" class="form-control" required>
                    </div>
                    <br>
                    <div class="input_box">
                        <label for="vehicule_id">Véhicule:</label>
                        <select name="vehicule_id" id="vehicule_id" class="form-control" required>
                            <option value="">Sélectionner un véhicule</option>
                            @foreach ($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}">{{ $vehicle->model }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="button">Réserver</button>
                    <div class="input_box">
                        <input type="hidden" name="prix" value="{{ $prix }}">
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('reservationForm');
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                var lieuDepart = document.getElementById('lieu_depart').value;
                var lieuArrivee = document.getElementById('lieu_arrivee').value;
                var heureDebut = document.getElementById('heure_debut').value;
                var heureFin = document.getElementById('heure_fin').value;

                geocodeAddress(lieuDepart, function(coordsDepart) {
                    geocodeAddress(lieuArrivee, function(coordsArrivee) {
                        var distanceKm = calculateDistance(coordsDepart, coordsArrivee);
                        var dureeHours = calculateDuration(heureDebut, heureFin); // Durée en heures
                        var prix = calculatePrice(distanceKm, dureeHours);

                        var distanceInput = document.createElement('input');
                        distanceInput.type = 'hidden';
                        distanceInput.name = 'distance';
                        distanceInput.value = distanceKm;
                        form.appendChild(distanceInput);

                        // Ajoutez le champ de durée caché
                        var dureeInput = document.createElement('input');
                        dureeInput.type = 'hidden';
                        dureeInput.name = 'duree';
                        dureeInput.value = dureeHours;
                        form.appendChild(dureeInput);

                        // Ajoutez le champ de prix caché
                        var prixInput = document.createElement('input');
                        prixInput.type = 'hidden';
                        prixInput.name = 'prix';
                        prixInput.value = prix;
                        form.appendChild(prixInput);

                        form.submit();
                    });
                });
            });

            // Fonction pour calculer la distance entre deux points en kilomètres en utilisant la formule de Haversine
            function calculateDistance(coordsDepart, coordsArrivee) {
                var R = 6371; // Rayon de la Terre en km
                var dLat = deg2rad(coordsArrivee.latitude - coordsDepart.latitude);
                var dLon = deg2rad(coordsArrivee.longitude - coordsDepart.longitude);
                var a =
                    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(deg2rad(coordsDepart.latitude)) * Math.cos(deg2rad(coordsArrivee.latitude)) *
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
                var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                var distance = R * c; // Distance en km
                return distance;
            }

            // Fonction pour convertir les degrés en radians
            function deg2rad(deg) {
                return deg * (Math.PI / 180);
            }

            // Fonction pour calculer la durée de la location en heures
            function calculateDuration(heureDebut, heureFin) {
                var debut = new Date(heureDebut);
                var fin = new Date(heureFin);
                var duree = (fin - debut) / (1000 * 60 * 60); // Durée en heures
                return duree;
            }

            // Fonction pour calculer le prix en fonction de la distance et de la durée de location
            function calculatePrice(distanceKm, dureeHours) {
                var prixParKm = 50; // Prix par kilomètre
                var prixParHeure = 25; // Prix par heure de location
                var prixDistance = distanceKm * prixParKm;
                var prixDuree = dureeHours * prixParHeure;
                var prixTotal = prixDistance + prixDuree;
                return prixTotal;
            }

            // Fonction pour géocoder une adresse et obtenir ses coordonnées géographiques
            function geocodeAddress(address, callback) {
                fetch('https://nominatim.openstreetmap.org/search?q=' + address + '&format=json')
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            var coords = {
                                latitude: parseFloat(data[0].lat),
                                longitude: parseFloat(data[0].lon)
                            };
                            callback(coords);
                        } else {
                            console.error('Adresse non trouvée');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur de géocodage:', error);
                    });
            }
        });
    </script>

</body>

</html>
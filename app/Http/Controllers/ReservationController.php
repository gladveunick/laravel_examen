<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Reservation;
use App\Models\Vehicule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class ReservationController extends Controller
{

    private function geocodeAddress($address)
    {
        // URL de l'API de géocodage de OpenStreetMap
        $url = 'https://nominatim.openstreetmap.org/search?q=' . urlencode($address) . '&format=json';

        // Effectuer une requête HTTP GET à l'API de géocodage
        $response = Http::get($url);

        // Vérifier si la requête a réussi et si des résultats ont été retournés
        if ($response->successful() && !empty($response->json())) {
            // Récupérer les coordonnées géographiques du premier résultat
            $latitude = $response->json()[0]['lat'];
            $longitude = $response->json()[0]['lon'];

            // Retourner les coordonnées géographiques sous forme de tableau associatif
            return ['lat' => $latitude, 'lon' => $longitude];
        } else {
            // En cas d'échec de la requête ou de résultats vides, retourner null
            return null;
        }
    }

    // Fonction pour calculer la distance entre deux points en kilomètres en utilisant la formule de Haversine
    private function calculateDistance($coordsDepart, $coordsArrivee)
    {
        // Rayon de la Terre en kilomètres
        $radius = 6371;

        // Convertir les degrés en radians
        $latFrom = deg2rad($coordsDepart['lat']);
        $lonFrom = deg2rad($coordsDepart['lon']);
        $latTo = deg2rad($coordsArrivee['lat']);
        $lonTo = deg2rad($coordsArrivee['lon']);

        // Calculer les différences de latitude et de longitude
        $latDiff = $latTo - $latFrom;
        $lonDiff = $lonTo - $lonFrom;

        // Calculer la distance en utilisant la formule de Haversine
        $a = sin($latDiff / 2) * sin($latDiff / 2) +
            cos($latFrom) * cos($latTo) *
            sin($lonDiff / 2) * sin($lonDiff / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $radius * $c;

        return $distance;
    }

    // Fonction pour calculer le prix en fonction de la distance et de la durée de location
    private function calculatePrice($distanceKm, $dureeHours)
    {
        $prixParKm = 500; // Prix par kilomètre
        $prixParHeure = 100; // Prix par heure de location
        $prixDistance = $distanceKm * $prixParKm;
        $prixDuree = $dureeHours * $prixParHeure;
        $prixTotal = $prixDistance + $prixDuree;
        return $prixTotal;
    }

    //fonction qui affiche la liste des reservations de l'utilisateur qui est connecter
    public function liste()
    {
        // Récupérer l'utilisateur actuellement connecté
        $user = auth()->user();

        // Vérifier si l'utilisateur est connecté
        if ($user) {
            // Récupérer les réservations de l'utilisateur
            $reservations = $user->reservations;

            // Retourner la vue avec les réservations de l'utilisateur
            return view('reservations.liste_reservation', compact('reservations'));
        } else {
            // Gérer le cas où l'utilisateur n'est pas connecté
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à cette page');
        }
    }
    // Fonction affichant le formulaire de réservation
    public function create()
    {
        $vehicles = Vehicule::where('statut', 'disponible')->get();

        $prix = 0;

        return view('reservations.create', compact('vehicles', 'prix'));
    }


    //Fonction qui affiche les reservations de l'utilisateur

    public function mesReservations()
    {
        // Récupérer l'utilisateur actuellement connecté
        $user = auth()->user();

        // Vérifier si l'utilisateur est connecté
        if ($user) {
            // Récupérer les réservations de l'utilisateur
            $reservations = $user->reservations;

            // Passer les réservations à la vue
            return view('reservations.mes_reservations', compact('reservations'));
        } else {
            // Gérer le cas où l'utilisateur n'est pas connecté
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à cette page');
        }
    }

    // Fonction de stockage de la réservation
    public function store(Request $request)
    {
        // Valider les données du formulaire
        $request->validate([
            'lieu_depart' => 'required',
            'lieu_arrivee' => 'required',
            'heure_debut' => ['required', 'date', function ($attribute, $value, $fail) {
                // Vérifier si l'heure de début est dans le futur
                if (Carbon::parse($value)->isPast()) {
                    $fail('L\'heure de début doit être dans le futur.');
                }
            }],
            'heure_fin' => ['required', 'date', 'after:heure_debut', function ($attribute, $value, $fail) use ($request) {
                // Vérifier si l'heure de fin est postérieure à l'heure de début
                if (Carbon::parse($value)->lte(Carbon::parse($request->heure_debut))) {
                    $fail('L\'heure de fin doit être postérieure à l\'heure de début.');
                }
            }],
            'vehicule_id' => 'required|exists:vehicules,id',
        ]);

        // Géocoder les adresses de départ et d'arrivée pour obtenir les coordonnées
        $coordsDepart = $this->geocodeAddress($request->lieu_depart);
        $coordsArrivee = $this->geocodeAddress($request->lieu_arrivee);

        // Vérifier si les adresses ont pu être géocodées
        if ($coordsDepart && $coordsArrivee) {
            // Calculer la distance entre les deux points en kilomètres
            $distance = $this->calculateDistance($coordsDepart, $coordsArrivee);

            // Calculer la durée de location en heures
            $heureDebut = Carbon::parse($request->heure_debut);
            $heureFin = Carbon::parse($request->heure_fin);
            $dureeHours = $heureFin->diffInHours($heureDebut);

            // Prix par kilomètre (ajustez selon vos tarifs)
            $prixParKm = 50;

            // Calculer le prix en fonction de la distance et de la durée de location
            $prix = intval($distance * $prixParKm * $dureeHours);


            // Récupérer l'ID de l'utilisateur authentifié
            $user_id = auth()->id();

            // Récupérez l'ID du véhicule sélectionné par l'utilisateur depuis le formulaire
            $vehiculeId = $request->vehicule_id;


            // Récupérez l'ID du chauffeur associé au véhicule à partir de la table des véhicules
            $vehicule = Vehicule::findOrFail($vehiculeId);
            $chauffeurId = $vehicule->chauffeur_id;


            // Créer une nouvelle réservation avec les données du formulaire
            $reservation = Reservation::create([
                'lieu_depart' => $request->lieu_depart,
                'lieu_arrivee' => $request->lieu_arrivee,
                'heure_debut' => $request->heure_debut,
                'heure_fin' => $request->heure_fin,
                'vehicule_id' => $request->vehicule_id,
                'user_id' => $user_id, // Utiliser l'ID du client authentifié
                'prix' => $prix, // Utiliser le prix calculé
            ]);

            // Mettez à jour le champ chauffeur_id dans la réservation avec l'ID du chauffeur récupéré
            $reservation->chauffeur_id = $chauffeurId;


            // Récupérez le chauffeur associé au véhicule réservé
            $vehicule = Vehicule::findOrFail($request->vehicule_id);
            $chauffeur_id = $vehicule->chauffeur_id;

            // Associez le chauffeur à la réservation
            $reservation->update(['chauffeur_id' => $chauffeur_id]);

            // Rediriger vers la liste des réservations avec un message de succès
            return redirect()->route('reservation.showInvoice', ['id' => $reservation->id])
                ->with('success', 'Réservation créée avec succès.');
        } else {
            // Gérer le cas où une adresse n'a pas pu être géocodée
            return back()->with('error', 'Erreur lors de la géocodage des adresses. Veuillez réessayer.');
        }
    }


    // Fonction pour afficher la facture
    public function showInvoice($id)
    {
        $reservation = Reservation::findOrFail($id);
        return view('reservations.invoice', compact('reservation'));
    }

    // Méthode pour afficher le formulaire de modification de la réservation
    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);
        $vehicles = Vehicule::all(); // Récupérez tous les véhicules
        return view('reservations.edit', compact('reservation', 'vehicles'));
    }
    // Méthode pour mettre à jour la réservation
    public function update(Request $request, $id)
    {
        // Valider les données du formulaire
        $request->validate([
            'lieu_depart' => 'required',
            'lieu_arrivee' => 'required',
            'heure_debut' => ['required', 'date'],
            'heure_fin' => ['required', 'date', 'after:heure_debut'],
            'vehicule_id' => 'required|exists:vehicules,id',
            'prix' => 'required|numeric',
        ]);

        // Trouver la réservation à mettre à jour
        $reservation = Reservation::findOrFail($id);

        // Mettre à jour les données de la réservation
        $reservation->lieu_depart = $request->lieu_depart;
        $reservation->lieu_arrivee = $request->lieu_arrivee;
        $reservation->heure_debut = $request->heure_debut;
        $reservation->heure_fin = $request->heure_fin;
        $reservation->vehicule_id = $request->vehicule_id;
        $reservation->prix = $request->prix;

        // Enregistrer les modifications
        $reservation->save();

        // Rediriger avec un message de succès
        return redirect()->route('reservation.showInvoice', ['id' => $reservation->id])
            ->with('success', 'Réservation modifiée avec succès.');
    }




    // Méthode pour afficher la page de paiement de la réservation
    public function pay($id)
    {
        $reservation = Reservation::findOrFail($id);
        return view('reservations.payment', compact('reservation'));
    }

    // Méthode pour traiter le paiement de la réservation
    public function processPayment(Request $request, $id)
    {
        // Ajoutez votre logique de paiement ici
        // ...

        return redirect()->route('dashboard')->with('success', 'Paiement effectué avec succès.');
    }

    public function checkout()
    {
        return view('reservations.payment');
    }





    public function show($id)
    {
        // Récupérer la réservation avec l'identifiant donné
        $reservation = Reservation::findOrFail($id);

        // Passer la réservation à la vue
        return view('reservations.show', compact('reservation'));
    }
}

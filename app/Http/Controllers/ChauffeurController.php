<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chauffeur;
use App\Models\Vehicule;
use App\Models\DriverRating;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class ChauffeurController extends Controller
{



    public function create()
    {

        // Récupérer uniquement les véhicules qui ne sont pas encore associés à un chauffeur
        $vehicules = \App\Models\Vehicule::whereDoesntHave('chauffeur')->get();
        return view('chauffeur.create', compact('vehicules'));
        // $vehicules = Vehicule::all();
        // return view('chauffeur.create', compact('vehicules'));
    }

    //fonction qui affiche la liste des chauffeur dans la base

    public function index()
    {
        $chauffeurs = Chauffeur::all();
        $totalVehicules = Vehicule::count();
        $totalusers = User::count();
        $totalreservations = Reservation::count();
        $totalChauffeurs = Chauffeur::count();
        return view('chauffeur.index', compact('chauffeurs', 'totalVehicules', 'totalChauffeurs', 'totalusers', 'totalreservations'));
    }

    public function commentaire()
    {
        $chauffeurs = Chauffeur::all();
        $totalVehicules = Vehicule::count();
        $totalusers = User::count();
        $totalreservations = Reservation::count();
        $totalChauffeurs = Chauffeur::count();
        $commentaires = DriverRating::all();
        return view('chauffeur.note', compact('chauffeurs', 'totalVehicules', 'totalChauffeurs', 'totalusers', 'totalreservations', 'commentaires'));
    }

    //fonction qui affiche les details d'un chauffeur specifique

    public function show(Chauffeur $chauffeur)
    {
        return view('chauffeur.show', compact('chauffeur'));
    }

    //fonction qui cree un chauffeur
    public function store(Request $request)
    {
        // Validation des données du formulaire
        $validator = Validator::make($request->all(), [
            'nom' => 'required',
            'prenom' => 'required',
            'experience' => 'required',
            'numero_permis' => [
                'required',
                'unique:chauffeurs,numero_permis',
            ],
            'date_emission' => 'required',
            'expiration' => 'required|date|after_or_equal:' . now()->addYear(),
            'categorie' => [
                'required',
                function ($attribute, $value, $fail) {
                    $categories = ['A', 'B', 'C', 'D', 'E'];
                    if (!in_array(strtoupper($value), $categories)) {
                        $fail($attribute . ' n\'est pas une catégorie de permis valide.');
                    }
                },
            ],
            'contrat' => 'required',
        ]);

        // Vérifier la validation
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Récupérer uniquement les véhicules qui ne sont pas encore associés à un chauffeur
        $vehiculesDisponibles = \App\Models\Vehicule::whereDoesntHave('chauffeur')->get();

        // Vérifier si des véhicules sont disponibles
        if ($vehiculesDisponibles->isEmpty()) {
            // Si aucun véhicule disponible n'est trouvé, retourner à la page précédente avec un message d'erreur
            return redirect()->back()->withErrors(['vehicule_id' => 'Aucun véhicule disponible pour l\'attribution.'])->withInput();
        }

        // Création du chauffeur avec les données du formulaire
        $chauffeur = Chauffeur::create($request->all());

        // Associer le premier véhicule disponible au chauffeur
        $chauffeur->vehicule()->associate($vehiculesDisponibles->first());
        $chauffeur->save();

        // Récupérer l'ID du véhicule associé au chauffeur depuis le formulaire
        $vehiculeId = $request->vehicule_id;

        // Mettre à jour la colonne chauffeur_id dans la table vehicules
        $vehicule = Vehicule::findOrFail($vehiculeId);
        $vehicule->update(['chauffeur_id' => $chauffeur->id]);

        // Redirection vers la page d'index des chauffeurs avec un message de succès
        return redirect()->route('chauffeur.index')
            ->with('success', 'Chauffeur créé avec succès.');
    }



    // Fonction permettant d'éditer un chauffeur
    public function edit(Chauffeur $chauffeur)
    {

        $vehicules = Vehicule::all();
        // dd($vehicules);
        return view('chauffeur.edit', compact('chauffeur', 'vehicules'));
    }

    // Fonction permettant de mettre à jour un chauffeur
    public function update(Request $request, Chauffeur $chauffeur)
    {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'experience' => 'required',
            'numero_permis' => 'required',
            'date_emission' => 'required',
            'expiration' => 'required',
            'categorie' => 'required',
            'contrat' => 'required',
            // Autres validations...
        ]);

        $chauffeur->update($request->all());

        return redirect()->route('chauffeur.index')
            ->with('success', 'Chauffeur mis à jour avec succès.');
    }

    // Fonction permettant de supprimer un chauffeur
    // Supprimer un chauffeur de la base de données
    public function destroy($id)
    {
        // Recherche du chauffeur à supprimer
        $chauffeur = Chauffeur::findOrFail($id);

        // Suppression du chauffeur
        $chauffeur->delete();

        // Redirection avec un message de succès
        return redirect()->route('chauffeur.index')->with('success', 'Chauffeur supprimé avec succès.');
    }


    //Fonction permettant de noter les chauffeurs
    public function rate(Request $request, $chauffeurId)
    {
        // Utilisez $chauffeurId pour récupérer le chauffeur à partir de la base de données
        $chauffeur = Chauffeur::findOrFail($chauffeurId);

        $request->validate([
            'note' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();

        $rating = new DriverRating([
            'note' => $request->note,
            'comment' => $request->comment,
            'chauffeur_id' => $chauffeur->id,
            'user_id' => $user->id,
        ]);

        $rating->save();

        return redirect()->back()->with('success', 'Evaluation soumise avec succès.');
    }




    public function showRatingForm($chauffeurId)
    {
        // Récupérer le chauffeur à partir de son ID
        $chauffeur = Chauffeur::findOrFail($chauffeurId);

        // Passer le chauffeur à la vue du formulaire de notation
        return view('rating-form', compact('chauffeur'));
    }
}

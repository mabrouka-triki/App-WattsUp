<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Habitation;
use App\Models\Compteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    // Affiche le formulaire et les habitations de l'utilisateur
    public function index()
    {
        // On récupère toutes les habitations de l'utilisateur connecté
        $habitations = Habitation::where('user_id', Auth::id())->get();

        return view('Client.index', compact('habitations'));
    }

    // Enregistre une nouvelle habitation
    public function store(Request $request)
    {
        $validated = $request->validate([
            'adresse_habitation' => 'required|string|max:255',
            'type_habitation' => 'required|string|in:Appartement,Maison',
            'surfaces' => 'required|integer|min:1',
            'nb_occupants' => 'required|integer|min:1',
        ]);

        $validated['user_id'] = Auth::id();

        Habitation::create($validated);

        return redirect()->route('Client.index')->with('success', 'Habitation ajoutée avec succès.');
    }

    
    public function showHabitation($id)
    {
        $habitation = Habitation::where('user_id', Auth::id())
            ->with('compteurs')
            ->findOrFail($id);

        return view('Client.show', compact('habitation'));
    }

    public function createCompteur($id)
    {
        $habitation = Habitation::where('user_id', Auth::id())->findOrFail($id);
        return view('Client.create_compteur', compact('habitation'));
    }

    public function storeCompteur(Request $request, $id)
    {
        $habitation = Habitation::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'Type_compteur' => 'required|string|in:Électricité,Gaz,Eau',
            'Reference_compteur' => 'required|string|max:100|unique:compteurs,Reference_compteur',
        ]);

        $validated['id_habitation'] = $habitation->id_habitation;

        Compteur::create($validated);

        return redirect()->route('client.habitation.show', $habitation->id_habitation)
                         ->with('success', 'Compteur ajouté avec succès.');
    }
}

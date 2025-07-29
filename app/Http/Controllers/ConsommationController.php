<?php

namespace App\Http\Controllers;

use App\Models\Compteur;
use App\Models\Consommation;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Support\Facades\Storage;

class ConsommationController extends Controller
{
    use AuthorizesRequests;
  public function index(Compteur $compteur)
{
    $compteur->load('habitation'); 
    $this->authorize('view', $compteur);

    $consommations = $compteur->consommations()
        ->orderByDesc('date_relev_consommation')
        ->get();

    $stats = $this->calculateStats($consommations); 

    return view('Client.gestionFacture', compact('compteur', 'consommations', 'stats'));
}


public function store(Request $request, Compteur $compteur)
{
    $this->authorize('update', $compteur);

    $validated = $this->validateRequest($request);

    // Crée la consommation liée au compteur, id_compteur est injecté automatiquement
    $compteur->consommations()->create($validated);

    return redirect()->back()->with('success', 'Relevé enregistré avec succès');
}


 protected function validateRequest(Request $request): array
{
    return $request->validate([
        'date_relev_consommation' => 'required|date|before_or_equal:today',
        'valeur_conso' => 'required|numeric|min:0|max:999999.99',
    ]);
}

    

    protected function calculateStats($consommations): ?array
    {
        if ($consommations->count() < 2) {
            return null;
        }

        $variations = [];
        $total = 0;

        $consommations->each(function ($item, $key) use ($consommations, &$variations, &$total) {
            if ($key > 0) {
                $variation = $consommations[$key-1]->valeur_conso - $item->valeur_conso;
                $variations[] = $variation;
                $total += $variation;
            }
        });

        $moyenne = $total / count($variations);

        return [
            'moyenne' => round($moyenne, 2),
            'max' => max($variations),
            'min' => min($variations),
            'total' => $total,
            'anomalies' => collect($variations)->filter(fn($v) => abs($v) > 2 * abs($moyenne))->count(),
            'tendance' => $total > 0 ? 'hausse' : ($total < 0 ? 'baisse' : 'stable'),
        ];
    }

public function inlineUpdate(Request $request, Compteur $compteur, Consommation $consoId)
{
    $conso = Consommation::findOrFail($consoId);
    $conso->update([
        'valeur_conso' => $request->valeur_conso
    ]);
    
    return back()->with('success', 'Consommation mise à jour!');
}

    
}



<?php

namespace App\Http\Controllers;

use App\Models\Compteur;
use App\Models\Consommation;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;

class ConsommationController extends Controller
{
    use AuthorizesRequests; // Trait permettant d’utiliser les méthodes d’autorisation de Laravel (policy)

    /**
     * Affiche les consommations d’un compteur donné ainsi que les statistiques associées.
     * 
     * @param Compteur $compteur Instance du modèle Compteur injectée automatiquement par le route model binding.
     * @return \Illuminate\View\View
     */
    public function index(Compteur $compteur)
    {
        // Charge la relation 'habitation' du compteur pour éviter les requêtes N+1.
        $compteur->load('habitation');

        // Vérifie si l’utilisateur est autorisé à consulter ce compteur (Policy 'view').
        $this->authorize('view', $compteur);

        // Récupère les consommations associées triées par date décroissante (la plus récente en premier).
        $consommations = $compteur->consommations()
            ->orderByDesc('date_relev_consommation')
            ->get();

        // Calcule les statistiques (variation, tendance, anomalies...) sur les consommations.
        $stats = $this->calculateStats($consommations);

        // Retourne la vue avec les données du compteur, des consommations et des statistiques.
        return view('Client.gestionFacture', compact('compteur', 'consommations', 'stats'));
    }

    /**
     * Enregistre une nouvelle consommation pour un compteur donné.
     * 
     * @param Request $request Requête HTTP contenant les données du formulaire.
     * @param Compteur $compteur Instance injectée via route model binding.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Compteur $compteur)
    {
        // Vérifie si l’utilisateur a le droit de modifier ce compteur (Policy 'update').
        $this->authorize('update', $compteur);

        // Valide les données du formulaire avec les règles définies.
        $validated = $this->validateRequest($request);

        // Crée une nouvelle consommation liée automatiquement au compteur (relation hasMany).
        $compteur->consommations()->create($validated);

        // Redirige vers la page précédente avec un message flash de succès.
        return redirect()->back()->with('success', 'Relevé enregistré avec succès');
    }

    /**
     * Valide les données de la requête pour l’enregistrement d’une consommation.
     * 
     * @param Request $request
     * @return array Données validées.
     */
    protected function validateRequest(Request $request): array
    {
        // Les règles garantissent que :
        // - la date est requise, bien formatée, et pas dans le futur,
        // - la valeur est un nombre positif avec une limite raisonnable.
        return $request->validate([
            'date_relev_consommation' => 'required|date|before_or_equal:today',
            'valeur_conso' => 'required|numeric|min:0|max:999999.99',
        ]);
    }

    /**
     * Calcule les statistiques à partir de la liste des consommations.
     * 
     * @param \Illuminate\Support\Collection $consommations
     * @return array|null
     */
    protected function calculateStats($consommations): ?array
    {
        // Si moins de 2 consommations, les statistiques ne sont pas pertinentes.
        if ($consommations->count() < 2) {
            return null;
        }

        $variations = []; // Stocke les différences entre les relevés successifs.
        $total = 0;       // Somme de toutes les variations (utile pour tendance et moyenne).

        // On parcourt chaque consommation pour calculer la variation avec la précédente.
        $consommations->each(function ($item, $key) use ($consommations, &$variations, &$total) {
            if ($key > 0) {
                // Calcul de la différence entre deux relevés successifs.
                $variation = $consommations[$key-1]->valeur_conso - $item->valeur_conso;
                $variations[] = $variation;
                $total += $variation;
            }
        });

        // Moyenne des variations (arrondie à 2 décimales).
        $moyenne = $total / count($variations);

        return [
            'moyenne' => round($moyenne, 2),
            'max' => max($variations), // Variation maximale enregistrée.
            'min' => min($variations), // Variation minimale enregistrée.
            'total' => $total,         // Somme des variations.
            'anomalies' => collect($variations)->filter(
                fn($v) => abs($v) > 2 * abs($moyenne) // Détecte les variations anormales (écart > 2x moyenne absolue).
            )->count(),
            'tendance' => $total > 0 ? 'hausse' : ($total < 0 ? 'baisse' : 'stable'), // Indique l’évolution générale.
        ];
    }

    /**
     * Permet de mettre à jour une consommation directement (ex : depuis une interface AJAX inline).
     * 
     * @param Request $request
     * @param Compteur $compteur
     * @param Consommation $consoId ID de la consommation à mettre à jour.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function inlineUpdate(Request $request, Compteur $compteur, Consommation $consoId)
    {
        // Recherche la consommation à partir de son ID (ou échoue avec 404 si non trouvée).
        $conso = Consommation::findOrFail($consoId);

        // Met à jour uniquement la valeur de consommation (utile pour des modifications rapides en ligne).
        $conso->update([
            'valeur_conso' => $request->valeur_conso
        ]);

        // Redirige avec un message de confirmation.
        return back()->with('success', 'Consommation mise à jour!');
    }
}

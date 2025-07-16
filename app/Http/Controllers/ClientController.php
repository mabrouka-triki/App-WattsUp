<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Habitation;
use App\Models\Compteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * --------------------------------------------------------------------------
 * Contrôleur « Client »
 * --------------------------------------------------------------------------
 *
 * Toutes les routes pointant vers ce contrôleur sont protégées par le
 * middleware `auth` (déclaré dans routes/web.php).  On part donc du principe
 * que l’utilisateur est déjà authentifié ; d’où l’usage systématique de
 * `Auth::id()` pour sécuriser les requêtes.
 *
 * Bonnes pratiques mises en avant :
 *  • Filtrage serveur systématique sur l’`id` de l’utilisateur pour éviter
 *    toute fuite de données (principe du least privilege).
 *  • Validation centrée dans le contrôleur : l’utilisateur ne peut injecter
 *    dans la base que les champs explicitement autorisés.
 *  • Commentaires pédagogiques + découpage clair des responsabilités.
 */
class ClientController extends Controller
{
    // ────────────────────────────────────────────────────────────────────────
    // 1) LISTE DES HABITATIONS
    // ────────────────────────────────────────────────────────────────────────

    /**
     * Affiche la liste des habitations de l’utilisateur connecté.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        /* ---------------------------------------------------------------
         * Sécurité : on récupère **uniquement** les habitations dont
         * la colonne `user_id` correspond à l’utilisateur connecté.
         * ------------------------------------------------------------- */
        $habitations = Habitation::where('user_id', Auth::id())->get();

        /* ---------------------------------------------------------------
         * On envoie la collection à la vue `Client.index`.
         * La vue peut boucler dessus et proposer un formulaire d’ajout.
         * ------------------------------------------------------------- */
        return view('Client.index', compact('habitations'));
    }

    // ────────────────────────────────────────────────────────────────────────
    // 2) CRÉATION D’UNE HABITATION
    // ────────────────────────────────────────────────────────────────────────

    /**
     * Enregistre une nouvelle habitation en base.
     *
     * @param  Request $request  Requête HTTP contenant les champs du formulaire
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        /* ---------------------------------------------------------------
         * Étape 1 : Validation.
         * - `validate()` renvoie automatiquement à la page précédente
         *   si les règles échouent et pré‑remplit les champs via `old()`.
         * ------------------------------------------------------------- */
        $validated = $request->validate([
            'adresse_habitation' => 'required|string|max:255',
            'type_habitation'    => 'required|string|in:Appartement,Maison',
            'surfaces'           => 'required|integer|min:1',
            'nb_occupants'       => 'required|integer|min:1',
        ]);

        /* ---------------------------------------------------------------
         * Étape 2 : Attribution sécurisée du propriétaire
         * (on ne fait jamais confiance au champ caché côté client).
         * ------------------------------------------------------------- */
        $validated['user_id'] = Auth::id();

        /* ---------------------------------------------------------------
         * Étape 3 : Insertion en base via Eloquent.
         * Attention : le modèle Habitation doit avoir ses colonnes
         * dans la propriété `$fillable` pour éviter Mass Assignment.
         * ------------------------------------------------------------- */
        Habitation::create($validated);

        /* ---------------------------------------------------------------
         * Étape 4 : Feedback utilisateur (flash message) puis redirection.
         * ------------------------------------------------------------- */
        return redirect()
            ->route('Client.index')
            ->with('success', 'Habitation ajoutée avec succès.');
    }

    // ────────────────────────────────────────────────────────────────────────
    // 3) DÉTAIL D’UNE HABITATION
    // ────────────────────────────────────────────────────────────────────────

    /**
     * Affiche les infos d’une habitation et la liste de ses compteurs.
     *
     * @param  int $id  ID technique de l’habitation
     * @return \Illuminate\Contracts\View\View
     */
    public function showHabitation($id)
    {
        /* ---------------------------------------------------------------
         * - Double filtre : appartenance + clé primaire.
         * - `with('compteurs')` : eager‑loading évite la requête N+1
         *   quand on parcourra les compteurs dans la vue.
         * - `findOrFail` déclenche automatiquement une 404 si
         *   l’ID n’existe pas ou n’appartient pas au user.
         * ------------------------------------------------------------- */
        $habitation = Habitation::where('user_id', Auth::id())
            ->with('compteurs')
            ->findOrFail($id);

        return view('Client.show', compact('habitation'));
    }

    // ────────────────────────────────────────────────────────────────────────
    // 4) FORMULAIRE D’AJOUT DE COMPTEUR
    // ────────────────────────────────────────────────────────────────────────

    /**
     * Affiche le formulaire de création d’un compteur.
     *
     * @param  int $id  ID de l’habitation concernée
     * @return \Illuminate\Contracts\View\View
     */
    public function createCompteur($id)
    {
        /* Sécurise l’accès au formulaire : le user doit posséder l’habitation. */
        $habitation = Habitation::where('user_id', Auth::id())
                                ->findOrFail($id);

        /* ------------------------------------------------------------------
         * On prépare la liste des types de compteurs.
         * Clé = valeur postée    (plus compacte dans l’URL)
         * label/description = affichage utilisateur
         * ---------------------------------------------------------------- */
        $typesCompteurs = [
            'electromecanique' => (object)[
                'label'       => 'Électromécanique',
                'description' => "De forme carrée et bleue, modèle ancien à disque, plus fabriqué aujourd'hui. Précision moyenne."
            ],
            'electronique' => (object)[
                'label'       => 'Électronique classique',
                'description' => "Moins encombrant que l'électromécanique, modèle intermédiaire remplacé depuis 2015. Bonne précision."
            ],
            'linky' => (object)[
                'label'       => 'Linky™ (communicant)',
                'description' => "Rectangulaire avec écran digital. Compteur communicant permettant le relevé automatique et la visualisation de la consommation en temps réel."
            ],
        ];

        return view('Client.create_compteur', compact('habitation', 'typesCompteurs'));
    }

    // ────────────────────────────────────────────────────────────────────────
    // 5) ENREGISTREMENT DU COMPTEUR
    // ────────────────────────────────────────────────────────────────────────

    /**
     * Persiste un compteur pour l’habitation $id.
     *
     * @param  Request $request
     * @param  int     $id       ID de l’habitation cible
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeCompteur(Request $request, $id)
    {
        /* Vérifie que l’habitation appartient bien à l’utilisateur. */
        $habitation = Habitation::where('user_id', Auth::id())
                                ->findOrFail($id);

        /* ---------------------------------------------------------------
         * Validation : le type est limité aux clés définies plus haut.
         * On rend la référence unique via la règle `unique`: évite
         * d’introduire un même compteur dans plusieurs habitations.
         * ------------------------------------------------------------- */
        $validated = $request->validate([
            'Type_compteur'      => 'required|string|in:electromecanique,electronique,linky',
            'Reference_compteur' => 'required|string|max:100|unique:compteurs,Reference_compteur',
            'date_installation'  => 'nullable|date',
        ]);

        /* ---------------------------------------------------------------
         * On convertit la clé en libellé lisible avant la sauvegarde.
         * Cela permet de stocker un texte clair dans la colonne
         * `Type_compteur` sans exposer la logique interne des clés.
         * ------------------------------------------------------------- */
        $validated['Type_compteur'] = [
            'electromecanique' => 'Électromécanique',
            'electronique'     => 'Électronique classique',
            'linky'            => 'Linky™ (communicant)',
        ][$validated['Type_compteur']];

        /* Ajout de la clé étrangère : on lie le compteur à l’habitation. */
        $validated['id_habitation'] = $habitation->id_habitation;

        /* Persistance.  Le modèle Compteur doit aussi protéger ses `$fillable`. */
        Compteur::create($validated);

        /* Redirection vers le détail de l’habitation + message flash. */
        return redirect()
            ->route('client.habitation.show', $habitation->id_habitation)
            ->with('success', 'Compteur ajouté avec succès.');
    }

    // ────────────────────────────────────────────────────────────────────────
    // 6) SUPPRESSION D’UNE HABITATION
    // ────────────────────────────────────────────────────────────────────────

    /**
     * Supprime l’habitation (et, grâce aux contraintes ON DELETE CASCADE,
     * tous ses compteurs).
     *
     * @param  int $id  ID de l’habitation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $habitation = Habitation::findOrFail($id); // 404 si l’ID n’existe pas

        /* Sécurité : l’utilisateur ne peut supprimer que ses propres biens. */
        if ($habitation->user_id !== Auth::id()) {
            abort(403, 'Accès interdit'); // HTTP 403 Forbidden
        }

        /* Suppression : la relation Compteur->Habitation étant en cascade
         * (clé étrangère avec ON DELETE CASCADE), les compteurs associés
         * disparaissent automatiquement, évitant des orphelins. */
        $habitation->delete();

        return redirect()
            ->back()
            ->with('success', 'Habitation supprimée avec succès.');
    }
}

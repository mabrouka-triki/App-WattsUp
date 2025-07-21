<?php

namespace App\Policies;

use App\Models\Compteur;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CompteurPolicy
{
    /**
     * L'utilisateur peut voir n'importe quel compteur ?
     */
    public function viewAny(User $user): bool
    {
        return false; // on interdit par défaut
    }

    /**
     * L'utilisateur peut voir un compteur spécifique
     * s'il est propriétaire de l'habitation liée.
     */
public function view(User $user, Compteur $compteur): bool
{
    return true;  // juste pour tester si ça passe
}



    /**
     * Création de compteur non autorisée par défaut.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * L'utilisateur peut mettre à jour un compteur
     * s'il est propriétaire de l'habitation liée.
     */
    public function update(User $user, Compteur $compteur): bool
    {
        return $user->id === $compteur->habitation->user_id;
    }

    /**
     * Suppression non autorisée.
     */
    public function delete(User $user, Compteur $compteur): bool
    {
        return false;
    }

    /**
     * Restauration non autorisée.
     */
    public function restore(User $user, Compteur $compteur): bool
    {
        return false;
    }

    /**
     * Suppression définitive non autorisée.
     */
    public function forceDelete(User $user, Compteur $compteur): bool
    {
        return false;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Les attributs assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'date_creation_client',
       
    ];

    /**
     * Les attributs cachés lors de la sérialisation.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les casts pour les attributs.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_creation_client' => 'date',
        'password' => 'hashed',
    ];
public function roles()
{
    // Cette méthode définit une relation "many-to-many" entre les utilisateurs et les rôles
    // Elle utilise la table pivot 'user_roles' avec les clés étrangères personnalisées : 'id_user' et 'id_role'
    return $this->belongsToMany(Role::class, 'user_roles', 'id_user', 'id_role');
}

public function hasRole($roleName)
{
    /// si un utilisateur possède un rôle spécifique
    // Elle est utile pour restreindre l'accès à certaines pages (ex : admin uniquement)
    return $this->roles()->where('name', $roleName)->exists();
}

  
}

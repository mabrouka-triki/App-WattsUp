<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habitation extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_habitation';

    protected $fillable = [
        'adresse_habitation',
        'type_habitation',
        'surfaces',
        'nb_occupants',
        'user_id',
    ];

  //relation appartient a un User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
//  habitation peut avoir plusieurs compteurs (électricité, gaz, eau)
    public function compteurs()
    {
        return $this->hasMany(Compteur::class, 'id_habitation', 'id_habitation');
    }
}

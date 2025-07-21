<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Compteur extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_compteur';

    protected $fillable = [
        'Type_compteur',
        'Reference_compteur',
        'id_habitation',
    ];

    public function habitation()
    {
        return $this->belongsTo(Habitation::class, 'id_habitation', 'id_habitation');
    }

    public function consommations()
    {
        return $this->hasMany(Consommation::class, 'id_compteur', 'id_compteur');
    }

    public function factures()
    {
        return $this->hasMany(Facture::class, 'id_compteur', 'id_compteur');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consommation extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_consommation';

    protected $fillable = [
        'date_relev_consommation',
        'valeur_conso',
        'id_compteur',
    ];

    public function compteur()
    {
        return $this->belongsTo(Compteur::class, 'id_compteur', 'id_compteur');
    }
}

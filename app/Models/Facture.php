<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_facture';

    protected $fillable = [
        'Fournisseur',
        'Date_de_facture',
        'Montant',
        'Consommation',
        'id_compteur',
    ];

    public function compteur()
    {
        return $this->belongsTo(Compteur::class, 'id_compteur', 'id_compteur');
    }
}






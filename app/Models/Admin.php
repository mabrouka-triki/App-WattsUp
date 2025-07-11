<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Admin extends Model
{
    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    public $timestamps = true;

    protected $fillable = [
        'nom_admin',
        'email_admin',
        'pwd_admin',
    ];

    // Pour hasher automatiquement le mot de passe quand il est défini
    public function setPwdAdminAttribute($value)
    {
        $this->attributes['pwd_admin'] = Hash::make($value);
    }
}

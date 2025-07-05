<?php

namespace App\Http\Controllers;

use App\Models\Consommation;
use App\Models\Habitation;
use Illuminate\Http\Request;

class HabitationController extends Controller
{
    public function index()
    {
        return view('home', [
            'habitations' => Habitation::all()
        ]);
    }
}

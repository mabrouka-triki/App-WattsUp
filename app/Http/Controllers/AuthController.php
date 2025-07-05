<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Habitation;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Page de connexion
    public function login()
    {
        return view('auth.login');
    }

    // Traitement de la connexion
    public function doLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:4'
        ]);

        $userEstValide = Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ]);

        if ($userEstValide) {
            $request->session()->regenerate();

            if (auth()->user()->role === 'admin') {
                return redirect()->intended(route('admin.index'));
            } else {
                return redirect()->intended(route('home'));
            }
        }

        return back()->withErrors([
            'email' => 'L’email ou le mot de passe est invalide.'
        ])->onlyInput('email');
    }

    // Déconnexion
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

    // Page d'inscription
    public function register()
    {
        return view('auth.register');
    }

    public function doRegister(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'adresse_habitation' => 'required|string|max:255',
            'type_habitation' => 'required|in:Appartement,Maison',
            'surfaces' => 'required|numeric|min:1',
            'nb_occupants' => 'required|integer|min:1',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'name' => 'Client', // ou mets un champ "name" si tu veux le demander plus tard
            'role' => 'client',
            'date_creation_client' => now(),
            'type_client' => 'perso', // ou 'pros' si nécessaire
        ]);

        Habitation::create([
            'adresse_habitation' => $request->adresse_habitation,
            'type_habitation' => $request->type_habitation,
            'surfaces' => $request->surfaces,
            'nb_occupants' => $request->nb_occupants,
            'user_id' => $user->id, // bien transmis ici aussi
        ]);
        Auth::login($user);
        return redirect()->route('home');
    }
}
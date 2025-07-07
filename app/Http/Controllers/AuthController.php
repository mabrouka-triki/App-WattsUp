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
    // Récupère uniquement l'email et le mot de passe depuis la requête
    $credentials = $request->only('email', 'password');

    // Tente de connecter l'utilisateur avec les identifiants fournis
    if (Auth::attempt($credentials, $request->remember)) {
        // Connexion réussie

        // Vérifie si l'utilisateur est un admin
        if (auth()->user()->role === 'admin') {
            // Redirige  le tableau de bord admin
            return redirect()->intended(route('admin.index'));
        } else {
            // Redirige vers la page d'accueil  des   utilisateurs 
            return redirect()->intended(route('Client.index'));
        }
    }

  
    return back()->with('error', 'Email ou mot de passe incorrect');
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
       return redirect()->route('Client.index');

    }
}
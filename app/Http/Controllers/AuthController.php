<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Habitation;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Http;

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
    // etape 1 : Validation du formulaire
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'g-recaptcha-response' => 'required',
    ]);

    // etape 2 : Vérification reCAPTCHA 
    $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
        'secret' => env('RECAPTCHA_SECRET_KEY'),
        'response' => $request->input('g-recaptcha-response'),
        'remoteip' => $request->ip(),
    ]);

    if (!($response->json()['success'] ?? false)) {
        return back()->with('error', 'Échec de vérification reCAPTCHA. Veuillez réessayer.')->withInput();
    }

    //  etape 3 : Tentative de connexion
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        // Connexion réussie

        // etape 4 : Redirection selon le rôle de l'utilisateur
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.index'));
        }

        return redirect()->intended(route('Client.index')); // pour les clients
    }

    return back()->with('error', 'Email ou mot de passe incorrect.')->withInput();
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
    'name' => 'required|string|max:255',
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
             'name' => $request->name,

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
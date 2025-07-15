<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class AuthController extends Controller
{


    public function login()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }

 

    public function doLogin(Request $request)
    {
        // 1. Validation
        $request->validate([
            'email'                => 'required|email',
            'password'             => 'required|string|min:6',
        
        ]);

     
      

        // 3. Authentification
        if (Auth::attempt($request->only('email', 'password'))) {
            /**
          
             * Regénère l’ID de session pour empêcher la fixation de session
             * et met à jour le jeton CSRF.
             */
            $request->session()->regenerate();

            return redirect()->intended(route('Client.index'));
        }

        return back()
            ->with('error', 'Email ou mot de passe incorrect.')
            ->withInput();
    }

    /* ---------- TRAITEMENT LOGOUT ---------- */

    public function logout(Request $request)
    {
        Auth::logout();                       // 1. Déconnexion
        $request->session()->invalidate();    // 2. Invalidation de la session
        $request->session()->regenerateToken(); // 3. Nouveau token CSRF

        return redirect()->route('home');
    }

    /* ---------- TRAITEMENT REGISTER ---------- */

    public function doRegister(Request $request)
    {
        // 1. Validation renforcée
$validated = $request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users,email',
    'password' => [
        'required',
        'confirmed',
        Password::min(12)
            ->letters()
            ->mixedCase()
            ->numbers()
            ->symbols()
    ],
]);
        // 2. Création de l’utilisateur
        $user = User::create([
            'name'                => $request->name,
            'email'               => $request->email,
            'password'            => Hash::make($request->password),
            'date_creation_client'=> now(),
        ]);

        // 3. Connexion + nouvelle session
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('Client.index');
    }
}

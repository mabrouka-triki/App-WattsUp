<?php

/**
 * ──────────────────────────────────────────────────────────────────────────────
 *  AuthController — Gestion complète de l’authentification (Laravel 10)
 * ──────────────────────────────────────────────────────────────────────────────
 *
 *  Rôle principal :
 *      • Affichage des vues de connexion et d’inscription.
 *      • Validation robuste des données utilisateur.
 *      • Connexion / déconnexion sécurisées (CSRF, fixation de session, etc.).
 *
 *  Bonnes pratiques mises en œuvre :
 *      • Validation forte des mots de passe (classe  Illuminate\Validation\Rules\Password).
 *      • Regeneration de l’ID de session après authentification pour contrer
 *        les attaques de fixation de session.
 *      • Invalidation & regeneration du token CSRF lors du logout.
 *      • Règles `unique:users,email` pour empêcher les doublons.
 *
 *  @package  App\Http\Controllers
 *  @author   Équipe WattsUp
 *  @version  1.0.0
 *  @license  MIT
 *  @since    2025‑07‑16
 * ──────────────────────────────────────────────────────────────────────────────
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\Admin;

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
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required|string|min:6',
    ]);

    if (Auth::attempt($request->only('email', 'password'))) {
        $request->session()->regenerate();

        if (Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.index');
        }

        return redirect()->route('Client.index');
    }

    return back()->with('error', 'Email ou mot de passe incorrect.')->withInput();
}


    /* ---------- TRAITEMENT LOGOUT ---------- */


    // Déconnexion
    public function logout()
    {
        Auth::logout();
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

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\HabitationController;

use Illuminate\Support\Facades\Route;

Route::get('/', [HabitationController::class, 'index'])->name('home');

// Routes accessibles uniquement si utilisateur NON authentifié (guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'doLogin'])->name('doLogin');

   
});


    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'doRegister'])->name('doRegister');



// Routes accessibles uniquement aux utilisateurs authentifiés
Route::middleware('auth')->group(function () {
    Route::get('/Client', [ClientController::class, 'index'])->name('Client.index');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
  // Détail d’une habitation + ses compteurs
    Route::get('/client/habitations/{id}',    [ClientController::class, 'showHabitation'])->name('client.habitation.show');

    // Formulaire d’ajout de compteur
  // ✅ Création d’une habitation
    Route::post('/client/habitations', [ClientController::class, 'store'])  ->name('Client.store');
    Route::post('/client/habitations/{id}/compteurs',[ClientController::class, 'storeCompteur']  )->name('client.compteur.store');

Route::delete('/client/habitation/{id}/delete', [ClientController::class, 'destroy']) ->name('client.habitation.delete');
});   


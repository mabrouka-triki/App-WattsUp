<?php 
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\HabitationController;
use App\Http\Controllers\ConsommationController;
use Illuminate\Support\Facades\Route;

/* ---------- Accueil public ---------- */
Route::get('/', [HabitationController::class, 'index'])->name('home');

/* ---------- Routes invité (guest) ---------- */
Route::middleware('guest')->group(function () {
    Route::get ('/login',  [AuthController::class, 'login'])   ->name('login');
    Route::post('/login',  [AuthController::class, 'doLogin']) ->name('doLogin');
});

/* Les deux routes d'inscription restent accessibles sans authentification */
Route::get ('/register', [AuthController::class, 'register'])  ->name('register');
Route::post('/register', [AuthController::class, 'doRegister'])->name('doRegister');

/* ---------- Routes authentifié (auth) ---------- */
Route::middleware('auth')->group(function () {

    /* Tableau de bord & déconnexion */
    Route::get ('/Client', [ClientController::class, 'index']) ->name('Client.index');
    Route::post('/logout', [AuthController::class, 'logout'])  ->name('logout');

    /* Habitations */
    Route::get   ('/client/habitations/{id}',           [ClientController::class, 'showHabitation'])->name('client.habitation.show');
    Route::post  ('/client/habitations',                [ClientController::class, 'store'])        ->name('Client.store');
    Route::delete('/client/habitation/{id}/delete',     [ClientController::class, 'destroy'])      ->name('client.habitation.delete');

  /* Compteurs */
    Route::get ('/client/habitations/{id}/compteurs/create', [ClientController::class, 'createCompteur'])->name('client.compteur.create');
    Route::post('/client/habitations/{id}/compteurs',        [ClientController::class, 'storeCompteur']) ->name('client.compteur.store');
  
/*Consommation*/ 
Route::get('/Client/gestionFacture/{compteur}', [ConsommationController::class, 'index'])
    ->name('Client.gestionFacture');

    Route::post('/consommation/{compteur}', [ConsommationController::class, 'store'])
        ->name('consommation.store');

});

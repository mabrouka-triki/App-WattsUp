<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HabitationController;
use App\Http\Controllers\ConsommationController;
use App\Http\Middleware\IsAdmin;

/* ============================================================================
| Routes publiques
============================================================================ */
Route::get('/', [HabitationController::class, 'index'])->name('home');

/* ============================================================================
| Routes invité (clients non connectés)
============================================================================ */
Route::middleware('guest')->group(function () {
    // Connexion client
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'doLogin'])->name('doLogin');

    // Inscription client
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'doRegister'])->name('doRegister');
});

/* ============================================================================
| Routes invité admin (non connecté en tant qu'admin)
============================================================================ */
Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'doAdminLogin'])->name('admin.doLogin');
});

/* ============================================================================
| Routes CLIENT connecté (guard: web)
============================================================================ */
Route::middleware('auth')->group(function () {
    // Tableau de bord client
    Route::get('/client', [ClientController::class, 'index'])->name('Client.index');

    // Déconnexion client
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Habitations
    Route::get   ('/client/habitations/{id}',       [ClientController::class, 'showHabitation'])->name('client.habitation.show');
    Route::post  ('/client/habitations',            [ClientController::class, 'store'])->name('Client.store');
    Route::delete('/client/habitation/{id}/delete', [ClientController::class, 'destroy'])->name('client.habitation.delete');

    // Compteurs
    Route::get ('/client/habitations/{id}/compteurs/create', [ClientController::class, 'createCompteur'])->name('client.compteur.create');
    Route::post('/client/habitations/{id}/compteurs',        [ClientController::class, 'storeCompteur'])->name('client.compteur.store');

    // Consommation
    Route::get ('/client/gestionFacture/{compteur}', [ConsommationController::class, 'index'])->name('Client.gestionFacture');
    Route::post('/consommation/{compteur}',          [ConsommationController::class, 'store'])->name('consommation.store');
});

/* ============================================================================
| ADMIN connecté (via rôle "admin", pas via guard)
============================================================================ */
Route::prefix('admin')->middleware(['auth',IsAdmin::class])->group(function () {
    // Dashboard admin
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');

    
});


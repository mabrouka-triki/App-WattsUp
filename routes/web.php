<?php
use App\Http\Controllers\AuthController;


use App\Http\Controllers\HabitationController;

use App\Http\Controllers\AdminController;

use Illuminate\Support\Facades\Route;



Route::get('/', [HabitationController::class, 'index'])->name('home');



Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
});


Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'doLogin'])->name('doLogin');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// register 

Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'doRegister']);


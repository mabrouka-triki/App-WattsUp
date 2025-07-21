<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Compteur;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap any application services.
     */
 
public function boot()
{
    View::composer('App.layoutClient', function ($view) {
        $user = auth()->user();

        if ($user) {
            // On récupère un compteur lié à ce user
            $compteur = Compteur::whereHas('habitation', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->first();

            $view->with('compteur', $compteur);
        }
    });
}

}

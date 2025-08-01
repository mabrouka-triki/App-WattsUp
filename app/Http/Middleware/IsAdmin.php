<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->hasRole('admin')) {

            return $next($request);
            
        }

        abort(403, 'Accès refusé. Vous devez être administrateur.');
    }
}

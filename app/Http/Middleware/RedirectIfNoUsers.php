<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNoUsers
{
    public function handle(Request $request, Closure $next)
    {
        // Verifica si la base de datos tiene usuarios
        $userCount = User::count();

        if ($userCount === 0 && !$request->is('wizard')) {
            // Si no hay usuarios y no está en "wizard", lo redirige ahí
            return redirect()->route('wizard');
        }

        if ($userCount > 0 && $request->is('/')) {
            // Si hay usuarios y está en la raíz "/", lo redirige al login
            return redirect()->route('login');
        }

        return $next($request);
    }
}

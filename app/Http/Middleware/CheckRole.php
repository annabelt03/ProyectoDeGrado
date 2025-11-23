<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Verificar el rol del usuario
        if ($user->role !== $role) {
            // Si no tiene el rol correcto, redirigir o mostrar error
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta página.');
        }

        return $next($request);
    }
}

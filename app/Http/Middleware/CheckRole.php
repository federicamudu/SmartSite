<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
{
    // Controlla se l'utente è loggato e se il suo ruolo corrisponde a quello richiesto
    if (!$request->user() || $request->user()->role !== $role) {
        return response()->json([
            'message' => 'Accesso negato. Non hai i permessi necessari.'
        ], 403);
    }

    return $next($request);
}
}

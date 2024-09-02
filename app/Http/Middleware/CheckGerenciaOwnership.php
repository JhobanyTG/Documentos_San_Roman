<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Gerencia;


class CheckGerenciaOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     return $next($request);
    // }
    public function handle($request, Closure $next)
    {
        $gerencia = Gerencia::find($request->route('id'));

        if (!$gerencia || $gerencia->usuario_id !== auth()->id()) {
            abort(403, 'No tienes permiso para acceder a esta gerencia.');
        }

        return $next($request);
    }

}

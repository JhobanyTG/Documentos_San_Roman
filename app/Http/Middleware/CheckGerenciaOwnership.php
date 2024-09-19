<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Gerencia;
use App\Models\Subgerencia;
use App\Models\Subusuario; // Asegúrate de importar tu modelo de Subusuario

class CheckGerenciaOwnership
{
    public function handle($request, Closure $next)
    {
        // Obtener la gerencia de la ruta
        $gerencia = Gerencia::find($request->route('id'));

        // Verificar si la gerencia existe
        if (!$gerencia) {
            abort(404, 'Gerencia no encontrada.');
        }

        // Obtener el ID del usuario autenticado
        $usuarioId = auth()->id();

        // Verificar si el usuario autenticado es el propietario de la gerencia
        if ($gerencia->usuario_id === $usuarioId) {
            return $next($request); // Si es el propietario, permitir acceso
        }

        // Verificar si el usuario es un subusuario relacionado con alguna subgerencia de la gerencia
        $subusuario = Subusuario::whereHas('subgerencia', function ($query) use ($gerencia) {
            $query->where('gerencia_id', $gerencia->id);
        })
        ->where('usuario_id', $usuarioId)
        ->first();

        if ($subusuario) {
            return $next($request); // Si es un subusuario de una subgerencia relacionada, permitir acceso
        }

        // Si no pertenece ni a la gerencia ni a una subgerencia, denegar acceso
        abort(403, 'No tienes permiso para acceder a esta gerencia o subgerencia.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Documento;
use App\Models\TipoDocumento;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    public function index(Request $request)
    {
        // Inicializar el query con el filtro de estado 'Publicado'
        $query = Documento::where('estado', 'Publicado');

        // Obtener los filtros de búsqueda
        $searchTerm = $request->input('q');
        $fecha = $request->input('fecha');
        $filtroAnio = $request->input('anio');
        $filtroMes = $request->input('mes', []); // Inicializar como array vacío si no hay valor

        // Aplicar filtros de búsqueda
        if ($searchTerm || $fecha || $filtroAnio || $filtroMes) {
            if ($searchTerm) {
                $query->where(function ($query) use ($searchTerm) {
                    $query->where('titulo', 'like', '%' . $searchTerm . '%')
                        ->orWhere('descripcion', 'like', '%' . $searchTerm . '%');
                });
            }

            if ($fecha) {
                $query->whereDate('created_at', $fecha);
            }

            if ($filtroAnio) {
                $query->whereYear('created_at', $filtroAnio);
            }

            if ($filtroMes && is_array($filtroMes) && !empty($filtroMes)) {
                // Usar whereIn para manejar múltiples meses
                $query->whereIn(DB::raw('MONTH(created_at)'), $filtroMes);
            }
        }

        // Ordenar por la fecha de creación más reciente
        $query->orderByDesc('created_at');

        // Paginación
        $documentos = $query->paginate(5);
        $documentos->appends(['q' => $searchTerm, 'fecha' => $fecha, 'anio' => $filtroAnio, 'mes' => $filtroMes]);

        // Obtener años disponibles para el filtro
        $availableYears = Documento::distinct()
            ->orderByDesc('created_at')
            ->pluck('created_at')
            ->map(function ($date) {
                return $date->format('Y');
            })
            ->unique();

        // Obtener meses disponibles para el filtro en el año seleccionado
        $availableMonths = [];
        if ($filtroAnio) {
            $availableMonths = Documento::selectRaw('MONTH(created_at) as month')
                ->whereYear('created_at', $filtroAnio)
                ->groupBy('month')
                ->pluck('month');
        }

        // Retornar la vista con los documentos filtrados
        return view('publics.index', compact('documentos', 'searchTerm', 'fecha', 'availableYears', 'availableMonths', 'filtroAnio', 'filtroMes'));
    }

}

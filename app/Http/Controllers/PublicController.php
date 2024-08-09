<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Documento;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    public function index(Request $request)
    {
        $query = Documento::query();

        $searchTerm = $request->input('q');
        $fecha = $request->input('fecha');
        $filtroAnio = $request->input('anio');
        $filtroMes = $request->input('mes', []);

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
                $query->whereIn(DB::raw('MONTH(created_at)'), $filtroMes);
            }
        }

        $query->orderByDesc('created_at');
        $documentos = $query->paginate(5);
        $documentos->appends(['q' => $searchTerm, 'fecha' => $fecha, 'anio' => $filtroAnio, 'mes' => $filtroMes]);

        $availableYears = Documento::distinct()
            ->orderByDesc('created_at')
            ->pluck('created_at')
            ->map(function ($date) {
                return $date->format('Y');
            })
            ->unique();

        $availableMonths = [];
        if ($filtroAnio) {
            $availableMonths = Documento::selectRaw('MONTH(created_at) as month')
                ->whereYear('created_at', $filtroAnio)
                ->groupBy('month')
                ->pluck('month');
        }

        return view('publics.index', compact('documentos', 'searchTerm', 'fecha', 'availableYears', 'availableMonths', 'filtroAnio', 'filtroMes'));
    }
}

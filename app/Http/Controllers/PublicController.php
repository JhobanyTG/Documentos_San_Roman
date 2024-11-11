<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Documento;
use App\Models\TipoDocumento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Crypt;

class PublicController extends Controller
{
    public function index(Request $request)
    {
        // Inicializar el query con el filtro de estado 'Publicado'
        $query = Documento::where('estado', 'Publicado');

        $tiposDocumento = TipoDocumento::all(); // Asegúrate de tener el modelo TipoDocumento configurado


        // Obtener los filtros de búsqueda
        $searchTerm = $request->input('q');
        $fecha = $request->input('fecha');
        $filtroAnio = $request->input('anio');
        $filtroMes = $request->input('mes', []); // Inicializar como array vacío si no hay valor
        $filtroTipoDocumento = $request->input('tipodocumento_id', []);

        // Aplicar filtros de búsqueda
        if ($searchTerm || $fecha || $filtroAnio || $filtroMes || $filtroTipoDocumento) {
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

            if (!empty($filtroTipoDocumento)) {
                $query->where('tipodocumento_id', $filtroTipoDocumento);
            }
        }

        // Ordenar por la fecha de creación más reciente
        $query->orderByDesc('created_at');

        // Paginación
        $documentos = $query->paginate(5);
        $documentos->appends(['q' => $searchTerm, 'fecha' => $fecha, 'anio' => $filtroAnio, 'mes' => $filtroMes, 'tipodocumento_id' => $filtroTipoDocumento]);

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
        return view('publics.index', compact('documentos', 'searchTerm', 'fecha', 'availableYears', 'availableMonths', 'filtroAnio', 'filtroMes',  'tiposDocumento', 'filtroTipoDocumento'));
    }

    public function getDocumentUrl($id)
    {
        $documento = Documento::findOrFail($id);

        // Generar token de acceso temporal
        $token = $documento->generateAccessToken();

        // Generar URL segura
        $url = route('public.documento.ver', [
            'id' => Crypt::encrypt($documento->id),
            'token' => $token
        ]);

        return response()->json(['url' => $url]);
    }

    public function viewDocument($id, $token)
    {
        try {
            $documentoId = Crypt::decrypt($id);
            $documento = Documento::where('id', $documentoId)
                                ->where('access_token', $token)
                                ->where('token_expires_at', '>', now())
                                ->firstOrFail();

            // Verificar si el archivo existe
            $path = storage_path('app/public/documentos/' . basename($documento->archivo));
            if (!file_exists($path)) {
                abort(404, 'Archivo no encontrado');
            }

            // Invalidar el token después de usarlo
            $documento->update([
                'access_token' => null,
                'token_expires_at' => null
            ]);

            // Obtener el tipo de contenido
            $type = mime_content_type($path);

            // Retornar el archivo
            return Response::file($path, [
                'Content-Type' => $type
            ]);

        } catch (\Exception $e) {
            abort(403, 'Link inválido o expirado');
        }
    }

}

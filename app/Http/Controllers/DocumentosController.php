<?php

namespace App\Http\Controllers;


use App\Models\Documento;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DocumentosController extends Controller
{
    public function index(Request $request)
    {
            $documentos = Documento::with('tipoDocumento')->get();
            $query = Documento::query();

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

            $query->orderByDesc('created_at');
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

            return view('documentos.index', compact('documentos', 'searchTerm', 'fecha', 'availableYears', 'availableMonths', 'filtroAnio', 'filtroMes'));
    }

    public function create()
    {
        if (auth()->user()) {
            $tiposDocumento = TipoDocumento::all();
            return view('documentos.create', compact('tiposDocumento'));
        } else {
            return redirect()->to('/');
        }

    }

    public function edit($id)
    {
        if (auth()->user()) {
            $documento = Documento::findOrFail($id);
            $tiposDocumento = TipoDocumento::all();
            return view('documentos.edit', compact('documento', 'tiposDocumento'));
        } else {
            return redirect()->to('/');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'tipodocumento_id' => 'required|exists:tipodocumento,id',
            'archivo' => [
                'required',
                'file',
                'mimes:pdf',
                'max:10000',
                Rule::unique('documentos', 'archivo'),
            ],
            'estado' => 'required|in:Creado,Validado,Publicado',
        ]);

        $fileName = null;
        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');
            $fileName = time() . '-' . $file->getClientOriginalName();
            $file->storeAs('public/documentos', $fileName);
        }

        Documento::create([
            'sub_usuarios_id' => Auth::user()->id,
            'tipodocumento_id' => $request->input('tipodocumento_id'),
            'titulo' => $request->input('titulo'),
            'descripcion' => $request->input('descripcion'),
            'archivo' => $fileName,
            'estado' => $request->input('estado'),
        ]);

        return redirect()->route('documentos.index')->with('success', 'Documento creado exitosamente.');
    }

    public function update(Request $request, $id)
    {

        $documento = Documento::findOrFail($id);
        $fileName = $documento->archivo;

        $request->validate([
            'titulo' => 'required|string|max:255',
            'tipodocumento_id' => 'required|exists:tipodocumento,id',
            'descripcion' => 'required|string',
            'archivo' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:10000',
                Rule::unique('documentos')->ignore($documento->id),
            ],
            'estado' => 'required|in:Creado,Validado,Publicado',
        ]);

        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');

            // Generar el número incremental
            $ultimoDocumento = Documento::latest('id')->first();
            $numeroIncremental = $ultimoDocumento ? str_pad($ultimoDocumento->id + 1, 4, '0', STR_PAD_LEFT) : '0001';

            // Modificar el nombre del archivo
            $archivoNombreOriginal = $archivo->getClientOriginalName();
            $archivoNombre = 'ATISR-' . $numeroIncremental . '-' . $archivoNombreOriginal;

            // Guardar el archivo con el nuevo nombre
            $archivoRuta = $archivo->storeAs('archivos', $archivoNombre, 'public');

            // Eliminar el archivo anterior si existe
            if ($documento->archivo) {
                Storage::delete('public/' . $documento->archivo);
            }

            $documento->archivo = $archivoRuta;
        }

        $documento->titulo = $request->input('titulo');
        $documento->descripcion = $request->input('descripcion');
        $documento->save();

        $documento->update([
            'tipodocumento_id' => $request->input('tipodocumento_id'),
            'titulo' => $request->input('titulo'),
            'descripcion' => $request->input('descripcion'),
            'archivo' => $fileName,
            'estado' => $request->input('estado'),
        ]);

        return redirect()->route('documentos.index')->with('success', 'Documento actualizado exitosamente.');
    }

    public function destroy($id)
    {

        if (auth()->user()) {
            $documento = Documento::findOrFail($id);
            if ($documento->archivo) {
                Storage::delete('public/documentos/' . $documento->archivo);
            }
            $documento->delete();

            return redirect()->route('documentos.index')
                ->with('success', 'El registro ha sido eliminado exitosamente.');
        } else {
            return redirect()->to('/');
        }
    }
}

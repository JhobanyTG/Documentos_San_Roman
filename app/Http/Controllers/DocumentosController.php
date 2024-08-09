<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Documento;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class DocumentosController extends Controller
{
    public function index(Request $request)
    {
        if ($user = auth()->user()) {
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
        } else {
            return redirect()->to('/');
        }
    }

    public function create()
    {
        if (auth()->user()) {
            return view('documentos.create');
        } else {
            return redirect()->to('/');
        }
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'archivo' => [
                    'required',
                    'file',
                    'mimes:pdf',
                    'max:10000',
                    Rule::unique('documentos', 'archivo'),
                ],
        ]);

        try {
            $archivo = $request->file('archivo');
            // $archivoNombre = $archivo->getClientOriginalName();
            // $archivoRuta = $archivo->storeAs('archivos', $archivoNombre, 'public');
            // $archivoExiste = Documento::where('archivo', $archivoRuta)->exists();
            // if ($archivoExiste) {
            //     return redirect()->route('documentos.create')
            //         ->withErrors(['archivo' => 'El archivo con ese nombre ya existe. Por favor, elige otro archivo diferente.'])
            //         ->withInput();
            // }

            // Generar el número incremental
            $ultimoDocumento = Documento::latest('id')->first();
            $numeroIncremental = $ultimoDocumento ? str_pad($ultimoDocumento->id + 1, 4, '0', STR_PAD_LEFT) : '0001';

            // Modificar el nombre del archivo
            $archivoNombreOriginal = $archivo->getClientOriginalName();
            $archivoNombre = 'ATISR-' . $numeroIncremental . '-' . $archivoNombreOriginal;

            // Guardar el archivo con el nuevo nombre
            $archivoRuta = $archivo->storeAs('archivos', $archivoNombre, 'public');

            $archivoExiste = Documento::where('archivo', $archivoRuta)->exists();
            if ($archivoExiste) {
                return redirect()->route('documentos.create')
                    ->withErrors(['archivo' => 'El archivo con ese nombre ya existe. Por favor, elige otro archivo diferente.'])
                    ->withInput();
            }

            // Crear el nuevo documento
            $documento = new Documento();
            $documento->user_id = Auth::id();
            $documento->titulo = $request->input('titulo');
            $documento->descripcion = $request->input('descripcion');
            $documento->archivo = $archivoRuta;
            $documento->save();

            Session::flash('success', 'El documento ha sido creado exitosamente.');
            return redirect()->route('documentos.index');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('documentos.create')
                ->with('error', 'Ha ocurrido un error en el servidor. Por favor, inténtalo de nuevo más tarde.');
        }
    }

    public function edit($id)
    {
        if (auth()->user()) {
            $documento = Documento::findOrFail($id);
            return view('documentos.edit', compact('documento'));
        } else {
            return redirect()->to('/');
        }
    }

    public function update(Request $request, $id)
    {
        $documento = Documento::findOrFail($id);

        $request->validate([
            'titulo' => 'required|max:255',
            'descripcion' => 'required|string',
            'archivo' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:10000',
                Rule::unique('documentos')->ignore($documento->id),
            ],
        ]);

        // if ($request->hasFile('archivo')) {
        //     $archivo = $request->file('archivo');
        //     $archivoNombre = $archivo->getClientOriginalName();
        //     $archivoRuta = $archivo->storeAs('archivos', $archivoNombre, 'public');

        //     if ($documento->archivo) {
        //         Storage::delete('public/' . $documento->archivo);
        //     }

        //     $documento->archivo = $archivoRuta;
        // }
        
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

        return redirect()->route('documentos.index')->with('success', 'El documento se actualizó correctamente.');
    }

    // Método para eliminar un documento
    // public function destroy($id)
    // {
    //     $documento = Documento::findOrFail($id);

    //     // Eliminar el archivo
    //     if (Storage::exists($documento->archivo)) {
    //         Storage::delete($documento->archivo);
    //     }

    //     // Eliminar el registro en la base de datos
    //     $documento->delete();

    //     Session::flash('success', 'El documento ha sido eliminado exitosamente.');
    //     return redirect()->route('documentos.index');
    // }
    public function destroy($id)
    {
        if (auth()->user()) {
            $documento = Documento::findOrFail($id);
            if ($documento->archivo) {
                Storage::delete('public/' . $documento->archivo);
            }
            $documento->delete();

            return redirect()->route('documentos.index')
                ->with('success', 'El registro ha sido eliminado exitosamente.');
        } else {
            return redirect()->to('/');
        }
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Gerencia;
use App\Models\User;
use App\Models\Persona;
use App\Models\Subgerencia;
use App\Models\Subusuario;
use Illuminate\Http\Request;

class GerenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // public function index()
    // {
    //     // Usa la relación 'user' en lugar de 'usuario'
    //     $gerencias = Gerencia::with('user.persona')->get();

    //     return view('gerencias.index', compact('gerencias'));
    // }
    public function index()
    {
        // Obtener el usuario autenticado
        $usuario = auth()->user();

        // Si el usuario es SuperAdmin, mostrar todas las gerencias
        if ($usuario->rol->nombre === 'SuperAdmin') {
            $gerencias = Gerencia::all();
        } else {
            // Si no es SuperAdmin, mostrar solo las gerencias asignadas a este usuario
            $gerencias = Gerencia::where('usuario_id', $usuario->id)->get();
        }

        // Retornar la vista con la lista de gerencias filtrada
        return view('gerencias.index', compact('gerencias'));
    }


    // public function index()
    // {
    //     $gerencias = Gerencia::all();
    //     return view('gerencias.index', compact('gerencias'));
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener todos los usuarios para el dropdown de gerente
        $users = User::with('persona')->get();

        return view('gerencias.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string|max:500',
            'telefono' => 'required|string',
            'direccion' => 'required|string|max:100',
            'estado' => 'required|string|max:20',
        ]);

        Gerencia::create([
            'usuario_id' => 1,  // Asignado por defecto
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'estado' => $request->estado,
        ]);

        return redirect()->route('gerencias.index')->with('success', 'Gerencia creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    // public function show($id)
    // {
    //     $gerencia = Gerencia::with('user.persona')->findOrFail($id);
    //     return view('gerencias.show', compact('gerencia'));
    // }


    // public function show(Gerencia $gerencia)
    // {
    //     // Cargar subgerencias con subusuarios y usuarios relacionados
    //     if (auth()->user()->rol->nombre === 'SuperAdmin' || 'Crear Gerencias') {
    //         $gerencia->load('subgerencias.subusuarios.user');

    //         return view('gerencias.show', compact('gerencia'));
    //     }
    // }
    // public function show($id)
    // {
    //     // Obtener la gerencia por su ID
    //     $gerencia = Gerencia::find($id);

    //     // Si no existe la gerencia, retornar un error 404
    //     if (!$gerencia) {
    //         abort(404, 'Gerencia no encontrada');
    //     }

    //     // Verificar si el usuario autenticado está asociado a esta gerencia
    //     $usuario = auth()->user();

    //     if ($gerencia->usuario_id !== $usuario->id) {
    //         // Si el usuario no tiene permiso, se puede redirigir o abortar con un error
    //         abort(403, 'No tienes permiso para ver esta gerencia');
    //     }

    //     // Si el usuario tiene permiso, mostrar la vista con los detalles de la gerencia
    //     return view('gerencias.show', compact('gerencia'));
    // }
    public function show($id)
    {
        $gerencia = Gerencia::find($id);

        if (!$gerencia) {
            abort(404, 'Gerencia no encontrada');
        }

        $usuario = auth()->user();

        // Si el usuario es SuperAdmin, permitir el acceso
        if ($usuario->rol->nombre === 'SuperAdmin') {
            return view('gerencias.show', compact('gerencia'));
        }

        // Si no es SuperAdmin, verificar que esté asignado a la gerencia
        if ($gerencia->usuario_id !== $usuario->id) {
            abort(403, 'No tienes permiso para ver esta gerencia');
        }

        return view('gerencias.show', compact('gerencia'));
    }



    // public function show(Gerencia $gerencia)
    // {
    //     // Cargar las subgerencias con el usuario y la persona relacionada
    //     $gerencia->load('subgerencias.user.persona');

    //     return view('gerencias.show', compact('gerencia'));
    // }





    // public function show($id)
    // {
    //     $gerencia = Gerencia::with(['subgerencias.user.persona', 'subUsuarios.persona'])->findOrFail($id);
    //     return view('gerencias.show', compact('gerencia'));
    // }
    // public function show($id)
    // {
    //     $gerencia = Gerencia::findOrFail($id);

    //     // Obtén los subusuarios relacionados con la gerencia
    //     $subusuarios = Subusuario::whereHas('subgerencia', function ($query) use ($id) {
    //         $query->where('gerencia_id', $id);
    //     })->get();

    //     return view('gerencias.show', compact('gerencia', 'subusuarios'));
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gerencia $gerencia)
    {
        // Consulta para obtener todos los usuarios que pueden ser seleccionados como gerentes
        $users = User::with('persona')->get();

        // Retornamos la vista de edición pasando la gerencia y la lista de usuarios
        return view('gerencias.edit', compact('gerencia', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gerencia $gerencia)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string|max:500',
            'telefono' => 'required|string',
            'direccion' => 'required|string|max:100',
            'estado' => 'required|string|max:20',
            'gerente_id' => 'required|exists:users,id',  // Validamos que el gerente seleccionado existe
        ]);

        $gerencia->update([
            'usuario_id' => $request->gerente_id,  // Se actualiza con el gerente seleccionado
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'estado' => $request->estado,
        ]);

        return redirect()->route('gerencias.index')->with('success', 'Gerencia actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gerencia $gerencia)
    {
        $gerencia->delete();
        return redirect()->route('gerencias.index')->with('success', 'Gerencia eliminada exitosamente.');
    }

    // Asegúrate de pasar el ID de la gerencia correcta
    public function mostrarGerencia($gerenciaId)
    {
        $usuario = auth()->user();

        if ($usuario->rol->nombre === 'SuperAdmin') {
            $gerencia = Gerencia::findOrFail($gerenciaId);
            return view('gerencias.show', compact('gerencia'));
        }

        // Aquí obtienes la gerencia asignada al usuario
        $gerencia = Gerencia::where('id', $gerenciaId)
            ->where('usuario_id', $usuario->usuario_id)
            ->first();

        if ($gerencia) {
            return redirect()->route('gerencias.show', ['gerencia' => $gerencia->gerencia_id]);
        }

        // return redirect()->route('/documentos')->withErrors('No tienes acceso a esta gerencia.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Gerencia;
use App\Models\Subgerencia;
use App\Models\Subusuario;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class SubgerenciaController extends Controller
{

    public function create(Gerencia $gerencia)
    {
        if (auth()->user()->rol->privilegios->contains('nombre', 'Acceso Total')  || auth()->user()->rol->nombre === 'Gerente') {

            // Obtener el ID del usuario autenticado
            $usuarioId = Auth::id();

            // Verificar si el usuario autenticado es el propietario de la gerencia
            if ($gerencia->usuario_id === $usuarioId) {
                $users = User::with('persona')->get();
                return view('subgerencias.create', compact('gerencia', 'users'));
            }

            if (auth()->user()->rol->privilegios->contains('nombre', 'Acceso Total')) {
                $users = User::with('persona')->get();
                return view('subgerencias.create', compact('gerencia', 'users'));
            }

            // Verificar si el usuario es un subusuario relacionado con alguna subgerencia de la gerencia
            $subusuario = Subusuario::whereHas('subgerencia', function ($query) use ($gerencia) {
                $query->where('gerencia_id', $gerencia->id);
            })->where('user_id', $usuarioId)->first();

            if ($subusuario) {
                $users = User::with('persona')->get();
                return view('subgerencias.create', compact('gerencia', 'users'));
            }

            // Si no pertenece ni a la gerencia ni a una subgerencia, denegar acceso
            abort(403, 'No tienes permiso para acceder a esta gerencia.');
        } else {
            // Si no tiene los permisos, bloquea el acceso
            abort(403, 'No tienes permiso para realizar esta acción');
        }
    }

    public function store(Request $request, Gerencia $gerencia)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string|max:500',
            'telefono' => 'required|string',
            'direccion' => 'required|string|max:100',
            'estado' => 'required|string|max:20',
        ]);

        $gerencia->subgerencias()->create([
            'usuario_id' => auth()->user()->id, // Asigna el usuario autenticado
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'estado' => $request->estado,
        ]);

        return redirect()->route('gerencias.show', $gerencia->id)->with('success', 'Subgerencia creada correctamente.');
    }

    // public function edit(Gerencia $gerencia)
    // {
    //     // Consulta para obtener todos los usuarios que pueden ser seleccionados como gerentes
    //     $users = User::with('persona')->get();

    //     // Retornamos la vista de edición pasando la gerencia y la lista de usuarios
    //     return view('gerencias.edit', compact('gerencia', 'users'));
    // }


    public function edit(Gerencia $gerencia, Subgerencia $subgerencia)
    {
        if (auth()->user()->rol->privilegios->contains('nombre', 'Acceso Total') || auth()->user()->rol->privilegios->contains('nombre', 'Acceso Gerencia') || auth()->user()->rol->nombre === 'SubGerente') {

            // Obtener el ID del usuario autenticado
            $usuarioId = Auth::id();

            // Verificar si el usuario autenticado es el propietario de la gerencia
            if ($gerencia->usuario_id === $usuarioId) {
                $users = User::with('persona')->get();
                return view('subgerencias.edit', compact('gerencia', 'subgerencia', 'users'));
            }

            // Verificar si el usuario tiene el privilegio de "Acceso Total"
            if (auth()->user()->rol->privilegios->contains('nombre', 'Acceso Total')) {
                $users = User::with('persona')->get();
                return view('subgerencias.edit', compact('gerencia', 'subgerencia', 'users'));
            }

            // Verificar si el usuario es un subusuario relacionado con alguna subgerencia de la gerencia
            $subusuario = Subusuario::whereHas('subgerencia', function ($query) use ($gerencia) {
                $query->where('gerencia_id', $gerencia->id);
            })->where('user_id', $usuarioId)->first();

            if ($subusuario) {
                $users = User::with('persona')->get();
                return view('subgerencias.edit', compact('gerencia', 'subgerencia', 'users'));
            }

            // Si no pertenece ni a la gerencia ni a una subgerencia, denegar acceso
            abort(403, 'No tienes permiso para acceder a esta gerencia.');
        } else {
            // Si no tiene los permisos, bloquea el acceso
            abort(403, 'No tienes permiso para realizar esta acción');
        }
    }


    public function update(Request $request, Gerencia $gerencia, Subgerencia $subgerencia)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string|max:500',
            'telefono' => 'required|string',
            'direccion' => 'required|string|max:100',
            'estado' => 'required|string|max:20',
            'usuario_id' => 'required',
        ]);

        $subgerencia->update($request->all());

        return redirect()->route('gerencias.show', $gerencia->id)->with('success', 'Subgerencia actualizada correctamente.');
    }

    public function destroy(Gerencia $gerencia, Subgerencia $subgerencia)
    {
        if (auth()->user()->rol->privilegios->contains('nombre', 'Acceso Total')  || auth()->user()->rol->nombre === 'Gerente') {
            $subgerencia->delete();
            return redirect()->route('gerencias.show', $gerencia->id)->with('success', 'Subgerencia eliminada correctamente.');
        } else {
            // Si no tiene los permisos, bloquea el acceso
            abort(403, 'No tienes permiso para realizar esta acción');
        }
    }

    public function show($id)
    {
        // Obtener la gerencia
        $gerencia = Gerencia::find($id);

        // Verificar si la gerencia existe
        if (!$gerencia) {
            abort(404, 'Gerencia no encontrada.');
        }

        // Obtener el ID del usuario autenticado
        $usuarioId = auth()->id();

        // Verificar si el usuario es propietario o subgerente
        $isOwner = $gerencia->usuario_id === $usuarioId;
        $isSubgerente = Subusuario::whereHas('subgerencia', function ($query) use ($gerencia) {
            $query->where('gerencia_id', $gerencia->id);
        })->where('usuario_id', $usuarioId)->exists();

        if (!$isOwner && !$isSubgerente) {
            return redirect()->back()->with('error', 'No tienes gerencias asignadas.');
        }

        // Si es propietario o subgerente, mostrar la vista
        return view('gerencias.show', compact('gerencia'));
    }
}

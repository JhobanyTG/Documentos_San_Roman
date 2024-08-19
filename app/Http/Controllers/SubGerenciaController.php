<?php

namespace App\Http\Controllers;

use App\Models\Gerencia;
use App\Models\Subgerencia;
use Illuminate\Http\Request;
use App\Models\User;

class SubgerenciaController extends Controller
{
    public function create(Gerencia $gerencia)
    {
        $users = User::with('persona')->get();
        return view('subgerencias.create', compact('gerencia', 'users'));
        // return view('subgerencias.create', compact());
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
        $users = User::with('persona')->get();
        return view('subgerencias.edit', compact('gerencia', 'subgerencia', 'users'));
    }

    public function update(Request $request, Gerencia $gerencia, Subgerencia $subgerencia)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string|max:500',
            'telefono' => 'required|string',
            'direccion' => 'required|string|max:100',
            'estado' => 'required|string|max:20',
        ]);

        $subgerencia->update($request->only('nombre', 'descripcion', 'telefono', 'direccion', 'estado'));

        return redirect()->route('gerencias.show', $gerencia->id)->with('success', 'Subgerencia actualizada correctamente.');
    }

    public function destroy(Gerencia $gerencia, Subgerencia $subgerencia)
    {
        $subgerencia->delete();
        return redirect()->route('gerencias.show', $gerencia->id)->with('success', 'Subgerencia eliminada correctamente.');
    }
}


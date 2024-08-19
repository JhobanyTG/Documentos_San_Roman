<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\User;
use App\Models\Privilegio;
use Illuminate\Http\Request;

class RolController extends Controller
{
    // Muestra la lista de roles
    public function index()
    {
        $roles = Rol::with('privilegios')->get();
        return view('rol.index', compact('roles'));

    }

    // Muestra el formulario para crear un nuevo rol
    public function create()
    {
        // return view('rol.create');
        $all_privilegios = Privilegio::all();
        return view('rol.create', compact('all_privilegios'));


    }

    // Almacena un nuevo rol en la base de datos
    public function store(Request $request)
    {
        // Validar los datos del rol
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'privilegios' => 'nullable|string'
        ]);

        // Crear el nuevo rol
        $role = Rol::create($request->only('nombre', 'descripcion'));

        // Obtener los privilegios del request
        $privilegios = $request->input('privilegios', '');
        $privilegiosArray = array_filter(explode(',', $privilegios), function($privilegio) {
            return is_numeric($privilegio) && (int)$privilegio > 0;
        });

        // Asignar los privilegios al rol
        $role->privilegios()->sync($privilegiosArray);

        return redirect()->route('roles.index')->with('success', 'Rol creado con éxito');
    }


    // Muestra el formulario para editar un rol existente
    public function edit($id)
    {
        $role = Rol::findOrFail($id);
        $all_privilegios = Privilegio::all();
        return view('rol.edit', compact('role','all_privilegios'));
    }

    // Actualiza un rol existente en la base de datos
    public function update(Request $request, $id)
    {
        $role = Rol::findOrFail($id);

        // Actualizar los campos nombre y descripción
        $role->update($request->only('nombre', 'descripcion'));

        // Obtener los privilegios del request
        $privilegios = $request->input('privilegios', '');
        $privilegiosArray = array_filter(explode(',', $privilegios), function($privilegio) {
            return is_numeric($privilegio) && (int)$privilegio > 0;
        });

        // Actualizar los privilegios asignados al rol
        $role->privilegios()->sync($privilegiosArray);

        return redirect()->route('roles.index')->with('success', 'Rol actualizado con éxito');
    }


    // Muestra los detalles de un rol
    public function show(Rol $rol)
    {
        return view('roles.show', compact('rol'));
    }

    // Elimina un rol existente de la base de datos
    public function destroy($id)
    {
        $role = Rol::findOrFail($id);

        // Verifica si hay usuarios asociados a este rol
        $usersWithRole = User::where('rol_id', $role->id)->count();

        if ($usersWithRole > 0) {
            // Si hay usuarios asociados, no elimines el rol y muestra un mensaje de error
            return redirect()->route('roles.index')->with('error', 'No se puede eliminar el rol porque está asignado a uno o más usuarios.');
        }

        // Si no hay usuarios asociados, elimina el rol
        $role->delete();

        // Redirige con mensaje de éxito
        return redirect()->route('roles.index')->with('success', 'Rol eliminado exitosamente.');
    }

}

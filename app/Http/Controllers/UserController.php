<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('persona', 'rol')->get();
        return view('user.index', compact('users'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Rol::all();
        return view('user.edit', compact('user', 'roles'));
    }

    public function create()
    {
        $roles = Rol::all();
        return view('user.create', compact('roles'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'rol_id' => 'required|integer',
            'nombre_usuario' => 'required|string|max:100',
            'email' => 'required|string|email|max:254',
            'password' => 'nullable|string|min:8',
            'estado' => 'required|string|max:20',
        ]);

        try {
            $user = User::findOrFail($id);
            $user->update([
                'rol_id' => $validatedData['rol_id'],
                'nombre_usuario' => $validatedData['nombre_usuario'],
                'email' => $validatedData['email'],
                'password' => $validatedData['password'] ? Hash::make($validatedData['password']) : $user->password,
                'estado' => $validatedData['estado'],
            ]);

            return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente.');
        } catch (\Exception $e) {
            // Imprime el error para depuración
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }




    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('user.show', compact('user'));
    }

    public function destroy($id)
    {
        $role = Rol::findOrFail($id);

        // Establecer el campo rol_id a null para los usuarios asociados
        User::where('rol_id', $role->id)->update(['rol_id' => null]);

        // Ahora puedes eliminar el rol
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Rol eliminado exitosamente.');
    }

}

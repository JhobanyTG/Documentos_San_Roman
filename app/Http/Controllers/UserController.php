<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = User::with('persona', 'rol');

        // Verificar si el usuario tiene el rol de 'SuperAdmin'
        if ($user->rol->nombre == 'SuperAdmin') {
            // Si es 'SuperAdmin', mostrar todos los usuarios sin restricciones
            $users = $query->get();
        } else {
            // Filtrar usuarios según la gerencia o subgerencia del usuario autenticado
            if ($user->subusuario) {
                // Si es un subusuario, obtener su subgerencia y gerencia
                $subgerencia = $user->subusuario->subgerencia;
                $gerencia = $subgerencia->gerencia;

                // Filtrar usuarios que pertenezcan a la misma subgerencia o gerencia
                $query->whereHas('subusuario.subgerencia', function ($q) use ($subgerencia) {
                    $q->where('id', $subgerencia->id);
                })->orWhereHas('gerencia', function ($q) use ($gerencia) {
                    $q->where('id', $gerencia->id);
                });
            } elseif ($user->gerencia) {
                // Si el usuario está directamente asociado a una gerencia
                $gerencia = $user->gerencia;

                // Filtrar usuarios que pertenezcan a la misma gerencia o subgerencias relacionadas
                $query->whereHas('gerencia', function ($q) use ($gerencia) {
                    $q->where('id', $gerencia->id); // Acceder correctamente a gerencias.id
                })
                    ->orWhereHas('subusuario.subgerencia', function ($q) use ($gerencia) {
                        $q->where('gerencia_id', $gerencia->id);
                    });
            } else {
                // Si no tiene una gerencia ni subgerencia asociada, mostrar solo su propio registro de usuario
                $query->where('id', $user->id);
            }

            $users = $query->get();
        }

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
            'estado' => 'required|string|in:Activo,Inactivo',
        ]);

        try {
            $user = User::findOrFail($id);

            // Si se ha subido un avatar
            if ($request->hasFile('avatar')) {
                // Elimina el avatar viejo si existe
                if ($user->avatar) {
                    Storage::delete('public/avatars/' . $user->avatar);
                }

                // Guarda el nuevo avatar
                $file = $request->file('avatar');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/avatars', $filename);
                $user->avatar = $filename;
            }

            $user->update([
                'rol_id' => $validatedData['rol_id'],
                'nombre_usuario' => $validatedData['nombre_usuario'],
                'email' => $validatedData['email'],
                'estado' => $request->input('estado'),
            ]);

            $user->save();

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
        $users = User::findOrFail($id);
        $users->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente.');
    }

    public function cambiarContrasena($id)
    {
        $user = User::findOrFail($id);
        return view('user.cambiarContrasena', compact('user'));
    }

    public function actualizarContrasena(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:8',
        ]);

        $user = User::findOrFail($id);
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('usuarios.index')->with('success', 'Contraseña actualizada exitosamente.');
    }
}

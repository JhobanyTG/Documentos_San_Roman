<?php

namespace App\Http\Controllers;

use App\Models\Gerencia;
use App\Models\Subgerencia;
use App\Models\Subusuario;
use App\Models\User;
use App\Models\Rol;
use App\Models\Persona;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class SubUsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Gerencia $gerencia, Subgerencia $subgerencia)
    {
        // Obtén todos los subusuarios de la subgerencia específica
        $subusuarios = $subgerencia->subusuarios()->with('user.persona')->get();

        return view('subusuarios.index', compact('gerencia', 'subgerencia', 'subusuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Gerencia $gerencia)
    {
        // Obtener todos los roles disponibles usando el modelo correcto
        $roles = Rol::all();

        // Obtener las subgerencias que pertenecen a la gerencia actual
        $subgerencias = Subgerencia::where('gerencia_id', $gerencia->id)->get();

        return view('subusuarios.create', compact('gerencia', 'subgerencias', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Gerencia $gerencia)
    {
        // Validar los datos
        $validatedData = $request->validate([
            'dni' => 'required|integer',
            'nombres' => 'required|string|max:100',
            'apellido_p' => 'required|string|max:50',
            'apellido_m' => 'required|string|max:50',
            'f_nacimiento' => 'required|date',
            'celular' => 'required|string|max:15',
            'direccion' => 'required|string|max:200',
            'rol_id' => 'required|integer',
            'nombre_usuario' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users,email',
            'password' => 'required|string|min:8',
            'estado' => 'required|string|max:20',
            'subgerencia_id' => 'required|integer',
            'cargo' => 'required|string|max:100',
        ]);

        // Validar la longitud de la contraseña
        if (strlen($request->password) < 8) {
            return redirect()->route('subusuarios.create', $gerencia->id)
                ->withErrors(['password' => 'La contraseña debe tener al menos 8 caracteres.'])
                ->withInput();
        }

        // Crear la persona
        $persona = Persona::create([
            'dni' => $validatedData['dni'],
            'nombres' => $validatedData['nombres'],
            'apellido_p' => $validatedData['apellido_p'],
            'apellido_m' => $validatedData['apellido_m'],
            'f_nacimiento' => $validatedData['f_nacimiento'],
            'celular' => $validatedData['celular'],
            'direccion' => $validatedData['direccion'],
        ]);

        // Crear el usuario asociado a la persona
        $user = User::create([
            'rol_id' => $validatedData['rol_id'],
            'persona_id' => $persona->id,
            'nombre_usuario' => $validatedData['nombre_usuario'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'estado' => $validatedData['estado'],
        ]);

        // Crear el subusuario con el user_id recién creado
        Subusuario::create([
            'user_id' => $user->id,
            'subgerencia_id' => $validatedData['subgerencia_id'],
            'cargo' => $validatedData['cargo'],
        ]);
        return redirect()->route('gerencia.show')->with('success', 'Subusuario creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Gerencia $gerencia, Subgerencia $subgerencia, Subusuario $subusuario)
    {
        return view('subusuarios.show', compact('gerencia', 'subgerencia', 'subusuario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gerencia $gerencia, Subgerencia $subgerencia, Subusuario $subusuario)
    {
        // Obtener los roles disponibles
        $roles = Rol::all();

        // Obtener las subgerencias que pertenecen a la gerencia actual
        $subgerencias = Subgerencia::where('gerencia_id', $gerencia->id)->get();

        // Obtener los usuarios disponibles para seleccionar (si lo necesitas)
        $users = User::with('persona')->get();

        return view('subusuarios.edit', compact('gerencia', 'subgerencia', 'subusuario', 'users', 'roles', 'subgerencias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gerencia $gerencia, Subgerencia $subgerencia, Subusuario $subusuario)
    {
        $request->validate([
            'dni' => 'required|integer',
            'nombres' => 'required|string|max:100',
            'apellido_p' => 'required|string|max:50',
            'apellido_m' => 'required|string|max:50',
            'f_nacimiento' => 'required|date',
            'celular' => 'required|string|max:15',
            'direccion' => 'required|string|max:200',
            'rol_id' => 'required|integer',
            'nombre_usuario' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users,email,' . $subusuario->user_id,
            'estado' => 'required|string|max:20',
            'subgerencia_id' => 'required|integer',
            'cargo' => 'required|string|max:100',
        ]);

        // Actualizar la persona
        $subusuario->user->persona->update([
            'dni' => $request->dni,
            'nombres' => $request->nombres,
            'apellido_p' => $request->apellido_p,
            'apellido_m' => $request->apellido_m,
            'f_nacimiento' => $request->f_nacimiento,
            'celular' => $request->celular,
            'direccion' => $request->direccion,
        ]);

        // Actualizar el usuario asociado a la persona
        $subusuario->user->update([
            'rol_id' => $request->rol_id,
            'nombre_usuario' => $request->nombre_usuario,
            'email' => $request->email,
            'estado' => $request->estado,
        ]);

        // Actualizar el subusuario
        $subusuario->update([
            'subgerencia_id' => $request->subgerencia_id,
            'cargo' => $request->cargo,
        ]);

        return redirect()->route('gerencias.show', $gerencia->id)->with('success', 'Subusuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gerencia $gerencia, Subgerencia $subgerencia, Subusuario $subusuario)
    {
        $user = $subusuario->user;
        $persona = $user->persona;

        // Eliminar el subusuario
        $subusuario->delete();

        // Eliminar el usuario y luego la persona asociada
        $user->delete();
        $persona->delete();

        return redirect()->route('gerencias.show', $gerencia->id)->with('success', 'Subusuario eliminado exitosamente.');
    }
}

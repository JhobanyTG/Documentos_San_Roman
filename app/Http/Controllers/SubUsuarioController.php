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
use Illuminate\Support\Facades\Auth;


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
        if (auth()->user()->rol->privilegios->contains('nombre', 'Acceso Total') || auth()->user()->rol->privilegios->contains('nombre', 'Acceso Gerencia') || auth()->user()->rol->nombre === 'SubGerente') {

            // Obtener el ID del usuario autenticado
            $usuarioId = Auth::id();

            // Verificar si el usuario autenticado es el propietario de la gerencia
            if ($gerencia->usuario_id === $usuarioId) {
                // Obtener todos los roles disponibles
                $roles = Rol::all();
                // Obtener las subgerencias que pertenecen a la gerencia actual
                $subgerencias = Subgerencia::where('gerencia_id', $gerencia->id)->get();
                return view('subusuarios.create', compact('gerencia', 'subgerencias', 'roles'));
            }

            if (auth()->user()->rol->privilegios->contains('nombre', 'Acceso Total')) {
                // Obtener todos los roles disponibles
                $roles = Rol::all();
                // Obtener las subgerencias que pertenecen a la gerencia actual
                $subgerencias = Subgerencia::where('gerencia_id', $gerencia->id)->get();
                return view('subusuarios.create', compact('gerencia', 'subgerencias', 'roles'));
            }

            // Verificar si el usuario es un subusuario relacionado con alguna subgerencia de la gerencia
            $subusuario = Subusuario::whereHas('subgerencia', function ($query) use ($gerencia) {
                $query->where('gerencia_id', $gerencia->id);
            })->where('user_id', $usuarioId)->first();

            if ($subusuario) {
                // Obtener todos los roles disponibles
                $roles = Rol::all();
                // Obtener las subgerencias que pertenecen a la gerencia actual
                $subgerencias = Subgerencia::where('gerencia_id', $gerencia->id)->get();
                return view('subusuarios.create', compact('gerencia', 'subgerencias', 'roles'));
            }

            // Si no pertenece ni a la gerencia ni a una subgerencia, denegar acceso
            abort(403, 'No tienes permiso para acceder a esta gerencia.');
        } else {
            // Si no tiene los permisos, bloquea el acceso
            abort(403, 'No tienes permiso para realizar esta acción');
        }
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
        return redirect()->route('gerencias.show', $gerencia->id)->with('success', 'Subusuario creado exitosamente.');
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
        if (auth()->user()->rol->privilegios->contains('nombre', 'Acceso Total') || auth()->user()->rol->privilegios->contains('nombre', 'Acceso Gerencia') || auth()->user()->rol->nombre === 'SubGerente') {

            // Obtener el ID del usuario autenticado
            $usuarioId = Auth::id();

            // Verificar si el usuario autenticado es el propietario de la gerencia
            if ($gerencia->usuario_id === $usuarioId) {
                // Obtener todos los roles disponibles
                $roles = Rol::all();
                // Obtener las subgerencias que pertenecen a la gerencia actual
                $subgerencias = Subgerencia::where('gerencia_id', $gerencia->id)->get();
                // Obtener los usuarios disponibles para seleccionar
                $users = User::with('persona')->get();
                return view('subusuarios.edit', compact('gerencia', 'subgerencia', 'subusuario', 'users', 'roles', 'subgerencias'));
            }

            // Verificar si el usuario tiene el privilegio de "Acceso Total"
            if (auth()->user()->rol->privilegios->contains('nombre', 'Acceso Total')) {
                // Obtener todos los roles disponibles
                $roles = Rol::all();
                // Obtener las subgerencias que pertenecen a la gerencia actual
                $subgerencias = Subgerencia::where('gerencia_id', $gerencia->id)->get();
                // Obtener los usuarios disponibles para seleccionar
                $users = User::with('persona')->get();
                return view('subusuarios.edit', compact('gerencia', 'subgerencia', 'subusuario', 'users', 'roles', 'subgerencias'));
            }

            // Verificar si el usuario es un subusuario relacionado con alguna subgerencia de la gerencia
            $subusuario = Subusuario::whereHas('subgerencia', function ($query) use ($gerencia) {
                $query->where('gerencia_id', $gerencia->id);
            })->where('user_id', $usuarioId)->first();

            if ($subusuario) {
                // Obtener todos los roles disponibles
                $roles = Rol::all();
                // Obtener las subgerencias que pertenecen a la gerencia actual
                $subgerencias = Subgerencia::where('gerencia_id', $gerencia->id)->get();
                // Obtener los usuarios disponibles para seleccionar
                $users = User::with('persona')->get();
                return view('subusuarios.edit', compact('gerencia', 'subgerencia', 'subusuario', 'users', 'roles', 'subgerencias'));
            }

            // Si no pertenece ni a la gerencia ni a una subgerencia, denegar acceso
            abort(403, 'No tienes permiso para acceder a esta gerencia.');
        } else {
            // Si no tiene los permisos, bloquea el acceso
            abort(403, 'No tienes permiso para realizar esta acción');
        }
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
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048' // Validación para el avatar
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

        // Verificar si se ha subido un nuevo avatar
        if ($request->hasFile('avatar')) {
            // Obtener el archivo subido
            $avatarFile = $request->file('avatar');

            // Generar un nombre único para el archivo
            $avatarName = time() . '_' . $avatarFile->getClientOriginalName();

            // Mover el archivo al directorio de almacenamiento
            $avatarPath = $avatarFile->storeAs('avatars', $avatarName, 'public');

            // Actualizar el avatar en el modelo Persona
            $subusuario->user->persona->update([
                'avatar' => $avatarPath,
            ]);
        }

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
        if (auth()->user()->rol->privilegios->contains('nombre', 'Acceso Total') || auth()->user()->rol->privilegios->contains('nombre', 'Acceso Gerencia') || auth()->user()->rol->nombre === 'SubGerente') {

            $user = $subusuario->user;
            $persona = $user->persona;

            // Eliminar el subusuario
            $subusuario->delete();

            // Eliminar el usuario y luego la persona asociada
            $user->delete();
            $persona->delete();

            return redirect()->route('gerencias.show', $gerencia->id)->with('success', 'Subusuario eliminado exitosamente.');
        } else {
            // Si no tiene los permisos, bloquea el acceso
            abort(403, 'No tienes permiso para realizar esta acción');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PersonaController extends Controller
{
    public function index()
    {
        $personas = Persona::with('user')->get();
        return view('persona.index', compact('personas'));
    }

    public function create()
    {
        $roles = Rol::all();
        return view('persona.create', compact('roles'));
    }

    public function store(Request $request)
    {
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
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Manejo del archivo de avatar
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $timestamp = time();
            $filename = $originalName . '-' . $timestamp . '.' . $extension;

            // Asegúrate de que el nombre del archivo sea único
            while (Storage::disk('public')->exists('avatars/' . $filename)) {
                $timestamp++;
                $filename = $originalName . '-' . $timestamp . '.' . $extension;
            }

            $avatarPath = $file->storeAs('avatars', $filename, 'public');
        }

        $persona = Persona::create([
            'dni' => $validatedData['dni'],
            'nombres' => $validatedData['nombres'],
            'apellido_p' => $validatedData['apellido_p'],
            'apellido_m' => $validatedData['apellido_m'],
            'f_nacimiento' => $validatedData['f_nacimiento'],
            'celular' => $validatedData['celular'],
            'direccion' => $validatedData['direccion'],
            'avatar' => $avatarPath,
        ]);

        $user = User::create([
            'rol_id' => $validatedData['rol_id'],
            'persona_id' => $persona->id,
            'nombre_usuario' => $validatedData['nombre_usuario'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'estado' => $validatedData['estado'],
        ]);

        return redirect()->route('personas.index')->with('success', 'Persona y usuario creados exitosamente.');
    }


    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'dni' => 'required|string|max:20',
            'nombres' => 'required|string|max:100',
            'apellido_p' => 'required|string|max:100',
            'apellido_m' => 'required|string|max:100',
            'f_nacimiento' => 'required|date',
            'celular' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $persona = Persona::findOrFail($id);
        // Manejo del archivo del avatar
        $avatarPath = $persona->avatar; // Mantiene el avatar actual si no se sube uno nuevo
        if ($request->hasFile('avatar')) {
            // Elimina el avatar antiguo si existe
            if ($persona->avatar && Storage::exists('public/' . $persona->avatar)) {
                Storage::delete('public/' . $persona->avatar);
            }

            $file = $request->file('avatar');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $timestamp = time();
            $filename = $originalName . '-' . $timestamp . '.' . $extension;

            // Asegúrate de que el nombre del archivo sea único
            while (Storage::disk('public')->exists('avatars/' . $filename)) {
                $timestamp++;
                $filename = $originalName . '-' . $timestamp . '.' . $extension;
            }

            $avatarPath = $file->storeAs('avatars', $filename, 'public');
        }



        $persona->update([
            'dni' => $request->input('dni'),
            'nombres' => $request->input('nombres'),
            'apellido_p' => $request->input('apellido_p'),
            'apellido_m' => $request->input('apellido_m'),
            'f_nacimiento' => $request->input('f_nacimiento'),
            'celular' => $request->input('celular'),
            'direccion' => $request->input('direccion'),
            'avatar' => $avatarPath,
        ]);

        // $user = User::where('persona_id', $id)->firstOrFail();
        // $userData = [
        //     'rol_id' => $request->input('rol_id'),
        //     'nombre_usuario' => $request->input('nombre_usuario'),
        //     'email' => $request->input('email'),
        //     'estado' => $request->input('estado'),
        // ];

        // $user->update($userData);

        return redirect()->route('personas.index')->with('success', 'Persona y usuario actualizados exitosamente.');
    }


    public function edit($id)
    {
        // Obtener la persona con el ID proporcionado
        $persona = Persona::findOrFail($id);

        // Obtener los roles disponibles para seleccionar en el formulario
        $roles = Rol::all();

        // Obtener el usuario asociado a la persona para completar el formulario
        $user = User::where('persona_id', $id)->firstOrFail();

        // Pasar los datos a la vista de edición
        return view('persona.edit', compact('persona', 'roles', 'user'));
    }



    public function show($id)
    {
        $persona = Persona::with('user')->findOrFail($id);
        $user = User::where('persona_id', $id)->firstOrFail();
        return view('user.show', compact('user', 'persona'));
    }


    public function destroy($id)
    {
        $persona = Persona::findOrFail($id);
            // Elimina la imagen asociada si existe
        if ($persona->avatar && Storage::exists('public/' . $persona->avatar)) {
            Storage::delete('public/' . $persona->avatar);
        }
        $persona->delete();

        return redirect()->route('personas.index')->with('success', 'Persona eliminada exitosamente.');
    }


}



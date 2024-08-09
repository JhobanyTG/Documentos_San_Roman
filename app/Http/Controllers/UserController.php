<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()->role == 'SuperAdmin') {
            $users = User::all();
            return view('user.index')->with('users', $users);
        } elseif (auth()->user()->role == 'Admin') {
            return redirect()->to('/documentos');
        } else {
            return redirect()->to('/');
        }
    }
    /**
     * Show the form for creating sa new resource.
     */
    public function create(){
        if(auth()->user()->role == 'SuperAdmin'){
            return view('auth.register');
        } elseif (auth()->user()->role == 'Admin') {
            return redirect()->to('/documentos');
        } else{
            return redirect()->to('/');
        }
    }

    public function store(){
        $this->validate(request(),[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'role' => 'required',
        ]);


        $user = User::create(request(['name', 'email', 'password', 'role']));
        return redirect()->to('/usuarios')->with('success', 'Se ha registrado un Nuevo usuario.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if(auth()->user()->role == 'SuperAdmin'){
            $users = User::findOrFail($id);
            return view('user.show', compact('users'));
        } elseif (auth()->user()->role == 'Admin') {
            return redirect()->to('/documentos');
        } else{
            return redirect()->to('/');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {


        if(auth()->user()->role == 'SuperAdmin'){
            $users = User::findOrFail($id);
            return view('user.edit', compact('users'));
        } elseif (auth()->user()->role == 'Admin') {
            return redirect()->to('/documentos');
        } else{
            return redirect()->to('/');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if(auth()->user()->role == 'SuperAdmin'){
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'role' => 'required|string|max:100',
            ]);
            $users = User::findOrFail($id);
            $users->name = $request->input('name');
            $users->email = $request->input('email');
            $users->role = $request->input('role');
            $users->save();

            return redirect()->route('usuarios.index', $users->id)
                    ->with('success', 'Usuario actualizado exitosamente.');
        } elseif (auth()->user()->role == 'Admin') {
            return redirect()->to('/documentos');
        } else{
            return redirect()->to('/');
        }
    }

    public function cambiarContrasena($id)
    {
        $user = User::findOrFail($id);
        return view('user.cambiarContrasena', compact('user'));
    }

    public function actualizarContrasena(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:5|confirmed',
        ]);

        $user = User::findOrFail($id);
        $user->password = bcrypt($request->input('password'));
        $user->save();

        return redirect()->route('usuarios.show', $user->id)
                        ->with('success', 'Contraseña del usuario ' . $user->name . ' actualizada exitosamente.');
    }

    public function destroy($id)
    {
        if(auth()->user()->role == 'SuperAdmin'){
            $users = User::findOrFail($id);
            $users->delete();

            return redirect()->route('usuarios.index')
            ->with('success', 'El usuario ha sido eliminado exitosamente.');
        } elseif (auth()->user()->role == 'Admin') {
            return redirect()->to('/documentos');
        } else{
            return redirect()->to('/');
        }
    }
}

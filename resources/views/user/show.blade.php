@extends('layout/template')

@section('title', 'Detalle del Usuario')

@section('content')
    <div class="container mt-4">
        <h2>Detalle del Usuario</h2>
        <div class="card">
            <div class="card-body">
                <p class="card-text"><strong>DNI:</strong> {{ $user->persona->dni }}</p>
                <p class="card-text"><strong>Nombres:</strong> {{ $user->persona->nombres }}</p>
                <p class="card-text"><strong>Apellido Paterno:</strong> {{ $user->persona->apellido_p }}</p>
                <p class="card-text"><strong>Apellido Materno:</strong> {{ $user->persona->apellido_m }}</p>
                <p class="card-text"><strong>Fecha de Nacimiento:</strong> {{ $user->persona->f_nacimiento }}</p>
                <p class="card-text"><strong>Celular:</strong> {{ $user->persona->celular }}</p>
                <p class="card-text"><strong>Dirección:</strong> {{ $user->persona->direccion }}</p>
                <p class="card-text"><strong>Nombre de Usuario:</strong> {{ $user->nombre_usuario }}</p>
                <p class="card-text"><strong>Email:</strong> {{ $user->email }}</p>
                <p class="card-text"><strong>Estado:</strong> {{ $user->estado }}</p>
                <p class="card-text"><strong>Rol:</strong> {{ $user->rol->nombre }}</p>
            </div>
        </div>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Regresar</a>

    </div>
@endsection

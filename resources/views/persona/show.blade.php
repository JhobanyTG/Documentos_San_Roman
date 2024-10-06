@extends('layout/template')

@section('title', 'Detalles de Persona')

@section('content')

    <div class="container mt-4">
        <h2>Detalle del Persona</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Información de Persona</h5>
                <p class="card-text"><strong>DNI:</strong> {{ $persona->dni }}</p>
                <p class="card-text"><strong>Nombres:</strong> {{ $persona->nombres }}</p>
                <p class="card-text"><strong>Apellido Paterno:</strong> {{ $persona->apellido_p }}</p>
                <p class="card-text"><strong>Apellido Materno:</strong> {{ $persona->apellido_m }}</p>
                <p class="card-text"><strong>Fecha de Nacimiento:</strong> {{ $persona->f_nacimiento }}</p>
                <p class="card-text"><strong>Celular:</strong> {{ $persona->celular }}</p>
                <p class="card-text"><strong>Dirección:</strong> {{ $persona->direccion }}</p>

                <h5 class="card-title">Información de Usuario</h5>
                <p class="card-text"><strong>Nombre de Usuario:</strong> {{ $persona->user->nombre_usuario }}</p>
                <p class="card-text"><strong>Email:</strong> {{ $persona->useremail }}</p>
                <p class="card-text"><strong>Estado:</strong> {{ $persona->user->estado }}</p>
                <p class="card-text"><strong>Rol:</strong> {{ $persona->user->rol->nombre }}</p>
            </div>
        </div>
        <a href="{{ route('personas.index') }}" class="btn btn-secondary">Regresar</a>

    </div>
@endsection

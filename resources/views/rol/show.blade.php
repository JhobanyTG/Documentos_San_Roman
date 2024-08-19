@extends('layout/template')

@section('title', 'Detalles del Rol')

@section('content')
    <div class="container mt-4">
        <h2>Detalles del Rol</h2>

        <div class="card">
            <div class="card-header">
                Rol: {{ $role->nombre }}
            </div>
            <div class="card-body">
                <p><strong>ID:</strong> {{ $role->id }}</p>
                <p><strong>Nombre:</strong> {{ $role->nombre }}</p>
                <p><strong>Descripción:</strong> {{ $role->descripcion }}</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning">Editar</a>
                <a href="{{ route('roles.index') }}" class="btn btn-secondary">Volver a la Lista</a>
            </div>
        </div>
    </div>
@endsection

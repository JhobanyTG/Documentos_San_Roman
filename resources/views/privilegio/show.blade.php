@extends('layout/template')

@section('title', 'Detalles del Privilegio')

@section('content')
    <div class="container mt-4">
        <h2>Detalles del Privilegio</h2>
        <a href="{{ route('privilegios.index') }}" class="btn btn-secondary mb-3">Volver a la Lista</a>
        <div class="mb-3">
            <strong>Nombre:</strong>
            <p>{{ $privilegio->nombre }}</p>
        </div>
        <div class="mb-3">
            <strong>Descripción:</strong>
            <p>{{ $privilegio->descripcion }}</p>
        </div>
        <a href="{{ route('privilegios.edit', $privilegio->id) }}" class="btn btn-warning">Editar</a>
        <form action="{{ route('privilegios.destroy', $privilegio->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este privilegio?')">Eliminar</button>
        </form>
    </div>
@endsection

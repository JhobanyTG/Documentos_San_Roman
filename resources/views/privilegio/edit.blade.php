@extends('layout/template')

@section('title', 'Editar Privilegio')

@section('content')
    <div class="container mt-4">
        <h2>Editar Privilegio</h2>
        <a href="{{ route('privilegios.index') }}" class="btn btn-secondary mb-3">Volver a la Lista</a>

        <form action="{{ route('privilegios.update', $privilegio->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre', $privilegio->nombre) }}" required>
                @error('nombre')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea id="descripcion" name="descripcion" class="form-control" rows="3" required>{{ old('descripcion', $privilegio->descripcion) }}</textarea>
                @error('descripcion')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
    </div>
@endsection

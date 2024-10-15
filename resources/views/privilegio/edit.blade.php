@extends('layout/template')

@section('title', 'Editar Privilegio')

@section('content')
    <div class="container mt-4 form_privilegio">
        <h2 class="form_title_privi">Editar Privilegio</h2>


        <form action="{{ route('privilegios.update', $privilegio->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-control privi" value="{{ old('nombre', $privilegio->nombre) }}" required>
                @error('nombre')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea id="descripcion" name="descripcion" class="form-control privi" rows="3" required>{{ old('descripcion', $privilegio->descripcion) }}</textarea>
                @error('descripcion')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <a href="{{ route('privilegios.index') }}" class="btn btn-secondary mb-3 btn-privi"><i
                class="fa fa-arrow-circle-left" aria-hidden="true"></i> Cancelar</a>
            <button type="submit" class="btn btn-primary mb-3 btn-privi"><i class="fa fa-save" aria-hidden="true"></i> Guardar Cambios</button>
        </form>
    </div>
@endsection

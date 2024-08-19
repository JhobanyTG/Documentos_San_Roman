@extends('layout/template')

@section('title', 'Editar Persona y Usuario')

@section('content')
    <div class="container mt-4">
        <h2>Editar Persona y Usuario</h2>
        <form action="{{ route('personas.update', $persona->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="dni">DNI:</label>
                <input type="text" class="form-control" id="dni" name="dni" value="{{ $persona->dni }}" required>
            </div>
            <div class="form-group">
                <label for="nombres">Nombres:</label>
                <input type="text" class="form-control" id="nombres" name="nombres" value="{{ $persona->nombres }}" required>
            </div>
            <div class="form-group">
                <label for="apellido_p">Apellido Paterno:</label>
                <input type="text" class="form-control" id="apellido_p" name="apellido_p" value="{{ $persona->apellido_p }}" required>
            </div>
            <div class="form-group">
                <label for="apellido_m">Apellido Materno:</label>
                <input type="text" class="form-control" id="apellido_m" name="apellido_m" value="{{ $persona->apellido_m }}" required>
            </div>
            <div class="form-group">
                <label for="f_nacimiento">Fecha de Nacimiento:</label>
                <input type="date" class="form-control" id="f_nacimiento" name="f_nacimiento" value="{{ $persona->f_nacimiento }}" required>
            </div>
            <div class="form-group">
                <label for="celular">Celular:</label>
                <input type="text" class="form-control" id="celular" name="celular" value="{{ $persona->celular }}" required>
            </div>
            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" class="form-control" id="direccion" name="direccion" value="{{ $persona->direccion }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="{{ url('personas') }}" class="btn btn-warning btn-cancel"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Cancelar</a>
        </form>
    </div>
@endsection

@extends('layout/template')

@section('title', 'Registrar Persona y Usuario')

@section('content')
    <div class="container mt-4">
        <h2>Registrar Persona y Usuario</h2>
        <form id="registrationForm" action="{{ route('personas.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="dni">DNI:</label>
                <input type="text" class="form-control" id="dni" name="dni" value="{{ old('dni') }}" required>
            </div>
            <div class="form-group">
                <label for="nombres">Nombres:</label>
                <input type="text" class="form-control" id="nombres" name="nombres" value="{{ old('nombres') }}" required>
            </div>
            <div class="form-group">
                <label for="apellido_p">Apellido Paterno:</label>
                <input type="text" class="form-control" id="apellido_p" name="apellido_p" value="{{ old('apellido_p') }}" required>
            </div>
            <div class="form-group">
                <label for="apellido_m">Apellido Materno:</label>
                <input type="text" class="form-control" id="apellido_m" name="apellido_m" value="{{ old('apellido_m') }}" required>
            </div>
            <div class="form-group">
                <label for="f_nacimiento">Fecha de Nacimiento:</label>
                <input type="date" class="form-control" id="f_nacimiento" name="f_nacimiento" value="{{ old('f_nacimiento') }}" required>
            </div>
            <div class="form-group">
                <label for="celular">Celular:</label>
                <input type="text" class="form-control" id="celular" name="celular" value="{{ old('celular') }}" required>
            </div>
            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion') }}" required>
            </div>
            <div class="form-group">
                <label for="rol_id">Rol:</label>
                <select class="form-control" id="rol_id" name="rol_id" required>
                    @foreach($roles as $rol)
                        <option value="{{ $rol->id }}" {{ old('rol_id') == $rol->id ? 'selected' : '' }}>{{ $rol->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="nombre_usuario">Nombre de Usuario:</label>
                <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" value="{{ old('nombre_usuario') }}" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" class="form-control" id="password" name="password" required>
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="estado">Estado:</label>
                <select class="form-control" id="estado" name="estado" required>
                    <option value="" disabled selected>Seleccione un estado</option>
                    <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
                @error('estado')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Crear</button>
            <a href="{{ url('personas') }}" class="btn btn-warning btn-cancel"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Cancelar</a>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            @if ($errors->any())
                toastr.options = {
                    "positionClass": "toast-bottom-right",
                };
                toastr.error("{{ $errors->first('password') }}");
            @endif
        });
    </script>
@endsection

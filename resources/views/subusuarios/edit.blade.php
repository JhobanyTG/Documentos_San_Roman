@extends('layout.template')

@section('title', 'Editar Subusuario')

@section('content')
    <div class="container mt-4">
        <h2>Editar Subusuario</h2>
        <form action="{{ route('subusuarios.update', ['gerencia' => $gerencia->id, 'subgerencia' => $subgerencia->id, 'subusuario' => $subusuario->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="dni">DNI:</label>
                <input type="text" class="form-control" id="dni" name="dni" value="{{ $subusuario->user->persona->dni }}" required>
            </div>
            <div class="form-group">
                <label for="nombres">Nombres:</label>
                <input type="text" class="form-control" id="nombres" name="nombres" value="{{ $subusuario->user->persona->nombres }}" required>
            </div>
            <div class="form-group">
                <label for="apellido_p">Apellido Paterno:</label>
                <input type="text" class="form-control" id="apellido_p" name="apellido_p" value="{{ $subusuario->user->persona->apellido_p }}" required>
            </div>
            <div class="form-group">
                <label for="apellido_m">Apellido Materno:</label>
                <input type="text" class="form-control" id="apellido_m" name="apellido_m" value="{{ $subusuario->user->persona->apellido_m }}" required>
            </div>
            <div class="form-group">
                <label for="f_nacimiento">Fecha de Nacimiento:</label>
                <input type="date" class="form-control" id="f_nacimiento" name="f_nacimiento" value="{{ $subusuario->user->persona->f_nacimiento }}" required>
            </div>
            <div class="form-group">
                <label for="celular">Celular:</label>
                <input type="text" class="form-control" id="celular" name="celular" value="{{ $subusuario->user->persona->celular }}" required>
            </div>
            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" class="form-control" id="direccion" name="direccion" value="{{ $subusuario->user->persona->direccion }}" required>
            </div>
            <div class="form-group">
                <label for="rol_id">Rol:</label>
                <select class="form-control" id="rol_id" name="rol_id" required>
                    @foreach($roles as $rol)
                        <option value="{{ $rol->id }}" {{ $subusuario->user->rol_id == $rol->id ? 'selected' : '' }}>{{ $rol->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="nombre_usuario">Nombre de Usuario:</label>
                <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" value="{{ $subusuario->user->nombre_usuario }}" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $subusuario->user->email }}" required>
            </div>
            <div class="form-group">
                <label for="estado">Estado:</label>
                <select name="estado" class="form-control" id="estado" required>
                    <option value="activo" {{ $subusuario->user->estado == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ $subusuario->user->estado == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>
            <div class="form-group">
                <label for="subgerencia_id">Subgerencia:</label>
                <select class="form-control" id="subgerencia_id" name="subgerencia_id" required>
                    @foreach($subgerencias as $subgerencia)
                        <option value="{{ $subgerencia->id }}" {{ $subusuario->subgerencia_id == $subgerencia->id ? 'selected' : '' }}>{{ $subgerencia->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="cargo">Cargo:</label>
                <input type="text" class="form-control" id="cargo" name="cargo" value="{{ $subusuario->cargo }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="{{ route('subgerencias.show', [$gerencia->id, $subgerencia->id]) }}" class="btn btn-warning btn-cancel"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Cancelar</a>
        </form>
    </div>
@endsection

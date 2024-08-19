@extends('layout.template')

@section('title', 'Editar Gerencia')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header pt-serif-regular">
            Editar Gerencia
        </div>
        <div class="card-body">
            <form action="{{ route('gerencias.update', $gerencia->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="nombre">Nombre de la Gerencia:</label>
                    <input type="text" name="nombre" class="form-control" id="nombre" value="{{ $gerencia->nombre }}" required>
                </div>

                <div class="form-group mt-3">
                    <label for="descripcion">Descripción:</label>
                    <textarea name="descripcion" class="form-control" id="descripcion" rows="4" required>{{ $gerencia->descripcion }}</textarea>
                </div>

                <div class="form-group mt-3">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" name="telefono" class="form-control" id="telefono" value="{{ $gerencia->telefono }}" required>
                </div>

                <div class="form-group mt-3">
                    <label for="direccion">Dirección:</label>
                    <input type="text" name="direccion" class="form-control" id="direccion" value="{{ $gerencia->direccion }}" required>
                </div>

                <div class="form-group mt-3">
                    <label for="estado">Estado:</label>
                    <select name="estado" class="form-control" id="estado" required>
                        <option value="Activo" {{ $gerencia->estado == 'Activo' ? 'selected' : '' }}>Activo</option>
                        <option value="Inactivo" {{ $gerencia->estado == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                <div class="form-group mt-3">
                    <label for="gerente_id">Gerente (User):</label>
                    <select name="gerente_id" class="form-control" id="gerente_id" required>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $gerencia->gerente_id == $user->id ? 'selected' : '' }}>
                                {{ $user->persona->nombres }} {{ $user->persona->apellido_p }} {{ $user->persona->apellido_m }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('gerencias.show', $gerencia->id) }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

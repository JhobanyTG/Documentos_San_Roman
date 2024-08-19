@extends('layout.template')

@section('title', 'Editar Sub Gerencia')

@section('content')
<div class="container mt-4">
    <h5 class="d-flex justify-content-center">{{ $gerencia->nombre }} </h5>
    <div class="card">
        <div class="card-header pt-serif-regular">
            Editar Sub Gerencia
        </div>
        <div class="card-body">
            <form action="{{ route('subgerencias.update', ['gerencia' => $gerencia->id, 'subgerencia' => $subgerencia->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="nombre">Nombre de la Sub Gerencia:</label>
                    <input type="text" name="nombre" class="form-control" id="nombre" value="{{ $subgerencia->nombre }}" required>
                </div>

                <div class="form-group mt-3">
                    <label for="descripcion">Descripción:</label>
                    <textarea name="descripcion" class="form-control" id="descripcion" rows="4" required>{{ $subgerencia->descripcion }}</textarea>
                </div>

                <div class="form-group mt-3">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" name="telefono" class="form-control" id="telefono" value="{{ $subgerencia->telefono }}" required>
                </div>

                <div class="form-group mt-3">
                    <label for="direccion">Dirección:</label>
                    <input type="text" name="direccion" class="form-control" id="direccion" value="{{ $subgerencia->direccion }}" required>
                </div>

                <div class="form-group mt-3">
                    <label for="estado">Estado:</label>
                    <select name="estado" class="form-control" id="estado" required>
                        <option value="Activo" {{ $subgerencia->estado == 'Activo' ? 'selected' : '' }}>Activo</option>
                        <option value="Inactivo" {{ $subgerencia->estado == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                <div class="form-group mt-3">
                    <label for="usuario_id">Encargado:</label>
                    <select name="usuario_id" class="form-control" id="usuario_id" required>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $subgerencia->usuario_id == $user->id ? 'selected' : '' }}>
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

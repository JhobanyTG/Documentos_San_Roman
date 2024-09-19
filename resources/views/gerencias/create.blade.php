@extends('layout.template')

@section('title', 'Crear Gerencia')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header pt-serif-regular">
                Crear Nueva Gerencia
            </div>
            <div class="card-body">
                <form action="{{ route('gerencias.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nombre">Nombre de la Gerencia:</label>
                        <input type="text" name="nombre" class="form-control" id="nombre" required>
                    </div>

                    <div class="form-group mt-3">
                        <label for="descripcion">Descripción:</label>
                        <textarea name="descripcion" class="form-control" id="descripcion" rows="4" required></textarea>
                    </div>

                    <div class="form-group mt-3">
                        <label for="telefono">Teléfono:</label>
                        <input type="text" name="telefono" class="form-control" id="telefono" required>
                    </div>

                    <div class="form-group mt-3">
                        <label for="direccion">Dirección:</label>
                        <input type="text" name="direccion" class="form-control" id="direccion" required>
                    </div>

                    <div class="form-group mt-3">
                        <label for="estado">Estado:</label>
                        <select name="estado" class="form-control" id="estado" required>
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                        </select>
                    </div>

                    <div class="form-group mt-3">
                        <label for="usuario_id">Gerente (User):</label>
                        <select name="usuario_id" class="form-control" id="usuario_id" required>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"> {{ $user->persona->nombres }}
                                    {{ $user->persona->apellido_p }} {{ $user->persona->apellido_m }} </option>
                            @endforeach
                        </select>
                    </div>



                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('gerencias.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

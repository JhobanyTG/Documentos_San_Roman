@extends('layout.template')

@section('content')
<h2>Crear Subgerencia para {{ $gerencia->nombre }}</h2>

<form action="{{ route('subgerencias.store', $gerencia->id) }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="nombre">Nombre de Subgerencia</label>
        <input type="text" name="nombre" class="form-control" required>
    </div>
    <div class="form-group mt-3">
        <label for="usuario_id">Encargado:</label>
        <select name="usuario_id" class="form-control" id="usuario_id" required>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->persona->nombres }} {{ $user->persona->apellido_p }} {{ $user->persona->apellido_m }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="descripcion">Descripción</label>
        <textarea name="descripcion" class="form-control" rows="3" required></textarea>
    </div>
    <div class="form-group">
        <label for="telefono">Teléfono</label>
        <input type="text" name="telefono" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="direccion">Dirección</label>
        <input type="text" name="direccion" class="form-control" required>
    </div>
    <div class="form-group mt-3">
        <label for="estado">Estado:</label>
        <select name="estado" class="form-control" id="estado" required>
            <option value="Activo">Activo</option>
            <option value="Inactivo">Inactivo</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="{{ route('gerencias.show', $gerencia->id) }}" class="btn btn-secondary">Cancelar</a>

</form>
@endsection

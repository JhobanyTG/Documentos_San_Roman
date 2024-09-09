@extends('layout/template')

@section('title', 'Lista de Personas')

@section('content')
    <div class="container mt-4">
        <h2>Lista de Personas</h2>
        <a href="{{ route('personas.create') }}" class="btn btn-primary mb-3">Registrar Persona y Usuario</a>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>DNI</th>
                    <th>Imagen</th>
                    <th>Nombres</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Celular</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($personas as $persona)
                    <tr>
                        <td>{{ $persona->dni }}</td>
                        <td>
                            <img src="{{ $persona->avatar ? asset('storage/' . $persona->avatar) : asset('images/logo/avatar.png') }}" alt="{{ $persona->nombres }}" width="100">
                        </td>
                        <td>{{ $persona->nombres }}</td>
                        <td>{{ $persona->apellido_p }}</td>
                        <td>{{ $persona->apellido_m }}</td>
                        <td>{{ $persona->f_nacimiento }}</td>
                        <td>{{ $persona->celular }}</td>
                        <td>{{ $persona->direccion }}</td>
                        <td>
                            <a href="{{ route('personas.edit', $persona->id) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('personas.destroy', $persona->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

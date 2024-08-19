@extends('layout/template')

@section('title', 'Lista de Privilegios')

@section('content')
    <div class="container mt-4">
        <h2>Lista de Privilegios</h2>
        <a href="{{ route('privilegios.create') }}" class="btn btn-primary mb-3">Crear Nuevo Privilegio</a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($privilegios as $privilegio)
                    <tr>
                        <td>{{ $privilegio->id }}</td>
                        <td>{{ $privilegio->nombre }}</td>
                        <td>{{ Str::limit($privilegio->descripcion, 50) }}</td>
                        <td>
                            <a href="{{ route('privilegios.show', $privilegio->id) }}" class="btn btn-info">Ver</a>
                            <a href="{{ route('privilegios.edit', $privilegio->id) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('privilegios.destroy', $privilegio->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este privilegio?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

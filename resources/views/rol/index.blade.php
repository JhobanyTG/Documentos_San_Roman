@extends('layout/template')

@section('title', 'Lista de Roles')

@section('content')
    <div class="container mt-4">
        <h2>Lista de Roles</h2>
        <a href="{{ route('roles.create') }}" class="btn btn-primary mb-3">Crear Nuevo Rol</a>

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
                    <th>Privilegios</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td>{{ $role->nombre }}</td>
                        <td>{{ Str::limit($role->descripcion, 50) }}</td>
                        <td>
                            @forelse ($role->privilegios as $privilegio)
                                <span class="text">{{ $privilegio->nombre }}</span>
                            @empty
                                <span class="text-muted">Sin privilegios</span>
                            @endforelse
                        </td>
                        <td>
                            <a href="{{ route('roles.show', $role->id) }}" class="btn btn-info">Ver</a>
                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este rol?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
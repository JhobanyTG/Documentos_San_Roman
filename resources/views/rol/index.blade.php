@extends('layout/template')

@section('title', 'Lista de Roles')

@section('content')
    <div class="container mt-4">
        <h2>Lista de Roles</h2>
        @if ( auth()->user()->rol->privilegios->contains('nombre', 'Acceso Total')  || auth()->user()->rol->nombre === 'SuberAdmin')
            <a href="{{ route('roles.create') }}" class="btn btn-primary mb-3">Crear Nuevo Rol</a>
        @endif
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
                    @if ( auth()->user()->rol->privilegios->contains('nombre', 'Acceso Total')  || auth()->user()->rol->nombre === 'SuberAdmin')
                        <th>Acciones</th>
                    @endif
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
                                <span class="text badge text-bg-secondary">{{ $privilegio->nombre }}</span>
                            @empty
                                <span class="text-muted">Sin privilegios</span>
                            @endforelse
                        </td>
                        @if ( auth()->user()->rol->privilegios->contains('nombre', 'Acceso Total')  || auth()->user()->rol->nombre === 'SuberAdmin')
                            <td>
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning">Editar</a>
                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este rol?')">Eliminar</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

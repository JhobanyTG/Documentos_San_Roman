@extends('layout.template')

@section('content')
<div class="container">
    <h1>Tipos de Documento</h1>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <a href="{{ route('tipodocumento.create') }}" class="btn btn-primary">Crear Nuevo Tipo de Documento</a>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tipodocumentos as $tipodocumento)
                <tr>
                    <td>{{ $tipodocumento->nombre }}</td>
                    <td>{{ $tipodocumento->descripcion }}</td>
                    <td>
                        <a href="{{ route('tipodocumento.show', $tipodocumento->id) }}" class="btn btn-info btn-sm">Ver</a>
                        <a href="{{ route('tipodocumento.edit', $tipodocumento->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('tipodocumento.destroy', $tipodocumento->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

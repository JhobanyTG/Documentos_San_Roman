@extends('layout.template')

@section('content')
    <h1>Lista de Tipos de Documento</h1>
    <a href="{{ route('tipodocumento.create') }}" class="btn btn-primary">Crear Tipo de Documento</a>

    @if ($message = Session::get('success'))
        <div class="alert alert-success mt-3">
            {{ $message }}
        </div>
    @endif

    <table class="table mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tipodocumentos as $tipodocumento)
                <tr>
                    <td>{{ $tipodocumento->id_tipodocumento }}</td>
                    <td>{{ $tipodocumento->nombre }}</td>
                    <td>{{ $tipodocumento->descripcion }}</td>
                    <td>
                        <a href="{{ route('tipodocumento.show', $tipodocumento->id_tipodocumento) }}" class="btn btn-info">Ver</a>
                        <a href="{{ route('tipodocumento.edit', $tipodocumento->id_tipodocumento) }}" class="btn btn-warning">Editar</a>
                        <form action="{{ route('tipodocumento.destroy', $tipodocumento->id_tipodocumento) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

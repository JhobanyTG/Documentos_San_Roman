@extends('layouts.app')

@section('content')
    <h1>Historial de Cambios</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Documento</th>
                <th>Estado Anterior</th>
                <th>Estado Nuevo</th>
                <th>Descripción</th>
                <th>Usuario</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($historialCambios as $cambio)
                <tr>
                    <td>{{ $cambio->id }}</td>
                    <td>{{ $cambio->documento->titulo }}</td>
                    <td>{{ $cambio->estado_anterior }}</td>
                    <td>{{ $cambio->estado_nuevo }}</td>
                    <td>{{ $cambio->descripcion }}</td>
                    <td>{{ $cambio->usuario->name }}</td>
                    <td>{{ $cambio->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@extends('layout.template')

@section('content')
<div class="container">
    <h1>Tipo de Documento</h1>
    <p><strong>Nombre:</strong> {{ $tipodocumento->nombre }}</p>
    <p><strong>Descripción:</strong> {{ $tipodocumento->descripcion }}</p>
    <a href="{{ route('tipodocumento.index') }}" class="btn btn-secondary">Volver</a>
</div>
@endsection


@extends('layout/template')

@section('title', 'Detalles de Persona')

@section('content')
    <div class="container mt-4">
        <h2>Detalles de Persona</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $persona->nombres }} {{ $persona->apellido_p }} {{ $persona->apellido_m }}</h5>
                <p class="card-text"><strong>DNI:</strong> {{ $persona->dni }}</p>
                <p class="card-text"><strong>Fecha de Nacimiento:</strong> {{ $persona->f_nacimiento }}</p>
                <p class="card-text"><strong>Celular:</strong> {{ $persona->celular }}</p>
                <p class="card-text"><strong>Dirección:</strong> {{ $persona->direccion }}</p>
                <a href="{{ url('personas') }}" class="btn btn-primary">Volver</a>
            </div>
        </div>
    </div>
@endsection

@extends('layout/template')

@section('title', 'Detalles de Persona')

@section('content')

<div class="container mt-4 form_persona">
    <h2 class="form_title_persona text-center">Detalles de Persona</h2>
    <div class="row forms">
        <!-- Columna izquierda (Detalles de Persona) -->
        <div class="col-md-6">
            <div class="form-group mt-3 text-center">
                <label for="avatar" class="form-label label_persona">Foto:</label>
                <p><img src="{{ $persona->avatar ? asset('storage/' . $persona->avatar) : asset('images/logo/avatar.png') }}"
                    alt="{{ $persona->nombres }}" class="img-thumbnail mb-2" style="width: 200px; height: 200px;" /></p>
            </div>
            <div class="form-group mt-3">
                <label class="form-label label_persona">DNI:</label>
                <p class="form-control persona">{{ $persona->dni }}</p>
            </div>
            <div class="form-group mt-3">
                <label class="form-label label_persona">Nombre de Usuario:</label>
                <p class="form-control persona">{{ $persona->user->nombre_usuario }}</p>
            </div>
            <div class="form-group mt-3">
                <label class="form-label label_persona">Email:</label>
                <p class="form-control persona">{{ $persona->user->email }}</p>
            </div>
        </div>

        <!-- Columna derecha (Detalles adicionales) -->
        <div class="col-md-6">
            <div class="form-group mt-3">
                <label class="form-label label_persona">Nombres:</label>
                <p class="form-control persona">{{ $persona->nombres }}</p>
            </div>
            <div class="row mt-2">
                <div class="form-group col-md-6">
                    <label class="form-label label_persona">Apellido Paterno:</label>
                    <p class="form-control persona">{{ $persona->apellido_p }}</p>
                </div>
                <div class="form-group col-md-6">
                    <label class="form-label label_persona">Apellido Materno:</label>
                    <p class="form-control persona">{{ $persona->apellido_m }}</p>
                </div>
            </div>
            <div class="row mt-2">
                <div class="form-group col-md-6">
                    <label class="form-label label_persona">Fecha de Nacimiento:</label>
                    <p class="form-control persona">{{ $persona->f_nacimiento }}</p>
                </div>
                <div class="form-group col-md-6">
                    <label class="form-label label_persona">Celular:</label>
                    <p class="form-control persona">{{ $persona->celular }}</p>
                </div>
            </div>
            <div class="form-group mt-2">
                <label class="form-label label_persona">Dirección:</label>
                <p class="form-control persona">{{ $persona->direccion }}</p>
            </div>
            <div class="form-group mt-2">
                <label class="form-label label_persona">Estado:</label>
                <p class="form-control persona">{{ $persona->user->estado }}</p>
            </div>
            <div class="form-group mt-2">
                <label class="form-label label_persona">Rol:</label>
                <p class="form-control persona">{{ $persona->user->rol->nombre }}</p>
            </div>
        </div>
    </div>

    <div class="mt-3 botones_form_persona text-center">
        <a href="{{ route('personas.index') }}" class="btn btn-form btn-persona me-2"><i class="fa fa-arrow-circle-left"></i> Cancelar</a>
        <a href="{{ route('personas.edit', $persona->id) }}" class="btn btn-form btn-persona ms-2 me-2">
            <i class="fa fa-edit" aria-hidden="true"></i> Editar mi información
        </a>
        <a href="{{ route('usuarios.edit', $user->id) }}" class="btn btn-form btn-persona ms-2">
            <i class="fa fa-user" aria-hidden="true"></i> Editar mi usuario
        </a>
    </div>
</div>

@endsection

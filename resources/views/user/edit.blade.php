@extends('layout/template')

@section('title', 'Editar usuario')

@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <form action="{{ route('usuarios.update', $users->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
          <div class="col-md-12">
            <div class="form-group mb-2">
              <label for="name" class="form-label">Nombre:</label>
              <input type="text" class="form-control" name="name" id="name" value="{{ $users->name }}">
            </div>
            <div class="form-group mb-2">
              <label for="email" class="form-label">Correo:</label>
              <input type="text" class="form-control" name="email" id="email" value="{{ $users->email }}">
            </div>
            <div class="form-group mb-2">
                <label for="role" class="form-label">Rol:</label>
                <select class="form-select" name="role" id="role">
                    <option value="SuperAdmin" {{ $users->role === 'SuperAdmin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="Admin" {{ $users->role === 'Admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div class="col-md-12 col-12 mb-2 d-flex align-items-end justify-content-end">
              <a href="{{ route('usuarios.show', $users->id) }}" class="btn btn-warning btn-cancel"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Cancelar</a>
              <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

@stop

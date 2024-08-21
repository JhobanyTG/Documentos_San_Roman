@extends('layout.template')

@section('title', 'Editar Rol Privilegio')

@section('content')
<div class="container">
    <h1>Editar Rol Privilegio</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('rolprivilegios.update', $rolprivilegio->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="privilegio_id">Privilegio</label>
            <select name="privilegio_id" id="privilegio_id" class="form-control">
                @foreach ($privilegios as $privilegio)
                    <option value="{{ $privilegio->id }}" @if($rolprivilegio->privilegio_id == $privilegio->id) selected @endif>{{ $privilegio->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="rol_id">Rol</label>
            <select name="rol_id" id="rol_id" class="form-control">
                @foreach ($roles as $rol)
                    <option value="{{ $rol->id }}" @if($rolprivilegio->rol_id == $rol->id) selected @endif>{{ $rol->nombre }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
</div>
@endsection
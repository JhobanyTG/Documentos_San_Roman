@extends('layout.template')

@section('title', 'Crear Tipo de Documento')

@section('content')
<div class="container form_tipodocumento">
    <h1 class="form_title_tipo">Crear Tipo de Documento</h1>
    <form action="{{ route('tipodocumento.store') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label for="nombre" class="form-label label_tipo">Nombre</label>
            <input type="text" class="form-control tipo @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" class="form-control tipo">
            @error('nombre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label label_tipo">Descripción</label>
            <textarea class="form-control tipo @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" class="form-control tipo">{{ old('descripcion') }}</textarea>
            @error('descripcion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary  btn-tipo">Guardar</button>
        <a href="{{ route('tipodocumento.index') }}" class="btn btn-secondary  btn-tipo">Cancelar</a>
    </form>
</div>
@endsection

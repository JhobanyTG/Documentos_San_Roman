@extends('layout/template')

@section('title', 'Editar Persona')

@section('content')
    <div class="container mt-4 form_persona">
        <h2>Editar Persona</h2>
        <form id="editForm" action="{{ route('personas.update', $persona->id) }}" method="POST" class="form_persona_user" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row forms">
                <!-- Formulario Persona -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="dni">DNI:</label>
                        <input type="text" class="form-control" id="dni" name="dni" value="{{ old('dni', $persona->dni) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="nombres">Nombres:</label>
                        <input type="text" class="form-control" id="nombres" name="nombres" value="{{ old('nombres', $persona->nombres) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido_p">Apellido Paterno:</label>
                        <input type="text" class="form-control" id="apellido_p" name="apellido_p" value="{{ old('apellido_p', $persona->apellido_p) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido_m">Apellido Materno:</label>
                        <input type="text" class="form-control" id="apellido_m" name="apellido_m" value="{{ old('apellido_m', $persona->apellido_m) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="f_nacimiento">Fecha de Nacimiento:</label>
                        <input type="date" class="form-control" id="f_nacimiento" name="f_nacimiento" value="{{ old('f_nacimiento', $persona->f_nacimiento) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="celular">Celular:</label>
                        <input type="text" class="form-control" id="celular" name="celular" value="{{ old('celular', $persona->celular) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="direccion">Dirección:</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion', $persona->direccion) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="avatar">Avatar:</label>
                        <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                        @if ($persona->avatar)
                            <img src="{{ asset('storage/' . $persona->avatar) }}" alt="Avatar" width="100">
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-3 botones_form_persona">
                <button type="submit" class="btn btn-form"><i class="fa fa-save" aria-hidden="true"></i> Guardar Cambios</button>
                <a href="{{ route('personas.index') }}" class="btn btn-form"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            @if ($errors->any())
                toastr.options = {
                    "positionClass": "toast-bottom-right",
                };
                toastr.error("{{ $errors->first() }}");
            @endif
        });
    </script>
@endsection

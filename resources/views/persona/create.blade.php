@extends('layout/template')

@section('title', 'Registrar Persona y Usuario')

@section('content')
    <div class="container mt-4 form_persona">
        <h2>Registrar Persona y Usuario</h2>
        <form id="registrationForm" action="{{ route('personas.store') }}" method="POST" class="form_persona_user" enctype="multipart/form-data">
            @csrf
            <div class="row forms">
                <!-- Formulario Persona -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="dni">DNI:</label>
                        <input type="text" class="form-control" id="dni" name="dni" value="{{ old('dni') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="nombres">Nombres:</label>
                        <input type="text" class="form-control" id="nombres" name="nombres" value="{{ old('nombres') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido_p">Apellido Paterno:</label>
                        <input type="text" class="form-control" id="apellido_p" name="apellido_p" value="{{ old('apellido_p') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido_m">Apellido Materno:</label>
                        <input type="text" class="form-control" id="apellido_m" name="apellido_m" value="{{ old('apellido_m') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="f_nacimiento">Fecha de Nacimiento:</label>
                        <input type="date" class="form-control" id="f_nacimiento" name="f_nacimiento" value="{{ old('f_nacimiento') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="celular">Celular:</label>
                        <input type="text" class="form-control" id="celular" name="celular" value="{{ old('celular') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="avatar">Avatar</label>
                        <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                    </div>
                </div>

                <!-- Formulario Usuario -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="direccion">Dirección:</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="rol_id">Rol:</label>
                        <select class="form-control" id="rol_id" name="rol_id" required>
                            @foreach ($roles as $rol)
                                <option value="{{ $rol->id }}" {{ old('rol_id') == $rol->id ? 'selected' : '' }}>
                                    {{ $rol->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nombre_usuario">Nombre de Usuario:</label>
                        <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" value="{{ old('nombre_usuario') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="estado">Estado:</label>
                        <select class="form-control" id="estado" name="estado" required>
                            <option value="Activo" selected>Activo</option>
                            <option value="Inactivo" {{ old('estado') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-3 botones_form_persona">
                <button type="submit" class="btn btn-form"><i class="fa fa-plus" aria-hidden="true"></i> Crear</button>
                <a href="{{ url('personas') }}" class="btn btn-form"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.options = {
                        "positionClass": "toast-top-right",
                        "timeOut": 5000,
                    };
                    toastr.error("{{ $error }}");
                @endforeach
            @endif
        });
    </script>
@endsection

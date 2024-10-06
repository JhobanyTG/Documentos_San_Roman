@extends('layout.template')

@section('title', 'Crear Gerencia')

@section('content')
    <div class="container mt-4">
        <div class=" container col-md-4 card form_gerencia">
            <h2 class="form_title_gerencia">
                Crear Nueva Gerencia
            </h2>
            <div class="card-body">
                <form action="{{ route('gerencias.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <!-- Primera columna -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre" class="form-label label_gerencia">Nombre de la Gerencia:</label>
                                <input type="text" name="nombre" class="form-control gerencia" id="nombre" required>
                            </div>
                            <div class="form-group">
                                <label for="descripcion" class="form-label label_gerencia">Descripción:</label>
                                <textarea name="descripcion" class="form-control gerencia" id="descripcion" rows="4" required></textarea>
                            </div>
                            <div class="form-group mt-3">
                                <label for="telefono" class="form-label label_gerencia">Teléfono:</label>
                                <input type="text" name="telefono" class="form-control gerencia" id="telefono" required>
                            </div>


                        </div>

                        <!-- Segunda columna -->
                        <div class="col-md-6">


                            <div class="form-group mt-3">
                                <label for="direccion" class="form-label label_gerencia">Dirección:</label>
                                <input type="text" name="direccion" class="form-control gerencia" id="direccion"
                                    required>
                            </div>

                            <div class="form-group mt-3">
                                <label for="usuario_id" class="form-label label_gerencia">Gerente (User):</label>
                                <select name="usuario_id" class="form-control gerencia" id="usuario_id" required>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"> {{ $user->persona->nombres }}
                                            {{ $user->persona->apellido_p }} {{ $user->persona->apellido_m }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mt-3">
                                <label for="estado" class="form-label label_gerencia">Estado:</label>
                                <select name="estado" class="form-control gerencia" id="estado" required>
                                    <option value="Activo">Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary btn-gerencia">Guardar</button>
                        <a href="{{ route('gerencias.index') }}" class="btn btn-secondary btn-gerencia">Cancelar</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
@stop

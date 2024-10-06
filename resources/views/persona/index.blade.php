@extends('layout/template')

@section('title', 'Lista de Personas')

@section('content')
    <div class="card-body mt-3 p-2">
        <div id="content_ta_wrapper" class="dataTables_wrapper">
            <div class="table-responsive">
                <a href="{{ route('personas.create') }}" class="btn btn-primary mb-3">Registrar Persona y Usuario</a>
                <table id="content_ta" class="table table-striped mt-4 table-hover custom-table pt-serif-regular"
                    role="grid" aria-describedby="content_ta_info">
                    <thead>
                        <tr role="row">
                            <th class="text-center">DNI</th>
                            <th class="text-center">Imagen</th>
                            <th class="text-center">Nombres</th>
                            <th class="text-center">Apellido Paterno</th>
                            <th class="text-center">Apellido Materno</th>
                            <th class="text-center">Fecha de Nacimiento</th>
                            <th class="text-center">Celular</th>
                            <th class="text-center">Dirección</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($personas as $persona)
                            <tr class="odd">
                                <td>{{ $persona->dni }}</td>
                                <td>
                                    <img src="{{ $persona->avatar ? asset('storage/' . $persona->avatar) : asset('images/logo/avatar.png') }}"
                                        alt="{{ $persona->nombres }}" width="100">
                                </td>
                                <td>{{ $persona->nombres }}</td>
                                <td>{{ $persona->apellido_p }}</td>
                                <td>{{ $persona->apellido_m }}</td>
                                <td>{{ $persona->f_nacimiento }}</td>
                                <td>{{ $persona->celular }}</td>
                                <td>{{ $persona->direccion }}</td>
                                <td>
                                    <a class="btn btn-info" href="{{ route('personas.show', $persona->id) }}">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                    <a href="{{ route('personas.edit', $persona->id) }}" class="btn btn-warning">
                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger"
                                        onclick="showUserConfirmationModal('{{ route('personas.destroy', $persona->id) }}')">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                </td>
                                <!-- Modal para confirmar la eliminación -->
                                <div class="modal fade" tabindex="-1" role="dialog" id="userConfirmationModal">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirmar Eliminación</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>¿Estás seguro de eliminar esta persona? Esta acción no se puede deshacer.
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-no" data-bs-dismiss="modal">No</button>
                                                <form id="userDeleteForm" method="POST" class="d-inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fa fa-trash"></i> Sí
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
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
    <script>
        $(document).ready(function() {
            @if (Session::has('success'))
                toastr.options = {
                    "positionClass": "toast-bottom-right",
                };
                toastr.success("{{ Session::get('success') }}");
            @endif
        });
    </script>


    <script>
        function showUserConfirmationModal(actionUrl) {
            // Establecer la acción del formulario
            document.getElementById('userDeleteForm').action = actionUrl;

            // Abrir el modal
            $('#userConfirmationModal').modal('show');
        }
    </script>
@endsection

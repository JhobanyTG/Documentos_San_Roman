@extends('layout/template')

@section('title', 'Lista de Privilegios')

@section('content')
    <div class="container mt-4">
        <h2>Lista de Privilegios</h2>
        <a href="{{ route('privilegios.create') }}" class="btn btn-primary mb-3">Crear Nuevo Privilegio</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($privilegios as $privilegio)
                    <tr>
                        <td>{{ $privilegio->id }}</td>
                        <td>{{ $privilegio->nombre }}</td>
                        <td>{{ Str::limit($privilegio->descripcion, 50) }}</td>
                        <td>
                            <a href="{{ route('privilegios.show', $privilegio->id) }}" class="btn btn-info">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </a>
                            <a href="{{ route('privilegios.edit', $privilegio->id) }}" class="btn btn-warning">
                                <i class="fa fa-edit" aria-hidden="true"></i>
                            </a>
                            <button type="button" class="btn btn-danger"
                                onclick="showUserConfirmationModal('{{ route('privilegios.destroy', $privilegio->id) }}')">
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
                                        <p>¿Estás seguro de eliminar este privilegio? Esta acción no se puede deshacer.
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-no"
                                            data-bs-dismiss="modal">No</button>
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

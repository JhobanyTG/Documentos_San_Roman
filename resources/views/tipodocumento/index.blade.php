@extends('layout.template')

@section('content')
<div class="container">
    <h1>Tipos de Documento</h1>
    <a href="{{ route('tipodocumento.create') }}" class="btn btn-primary">Crear Nuevo Tipo de Documento</a>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tipodocumentos as $tipodocumento)
                <tr>
                    <td>{{ $tipodocumento->nombre }}</td>
                    <td>{{ $tipodocumento->descripcion }}</td>
                    <td>
                        <a href="{{ route('tipodocumento.show', $tipodocumento->id) }}" class="btn btn-info">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </a>
                        <a href="{{ route('tipodocumento.edit', $tipodocumento->id) }}" class="btn btn-warning">
                            <i class="fa fa-edit" aria-hidden="true"></i>
                        </a>
                        <button type="button" class="btn btn-danger"
                                onclick="showUserConfirmationModal('{{ route('tipodocumento.destroy', $tipodocumento->id) }}')">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </td>
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
                                    <p>¿Estás seguro de eliminar este tipo de documento? Esta acción no se puede deshacer.
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

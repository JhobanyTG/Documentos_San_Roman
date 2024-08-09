@extends('layout/template')

@section('title', 'Detalles del usuario')

@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <form action="{{ route('usuarios.destroy', $users->id) }}" method="POST" id="deleteForm">
        @csrf
        @method('DELETE')
        <div class="row">
          <div class="col-md-12">
            <div class="form-group mb-2">
              <label for="name" class="form-label">Nombre:</label>
              <input type="text" class="form-control" name="name" id="name" value="{{ $users->name }}" readonly>
            </div>
            <div class="form-group mb-2">
              <label for="email" class="form-label">Correo:</label>
              <input type="text" class="form-control" name="email" id="email" value="{{ $users->email }}" readonly>
            </div>
            <div class="form-group mb-2">
              <label for="role" class="form-label">Rol:</label>
              <input type="text" class="form-control" name="role" id="role" value="{{ $users->role }}" readonly>
            </div>
            <div class="col-md-12 col-12 mb-2 d-flex align-items-end justify-content-end">
              <a href="{{ url('usuarios') }}" class="btn btn-warning btn-cancel"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Volver</a>
              <a href="{{ route('usuarios.cambiarContrasena', $users->id) }}" class="btn btn-secondary btn-edit"><i class="fa fa-key" aria-hidden="true"></i> Cambiar Contraseña</a>
              <a href="{{ route('usuarios.edit', $users->id) }}" class="btn btn-info btn-edit"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
              <button type="button" class="btn btn-danger" onclick="showConfirmationModal()">
                  <i class="fa fa-trash" aria-hidden="true"></i> Eliminar
              </button>
            </div>
          </div>
        </div>
      </form>
      <div class="modal" tabindex="-1" role="dialog" id="confirmationModal">
        <div class="modal-dialog" role="documentoo">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de eliminar este usuario? Todos los registros del usuario seran eliminados, Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-no" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" onclick="deleteRecord()">Sí</button>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
    function showConfirmationModal() {
        $('#confirmationModal').modal('show');
    }

    function deleteRecord() {
        documento.getElementById('deleteForm').submit();
    }
</script>
<script>
    $(documento).ready(function() {
        $('.btn-close, .btn-no').click(function() {
            $('#confirmationModal, #pdfModal').modal('hide');
        });
    });
</script>
<script>
$(documento).ready(function() {
    @if(Session::has('success'))
        toastr.options = {
            "positionClass": "toast-bottom-right",
        };
        toastr.success("{{ Session::get('success') }}");
    @endif
});
</script>


@stop

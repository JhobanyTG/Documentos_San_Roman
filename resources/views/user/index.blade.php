@extends('layout/template')

@section('title', 'Usuarios')

@section('content')
    <div class="card-body mt-3 p-2">
        <div id="content_ta_wrapper" class="dataTables_wrapper">
            <div class="table-responsive">
                <a href="{{ route('personas.create') }}" class="btn btn-doc mb-3"><i class="fa fa-plus" aria-hidden="true"></i> Registrar Persona y Usuario</a>
                <table id="content_ta" class="table table-striped mt-4 table-hover custom-table pt-serif-regular"
                    role="grid" aria-describedby="content_ta_info">
                    <thead>
                        <tr role="row">
                            <th class="text-center">Imagen</th>
                            <th class="text-center">Nombre de Usuario</th>
                            <th class="text-center">Correo</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Rol</th>
                            <th class="text-center">Persona</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($users as $user)
                            <tr class="odd">
                                <td class="text-center">
                                    <img src="{{ $user->persona->avatar ? asset('storage/' . $user->persona->avatar) : asset('images/logo/avatar.png') }}"
                                        alt="{{ $user->persona->nombres }}" width="100">
                                </td>
                                <td class="text-center">{{ $user->nombre_usuario }}</td>
                                <td class="text-center">{{ $user->email }}</td>
                                <td class="text-center">{{ $user->estado }}</td>
                                <td class="text-center">{{ $user->rol->nombre }}</td>
                                <td class="text-center">{{ $user->persona->nombres }} {{ $user->persona->apellido_p }}
                                    {{ $user->persona->apellido_m }}</td>
                                <td class="text-center">
                                    <a class="btn btn-info" href="{{ route('usuarios.show', $user->id) }}">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                    <a class="btn btn-warning" href="{{ route('usuarios.edit', $user->id) }}">
                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger"
                                        onclick="showUserConfirmationModal('{{ route('usuarios.destroy', $user->id) }}')">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                </td>

                                <!-- Modal para confirmar la eliminación -->
                                <div class="modal fade" tabindex="-1" role="dialog" id="userConfirmationModal">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirmar Eliminación</h5>
                                                <button type="button" class="btn-close" data-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-no"
                                                    data-dismiss="modal">No</button>
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
            @if (Session::has('success'))
                toastr.options = {
                    "positionClass": "toast-bottom-right",
                };
                toastr.success("{{ Session::get('success') }}");
            @endif
        });
    </script>

    <script>
        function openPdfModal(pdfUrl, pdfName, modalId) {
            var modalBody = document.getElementById('pdfModalBody-' + modalId);
            modalBody.innerHTML = '<embed src="' + pdfUrl + '" type="application/pdf" width="100%" height="500px" />';
            document.getElementById('pdfModalLabel-' + modalId).innerText = pdfName;
            $('#pdfModal-' + modalId).modal('show');
        }

        $(document).ready(function() {
            $('.archivo-preview').on('click', function() {
                var pdfUrl = $(this).find('iframe').attr('src');
                var pdfName = $(this).closest('tr').find('td.text-center:first').text().trim();
                var modalId = $(this).closest('tr').data('id');
                openPdfModal(pdfUrl, pdfName, modalId);
            });

            $('.img_file_pdf').on('click', function() {
                var pdfUrl = $(this).closest('td').find('.archivo-preview iframe').attr('src');
                var pdfName = $(this).closest('tr').find('td.text-center:first').text().trim();
                var modalId = $(this).closest('tr').data('id');
                openPdfModal(pdfUrl, pdfName, modalId);
            });

            $('.btn-close, .btn-no').click(function() {
                $(this).closest('.modal').modal('hide');
            });
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

@stop

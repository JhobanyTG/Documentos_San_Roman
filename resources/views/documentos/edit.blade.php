@extends('layout/template')

@section('title', 'Editar Documento')

@section('content')
    <div class="container mt-4 form_documento">
        <h2>Editar Documento</h2>
        <form action="{{ route('documentos.update', $documento->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <!-- Subir archivo a la izquierda -->
                <div class="col-md-4 mb-4">
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-center">
                            <img class="img_file" src="{{ asset('images/icons/pdf.png') }}" />
                        </div>
                        <div class="col-md-12 d-flex justify-content-center pt-serif-regular mt-2">
                            <p class="nombre_archivo text-center" data-original-name="{{ basename($documento->archivo) }}">
                                {{ basename($documento->archivo) }}</p>
                        </div>
                        <div class="col-md-12 container-input">
                            <input type="file" name="archivo" id="archivo" class="inputfile inputfile-1"
                                accept=".pdf" />
                            <label for="archivo">
                                <i class="fa fa-repeat" aria-hidden="true"></i>
                                <span class="iborrainputfile">Reemplazar archivo</span>
                            </label>
                        </div>
                        <div class="col-md-12 container-input pt-serif-regular">
                            <a href="#" class="btn btn-success" onclick="openPdfModal()"><i class="fa fa-eye"
                                    aria-hidden="true"></i> Visualizar Archivo Actual</a>
                        </div>
                        <div class="modal fade pt-serif-regular" id="pdfModal" tabindex="-1" role="dialog"
                            aria-labelledby="pdfModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="pdfModalLabel">{{ basename($documento->archivo) }}</h5>
                                        <button type="button" class="btn-close" data-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" id="pdfModalBody"></div>
                                    <div class="modal-footer">
                                        <a href="{{ asset('storage/archivos/' . basename($documento->archivo)) }}"
                                            class="btn btn-info" target="_blank"><i class="fa fa-external-link-square"
                                                aria-hidden="true"></i> Abrir en otra ventana</a>
                                        <a href="{{ asset('storage/archivos/' . basename($documento->archivo)) }}"
                                            download="{{ basename($documento->archivo) }}" class="btn btn-dark"><i
                                                class="fa fa-download" aria-hidden="true"></i> Descargar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulario a la derecha -->
                <div class="col-md-8">
                    <div class="form-group mb-2">
                        <label for="titulo">Título:</label>
                        <input type="text" class="form-control" name="titulo" id="titulo"
                            placeholder="Ingrese el título" value="{{ old('titulo', $documento->titulo) }}" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="tipodocumento_id">Tipo Documento:</label>
                        <select name="tipodocumento_id" class="form-control" id="tipodocumento_id" required>
                            @foreach ($tiposDocumento as $tipoDocumento)
                                <option value="{{ $tipoDocumento->id }}"
                                    {{ $documento->tipodocumento_id == $tipoDocumento->id ? 'selected' : '' }}>
                                    {{ $tipoDocumento->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mt-3">
                        <label for="descripcion">Descripción:</label>
                        <textarea class="form-control" name="descripcion" id="descripcion" rows="5" placeholder="Ingrese la descripción"
                            required>{{ old('descripcion', $documento->descripcion) }}</textarea>
                    </div>

                    <div class="form-group mt-3">
                        <label for="estado">Estado:</label>
                        <select name="estado" class="form-control" id="estado" disabled required>
                            <option value="Creado" {{ $documento->estado == 'Creado' ? 'selected' : '' }}
                                style="color: red">Creado</option>
                            <option value="Validado" {{ $documento->estado == 'Validado' ? 'selected' : '' }}
                                style="color: green">Validado</option>
                            <option value="Publicado" {{ $documento->estado == 'Publicado' ? 'selected' : '' }}
                                style="color: blue">Publicado</option>
                        </select>
                        <!-- Botón para abrir el modal de cambio de estado -->
                        <button type="button" class="btn btn-warning mt-2" data-bs-toggle="modal"
                            data-bs-target="#changeStatusModal">Cambiar Estado</button>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('documentos.index') }}" class="btn btn-warning">Cancelar</a>
                        <button type="submit" class="btn btn-success">Actualizar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal para cambiar el estado -->
    <div class="modal fade" id="changeStatusModal" tabindex="-1" aria-labelledby="changeStatusLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changeStatusLabel">Cambiar Estado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="documento_id" name="documento_id" value="{{ $documento->id }}">
                    <label for="nuevo_estado">Nuevo Estado:</label>
                    <select id="nuevo_estado" class="form-control" name="nuevo_estado" required>
                        <option value="Validado">Validado</option>
                        <option value="Publicado">Publicado</option>
                        <!-- Agrega otros estados si es necesario -->
                    </select>
                    <label for="descripcion_modal">Descripción:</label>
                    <textarea id="descripcion_modal" class="form-control" name="descripcion_modal" rows="3" required></textarea>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="confirmChangeStatus">Cambiar Estado</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let isRequestInProgress = false;

            $('#confirmChangeStatus').click(function() {
                if (isRequestInProgress) return;

                const descripcion = $('#descripcion_modal').val().trim();
                const documentoId = $('#documento_id').val();
                const nuevoEstado = $('#nuevo_estado').val();

                // Validación del lado del cliente
                if (!descripcion) {
                    toastr.error('La descripción es obligatoria.');
                    return;
                }

                isRequestInProgress = true;
                $(this).prop('disabled', true);

                $.ajax({
                    url: `{{ url('documentos/${documentoId}/cambiarEstado') }}`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        estado: nuevoEstado,
                        descripcion: descripcion
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Estado cambiado exitosamente');
                            $('#changeStatusModal').modal('hide');
                            $('#estado').val(nuevoEstado).prop('disabled', true);
                        } else {
                            toastr.error(response.message ||
                                'Ha ocurrido un error al cambiar el estado.');
                        }
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        if (response && response.errors) {
                            // Mostrar solo el primer error
                            const firstError = Object.values(response.errors)[0];
                            toastr.error(Array.isArray(firstError) ? firstError[0] :
                            firstError);
                        } else {
                            toastr.error(
                            'Ha ocurrido un error. Por favor, inténtalo de nuevo.');
                        }
                    },
                    complete: function() {
                        $('#confirmChangeStatus').prop('disabled', false);
                        isRequestInProgress = false;
                    }
                });
            });

            // Eliminar el manejo de errores del servidor al inicio del documento
            // @if ($errors->any())
            //     toastr.options = {
            //         "positionClass": "toast-bottom-right",
            //     };
            //     toastr.error("{{ $errors->first() }}");
            // @endif
        });
    </script>

    <script>
        $('#cambiarEstadoModal').on('submit', function(e) {
            e.preventDefault(); // Prevenir el envío normal del formulario

            var estado = $('#estadoSelect').val(); // Asumiendo que tienes un select para elegir el estado
            var documentoId = $(this).data('documento-id'); // Asegúrate de que estás pasando el ID del documento

            $.ajax({
                url: `{{ url('documentos/${documentoId}/cambiarEstado') }}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    estado: nuevoEstado,
                    descripcion: descripcion
                },

                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload(); // Recargar la página para reflejar los cambios
                    }
                },
                error: function(xhr) {
                    alert('Error al cambiar el estado: ' + xhr.responseText);
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            let isRequestInProgress = false;

            $('#confirmChangeStatus').click(function() {
                if (isRequestInProgress) return;

                const descripcion = $('#descripcion_modal').val().trim();

                // Validación del lado del cliente
                if (!descripcion) {
                    toastr.error('La descripción es obligatoria.');
                    return;
                }

                isRequestInProgress = true;
                $(this).prop('disabled', true);

                const documentoId = $('#documento_id').val();
                const nuevoEstado = $('#nuevo_estado').val();

                $.ajax({
                    url: `{{ url('documentos/${documentoId}/cambiarEstado') }}`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        estado: nuevoEstado,
                        descripcion: descripcion
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Estado cambiado exitosamente');
                            $('#changeStatusModal').modal('hide');
                            $('#estado').val(nuevoEstado).prop('disabled', true);
                        } else {
                            // Si la respuesta no es exitosa, mostrar mensaje de error específico
                            toastr.error(response.message ||
                                'Ha ocurrido un error al cambiar el estado.');
                        }
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        if (response && response.errors) {
                            // Priorizar el error de descripción
                            if (response.errors.descripcion) {
                                toastr.error(response.errors.descripcion[0]);
                            } else {
                                // Si no hay error de descripción, mostrar el primer error encontrado
                                const firstError = Object.values(response.errors)[0];
                                toastr.error(Array.isArray(firstError) ? firstError[0] :
                                    firstError);
                            }
                        } else {
                            // Si no hay errores específicos, mostrar un mensaje genérico
                            toastr.error(
                                'Ha ocurrido un error. Por favor, inténtalo de nuevo.');
                        }
                    },
                    complete: function() {
                        $('#confirmChangeStatus').prop('disabled', false);
                        isRequestInProgress = false;
                    }
                });
            });
        });
    </script>



@endsection

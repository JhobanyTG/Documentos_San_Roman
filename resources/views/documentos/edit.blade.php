@extends('layout/template')

@section('title', 'Editar Documento')

@section('content')
    <div class="container mt-4 form_documento">
        <h2 class="form_title_documento">Editar Documento</h2>
        <form action="{{ route('documentos.update', $documento->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <!-- Subir archivo a la izquierda -->
                <div class="col-md-4 mb-4">
                    <div class="row text-center">
                        <div class="col-md-12">
                            <iframe id="pdfIframe" src="{{ asset('storage/documentos/' . basename($documento->archivo)) }}"
                                style="display: block; border: 1px solid #ccc; pointer-events: auto;" frameborder="0"
                                loading="lazy" width="100%" height="250px"></iframe>
                            <!-- Añadir div para el ícono de PDF -->
                            <div id="pdfIcon" class="pdf-preview-icon text-center" style="display: none;">
                                <i class="fa fa-file-pdf-o" style="font-size: 64px; color: #d9534f;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center pt-serif-regular mt-2">
                        <p class="nombre_archivo text-center" data-original-name="{{ basename($documento->archivo) }}">
                            {{ basename($documento->archivo) }}</p>
                    </div>
                    <div class="container-input mb-3">
                        <input type="file" name="archivo" id="archivo" class="inputfile inputfile-1" accept=".pdf" />
                        <label for="archivo">
                            <i class="fa fa-repeat" aria-hidden="true"></i>
                            <span class="iborrainputfile">Reemplazar archivo</span>
                        </label>
                    </div>
                    <div class="container-input mb-3">
                        <a href="#" class="btn btn-doc" onclick="openPdfModal()">
                            <i class="fa fa-eye" aria-hidden="true"></i> Visualizar Archivo Actual
                        </a>
                    </div>
                    <!-- Modal para visualizar PDF -->
                    <div class="modal fade pt-serif-regular" id="pdfModal" tabindex="-1" role="dialog"
                        aria-labelledby="pdfModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="pdfModalLabel">{{ basename($documento->archivo) }}</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body" id="pdfModalBody">
                                    <!-- Ícono de PDF que se muestra solo en dispositivos móviles -->
                                    <div id="pdfIcon" class="text-center" style="display: none;">
                                        <i class="fa fa-file-pdf-o" style="font-size: 5em; color: #D9534F;"></i>
                                    </div>

                                    <!-- Previsualización del PDF que se muestra en pantallas grandes -->
                                    <iframe id="pdfPreview"
                                        src="{{ asset('storage/documentos/' . basename($documento->archivo)) }}"
                                        style="width: 100%; height: 500px; border: none;"></iframe>
                                </div>
                                <div class="modal-footer">
                                    <a href="{{ asset('storage/documentos/' . basename($documento->archivo)) }}"
                                        class="btn btn-info" target="_blank">
                                        <i class="fa fa-external-link-square" aria-hidden="true"></i> Abrir en otra ventana
                                    </a>
                                    <a href="{{ asset('storage/documentos/' . basename($documento->archivo)) }}"
                                        download="{{ basename($documento->archivo) }}" class="btn btn-dark">
                                        <i class="fa fa-download" aria-hidden="true"></i> Descargar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- JavaScript para detectar la resolución de pantalla -->
                    <script>
                        function checkScreenSize() {
                            const pdfIcon = document.getElementById('pdfIcon');
                            const pdfPreview = document.getElementById('pdfPreview');

                            if (window.innerWidth <= 768) { // Se puede ajustar el tamaño de la resolución
                                pdfIcon.style.display = 'block';
                                pdfPreview.style.display = 'none';
                            } else {
                                pdfIcon.style.display = 'none';
                                pdfPreview.style.display = 'block';
                            }
                        }

                        // Ejecutar la función cuando el modal se abre
                        document.getElementById('pdfModal').addEventListener('shown.bs.modal', checkScreenSize);

                        // Detectar cambio de tamaño de pantalla
                        window.addEventListener('resize', checkScreenSize);
                    </script>

                </div>


                <!-- Formulario a la derecha -->
                <div class="col-md-8">
                    <div class="form-group mb-2">
                        <label for="titulo">Título:</label>
                        <input type="text" class="form-control" name="titulo" id="titulo"
                            placeholder="Ingrese el título" value="{{ old('titulo', $documento->titulo) }}" required>
                    </div>

                    <div class="form-group mt-3">
                        <label for="descripcion">Descripción:</label>
                        <textarea class="form-control" name="descripcion" id="descripcion" rows="5" placeholder="Ingrese la descripción"
                            required>{{ old('descripcion', $documento->descripcion) }}</textarea>
                    </div>
                    <div class="row">
                        <div class="form-group mb-2 mt-3 col-md-6">
                            <label for="tipodocumento_id">Tipo Documento:</label>
                            <select name="tipodocumento_id" class="form-control" id="tipodocumento_id" required>
                                @foreach ($tiposDocumento as $tipoDocumento)
                                    <option value="{{ $tipoDocumento->id }}"
                                        {{ $documento->tipodocumento_id == $tipoDocumento->id ? 'selected' : '' }}>
                                        {{ $tipoDocumento->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-3 col-md-6">
                            <label for="estado">Estado:</label>
                            <select name="estado" class="form-control" id="estado" disabled>
                                <option value="Creado" {{ $documento->estado == 'Creado' ? 'selected' : '' }}
                                    style="color: red">Creado</option>
                                <option value="Validado" {{ $documento->estado == 'Validado' ? 'selected' : '' }}
                                    style="color: green">Validado</option>
                                <option value="Publicado" {{ $documento->estado == 'Publicado' ? 'selected' : '' }}
                                    style="color: blue">Publicado</option>
                            </select>
                            <!-- Botón para abrir el modal de cambio de estado -->
                            <button type="button" class="btn btn-warning mt-2 btn-documento" data-bs-toggle="modal"
                                data-bs-target="#changeStatusModal">Cambiar Estado</button>
                        </div>
                    </div>

                    <div class="mt-0">
                        <a href="{{ route('documentos.index') }}" class="btn btn-warning btn-documento me-2"><i
                                class="fa fa-arrow-circle-left" aria-hidden="true"></i> Cancelar</a>
                        <button type="submit" class="btn btn-success btn-documento ms-2"><i class="fa fa-save"
                                aria-hidden="true"></i> Guardar Cambios</button>
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
                    @php
                        // Obtener el usuario autenticado
                        $usuarioAutenticado = auth()->user();

                        // Verificar si el usuario tiene el rol "Usuario Validador" con el privilegio "Acceso a Validar Documento"
                        $esUsuarioValidadorConPrivilegio =
                            $usuarioAutenticado->rol->nombre === 'UsuarioValidador' &&
                            $usuarioAutenticado->rol->privilegios->contains('nombre', 'Acceso a Validar Documento');

                        // Verificar si el usuario tiene el rol "Usuario Publicador" con el privilegio "Acceso a Publicar Documento"
                        $esUsuarioPublicadorConPrivilegio =
                            $usuarioAutenticado->rol->nombre === 'UsuarioPublicador' &&
                            $usuarioAutenticado->rol->privilegios->contains('nombre', 'Acceso a Publicar Documento');
                    @endphp

                    <select id="nuevo_estado" class="form-control" name="nuevo_estado" required>
                        {{-- Mostrar solo "Validado" si es "Usuario Validador" con privilegio de validar --}}
                        @if ($esUsuarioValidadorConPrivilegio)
                            <option value="Validado" style="color: green">Validado</option>
                            {{-- Mostrar solo "Publicado" si es "Usuario Publicador" con privilegio de publicar --}}
                        @elseif ($esUsuarioPublicadorConPrivilegio)
                            <option value="Publicado" style="color: blue">Publicado</option>
                        @else
                            {{-- Mostrar todas las opciones para otros usuarios --}}
                            <option value="Creado" style="color: red">Creado</option>
                            <option value="Validado" style="color: green">Validado</option>
                            <option value="Publicado" style="color: blue">Publicado</option>
                        @endif
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
                            // Redireccionar a la página de índice de documentos
                            window.location.href =
                                "{{ route('documentos.index') }}"; // Asegúrate de que la ruta sea correcta
                        } else {
                            toastr.error(response.message ||
                                'Ha ocurrido un error al cambiar el estado.');
                        }
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        if (response && response.errors) {
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
        });
    </script>
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
    <script>
        function openPdfModal() {
            var pdfUrl = "{{ asset('storage/documentos/' . basename($documento->archivo)) }}";
            var modalBody = document.getElementById('pdfModalBody');
            modalBody.innerHTML = '<embed src="' + pdfUrl + '" type="application/pdf" width="100%" height="500px" />';
            $('#pdfModal').modal('show');
        }
    </script>
    <script>
        $(document).ready(function() {
            $('.btn-close, .btn-no').click(function() {
                $('#pdfModal').modal('hide');
            });
        });
    </script>
    <script>
        // Función para ajustar la vista según el tamaño de pantalla
        function adjustPdfView() {
            const pdfIframe = document.getElementById('pdfIframe');
            const pdfIcon = document.getElementById('pdfIcon');
            const currentFile = document.querySelector('.nombre_archivo').textContent;

            if (window.innerWidth < 768) {
                // Vista móvil
                if (pdfIframe) pdfIframe.style.display = 'none';
                if (pdfIcon) {
                    pdfIcon.style.display = 'block';
                    // Actualizar el nombre del archivo en el ícono
                    const fileNameElement = pdfIcon.querySelector('p');
                    if (fileNameElement) fileNameElement.textContent = currentFile;
                }
            } else {
                // Vista desktop
                if (pdfIframe) pdfIframe.style.display = 'block';
                if (pdfIcon) pdfIcon.style.display = 'none';
            }
        }

        // Manejar el cambio de archivo
        document.getElementById('archivo').addEventListener('change', function(e) {
            const file = this.files[0];
            const nombreArchivoElement = document.querySelector('.nombre_archivo');
            const pdfIframe = document.getElementById('pdfIframe');
            const pdfIcon = document.getElementById('pdfIcon');

            if (file) {
                const fileName = file.name;
                const fileURL = URL.createObjectURL(file);

                // Actualizar el nombre del archivo
                if (nombreArchivoElement) {
                    nombreArchivoElement.textContent = fileName;
                }

                // Actualizar la previsualización según el tamaño de pantalla
                if (window.innerWidth < 768) {
                    // Vista móvil
                    if (pdfIframe) pdfIframe.style.display = 'none';
                    if (pdfIcon) {
                        pdfIcon.style.display = 'block';
                        const fileNameElement = pdfIcon.querySelector('p');
                        if (fileNameElement) fileNameElement.textContent = fileName;
                    }
                } else {
                    // Vista desktop
                    if (pdfIframe) {
                        pdfIframe.src = fileURL;
                        pdfIframe.style.display = 'block';
                    }
                    if (pdfIcon) pdfIcon.style.display = 'none';
                }
            }
        });

        // Ejecutar al cargar la página y al cambiar el tamaño de la ventana
        document.addEventListener('DOMContentLoaded', adjustPdfView);
        window.addEventListener('resize', adjustPdfView);
    </script>
    <script>
        function checkScreenSize() {
            const pdfIcon = document.getElementById('pdfIcon');
            const pdfPreview = document.getElementById('pdfPreview');

            if (window.innerWidth <= 768) { // Se puede ajustar el tamaño de la resolución
                pdfIcon.style.display = 'block';
                pdfPreview.style.display = 'none';
            } else {
                pdfIcon.style.display = 'none';
                pdfPreview.style.display = 'block';
            }
        }

        // Ejecutar la función cuando el modal se abre
        document.getElementById('pdfModal').addEventListener('shown.bs.modal', checkScreenSize);

        // Detectar cambio de tamaño de pantalla
        window.addEventListener('resize', checkScreenSize);
    </script>

@endsection

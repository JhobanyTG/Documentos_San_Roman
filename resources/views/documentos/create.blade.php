@extends('layout/template')

@section('title', 'Crear Documento')

@section('content')

    <div class="container">
        <div class="row">
            <div class="container col-md-4 card form_documento">
                <h2 class="form_title_documento">
                    Crear Documento
                </h2>
                <div class="col-md-12">
                    <form action="{{ route('documentos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- Subir archivo a la izquierda -->
                            <div class="col-md-4 mb-3" style="margin-top:15px">
                                <div class="row">
                                    <div class="col-md-12 d-flex justify-content-center">
                                        <!-- Imagen para subir archivo -->
                                        <img class="img_file" id="uploadImage"
                                            src="{{ asset('images/icons/upload-file2.png') }}" />
                                    </div>
                                    <!-- Previsualización del archivo PDF -->
                                    <div class="row text-center">
                                        <div class="col-md-12">
                                            <iframe id="pdfPreview" src="" width="100%" height="250px"
                                                style="display:none; border: 1px solid #ccc;"></iframe>
                                        </div>
                                    </div>
                                    <div class="col-md-12 container-input mt-3">
                                        <input type="file" name="archivo" id="archivo" class="inputfile inputfile-1"
                                            accept=".pdf" required />
                                        <label for="archivo" class="form-label">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="iborrainputfile" width="20"
                                                height="17" viewBox="0 0 20 17">
                                                <path
                                                    d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z">
                                                </path>
                                            </svg>
                                            <span class="iborrainputfile">Seleccionar archivo</span>
                                        </label>
                                        <div class="container-input mb-3">
                                            <a href="#" class="btn btn-doc" id="viewPdfBtn">
                                                <i class="fa fa-eye" aria-hidden="true"></i> Visualizar Archivo
                                            </a>
                                        </div>
                                        <!-- Modal para visualizar PDF -->
                                        <div class="modal fade" id="pdfModal" tabindex="-1"
                                            aria-labelledby="pdfModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="pdfModalLabel">Vista Previa del Archivo
                                                            PDF</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Iframe para mostrar el PDF en el modal -->
                                                        <iframe id="modalPdfViewer" src="" width="100%"
                                                            height="500px" style="border: none;"></iframe>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <!-- Botón de Abrir en Nueva Ventana -->
                                                        <a id="openInNewWindowBtn" class="btn btn-secondary" href="#"
                                                            target="_blank">
                                                            Abrir en Nueva Ventana
                                                        </a>
                                                        <!-- Botón de Descargar -->
                                                        <a id="downloadPdfBtn" class="btn btn-primary" href="#"
                                                            download>
                                                            Descargar
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        @error('archivo')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>


                            </div>
                            <!-- Formulario a la derecha -->
                            <div class="col-md-8">
                                <div class="form-group mb-2">
                                    <label for="titulo" class="form-label label_documento">Título:</label>
                                    <input type="text"
                                        class="form-control documento @error('titulo') is-invalid @enderror" name="titulo"
                                        id="titulo" placeholder="Ingrese el título" value="{{ old('titulo') }}" required>
                                    @error('titulo')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="form-group mb-2 mt-2 col-md-6">
                                        <label for="tipodocumento_id" class="form-label label_documento">Tipo
                                            Documento:</label>
                                        <select name="tipodocumento_id"
                                            class="form-control documento @error('tipodocumento_id') is-invalid @enderror"
                                            id="tipodocumento_id" required>
                                            @foreach ($tiposDocumento as $tipoDocumento)
                                                <option value="{{ $tipoDocumento->id }}">{{ $tipoDocumento->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('tipodocumento_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mt-2 col-md-6">
                                        <label for="estado" class="form-label label_documento">Estado:</label>
                                        <select name="estado"
                                            class="form-control documento @error('estado') is-invalid @enderror"
                                            id="estado" required>
                                            <option value="Creado" style="color: red">Creado</option>
                                            <option value="Validado" style="color: green">Validado</option>
                                            <option value="Publicado" style="color: blue">Publicado</option>
                                        </select>
                                        @error('estado')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group mt-2">
                                    <label for="descripcion" class="form-label label_documento">Descripción:</label>
                                    <textarea class="form-control documento @error('descripcion') is-invalid @enderror" name="descripcion"
                                        id="descripcion" rows="5" placeholder="Ingrese la descripción" required>{{ old('descripcion') }}</textarea>
                                    @error('descripcion')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>



                                <div class="mt-3">
                                    <a href="{{ route('documentos.index') }}"
                                        class="btn btn-warning btn-documento me-2"><i class="fa fa-arrow-circle-left"
                                        aria-hidden="true"></i> Cancelar</a>
                                    <button type="submit" class="btn btn-success btn-documento ms-2"><i class="fa fa-plus" aria-hidden="true"></i>
                                        Crear</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
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
        document.addEventListener("DOMContentLoaded", function() {
            var inputs = document.querySelectorAll(".inputfile");
            Array.prototype.forEach.call(inputs, function(input) {
                var label = input.nextElementSibling;
                input.addEventListener("change", function(e) {
                    var fileName = "";
                    if (input.files && input.files.length > 1)
                        fileName = input.getAttribute("data-multiple-caption").replace("{count}",
                            input.files.length);
                    else
                        fileName = e.target.value.split("\\").pop();
                    label.querySelector("span").innerHTML = fileName;
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('input[type="file"]').change(function() {
                var fileInput = $(this);
                var fileName = fileInput.val().split('\\').pop();
                var fileExtension = fileName.split('.').pop().toLowerCase();
                var allowedExtensions = ['pdf'];

                if (allowedExtensions.indexOf(fileExtension) === -1) {
                    fileInput.val('');
                    fileName = '';

                    var alertMessage =
                        'Solo se permiten archivos PDF.<br>Seleccione otro archivo por favor.';
                    var alertDiv = $(
                        '<div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 mt-2 ms-2" role="alert" style="z-index: 999; background-color: #C71E42; color: #FFFFFF;">' +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                        '<i class="fa fa-exclamation-triangle me-2" aria-hidden="true"></i>' +
                        alertMessage +
                        '</div>');
                    $('body').append(alertDiv);
                    setTimeout(function() {
                        alertDiv.fadeOut(500, function() {
                            $(this).remove();
                        });
                    }, 5000);
                }

                fileInput.siblings('.inputfile').text(fileName);
            });

            $('form').submit(function() {
                var fileInput = $('input[type="file"]');
                var fileName = fileInput.val().split('\\').pop();
                var fileExtension = fileName.split('.').pop().toLowerCase();
                var allowedExtensions = ['pdf'];

                if (allowedExtensions.indexOf(fileExtension) === -1) {
                    fileInput.val('');
                    fileName = '';

                    var alertMessage =
                        'Solo se permiten archivos PDF.<br>Seleccione otro archivo por favor.';
                    var alertDiv = $(
                        '<div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 mt-2 ms-2" role="alert" style="z-index: 999; background-color: #C71E42; color: #FFFFFF;">' +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                        '<i class="fa fa-exclamation-triangle me-2" aria-hidden="true"></i>' +
                        alertMessage +
                        '</div>');
                    $('body').append(alertDiv);
                    setTimeout(function() {
                        alertDiv.fadeOut(500, function() {
                            $(this).remove();
                        });
                    }, 5000);

                    return false;
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var estadoSelect = document.getElementById('estado');

            function updateColor() {
                var selectedOption = estadoSelect.options[estadoSelect.selectedIndex];
                estadoSelect.style.color = getComputedStyle(selectedOption).color;
            }

            // Inicializa el color al cargar la página
            updateColor();

            // Actualiza el color cuando se cambia la opción
            estadoSelect.addEventListener('change', updateColor);
        });
    </script>
    <script>
        document.getElementById('archivo').addEventListener('change', function(event) {
            var file = event.target.files[0];
            if (file && file.type === 'application/pdf') {
                var fileURL = URL.createObjectURL(file);
                var pdfPreview = document.getElementById('pdfPreview');
                pdfPreview.src = fileURL;
                pdfPreview.style.display = 'block'; // Mostrar el iframe
            } else {
                // Ocultar la vista previa si el archivo no es PDF
                document.getElementById('pdfPreview').style.display = 'none';
            }
        });
    </script>
    <script>
        document.getElementById('archivo').addEventListener('change', function(event) {
            var file = event.target.files[0];
            var uploadImage = document.getElementById('uploadImage');
            var pdfPreview = document.getElementById('pdfPreview');

            if (file) {
                var fileURL = URL.createObjectURL(file);
                pdfPreview.src = fileURL;

                // Mostrar previsualización y ocultar imagen de subida
                pdfPreview.style.display = 'block';
                uploadImage.style.display = 'none';
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var viewPdfBtn = document.getElementById('viewPdfBtn');
            var modalPdfViewer = document.getElementById('modalPdfViewer');
            var downloadPdfBtn = document.getElementById('downloadPdfBtn');
            var openInNewWindowBtn = document.getElementById('openInNewWindowBtn');
            var archivoInput = document.getElementById('archivo');
            var pdfModal = new bootstrap.Modal(document.getElementById('pdfModal'));
            var currentPdfUrl = '';

            // Al hacer clic en "Visualizar Archivo"
            viewPdfBtn.addEventListener('click', function(e) {
                e.preventDefault();

                // Obtener el archivo actual del input
                var file = archivoInput.files[0];

                if (file && file.type === 'application/pdf') {
                    currentPdfUrl = URL.createObjectURL(file);
                    modalPdfViewer.src = currentPdfUrl; // Cargar el archivo en el iframe
                    downloadPdfBtn.href = currentPdfUrl; // Configurar enlace de descarga
                    openInNewWindowBtn.href =
                    currentPdfUrl; // Configurar enlace para abrir en nueva ventana

                    // Establecer el título del modal al nombre del archivo
                    var fileName = file.name; // Obtener el nombre del archivo
                    document.getElementById('pdfModalLabel').textContent =
                    fileName; // Actualizar el título del modal

                    pdfModal.show(); // Mostrar el modal
                } else {
                    alert('Por favor, selecciona un archivo PDF primero.');
                }
            });
        });
    </script>
@endsection

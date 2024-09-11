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
                        <p class="nombre_archivo text-center" data-original-name="{{ basename($documento->archivo) }}">{{ basename($documento->archivo) }}</p>
                    </div>
                    <div class="col-md-12 container-input">
                        <input type="file" name="archivo" id="archivo" class="inputfile inputfile-1" accept=".pdf" />
                        <label for="archivo">
                            <i class="fa fa-repeat" aria-hidden="true"></i>
                            <span class="iborrainputfile">Reemplazar archivo</span>
                        </label>
                    </div>
                    <div class="col-md-12 container-input pt-serif-regular">
                        <a href="#" class="btn btn-success" onclick="openPdfModal()"><i class="fa fa-eye" aria-hidden="true"></i> Visualizar Archivo Actual</a>
                    </div>
                    <div class="modal fade pt-serif-regular" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="pdfModalLabel">{{ basename($documento->archivo) }}</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" id="pdfModalBody"></div>
                                <div class="modal-footer">
                                <a href="{{ asset('storage/archivos/'.basename($documento->archivo)) }}" class="btn btn-info" target="_blank"><i class="fa fa-external-link-square" aria-hidden="true"></i> Abrir en otra ventana</a>
                                <a href="{{ asset('storage/archivos/'.basename($documento->archivo)) }}" download="{{ basename($documento->archivo) }}" class="btn btn-dark"><i class="fa fa-download" aria-hidden="true"></i> Descargar</a>
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
                    <input type="text" class="form-control" name="titulo" id="titulo" placeholder="Ingrese el título" value="{{ old('titulo', $documento->titulo) }}" required>
                </div>
                <div class="form-group mb-2">
                    <label for="tipodocumento_id">Tipo Documento:</label>
                    <select name="tipodocumento_id" class="form-control" id="tipodocumento_id" required>
                        @foreach($tiposDocumento as $tipoDocumento)
                            <option value="{{ $tipoDocumento->id }}" {{ $documento->tipodocumento_id == $tipoDocumento->id ? 'selected' : '' }}>{{ $tipoDocumento->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mt-3">
                    <label for="descripcion">Descripción:</label>
                    <textarea class="form-control" name="descripcion" id="descripcion" rows="5" placeholder="Ingrese la descripción" required>{{ old('descripcion', $documento->descripcion) }}</textarea>
                </div>

                <div class="form-group mt-3">
                    <label for="estado">Estado:</label>
                    <select name="estado" class="form-control" id="estado" required>
                        <option   value="Creado" {{ $documento->estado == 'Creado' ? 'selected' : '' }} style="color: red">Creado</option>
                        <option   value="Validado" {{ $documento->estado == 'Validado' ? 'selected' : '' }} style="color: green">Validado</option>
                        <option   value="Publicado" {{ $documento->estado == 'Publicado' ? 'selected' : '' }} style="color: blue">Publicado</option>
                    </select>
                </div>

                <div class="mt-3">
                    <a href="{{ route('documentos.index') }}" class="btn btn-warning">Cancelar</a>
                    <button type="submit" class="btn btn-success">Actualizar</button>
                </div>
            </div>
        </div>
    </form>
</div>

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
        var pdfUrl = "{{ asset('storage/documentos/'.basename($documento->archivo)) }}";
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
    document.getElementById('archivo').addEventListener('change', function(e) {
        var fileName = '';
        if (this.files && this.files.length > 0) {
            fileName = this.files[0].name;
        }
        var nombreArchivoElement = document.querySelector('.nombre_archivo');
        if (nombreArchivoElement) {
            nombreArchivoElement.textContent = fileName;
        }
    });
</script>
@endsection

@extends('layout/template')

@section('title', 'Lista de Documentos')

@section('content')

    <div class="container mt-4">
        <div class="d-flex justify-content-end mb-3">
            @php
                // Obtener el usuario autenticado
                $usuarioAutenticado = auth()->user();

                // Verificar si el usuario tiene el rol de "Usuario Validador"
                $esUsuarioValidador =
                    $usuarioAutenticado->rol->nombre === 'UsuarioValidador' ||
                    $usuarioAutenticado->rol->nombre === 'UsuarioPublicador';

                // Verificar si el usuario tiene el privilegio "Acceso a Validar Documento"
                $tienePrivilegioValidarDocumento =
                    $usuarioAutenticado->rol->privilegios->contains('nombre', 'Acceso a Validar Documento') ||
                    $usuarioAutenticado->rol->privilegios->contains('nombre', 'Acceso a Publicar Documento');
            @endphp

            {{-- Mostrar el botón solo si el usuario no es "Usuario Validador" o no tiene el privilegio "Acceso a Validar Documento" --}}
            @if (!($esUsuarioValidador && $tienePrivilegioValidarDocumento))
                <a href="{{ route('documentos.create') }}" class="btn btn-doc pt-serif-regular me-2">
                    <i class="fa fa-plus" aria-hidden="true"></i> Registrar
                </a>
            @endif

            <form action="{{ route('reporte.documentos') }}" method="GET" style="display: inline;">
                <input type="hidden" name="q" value="{{ $searchTerm }}">
                <input type="hidden" name="fecha" value="{{ $fecha }}">
                <input type="hidden" name="anio" value="{{ $filtroAnio }}">
                <input type="hidden" name="mes" value="{{ json_encode($filtroMes) }}">
                <input type="hidden" name="tipodocumento_id" value="{{ json_encode($filtroTipoDocumento) }}">
                <button type="submit" class="btn btn-doc pt-serif-regular"><i class="fa fa-download"></i> Descargar Reporte
                    PDF</button>
            </form>
        </div>
        <form action="{{ route('documentos.index') }}" method="GET" class="mb-3">
            <div class="buscador input-group mb-3">
                <input type="text" class="form-control" placeholder="Buscar..." name="q"
                    value="{{ $searchTerm }}">
                @if ($filtroAnio || $searchTerm || !empty($filtroMes) || !empty($filtroTipoDocumento))
                    <a class="border border-2" href="{{ route('documentos.index') }}">
                        <i class="fa fa-times m-4" style="color: red;" aria-hidden="true"></i>
                    </a>
                @endif
                @if ($filtroAnio)
                    <input type="hidden" name="anio" value="{{ $filtroAnio }}">
                @endif
                @if (!empty($filtroMes))
                    @foreach ($filtroMes as $mes)
                        <input type="hidden" name="mes[]" value="{{ $mes }}">
                    @endforeach
                @endif
                @if (!empty($filtroTipoDocumento))
                    @foreach ($filtroTipoDocumento as $tipo)
                        <input type="hidden" name="tipodocumento_id[]" value="{{ $tipo }}">
                    @endforeach
                @endif
                <button class="btn btn-doc " type="submit">Buscar</button>
            </div>
        </form>
        @if ($searchTerm || $filtroAnio || !empty($filtroMes) || !empty($filtroTipoDocumento))
            <p class="resultado-buscador">
                Resultados de búsqueda de:
                @if ($searchTerm || $filtroAnio || !empty($filtroMes) || !empty($filtroTipoDocumento))
                    @if ($filtroAnio)
                        @if ($searchTerm || $filtroMes || $filtroTipoDocumento)
                        @endif
                        <strong>Año: {{ $filtroAnio }}</strong>
                    @endif

                    @if ($filtroMes)
                        @if ($filtroAnio || $filtroTipoDocumento || $searchTerm)
                            ,
                        @endif
                        <strong>Mes:

                            @php
                                $mesesEnEspanol = [
                                    1 => 'Enero',
                                    2 => 'Febrero',
                                    3 => 'Marzo',
                                    4 => 'Abril',
                                    5 => 'Mayo',
                                    6 => 'Junio',
                                    7 => 'Julio',
                                    8 => 'Agosto',
                                    9 => 'Septiembre',
                                    10 => 'Octubre',
                                    11 => 'Noviembre',
                                    12 => 'Diciembre',
                                ];
                            @endphp
                            {{ implode(', ', array_map(fn($mes) => $mesesEnEspanol[$mes] ?? $mes, $filtroMes)) }}
                        </strong>
                    @endif

                    @if (!empty($filtroTipoDocumento))
                        @if ($searchTerm || $filtroAnio || $filtroMes)
                            ,
                        @endif
                        <strong>Tipo:
                            {{ implode(', ', $tiposDocumento->whereIn('id', $filtroTipoDocumento)->pluck('nombre')->toArray()) ?: 'Ninguno seleccionado' }}
                        </strong>
                    @endif

                    @if ($searchTerm)
                        @if ($filtroAnio || $filtroMes || $filtroTipoDocumento)
                            y
                        @endif
                        <strong> Término: {{ $searchTerm }}</strong>
                    @endif
                @else
                    <strong>No se encontraron resultados.</strong>
                @endif
            </p>
        @endif
        <div class="row">
            <!-- Columna del filtro -->
            <div class="filtro order-md-2 col-md-2 filtro_doc">
                <div class="mb-3">
                    <h4>Listar</h4>
                    <div class="row">
                        <div class="col-12">
                            <form action="{{ route('documentos.index') }}" method="GET" class="d-inline">
                                <button type="submit"
                                    class="btn btn-block w-100 {{ !$filtroAnio ? 'btn-dark' : 'btn-light' }}">
                                    Todos
                                </button>
                            </form>
                            @foreach ($availableYears as $year)
                                <form action="{{ route('documentos.index') }}" method="GET" class="d-inline">
                                    <input type="hidden" name="anio" value="{{ $year }}">
                                    <button type="submit"
                                        class="btn btn-block w-100 {{ $filtroAnio == $year ? 'btn-dark' : 'btn-light' }}">
                                        {{ $year }}
                                    </button>
                                    <input type="hidden" class="form-control" placeholder="Buscar..." name="q"
                                        value="{{ $searchTerm }}">
                                </form>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Filtros de Meses -->
                <div class="row mt-4 ">
                    <div class="col-12">
                        <h4>Filtros</h4>
                        <div class="row">
                            <div class="col-12">
                                <form action="{{ route('documentos.index') }}" method="GET" id="filtroForm">
                                    <div class="input-group mb-3 ">
                                        <div class="col-12">
                                            @if ($filtroAnio)
                                                @php
                                                    $mesesEnEspanol = [
                                                        1 => 'Enero',
                                                        2 => 'Febrero',
                                                        3 => 'Marzo',
                                                        4 => 'Abril',
                                                        5 => 'Mayo',
                                                        6 => 'Junio',
                                                        7 => 'Julio',
                                                        8 => 'Agosto',
                                                        9 => 'Septiembre',
                                                        10 => 'Octubre',
                                                        11 => 'Noviembre',
                                                        12 => 'Diciembre',
                                                    ];
                                                @endphp
                                                @foreach ($availableMonths as $month)
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input m-1" name="mes[]"
                                                            id="mes{{ $month }}" value="{{ $month }}"
                                                            {{ in_array($month, $filtroMes) ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="mes{{ $month }}">{{ $mesesEnEspanol[$month] }}</label>
                                                    </div>
                                                @endforeach
                                            @else
                                                <p>Selecciona un año para filtrar los meses.</p>
                                            @endif
                                        </div>
                                        <br>
                                        <div class="mb-3">
                                            <h4 for="tipodocumento_id" class="form-label">Tipo de Documento</h4>
                                            <div>
                                                @foreach ($tiposDocumento as $tipo)
                                                    <div class="form-check">
                                                        <input type="checkbox" name="tipodocumento_id[]"
                                                            value="{{ $tipo->id }}"
                                                            id="tipodocumento_{{ $tipo->id }}"
                                                            class="form-check-input"
                                                            {{ is_array($filtroTipoDocumento) && in_array($tipo->id, $filtroTipoDocumento) ? 'checked' : '' }}>
                                                        <label for="tipodocumento_{{ $tipo->id }}"
                                                            class="form-check-label">{{ $tipo->nombre }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <input type="hidden" name="anio" value="{{ $filtroAnio }}">
                                        <input type="hidden" name="q" value="{{ $searchTerm }}">
                                        <div style="display: block; margin-bottom: 10px; width: 100%;">
                                            <button class="boton-filtro btn btn-doc " type="submit">Ejecutar
                                                Filtro</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table_doc col-md-10 order-md-1">
                <div class="card-body">
                    <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                        <table id="example1" class="table mt-4 table-hover pt-serif-regular" role="grid"
                            aria-describedby="example1_info">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Fecha</th>
                                    <th>Título</th>
                                    <th>Tipo Documento</th>
                                    <th>Descripción</th>
                                    <th class="col-1">
                                        <div class="imagen_title_index">Archivo</div>
                                    </th>
                                    <th>Gerencia</th>
                                    <th>SubGerencia</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($documentos as $documento)
                                    <tr role="row" class="border-table border-bottom-3"
                                        data-id="{{ $documento->id }}">
                                        <td class="text-center">
                                            {{ $documento->id }}

                                        </td>
                                        <td class="text-center">
                                            {{ $documento->created_at->format('Y-m-d') }}<br>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                if ($searchTerm) {
                                                    // Escapar el término de búsqueda para evitar problemas de HTML
                                                    $escapedSearchTerm = preg_quote($searchTerm, '/');
                                                    // Usar preg_replace para reemplazo insensible al caso
                                                    $highlightedTitle = preg_replace(
                                                        '/(' . $escapedSearchTerm . ')/i',
                                                        '<mark>$1</mark>',
                                                        $documento->titulo,
                                                    );
                                                } else {
                                                    // Si no hay término de búsqueda, mostrar el título sin resaltar
                                                    $highlightedTitle = $documento->titulo;
                                                }
                                            @endphp
                                            {!! $highlightedTitle !!}
                                        </td>
                                        <td class="text-center">{{ $documento->tipoDocumento->nombre }}</td>
                                        <td class="text-center">
                                            @if (strlen($documento->descripcion) > 370)
                                                @php
                                                    if ($searchTerm) {
                                                        // Escapar el término de búsqueda para evitar problemas de HTML
                                                        $escapedSearchTerm = preg_quote($searchTerm, '/');
                                                        // Usar preg_replace para reemplazo insensible al caso en la descripción truncada
                                                        $highlightedDescription = preg_replace(
                                                            '/(' . $escapedSearchTerm . ')/i',
                                                            '<mark>$1</mark>',
                                                            substr($documento->descripcion, 0, 370),
                                                        );
                                                        // Usar preg_replace para reemplazo insensible al caso en la descripción completa
                                                        $highlightedFullDescription = preg_replace(
                                                            '/(' . $escapedSearchTerm . ')/i',
                                                            '<mark>$1</mark>',
                                                            $documento->descripcion,
                                                        );
                                                    } else {
                                                        // Si no hay término de búsqueda, mostrar la descripción sin resaltar
                                                        $highlightedDescription = substr(
                                                            $documento->descripcion,
                                                            0,
                                                            370,
                                                        );
                                                        $highlightedFullDescription = $documento->descripcion;
                                                    }
                                                @endphp
                                                <span class="truncated">{!! $highlightedDescription !!}...</span>
                                                <span class="expand-description"
                                                    data-target="#desc-{{ $documento->id }}">Ver más</span>
                                                <div id="desc-{{ $documento->id }}" class="collapse full-description">
                                                    <span>{!! $highlightedFullDescription !!}</span>
                                                    <span class="collapse-description">Ver menos</span>
                                                </div>
                                            @else
                                                @php
                                                    if ($searchTerm) {
                                                        // Escapar el término de búsqueda para evitar problemas de HTML
                                                        $escapedSearchTerm = preg_quote($searchTerm, '/');
                                                        // Usar preg_replace para reemplazo insensible al caso en la descripción completa
                                                        $highlightedDescription = preg_replace(
                                                            '/(' . $escapedSearchTerm . ')/i',
                                                            '<mark>$1</mark>',
                                                            $documento->descripcion,
                                                        );
                                                    } else {
                                                        // Si no hay término de búsqueda, mostrar la descripción sin resaltar
                                                        $highlightedDescription = $documento->descripcion;
                                                    }
                                                @endphp
                                                {!! $highlightedDescription !!}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="previwe-pdf">
                                                <div class="archivo-preview" style="overflow: hidden">
                                                    <div style="margin-right: -16px;">
                                                        <iframe id="pdfIframe"
                                                            src="{{ asset('storage/documentos/' . basename($documento->archivo)) }}"
                                                            type="application/pdf"
                                                            style="display: block; overflow: hidden scroll; height: 160px; width: 100%; pointer-events: none;"
                                                            frameborder="0" loading="lazy"></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                            <img class="img_file_pdf centered-img"
                                                src="{{ asset('images/icons/pdf.png') }}" alt="PDF" />
                                        </td>
                                        <td class="text-center">
                                            {{ $documento->gerencia ? $documento->gerencia->nombre : 'Creado Por el administrador' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $documento->subgerencia ? $documento->subgerencia->nombre : 'N/A' }}</td>
                                        <td class="text-center">
                                            @if ($documento->estado === 'Creado')
                                                <span class="badge text-bg-danger">Creado</span>
                                            @elseif($documento->estado === 'Validado')
                                                <span class="badge text-bg-success">Validado</span>
                                            @elseif($documento->estado === 'Publicado')
                                                <span class="badge text-bg-primary">Publicado</span>
                                            @endif
                                        </td>
                                        {{-- <td>
                                        <a href="{{ route('documentos.edit', $documento->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                        <form action="{{ route('documentos.destroy', $documento->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar este documento?')">Eliminar</button>
                                        </form>
                                    </td> --}}

                                        <td class="text-center border-table-row-final">
                                            <a href="{{ asset('storage/documentos/' . basename($documento->archivo)) }}"
                                                class="btn btn-primary" download><i class="fa fa-download"
                                                    aria-hidden="true"></i></a>

                                            @php
                                                // Obtener el usuario autenticado
                                                $usuarioAutenticado = auth()->user();

                                                // Verificar si el usuario tiene el privilegio "Acceso a Gerencia"
                                                $tieneAccesoGerencia = $usuarioAutenticado->rol->privilegios->contains(
                                                    'nombre',
                                                    'Acceso a Gerencia',
                                                );

                                                // Verificar si el usuario tiene el privilegio "Acceso a Subgerencia"
                                                $tieneAccesoSubgerencia = $usuarioAutenticado->rol->privilegios->contains(
                                                    'nombre',
                                                    'Acceso a Subgerencia',
                                                );

                                                // Verificar si el usuario tiene el rol "UsuarioPublicador"
                                                $esUsuarioPublicador =
                                                    $usuarioAutenticado->rol->nombre === 'UsuarioPublicador';

                                                // Verificar si el usuario tiene el rol "UsuarioValidador"
                                                $esUsuarioValidador =
                                                    $usuarioAutenticado->rol->nombre === 'UsuarioValidador';

                                                // Determinar si se deben mostrar los botones de edición
                                                $mostrarBotonEditar =
                                                    ($esUsuarioPublicador && $documento->estado === 'Validado') ||
                                                    ($esUsuarioValidador && $documento->estado === 'Creado') ||
                                                    $tieneAccesoGerencia;

                                                // Determinar si se deben mostrar los botones de eliminación
                                                $mostrarBotonEliminar = $tieneAccesoGerencia;
                                            @endphp

                                            @if ($mostrarBotonEditar)
                                                <a href="{{ route('documentos.edit', $documento->id) }}"
                                                    class="btn btn-warning">
                                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                                </a>
                                            @endif

                                            @if ($mostrarBotonEliminar)
                                                <form action="{{ route('documentos.destroy', $documento->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="showConfirmationModal()">
                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>


                                        <div class="modal fade pt-serif-regular" id="pdfModal-{{ $documento->id }}"
                                            tabindex="-1" role="dialog"
                                            aria-labelledby="pdfModalLabel-{{ $documento->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="pdfModalLabel-{{ $documento->id }}">
                                                            {{ $documento->titulo }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body" id="pdfModalBody-{{ $documento->id }}">
                                                        <!-- El contenido se insertará dinámicamente -->
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a href="#" onclick="getDocumentUrl({{ $documento->id }})"
                                                            class="btn btn-info">
                                                            <i class="fa fa-external-link-square" aria-hidden="true"></i>
                                                            Abrir en otra ventana
                                                        </a>
                                                        <a href="{{ asset('storage/documentos/' . basename($documento->archivo)) }}"
                                                            download="{{ basename($documento->archivo) }}"
                                                            class="btn btn-dark">
                                                            <i class="fa fa-download" aria-hidden="true"></i>
                                                            Descargar
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                    <div class="modal pt-serif-regular" tabindex="-1" role="dialog"
                                        id="confirmationModal">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Confirmar Eliminación</h5>
                                                    <button type="button" class="btn-close" data-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="text-center">¿Estás seguro de eliminar este Documento? Esta acción no se puede deshacer.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-no"
                                                        data-dismiss="modal"><i class="fa fa-ban" aria-hidden="true"></i>
                                                        Cancelar</button>
                                                    <form action="{{ route('documentos.destroy', $documento->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger"
                                                            onclick="return);"><i class="fa fa-trash"
                                                                aria-hidden="true"></i> Confirmar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                @endforeach
                                @if ($documentos->isEmpty())
                                    <p>No se encontraron resultados para la búsqueda.</p>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="paginacion">
            <div class="d-flex justify-content-center mt-4">
                {{ $documentos->links('pagination.custom') }}
            </div>
            <div class="text-center">
                @if ($documentos->count() > 1)
                    Mostrando ítems {{ $documentos->firstItem() }}-{{ $documentos->lastItem() }} de
                    {{ $documentos->total() }}
                @else
                    Mostrando ítem {{ $documentos->firstItem() }} de {{ $documentos->total() }}
                @endif
            </div>
        </div>
    </div>
    <script>
        function getDocumentUrl(documentId) {
            fetch(`/documento/get-url/${documentId}`)
                .then(response => response.json())
                .then(data => {
                    window.open(data.url, '_blank');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al acceder al documento');
                });
        }
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
        function showConfirmationModal() {
            $('#confirmationModal').modal('show');
        }
    </script>
    <script>
        $(document).ready(function() {
            $('.btn-close, .btn-no').click(function() {
                $('#confirmationModal').modal('hide');
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            function openPdfModal(pdfUrl, pdfName, modalId) {
                var modalBody = document.getElementById('pdfModalBody-' + modalId);

                // Verificar si es dispositivo móvil
                if (window.innerWidth <= 768) {
                    // Vista móvil - mostrar ícono PDF y botones
                    modalBody.innerHTML = `
                <div class="text-center">
                    <img src="/images/icons/pdf.png" alt="PDF Icon" class="pdf-icon-modal mb-3" style="width: 100px;">
                </div>
            `;
                } else {
                    // Vista desktop - mostrar embed del PDF
                    modalBody.innerHTML = '<embed src="' + pdfUrl +
                        '" type="application/pdf" width="100%" height="500px" />';
                }

                document.getElementById('pdfModalLabel-' + modalId).innerText = pdfName;
                $('#pdfModal-' + modalId).modal('show');
            }

            // Event listeners existentes
            $('.archivo-preview').on('click', function() {
                var pdfUrl = $(this).find('iframe').attr('src');
                var pdfName = $(this).closest('tr').find('td:nth-child(3)').text().trim();
                var modalId = $(this).closest('tr').data('id');
                openPdfModal(pdfUrl, pdfName, modalId);
            });

            $('.img_file_pdf').on('click', function() {
                var pdfUrl = $(this).closest('td').find('.archivo-preview iframe').attr('src');
                var pdfName = $(this).closest('tr').find('td:nth-child(3)').text().trim();
                var modalId = $(this).closest('tr').data('id');
                openPdfModal(pdfUrl, pdfName, modalId);
            });

            // Manejar cambios de tamaño de ventana
            $(window).on('resize', function() {
                $('.modal.show').each(function() {
                    var modalId = $(this).attr('id').replace('pdfModal-', '');
                    var pdfUrl = $(this).find('.modal-footer a').first().attr('href');
                    var pdfName = $(this).find('.modal-title').text();
                    openPdfModal(pdfUrl, pdfName, modalId);
                });
            });

            $('.btn-close, .btn-no').click(function() {
                $(this).closest('.modal').modal('hide');
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.expand-description').on('click', function(e) {
                e.preventDefault();
                var target = $(this).data('target');
                $(this).hide();
                $(target).collapse('show');
                $(target).find('.collapse-description').show();
                $(target).siblings('.truncated').hide();
            });

            $('.collapse-description').on('click', function(e) {
                e.preventDefault();
                var target = $(this).closest('.full-description');
                $(target).collapse('hide');
                $(this).hide();
                $(target).siblings('.expand-description').show();
                $(target).siblings('.truncated').show();
            });

            $('.collapse-description').hide();
            $('.full-description').collapse('hide');
        });
    </script>
@endsection

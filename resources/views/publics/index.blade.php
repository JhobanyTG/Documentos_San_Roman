@extends('layout/templatep')

@section('content')
<div class="container mt-4">
    <form action="{{ route('documentos.index') }}" method="GET" class="mb-3">
        <div class="buscador input-group mb-3">
            <input type="text" class="form-control" placeholder="Buscar..." name="q" value="{{ $searchTerm }}">
            @if ($filtroAnio || $searchTerm || !empty($filtroMes))
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
            <button class="btn btn-custom" type="submit">Buscar</button>
        </div>
    </form>
    @if ($searchTerm || $filtroAnio || !empty($filtroMes))
        <p class="resultado-buscador">
            Resultados de búsqueda de:
            @if ($filtroAnio)
                @if ($searchTerm || $filtroMes) @endif
                <strong>Año: {{ $filtroAnio }}</strong>
            @endif
            @if ($filtroMes)
                @if ($filtroAnio || $searchTerm), @endif
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
                            12 => 'Diciembre'
                        ];
                    @endphp
                    {{ implode(', ', array_map(fn($mes) => $mesesEnEspanol[$mes] ?? $mes, $filtroMes)) }}
                </strong>
            @endif
            @if ($searchTerm)
                @if ($filtroAnio || $filtroMes) y @endif
                <strong>Término: {{ $searchTerm }}</strong>
            @endif
        </p>
    @endif
    <div class="row">
        <div class="filtro order-md-2 col-md-2">
            <div class="mb-3">
                <h4>Listar</h4>
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('documentos.index') }}" method="GET" class="d-inline">
                            <button type="submit" class="btn btn-block w-100 {{ !$filtroAnio ? 'btn-dark' : 'btn-light' }}">
                                Todos
                            </button>
                        </form>
                        @foreach ($availableYears as $year)
                            <form action="{{ route('documentos.index') }}" method="GET" class="d-inline">
                                <input type="hidden" name="anio" value="{{ $year }}">
                                <button type="submit" class="btn btn-block w-100 {{ $filtroAnio == $year ? 'btn-dark' : 'btn-light' }}">
                                    {{ $year }}
                                </button>
                                <input type="hidden" class="form-control" placeholder="Buscar..." name="q" value="{{ $searchTerm }}">
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <h4>Filtros</h4>
                    <div class="row">
                        <div class="col-12">
                            <form action="{{ route('documentos.index') }}" method="GET" id="filtroForm">
                                <div class="input-group mb-3">
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
                                                12 => 'Diciembre'
                                            ];
                                        @endphp
                                        @foreach ($availableMonths as $month)
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input m-1" name="mes[]" id="mes{{ $month }}" value="{{ $month }}" {{ in_array($month, $filtroMes) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="mes{{ $month }}">{{ $mesesEnEspanol[$month] }}</label>
                                            </div>
                                        @endforeach
                                    @else
                                        <p>Selecciona un año para filtrar los meses.</p>
                                    @endif
                                    <input type="hidden" name="anio" value="{{ $filtroAnio }}">
                                    <input type="hidden" name="q" value="{{ $searchTerm }}">
                                    <div style="display: block; margin-bottom: 10px; width: 100%;">
                                        <button class="boton-filtro btn btn-primary" type="submit">Ejecutar Filtro</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-10 order-md-1">
            <div class="card-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <table id="example1" class="table mt-4 table-hover pt-serif-regular" role="grid" aria-describedby="example1_info">
                        <thead>
                            <tr role="row">
                                <th class="col-1"><div class="imagen_title_index">Imagen</div></th>
                                <th class="text-center col-2">Titulo</th>
                                <th class="text-center col-7">Descripcion</th>
                                <th class="text-center col-1">Fecha y Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documentos as $documento)
                                <tr role="row" class="border-table border-bottom-3" data-id="{{ $documento->id }}">
                                    <td class="text-center">
                                        <div class="previwe-pdf">
                                            <div class="archivo-preview" style="overflow: hidden">
                                                <div style="margin-right: -16px;">
                                                    <iframe id="pdfIframe" src="{{ asset('storage/archivos/' . basename($documento->archivo)) }}" type="application/pdf" style="display: block; overflow: hidden scroll; height: 160px; width: 100%; pointer-events: none;" frameborder="0" loading="lazy"></iframe>
                                                </div>
                                            </div>
                                        </div>
                                        <img class="img_file_pdf centered-img" src="{{ asset('images/icons/pdf.png') }}" alt="PDF" />
                                    </td>
                                    <td class="text-center">
                                        @php
                                            if ($searchTerm) {
                                                // Escapar el término de búsqueda para evitar problemas de HTML
                                                $escapedSearchTerm = preg_quote($searchTerm, '/');
                                                // Usar preg_replace para reemplazo insensible al caso
                                                $highlightedTitle = preg_replace('/(' . $escapedSearchTerm . ')/i', '<mark>$1</mark>', $documento->titulo);
                                            } else {
                                                // Si no hay término de búsqueda, mostrar el título sin resaltar
                                                $highlightedTitle = $documento->titulo;
                                            }
                                        @endphp
                                        {!! $highlightedTitle !!}
                                    </td>

                                    <td class="text-center">
                                        @if(strlen($documento->descripcion) > 370)
                                            @php
                                                if ($searchTerm) {
                                                    // Escapar el término de búsqueda para evitar problemas de HTML
                                                    $escapedSearchTerm = preg_quote($searchTerm, '/');
                                                    // Usar preg_replace para reemplazo insensible al caso en la descripción truncada
                                                    $highlightedDescription = preg_replace('/(' . $escapedSearchTerm . ')/i', '<mark>$1</mark>', substr($documento->descripcion, 0, 370));
                                                    // Usar preg_replace para reemplazo insensible al caso en la descripción completa
                                                    $highlightedFullDescription = preg_replace('/(' . $escapedSearchTerm . ')/i', '<mark>$1</mark>', $documento->descripcion);
                                                } else {
                                                    // Si no hay término de búsqueda, mostrar la descripción sin resaltar
                                                    $highlightedDescription = substr($documento->descripcion, 0, 370);
                                                    $highlightedFullDescription = $documento->descripcion;
                                                }
                                            @endphp
                                            <span class="truncated">{!! $highlightedDescription !!}...</span>
                                            <span class="expand-description" data-target="#desc-{{ $documento->id }}">Ver más</span>
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
                                                    $highlightedDescription = preg_replace('/(' . $escapedSearchTerm . ')/i', '<mark>$1</mark>', $documento->descripcion);
                                                } else {
                                                    // Si no hay término de búsqueda, mostrar la descripción sin resaltar
                                                    $highlightedDescription = $documento->descripcion;
                                                }
                                            @endphp
                                            {!! $highlightedDescription !!}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ $documento->created_at->format('Y-m-d') }}<br>
                                        {{ $documento->created_at->format('H:i') }}
                                    </td>
                                    <div class="modal fade pt-serif-regular" id="pdfModal-{{ $documento->id }}" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel-{{ $documento->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="pdfModalLabel-{{ $documento->id }}">{{ basename($documento->archivo) }}</h5>
                                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body" id="pdfModalBody-{{ $documento->id }}"></div>
                                                <div class="modal-footer">
                                                    <a href="{{ asset('storage/archivos/'.basename($documento->archivo)) }}" class="btn btn-info" target="_blank"><i class="fa fa-external-link-square" aria-hidden="true"></i> Abrir en otra ventana</a>
                                                    <a href="{{ asset('storage/archivos/'.basename($documento->archivo)) }}" download="{{ basename($documento->archivo) }}" class="btn btn-dark"><i class="fa fa-download" aria-hidden="true"></i> Descargar</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
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
                Mostrando ítems {{ $documentos->firstItem() }}-{{ $documentos->lastItem() }} de {{ $documentos->total() }}
            @else
                Mostrando ítem {{ $documentos->firstItem() }} de {{ $documentos->total() }}
            @endif
        </div>
    </div>
</div>

    <script>
        $(document).ready(function() {
            @if(Session::has('success'))
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

@stop

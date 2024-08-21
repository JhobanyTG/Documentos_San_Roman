@extends('layout.template')

@section('content')
<div class="container mt-4">

    <a href="{{ route('gerencias.index') }}" class="btn btn-secondary mb-3"><i class="fa fa-arrow-left" aria-hidden="true"></i> Volver</a>

    <h4 class="d-flex justify-content-center">Detalles de la Gerencia</h4>
    
    <div class="card">
        <div class="card-body">
            <p><strong>Nombre:</strong> {{ $gerencia->nombre }}</p>
            <p><strong>Descripción:</strong> {{ $gerencia->descripcion }}</p>
            <p><strong>Teléfono:</strong> {{ $gerencia->telefono }}</p>
            <p><strong>Dirección:</strong> {{ $gerencia->direccion }}</p>
            <p><strong>Estado:</strong> {{ $gerencia->estado }}</p>
            <p><strong>Gerente:</strong> {{ $gerencia->user->persona->nombres }} {{ $gerencia->user->persona->apellido_p }} {{ $gerencia->user->persona->apellido_m }}</p>

            <div class="mt-4 d-flex justify-content-end">
                <a href="{{ route('gerencias.edit', $gerencia->id) }}" class="btn btn-info mx-1">
                    <i class="fa fa-edit"></i> Editar
                </a>
                <button type="button" class="btn btn-secondary" onclick="showConfirmationModal()">
                    <i class="fa fa-trash" aria-hidden="true"></i> Eliminar
                </button>
                
                <div class="modal fade" tabindex="-1" role="dialog" id="confirmationModal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirmar Eliminación</h5>
                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>¿Estás seguro de eliminar esta gerencia? Esta acción no se puede deshacer.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-no" data-dismiss="modal">No</button>
                                <form action="{{ route('gerencias.destroy', $gerencia->id) }}" method="POST" class="d-inline-block">
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
            </div>
        </div>
    </div>

    <h4 class="d-flex justify-content-center mt-5 pt-serif-bold">Sub Usuarios</h4>
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('subusuarios.create', ['gerencia' => $gerencia->id]) }}" class="btn btn-agregar pt-serif-regular">
            <i class="fa fa-plus" aria-hidden="true"></i> Registrar
        </a>
    </div>
    <table class="table">
        <thead>
            <tr class="pt-serif-bold">
                <th class="">Nombre</th>
                <th class="">Cargo</th>
                <th class="">DNI</th>
                <th class="">Rol</th>
                <th class="">Sub Gerencia</th>
                <th class="col-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($gerencia->subgerencias as $subgerencia)
                @foreach($subgerencia->subusuarios as $subusuario)
                    <tr class="pt-serif-regular">
                        <td>{{ $subusuario->user->persona->nombres }} {{ $subusuario->user->persona->apellido_p }} {{ $subusuario->user->persona->apellido_m }}</td>
                        <td>{{ $subusuario->cargo }}</td>
                        <td>{{ $subusuario->user->persona->dni }}</td>
                        <td>{{ $subusuario->user->rol->nombre }}</td>
                        <td>{{ $subgerencia->nombre }}</td>
                        <td>
                            <a href="{{ route('subusuarios.edit', [$gerencia->id, $subusuario->id]) }}" class="btn btn-sm btn-info">
                                <i class="fa fa-edit" style="line-height: 1;"></i> Editar
                            </a>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="showSubusuarioConfirmationModal('{{ route('subusuarios.destroy', [$gerencia->id, $subusuario->id]) }}')">
                                <i class="fa fa-trash" style="line-height: 1;"></i> Eliminar
                            </button>
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <div class="modal fade" tabindex="-1" role="dialog" id="subusuarioConfirmationModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de eliminar este subusuario? Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-no" data-dismiss="modal">No</button>
                    <form id="subusuarioDeleteForm" method="POST" class="d-inline-block">
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

    <h4 class="d-flex justify-content-center pt-serif-bold mt-5">Sub Gerencias</h4>
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('subgerencias.create', $gerencia->id) }}" class="btn btn-agregar pt-serif-regular">
                <i class="fa fa-plus" aria-hidden="true"></i> Registrar
            </a>
        </div>
    <table class="table">
        <thead>
            <tr class="pt-serif-bold">
                <th>Nombre</th>
                <th>Encargado</th>
                <th>Teléfono</th>
                <!-- <th>Dirección</th> -->
                <th>Estado</th>
                <th class="col-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($gerencia->subgerencias as $subgerencia)
                <tr class="pt-serif-regular">
                    <td>{{ $subgerencia->nombre }}</td>
                    <td>{{ $subgerencia->user->persona->nombres }} {{ $subgerencia->user->persona->apellido_p }} {{ $subgerencia->user->persona->apellido_m }}</td>
                    <td>{{ $subgerencia->telefono }}</td>
                    <!-- <td>{{ $subgerencia->direccion }}</td> -->
                    <td>{{ $subgerencia->estado }}</td>
                    <td>
                        <a href="{{ route('subgerencias.edit', [$gerencia->id, $subgerencia->id]) }}" class="btn btn-sm btn-info">
                            <i class="fa fa-edit" style="line-height: 1;"></i> Editar
                        </a>
                        <button type="button" class="btn btn-sm btn-secondary" onclick="showSubgerenciaConfirmationModal('{{ route('subgerencias.destroy', [$gerencia->id, $subgerencia->id]) }}')">
                            <i class="fa fa-trash" style="line-height: 1;"></i> Eliminar
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="modal fade" tabindex="-1" role="dialog" id="subgerenciaConfirmationModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de eliminar esta subgerencia? Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-no" data-dismiss="modal">No</button>
                    <form id="subgerenciaDeleteForm" method="POST" class="d-inline-block">
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

</div>

<script>
    $(document).ready(function() {
        @if(Session::has('success'))
            toastr.options = { "positionClass": "toast-bottom-right" };
            toastr.success("{{ Session::get('success') }}");
        @endif
    });

    function showConfirmationModal() {
        $('#confirmationModal').modal('show');
    }

    function showSubgerenciaConfirmationModal(deleteUrl) {
        $('#subgerenciaDeleteForm').attr('action', deleteUrl);
        $('#subgerenciaConfirmationModal').modal('show');
    }

    $(document).ready(function() {
        $('.btn-close, .btn-no').click(function() {
            $('#confirmationModal').modal('hide');
            $('#subgerenciaConfirmationModal').modal('hide');
            $('#subusuarioConfirmationModal').modal('hide');
        });
    });

    function showSubusuarioConfirmationModal(url) {
        $('#subusuarioConfirmationModal').modal('show');
        $('#subusuarioDeleteForm').attr('action', url);
    }
</script>
</script>
@stop

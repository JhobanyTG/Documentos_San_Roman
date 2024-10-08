@extends('layout.template')

@section('title', 'Gerencias')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('gerencias.create') }}" class="btn btn-agregar pt-serif-regular">
            <i class="fa fa-plus" aria-hidden="true"></i> Registrar
        </a>
    </div>
    <form action="{{ route('gerencias.index') }}" method="GET" class="mb-3">
        <div class="col-md-10 order-md-1">
            <div class="card-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <table id="example1" class="table mt-4 table-hover pt-serif-regular" role="grid">
                        <thead>
                            <tr>
                                <th class="col-1">ID</th>
                                <th class="text-center col-2">Nombre</th>
                                <th class="text-center col-4">Gerente</th>
                                <th class="text-center col-2">Teléfono</th>
                                <th class="text-center col-2">Dirección</th>
                                <th class="text-center col-1">Estado</th>
                                <th class="text-center col-1">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($gerencias as $gerencia)
                            <tr>
                                <td>{{ $gerencia->id }}</td>
                                <td>{{ $gerencia->nombre }}</td>
                                <td class="text-center">
                                    {{ $gerencia->user->persona->nombres }} 
                                    {{ $gerencia->user->persona->apellido_p }} 
                                    {{ $gerencia->user->persona->apellido_m }}
                                </td>
                                <td class="text-center">{{ $gerencia->telefono }}</td>
                                <td class="text-center">{{ $gerencia->direccion }}</td>
                                <td class="text-center">{{ $gerencia->estado }}</td>
                                <td class="text-center">
                                    <a href="{{ route('gerencias.show', $gerencia->id) }}" class="btn btn-info"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        @if(Session::has('success'))
            toastr.options = { "positionClass": "toast-bottom-right" };
            toastr.success("{{ Session::get('success') }}");
        @endif
    });
</script>
@stop

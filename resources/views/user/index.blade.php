@extends('layout/template')

@section('title', 'Usuarios')

@section('content')
    <div class="d-flex justify-content-end">
        <a href="{{ route('register.create') }}" class="btn btn-agregar" class="icon-a"><i class="fa fa-users icons"></i><p class="letra_icon d-inline"> Registrar Usuario </p></a>
    </div>
    <div class="card-body mt-3 p-2">
        <div id="content_ta_wrapper" class="dataTables_wrapper">
            <div class="table-responsive">
                <table id="content_ta" class="table table-striped mt-4 table-hover custom-table pt-serif-regular" role="grid" aria-describedby="content_ta_info">
                    <thead>
                        <tr role="row">
                            <th class="d-none">Fecha</th>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Correo</th>
                            <th class="text-center">Rol</th>
                            <th class="text-center">Detalles</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($users as $user)
                        <tr class="odd">
                            <td class="d-none">{{ $user->updated_at }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role }}</td>
                            <td class="">
                                <a class="icono_eye" href="{{ route('usuarios.show', $user->id) }}">
                                    <i class="fa fa-eye fa-2x" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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

@stop
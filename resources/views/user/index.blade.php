@extends('layout/template')

@section('title', 'Usuarios')

@section('content')
    <div class="card-body mt-3 p-2">
        <div id="content_ta_wrapper" class="dataTables_wrapper">
            <div class="table-responsive">
                <table id="content_ta" class="table table-striped mt-4 table-hover custom-table pt-serif-regular" role="grid" aria-describedby="content_ta_info">
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
                            <td>
                                <img src="{{ $user->persona->avatar ? asset('storage/' . $user->persona->avatar) : asset('images/logo/avatar.png') }}" alt="{{ $user->persona->nombres }}" width="100">
                            </td>
                            <td>{{ $user->nombre_usuario }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->estado }}</td>
                            <td>{{ $user->rol->nombre }}</td>
                            <td>{{ $user->persona->nombres }} {{ $user->persona->apellido_p }} {{ $user->persona->apellido_m }}</td>
                            <td>
                                <a class="icono_eye" href="{{ route('usuarios.show', $user->id) }}">
                                    <i class="fa fa-eye fa-2x" aria-hidden="true"></i>
                                </a>
                                <a class="icono_edit" href="{{ route('usuarios.edit', $user->id) }}">
                                    <i class="fa fa-pencil fa-2x" aria-hidden="true"></i>
                                </a>
                                <form action="{{ route('usuarios.destroy', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="icono_delete" onclick="return confirm('¿Está seguro de que desea eliminar este usuario?')">
                                        <i class="fa fa-trash fa-2x" aria-hidden="true"></i>
                                    </button>
                                </form>
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

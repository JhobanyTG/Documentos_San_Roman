@extends('layout/template')

@section('title', 'Lista de Roles')

@section('content')
    <div class="container mt-4">
        <h2>Lista de Roles</h2>
        @if (auth()->user()->rol->privilegios->contains('nombre', 'Acceso Total') ||
                auth()->user()->rol->nombre === 'SuberAdmin')
            <a href="{{ route('roles.create') }}" class="btn btn-primary mb-3">Crear Nuevo Rol</a>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Privilegios</th>
                    @if (auth()->user()->rol->privilegios->contains('nombre', 'Acceso Total') ||
                            auth()->user()->rol->nombre === 'SuberAdmin')
                        <th>Acciones</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td>{{ $role->nombre }}</td>
                        <td>{{ Str::limit($role->descripcion, 50) }}</td>
                        <td>
                            @forelse ($role->privilegios as $privilegio)
                                <span class="text badge text-bg-secondary">{{ $privilegio->nombre }}</span>
                            @empty
                                <span class="text-muted">Sin privilegios</span>
                            @endforelse
                        </td>
                        @if (auth()->user()->rol->privilegios->contains('nombre', 'Acceso Total') ||
                                auth()->user()->rol->nombre === 'SuberAdmin')
                            <td>
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning">
                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                </a>
                                <button type="button" class="btn btn-danger"
                                    onclick="showUserConfirmationModal('{{ route('roles.destroy', $role->id) }}')">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                            </td>
                            <!-- Modal para confirmar la eliminación -->
                            <div class="modal fade" tabindex="-1" role="dialog" id="userConfirmationModal">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirmar Eliminación</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Estás seguro de eliminar este rol? Esta acción no se puede deshacer.
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary btn-no"
                                                data-bs-dismiss="modal">No</button>
                                            <form id="userDeleteForm" method="POST" class="d-inline-block">
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
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
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
        function showUserConfirmationModal(actionUrl) {
            // Establecer la acción del formulario
            document.getElementById('userDeleteForm').action = actionUrl;

            // Abrir el modal
            $('#userConfirmationModal').modal('show');
        }
    </script>
@endsection

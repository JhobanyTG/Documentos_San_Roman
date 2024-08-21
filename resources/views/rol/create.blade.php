@extends('layout/template')

@section('title', 'Crear Rol')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <!-- Formulario -->
            <div class="col-md-5">
                <h2>Crear Nuevo Rol</h2>
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea name="descripcion" id="descripcion" class="form-control" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="available_privileges">Privilegios Disponibles</label>
                        <select id="available_privileges" class="form-control" multiple>
                            @foreach($all_privilegios as $privilegio)
                                <option value="{{ $privilegio->id }}">{{ $privilegio->nombre }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-primary mt-2" onclick="addPrivileges()">Agregar Privilegios</button>
                    </div>

                    <div class="form-group">
                        <label>Privilegios Asignados</label>
                        <ul id="assigned_privileges" class="list-group">
                            <li class="list-group-item">No se han asignado privilegios aún.</li>
                        </ul>
                    </div>

                    <!-- Campo oculto para los IDs de los privilegios -->
                    <input type="hidden" name="privilegios" id="privilegios" value="">

                    <button type="submit" class="btn btn-primary mt-3">Crear Rol</button>
                </form>
            </div>

            <!-- Columna de Privilegios -->
            <div class="col-md-7">
                <h2>Lista de Privilegios</h2>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($all_privilegios as $privilegio)
                            <tr>
                                <td>{{ $privilegio->id }}</td>
                                <td>{{ $privilegio->nombre }}</td>
                                <td>{{ Str::limit($privilegio->descripcion, 50) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            updatePrivilegesInput(); // Asegúrate de que el campo oculto se actualice al cargar la página
        });

        function addPrivileges() {
            let availablePrivileges = document.getElementById('available_privileges');
            let assignedPrivileges = document.getElementById('assigned_privileges');

            Array.from(availablePrivileges.selectedOptions).forEach(option => {
                let privilegeId = option.value;
                let privilegeName = option.text;

                // Add to assigned list
                let listItem = document.createElement('li');
                listItem.className = 'list-group-item';
                listItem.innerHTML = `${privilegeName} <button type="button" class="btn btn-danger btn-sm float-right" onclick="removePrivilege(${privilegeId})">Quitar</button>`;
                assignedPrivileges.appendChild(listItem);

                // Remove from available list
                option.remove();
            });

            // Update hidden input
            updatePrivilegesInput();
        }

        function removePrivilege(id) {
            let assignedPrivileges = document.getElementById('assigned_privileges');
            let availablePrivileges = document.getElementById('available_privileges');
            let privilegeName = null;

            Array.from(assignedPrivileges.children).forEach(listItem => {
                if (listItem.innerHTML.includes(`onclick="removePrivilege(${id})"`)) {
                    privilegeName = listItem.innerText.replace('Quitar', '').trim();
                    listItem.remove();
                }
            });

            // Add to available list
            if (privilegeName) {
                let option = document.createElement('option');
                option.value = id;
                option.text = privilegeName;
                availablePrivileges.appendChild(option);
            }

            // Update hidden input
            updatePrivilegesInput();
        }

        function updatePrivilegesInput() {
            let assignedPrivileges = document.getElementById('assigned_privileges');
            let privilegeIds = Array.from(assignedPrivileges.children)
                .filter(listItem => listItem.innerHTML.includes('onclick="removePrivilege'))
                .map(listItem => listItem.innerHTML.match(/onclick="removePrivilege\((\d+)\)/)[1]);

            document.getElementById('privilegios').value = privilegeIds.join(',');

            // Handle empty state
            if (privilegeIds.length === 0) {
                document.getElementById('assigned_privileges').innerHTML = '<li class="list-group-item">No se han asignado privilegios aún.</li>';
            }
        }
    </script>

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
@endsection

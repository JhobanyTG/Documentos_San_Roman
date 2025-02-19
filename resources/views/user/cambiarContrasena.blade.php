@extends('layout/template')

@section('title', 'Cambiar Contraseña')

@section('content')
    <div class="container form_contrasenia">
        <div class="row">
            <div class="col-md-12">
                <h1 class="form_title_contrasenia">Cambiar Contraseña</h1>
                <form id="passwordForm" action="{{ route('usuarios.actualizarContrasena', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-2">
                        <label for="password" class="form-label label_contrasenia">Nueva Contraseña:</label>
                        <div class="input-group">
                            <input type="password" class="form-control contrasenia" name="password" id="password" required>
                            <button type="button" class="btn btn-sm toggle-password" data-target="password">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label for="password_confirmation" class="form-label label_contrasenia">Confirmar Contraseña:</label>
                        <div class="input-group">
                            <input type="password" class="form-control contrasenia" name="password_confirmation"
                                id="password_confirmation" required>
                            <button type="button" class="btn btn-sm toggle-password" data-target="password_confirmation">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="col-md-12 col-12 mt-4 d-flex align-items-center justify-content-center">
                        <a href="{{ route('usuarios.edit', $user->id) }}" class="btn btn-warning btn-cancel btn-contrasenia">
                            <i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success btn-contrasenia">
                            <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar Contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#passwordForm').submit(function (event) {
                var password1 = $('#password').val();
                var password2 = $('#password_confirmation').val();

                if (password1 !== password2) {
                    event.preventDefault(); // Evita el envío del formulario

                    var alertMessage =
                        'Las contraseñas no coinciden. <br> Por favor, asegúrate de ingresar la misma contraseña en ambos campos.';

                    var alertDiv = $(
                        '<div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 mt-2 ms-2" role="alert" style="z-index: 999; background-color: #C71E42; color: #FFFFFF;">' +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                        '<i class="fa fa-exclamation-triangle me-2" aria-hidden="true"></i> ' +
                        alertMessage +
                        '</div>'
                    );

                    $('body').append(alertDiv);

                    setTimeout(function () {
                        alertDiv.fadeOut(500, function () {
                            $(this).remove();
                        });
                    }, 5000);
                }
            });

            // Alternar visibilidad de la contraseña
            $('.toggle-password').click(function () {
                let input = $('#' + $(this).data('target'));
                let icon = $(this).find('i');

                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });
    </script>
@stop

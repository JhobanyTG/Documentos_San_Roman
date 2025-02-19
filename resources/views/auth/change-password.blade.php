@extends('layout.template')

@section('title', 'Cambiar Contraseña')

@section('content')
    <div class="container form_password">
        <h1 class="card-header form_title_password">Cambiar Contraseña</h1>
        <div class="card-body">
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 mt-2 ms-2" role="alert" style="z-index: 999; background-color: #C71E42; color: #FFFFFF;">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <i class="fa fa-exclamation-triangle me-2" aria-hidden="true"></i>
                        {{ $error }}
                    </div>
                @endforeach
            @endif
            <div id="password-alert" class="alert alert-danger d-none alert-dismissible fade show position-fixed top-0 end-0 mt-2 ms-2" role="alert" style="z-index: 999; background-color: #C71E42; color: #FFFFFF;">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <i class="fa fa-exclamation-triangle me-2" aria-hidden="true"></i>
                Las contraseñas nueva y la confirmación no coinciden. Por favor, verifique.
            </div>
        </div>
        <form method="POST" action="{{ route('change-password') }}" onsubmit="return validarContraseña()">
            @csrf
            <div class="form-group mb-3 mt-2">
                <label for="current_password" class="form-label label_password">{{ __('Contraseña Actual:') }}</label>
                <div class="input-group">
                    <input id="current_password" type="password" class="form-control input_change_pass password" name="current_password" required autocomplete="current-password">
                    <button type="button" class="btn btn-sm btn-outline-secondary toggle-password" data-target="current_password">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>
            </div>
            <div class="form-group mb-3">
                <label for="new_password" class="form-label label_password">{{ __('Nueva Contraseña:') }}</label>
                <div class="input-group">
                    <input id="new_password" type="password" class="form-control input_change_pass password" name="new_password" required autocomplete="new-password">
                    <button type="button" class="btn btn-sm btn-outline-secondary toggle-password" data-target="new_password">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>
            </div>
            <div class="form-group mb-3">
                <label for="confirm_password" class="form-label label_password">{{ __('Confirmar Nueva Contraseña:') }}</label>
                <div class="input-group">
                    <input id="confirm_password" type="password" class="form-control input_change_pass password" name="confirm_password" required autocomplete="new-password">
                    <button type="button" class="btn btn-sm btn-outline-secondary toggle-password" data-target="confirm_password">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-12 col-12 mt-4 d-flex align-items-center justify-content-center">
                <a href="{{ route('documentos.index') }}" class="btn btn-danger btn-password me-2">
                    <i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success btn-password ms-2">
                    <i class="fa fa-floppy-o" aria-hidden="true"></i> {{ __('Cambiar Contraseña') }}
                </button>
            </div>
        </form>
    </div>

    <script>
        function validarContraseña() {
            let nuevaContraseña = document.getElementById("new_password").value;
            let confirmarContraseña = document.getElementById("confirm_password").value;
            let alerta = document.getElementById("password-alert");

            if (nuevaContraseña !== confirmarContraseña) {
                alerta.classList.remove("d-none");
                return false;
            } else {
                alerta.classList.add("d-none");
                return true;
            }
        }

        document.querySelectorAll(".toggle-password").forEach(button => {
            button.addEventListener("click", function () {
                let input = document.getElementById(this.dataset.target);
                let icon = this.querySelector("i");
                if (input.type === "password") {
                    input.type = "text";
                    icon.classList.remove("fa-eye");
                    icon.classList.add("fa-eye-slash");
                } else {
                    input.type = "password";
                    icon.classList.remove("fa-eye-slash");
                    icon.classList.add("fa-eye");
                }
            });
        });
    </script>
@endsection

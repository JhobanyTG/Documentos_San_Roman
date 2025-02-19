@extends('layout/app')

@section('title', 'Login | Info')

@section('content')

    <head>
        <meta charset="utf-8">
        <link rel="icon" href="{{ asset('images\logo\logo1.png') }}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ asset('css/login.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>

    <body>
        <div class="contenido_login">
            <div class="container">
                <div class="row">
                    <div class="mb-2">
                        <a href="{{ route('public.index') }}" class="boton_back"><i class="fa fa-arrow-left"
                                aria-hidden="true"></i>
                        </a>
                    </div>
                    <h2 class="fw-bold nombre_proyecto">MUNICIPALIDAD PROVINCIAL DE SAN ROMÁN JULIACA</h2>
                    <h2 class="fw-bold nombre_proyecto_2">ÁREA DE TECNOLOGÍA INFORMÁTICA</h2>
                    <div class="col">
                        <img class="logo mt-0" src="images/logo/logo.png">
                    </div>
                    <h2 class="fw-bold nombre_proyecto_3">GESTIÓN DE DOCUMENTOS DIGITALES</h2>
                    <!-- Login-->
                    <form action="{{ url('/login') }}" method="POST">
                        @csrf
                        @if (session('error'))
                            <div class="text-danger text-center fw-bold">
                                {{ session('error') }}
                            </div>
                        @endif
                        <div class="mb-4">
                            <input type="email" class="form-control" id="email" name="email" placeholder="email"
                                required>
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4 position-relative">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Contraseña" required>
                            <i class="fa fa-eye position-absolute top-50 end-0 translate-middle-y me-5" id="togglePassword"
                                style="cursor: pointer;"></i>
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="boton_inicio"> <i class="fa fa-sign-in fa-1g"
                                    aria-hidden="true"></i>Iniciar Sesión</button>
                        </div>
                        <p class="text-center creditos">Desarrollado por <strong>Alan Dagner</strong> & <strong>Jhobany
                                Etduard</strong> &copy; {{ date('Y') }}</p>
                    </form>
                </div>
            </div>
        </div>
    </body>
    <script>
        document.getElementById("togglePassword").addEventListener("click", function() {
            let passwordInput = document.getElementById("password");
            let icon = this;

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        });
    </script>
@endsection

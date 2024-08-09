@auth
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>@yield('title')</title>
		<link rel="icon" href="{{ asset('images/logo/logo1.png') }}">
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
		<link rel="stylesheet" href="{{ asset('css/layout.css') }}">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">

		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
		<script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
		<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
	</head>
    <body>
	<div class="sidebar" id="sidebar">
		<div class="logo">
			<img src="{{ asset('images/logo/logo1.png') }}" alt="Logo">
		</div>
		<div class="logo-nombre mt-3">
			<p class="company-name">ATI SAN ROMÁN</p>
		</div>
			<ul class="nav">
				<li class="nav-item active">
					<a href="{{ url('documentos') }}">
						<i class="fa fa-files-o"></i>
						<span class="nav-text">Documentos</span>
					</a>
				</li>
                <!-- <li class="nav-item">
                    <a href="#">
                        <i class="fa fa-book"></i>
                        <span class="nav-text">Otros</span>
                    </a>
                </li> -->
				@if (auth()->check() && auth()->user()->role === 'SuperAdmin')
					<li class="nav-item">
						<a href="{{ url('usuarios') }}">
							<i class="fa fa-users"></i>
							<span class="nav-text">Usuarios</span>
						</a>
					</li>
				@endif
			</ul>
		<div class="logout-btn">
			<div class="logout-btn-wrapper">
				<button onclick="window.location.href='{{ route('logout') }}'">
					<i class="fa fa-sign-out" aria-hidden="true"></i>
					<a>Cerrar Sesión</a>
				</button>
			</div>
		</div>
	</div>
	<div class="content">
		<header>
			<div class="header-left">
				<div class="toggle-sidebar-btn" id="toggleSidebarBtn">
					<i class="fa fa-bars fa-2x" aria-hidden="true"></i>
				</div>
			</div>
			<div class="header-right pt-serif-bold">
				<div class="profile" id="profile-div">
					<img src="{{ asset('images/logo/avatar.png') }}" alt="Avatar">
					<p>{{ auth()->user()->name }}<span>{{ auth()->user()->role }}</span></p>
				</div>
				<a id="change-password-link" href="{{ route('change-password') }}" class="boton_cambiar_contrasena"><i class="fa fa-lock" aria-hidden="true"></i>Cambiar Contraseña</a>
			</div>
		</header>
		<div class="border-dark border-bottom mb-2">
			<h4 class="pt-serif-bold">
				@yield('title')
			</h4>
		</div>
		<main>
			<div>
				@yield('content')
			</div>
		</main>
	</div>
	<script>
    document.addEventListener("DOMContentLoaded", function() {
        const toggleSidebarBtn = document.getElementById('toggleSidebarBtn');
        const sidebar = document.getElementById('sidebar');
        const content = document.querySelector('.content');
        
        // Función para minimizar el sidebar
        function minimizeSidebar() {
            sidebar.classList.add('sidebar-closed');
            content.classList.add('content-closed');
            localStorage.setItem('sidebarState', 'closed');
        }

        // Función para maximizar el sidebar
        function maximizeSidebar() {
            sidebar.classList.remove('sidebar-closed');
            content.classList.remove('content-closed');
            localStorage.setItem('sidebarState', 'open');
        }

        // Listener para el botón de toggle
        toggleSidebarBtn.addEventListener('click', () => {
            if (sidebar.classList.contains('sidebar-closed')) {
                maximizeSidebar();
            } else {
                minimizeSidebar();
            }
        });

        // Listener para detectar el cambio de tamaño de pantalla
        window.addEventListener('resize', () => {
            if (window.innerWidth <= 600) {
                minimizeSidebar();
            } else {
                maximizeSidebar();
            }
        });

        // Recuperar el estado del sidebar desde el localStorage
        const sidebarState = localStorage.getItem('sidebarState');
        if (sidebarState === 'closed') {
            minimizeSidebar();
        } else {
            maximizeSidebar();
        }

        // Si la pantalla es menor a 600px al cargar, minimizar el sidebar
        if (window.innerWidth <= 600) {
            minimizeSidebar();
        }
    });
</script>
<!-- <script>
	const toggleSidebarBtn = document.getElementById('toggleSidebarBtn');
	const sidebar = document.getElementById('sidebar');
	const content = document.querySelector('.content');

	// Función para minimizar el sidebar
	function minimizeSidebar() {
	sidebar.classList.add('sidebar-closed');
	content.classList.add('content-closed');
	}

	// Función para maximizar el sidebar
	function maximizeSidebar() {
	sidebar.classList.remove('sidebar-closed');
	content.classList.remove('content-closed');
	}

	// Listener para el botón de toggle
	toggleSidebarBtn.addEventListener('click', () => {
	if (sidebar.classList.contains('sidebar-closed')) {
		maximizeSidebar();
	} else {
		minimizeSidebar();
	}
	});

	// Listener para detectar el cambio de tamaño de pantalla
	window.addEventListener('resize', () => {
	if (window.innerWidth <= 600) {
		minimizeSidebar();
	} else {
		maximizeSidebar();
	}
	});

	if (window.innerWidth <= 600) {
	minimizeSidebar();
	}
</script> -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
      const changePasswordLink = document.getElementById("change-password-link");
      const profileDiv = document.getElementById("profile-div");
      changePasswordLink.style.display = "none";
      profileDiv.addEventListener("click", function(event) {
        event.preventDefault();
        if (changePasswordLink.style.display === "none") {
          changePasswordLink.style.display = "block";
        } else {
          changePasswordLink.style.display = "none";
        }
      });
    });
</script>
<script>
    // var espanol = {
    //     "sProcessing": "Procesando...",
    //     "sLengthMenu": "Mostrar _MENU_ registros",
    //     "sZeroRecords": "No se encontraron resultados",
    //     "sEmptyTable": "Ningún dato disponible en esta tabla",
    //     "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    //     "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
    //     "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
    //     "sInfoPostFix": "",
    //     "sSearch": "Buscar:",
    //     "sUrl": "",
    //     "sInfoThousands": ",",
    //     "sLoadingRecords": "Cargando...",
    //     "oPaginate": {
    //         "sFirst": "Primero",
    //         "sLast": "Último",
    //         "sNext": "<i class='fa fa-chevron-right' aria-hidden='true'></i>",
    //         "sPrevious": "<i class='fa fa-chevron-left' aria-hidden='true'></i>"
    //     },
    //     "oAria": {
    //         "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
    //         "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    //     }
    // };

    // $(document).ready(function() {
    //     $('#content_ta').DataTable({
    //         "language": espanol,
    //         "paging": true,
    //         "ordering": true,
    //         "order": [[0, "desc"]],
    //         "lengthMenu": [5, 10, 25, 50],
    //         "pageLength": 5,
    //         "dom": '<"row" <"col-sm-12 col-md-6" l><"col-sm-12 col-md-6" f>>rtip',
    //         "responsive": true
    //     });

    //     $('#search').on('keyup', function () {
    //         $('#content_ta').DataTable().search(this.value).draw();
    //     });
    // });
</script>

</body>


</html>
@endauth

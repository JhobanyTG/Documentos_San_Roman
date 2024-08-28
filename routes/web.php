<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentosController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\PrivilegioController;
use App\Http\Controllers\RolPrivilegioController;
use App\Http\Controllers\GerenciaController;
use App\Http\Controllers\SubgerenciaController;
use App\Http\Controllers\SubUsuarioController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Rutas de autenticación

// Route::get('/', function () {
//     return redirect()->route('publics');
// });

Route::get('/', [PublicController::class, 'index'])->name('publics.index');

// Route::post('/login', function () {
//     return view('auth.login');
// });

Route::get('/login', [AuthController::class, 'showLoginForm'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Rutas de cambio de contraseña
Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->middleware('auth')->name('change-password');
Route::post('/change-password', [AuthController::class, 'changePassword'])->middleware('auth');

// Rutas de registro (solo para administradores)
Route::get('/register', [AuthController::class, 'showRegisterForm'])->middleware('auth')->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('auth');

Route::middleware(['auth'])->group(function () {
    // Otras rutas protegidas por autenticación...

    // Ruta a la vista documentos.index
    // Route::get('/documentos', [DocumentosController::class, 'index'])->name('documentos.index');
    // Route::get('/documentos/create', [DocumentosController::class, 'create'])->name('documentos.create');
    // Route::get('/documentos/edit', [DocumentosController::class, 'edit'])->name('documentos.edit');
    // Route::post('documentos', [DocumentosController::class, 'store'])->name('documentos.store');

    Route::resource('documentos', DocumentosController::class)->middleware('auth');
    Route::resource('gerencias', GerenciaController::class)->middleware('auth');
    route::get('/gerencias/{id}', [GerenciaController::class, 'show'])    ->middleware(['auth', 'checkGerenciaOwnership']);
    // Route::resource('subgerencias', SubgerenciaController::class);
    // Route::prefix('gerencias/{gerencia}')->group(function () {
    //     Route::resource('subgerencias', SubgerenciaController::class);
    // });
    Route::prefix('gerencias/{gerencia}')->group(function () {
        Route::resource('subgerencias', SubgerenciaController::class);
        Route::resource('subusuarios', SubUsuarioController::class);
    });


    // route::get('/gerencias/{gerencia}', [GerenciaController::class, 'show'])->name('gerencias.show');
    // route::get('/gerencias/{id}', [GerenciaController::class, 'mostrarGerencia'])->name('gerencias.show');

    // Register Usuarios
    Route::resource('/usuarios',UserController::class)
        ->middleware('auth');
    Route::get('/usuarios/{id}/cambiar-contrasena', [UserController::class, 'cambiarContrasena'])
        ->middleware('auth')
        ->name('usuarios.cambiarContrasena');
    Route::put('/usuarios/{id}/actualizar-contrasena', [UserController::class, 'actualizarContrasena'])
        ->middleware('auth')
        ->name('usuarios.actualizarContrasena');
    Route::get('/register', [UserController::class, 'create'])
        ->middleware('auth')
        ->name('register.create');
    Route::post('/register', [UserController::class, 'store'])
        ->middleware('auth')
        ->name('register.store');

    Route::resource('personas', PersonaController::class);
    Route::resource('roles', RolController::class);

    Route::resource('privilegios', PrivilegioController::class);
    Route::resource('rolprivilegios', RolPrivilegioController::class);
});

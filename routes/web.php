<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\CanjeController;
use App\Http\Controllers\Usuario\EstudianteController;
use App\Http\Controllers\Usuario\DashboardController as UsuarioDashboardController;
Use App\Http\Controllers\MqttController;
use App\Http\Controllers\Api\RegistroPuntoController;
use App\Http\Controllers\Usuario\CanjeoController;
use App\Http\Controllers\Admin\PuntosController;
use App\Http\Controllers\Admin\AdminCanjeoController;
use App\Http\Controllers\Usuario\PuntosCanjeosController;
use App\Http\Controllers\Admin\EstadisticasController;
use App\Http\Controllers\Admin\ReportesController;



Route::post('/registro-puntos', [RegistroPuntoController::class, 'store']);
Route::get('/registro-puntos', [RegistroPuntoController::class, 'index']);

// Ruta pública - Welcome
Route::get('/', [AuthController::class, 'welcome'])->name('welcome');

// Rutas de autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    Route::get('/loginAdmin', [AuthController::class, 'showLoginAdmin'])->name('loginAdmin');
    Route::post('/loginAdmin', [AuthController::class, 'loginAdmin'])->name('loginAdmin.attempt');
});

// Cerrar sesión
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Rutas protegidas - SOLO ADMINISTRADORES
Route::middleware(['auth', 'role:administrador'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

    // Gestión de Usuarios
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
    Route::get('/usuarios/create/h', [UsuarioController::class, 'create'])->name('usuarios.create.h');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{usuario}', [UsuarioController::class, 'show'])->name('usuarios.show');
    Route::get('/usuarios/{usuario}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

     // productos
    Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
    Route::get('/productos/crear', [ProductoController::class, 'create'])->name('productos.create');
    Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
    Route::get('/productos/{id}', [ProductoController::class, 'show'])->name('productos.show');
    Route::get('/productos/{id}/editar', [ProductoController::class, 'edit'])->name('productos.edit');
    Route::put('/productos/{id}', [ProductoController::class, 'update'])->name('productos.update');
    Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');

// Rutas de estadísticas

    Route::get('/estadisticas', [EstadisticasController::class, 'index'])->name('estadisticas.index');
    Route::get('/estadisticas/puntos', [EstadisticasController::class, 'puntos'])->name('estadisticas.puntos');
    Route::get('/estadisticas/canjes', [EstadisticasController::class, 'canjes'])->name('estadisticas.canjes');
    Route::get('/estadisticas/usuarios', [EstadisticasController::class, 'usuarios'])->name('estadisticas.usuarios');

    // APIs para gráficos
    Route::get('/estadisticas/api/puntos-diarios', [EstadisticasController::class, 'apiPuntosDiarios'])->name('estadisticas.api.puntos');
    Route::get('/estadisticas/api/canjes-diarios', [EstadisticasController::class, 'apiCanjesDiarios'])->name('estadisticas.api.canjes');


    // Rutas de reportes (opcional)

    Route::get('/reportes/exportar-puntos', [ReportesController::class, 'exportarPuntos'])->name('reportes.exportar.puntos');
    Route::get('/reportes/exportar-canjes', [ReportesController::class, 'exportarCanjes'])->name('reportes.exportar.canjes');



// Rutas adicionales
Route::patch('/productos/{id}/toggle-status', [ProductoController::class, 'toggleStatus'])->name('productos.toggle-status');
Route::get('/productos-stock-bajo', [ProductoController::class, 'stockBajo'])->name('productos.stock-bajo');

// producto- registro canjeo
Route::get('/canjes', [AdminCanjeoController::class, 'index'])->name('canjes.index');
    Route::get('/canjes/{id}', [AdminCanjeoController::class, 'show'])->name('canjes.show');
    Route::patch('/canjes/{id}/estado', [AdminCanjeoController::class, 'updateEstado'])->name('canjes.update-estado');
    Route::get('/canjes/estadisticas', [AdminCanjeoController::class, 'estadisticas'])->name('canjes.estadisticas');

    Route::get('/puntos/historial', [PuntosController::class, 'historial'])
    ->middleware('auth')
    ->name('usuario.puntos.historial');

// Canjeo - usuario
Route::get('/canjeo/catalogo', [AdminCanjeoController::class, 'catalogo'])
    ->middleware('auth')
    ->name('usuario.canjeo.catalogo');

Route::get('/canjeo/historial', [AdminCanjeoController::class, 'historial'])
    ->middleware('auth')
    ->name('usuario.canjeo.historial');

Route::post('/canjeo/canjear/{producto}', [AdminCanjeoController::class, 'canjear'])
    ->middleware('auth')
    ->name('usuario.canjeo.canjear');

Route::post('/canjeo/cancelar/{canjeo}', [AdminCanjeoController::class, 'cancelar'])
    ->middleware('auth')
    ->name('usuario.canjeo.cancelar');

// Dashboard admin

// Puntos - admin
Route::get('/puntos', [PuntosController::class, 'index'])->name('puntos.index');

Route::get('/puntos/estadisticas', [PuntosController::class, 'estadisticas'])
    ->name('puntos.estadisticas');

Route::get('/puntos/{id}', [PuntosController::class, 'show'])
    ->name('puntos.show');

Route::delete('/puntos/{id}', [PuntosController::class, 'destroy'])
    ->name('puntos.destroy');

// Canjeos - admin
Route::get('/canjeos', [AdminCanjeoController::class, 'index'])
    ->name('canjeos.index');

Route::get('/canjeos/estadisticas', [AdminCanjeoController::class, 'estadisticas'])
    ->name('canjeos.estadisticas');

Route::post('/canjeos/{id}/entregado', [AdminCanjeoController::class, 'marcarEntregado'])
    ->name('canjeos.entregado');

Route::post('/canjeos/{id}/cancelar', [AdminCanjeoController::class, 'cancelar'])
    ->name('canjeos.cancelar');
});

// Rutas protegidas - SOLO ESTUDIANTES
Route::middleware(['auth', 'role:estudiante'])->prefix('estudiante')->name('estudiante.')->group(function () {
    // Dashboard Estudiante
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    //Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    // Productos
    //Route::get('/dashboard', [EstudianteController::class, 'dashboard'])->name('dashboard');

    // Productos disponibles
    Route::get('/productos', [EstudianteController::class, 'productosDisponibles'])->name('productos');
    Route::get('/productos/{id}', [EstudianteController::class, 'verProducto'])->name('ver-producto');
    Route::post('/productos/{id}/canjear', [EstudianteController::class, 'canjearProducto'])->name('canjear-producto');


    Route::get('/canjear-productos', [PuntosCanjeosController::class, 'canjearProductos'])->name('canjear-productos');
    Route::get('/verificar-canjeo/{productoId}', [PuntosCanjeosController::class, 'verificarCanjeo'])->name('verificar-canjeo');
    Route::post('/procesar-canjeo/{productoId}', [PuntosCanjeosController::class, 'procesarCanjeo'])->name('procesar-canjeo');
    Route::get('/mis-puntos', [PuntosCanjeosController::class, 'misPuntos'])->name('mis-puntos');

        Route::get('/historial-puntos', [PuntosCanjeosController::class, 'historialPuntos'])->name('historial-puntos');
        Route::get('/historial-canjes', [PuntosCanjeosController::class, 'historialCanjes'])->name('historial-canjes');

    // Historial de canjes


    Route::get('/mis-canjes/{id}', [EstudianteController::class, 'verCanje'])->name('ver-canje');
    // Puntos - usuario
Route::get('/puntos/historial', [PuntosController::class, 'historial'])
    ->name('usuario.puntos.historial');

// Canjeo - usuario
Route::get('/canjeo/catalogo', [CanjeoController::class, 'catalogo'])
    ->name('canjeo.catalogo');

Route::get('/canjeo/historial', [CanjeoController::class, 'historial'])
    ->name('usuario.canjeo.historial');

Route::post('/canjeo/canjear/{producto}', [CanjeoController::class, 'canjear'])
    ->name('canjeo.canjear');

Route::post('/canjeo/cancelar/{canjeo}', [CanjeoController::class, 'cancelar'])
    ->name('canjeo.cancelar');

});
Route::post('/mqtt-data', [MqttController::class, 'store']);




// Rutas comentadas (para futura implementación)
/*
Route::resource('admin/productos', ProductoController::class)->only(['index','store','update','destroy']);
Route::resource('admin/canjeos', CanjeoController::class)->only(['index','update']);
*/

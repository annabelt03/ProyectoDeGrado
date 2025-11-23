<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\RegistroPuntoController;
use Illuminate\Support\Facades\Route;

// Las rutas en routes/api.php ya tienen el prefijo 'api' y middleware 'api' por defecto
Route::prefix('registro-puntos')->group(function () {
    Route::get('/', [RegistroPuntoController::class, 'index']);
    Route::post('/', [RegistroPuntoController::class, 'store']);
    Route::get('/{id}', [RegistroPuntoController::class, 'show']);
    Route::put('/{id}', [RegistroPuntoController::class, 'update']);
    Route::delete('/{id}', [RegistroPuntoController::class, 'destroy']);

    Route::get('/usuario/{usuarioId}', [RegistroPuntoController::class, 'porUsuario']);
    Route::get('/usuario/{usuarioId}/total-puntos', [RegistroPuntoController::class, 'totalPuntosUsuario']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

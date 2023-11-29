<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\Api\AuthController;

Route::middleware('auth:sanctum')->group(
    function (){
        Route::apiResource('clientes', ClienteController::class);
        Route::apiResource('categorias', CategoriaController::class);
        Route::apiResource('compras', CompraController::class);
        Route::apiResource('productos',ProductoController::class);
        Route::apiResource('marcas',MarcaController::class);
        Route::post('auth/cambio-password', [AuthController::class, 'changePassword']);
    }
);
//Saltamos la autenticación
Route::apiResource('clientes', ClienteController::class);
Route::apiResource('categorias', CategoriaController::class);
Route::apiResource('compras', CompraController::class);
Route::apiResource('productos',ProductoController::class);
Route::apiResource('marcas',MarcaController::class);
//Route::post('auth/cambio-password', [AuthController::class, 'changePassword']);
Route::prefix('auth')->controller(AuthController::class)->group(
    function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
    }
);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
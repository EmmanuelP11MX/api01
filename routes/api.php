<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CategoriaController;
use App\Http\Responses\ComprasController;

Route::apiResource('auth:sanctum')->group(
    function (){
        Route::apiResource('clientes', ClienteController::class);
        Route::apiResource('categorias', CategoriaController::class);
        Route::apiResource('compras', ComprasController::class);
        Route::apiResource('productos',ProductoController::class);
    }
);

Route::prefix('auth')->controller(AuthController::class)->group(
    function () {
        Route::post('register', 'register');
        Route::post('login', 'logion');
    }
);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
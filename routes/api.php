<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CategoriaController;


Route::apiResource('clientes', ClienteController::class);
Route::apiResource('categorias', CategoriaController::class);
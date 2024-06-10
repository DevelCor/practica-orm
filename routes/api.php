<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventarioController;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::post('/productos', [InventarioController::class, 'agregarProducto']);
Route::post('/movimientos/entrada', [InventarioController::class, 'agregarMovimientoEntrada']);
Route::post('/movimientos/entrada/sql', [InventarioController::class, 'agregarMovimientoEntradaSQL']);
Route::post('/movimientos/salida', [InventarioController::class, 'agregarMovimientoSalida']);
Route::post('/movimientos/salida/sql', [InventarioController::class, 'agregarMovimientoSalidaSQL']);
Route::get('/productos/{id}', [InventarioController::class, 'consultarProducto']);

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rutas para las APIs de los modelos
Route::apiResource('temperaturas', 'App\Http\Controllers\TemperaturaController');
Route::apiResource('humedades', 'App\Http\Controllers\HumedadController');
Route::apiResource('presiones', 'App\Http\Controllers\PresionController');
Route::apiResource('iluminaciones', 'App\Http\Controllers\IluminacionController');  
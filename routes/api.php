<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FanController;
use App\Http\Controllers\CalidadAireController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rutas para las APIs de los modelos
Route::apiResource('temperaturas', 'App\Http\Controllers\TemperaturaController');
Route::apiResource('humedades', 'App\Http\Controllers\HumedadController');
Route::apiResource('presiones', 'App\Http\Controllers\PresionController');
Route::apiResource('iluminaciones', 'App\Http\Controllers\IluminacionController');  
Route::apiResource('medidas', 'App\Http\Controllers\MedidaController');
Route::apiResource('calidadA', 'App\Http\Controllers\CalidadAireController');

// Ruta para controlar el ventilador
Route::post('/fan/on', [FanController::class, 'turnOnFan']);
// Ruta para guardar el estado de calidad de aire
  
 
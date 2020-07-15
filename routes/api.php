<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
    Route::prefix('vehiculos')->group(function () {
        Route::post('new', 'VehiculoController@createVehiculo')->name('vehiculos.new')->middleware('cros');
        Route::get('search', 'VehiculoController@findVehiculo')->name('vehiculos.search')->middleware('cros');
    });
    Route::prefix('marcas')->group(function () {
        Route::get('list','MarcaController@vehiculosPorMarcas')->name('marcas.list')->middleware('cros');
    });

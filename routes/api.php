<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Geojson API
Route::get('/points', [App\Http\Controllers\ApiController::class, 'geojson_points'])
    ->name('geojson_points');
Route::get('/polylines', [App\Http\Controllers\ApiController::class, 'geojson_polylines'])
    ->name('geojson_polylines');
Route::get('/polygons', [App\Http\Controllers\ApiController::class, 'geojson_polygons'])
    ->name('geojson_polygons');

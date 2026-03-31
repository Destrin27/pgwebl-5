<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// Halaman utama
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Halaman peta
Route::get('/map', PageController::class . '@map')->name('map');

// Dashboard (default dari Laravel Breeze/Jetstream)
Route::view('/dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/table', PageController::class . '@table')->name('table');

//Points
Route::post('/points', [App\Http\Controllers\PointsController::class, 'store'])
->name('points.store');

//Polylines
Route::post('/polylines', [App\Http\Controllers\PolylinesController::class, 'store'])
->name('polylines.store');

// Polygons
Route::post('/polygons', [App\Http\Controllers\PolygonsController::class, 'store'])
->name('polygons.store');

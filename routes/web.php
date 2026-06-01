<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// Halaman utama
Route::get('/', PageController::class . '@landingpage')->name('home');

// Halaman peta
Route::get('/map', PageController::class . '@map')
->middleware(['auth', 'verified'])
->name('map');

// Dashboard (default dari Laravel Breeze/Jetstream)
Route::view('/dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/table', PageController::class . '@table')->name('table');

//Points
Route::post('/points', [App\Http\Controllers\PointsController::class, 'store'])
->name('points.store');

//  Delete Point
Route::delete('/delete-points/{id}', [App\Http\Controllers\PointsController::class, 'destroy'])
->name('points.delete');

// Edit Point
Route::get('/edit-points/{id}', [App\Http\Controllers\PointsController::class, 'edit'])
->name('points.edit');

//Route untuk update point
Route::patch('/update-points/{id}', [App\Http\Controllers\PointsController::class, 'update'])->name('points.update');


//Polylines
Route::post('/polylines', [App\Http\Controllers\PolylinesController::class, 'store'])
->name('polylines.store');

Route::delete('/delete-polylines/{id}', [App\Http\Controllers\PolylinesController::class, 'destroy'])
->name('polylines.delete');

// Edit Polyline
Route::get('/edit-polylines/{id}', [App\Http\Controllers\PolylinesController::class, 'edit'])
->name('polylines.edit');

//Route untuk update polyline
Route::patch('/update-polylines/{id}', [App\Http\Controllers\PolylinesController::class, 'update'])->name('polylines.update');


// Polygons
Route::post('/polygons', [App\Http\Controllers\PolygonsController::class, 'store'])
->name('polygons.store');

Route::delete('/delete-polygons/{id}', [App\Http\Controllers\PolygonsController::class, 'destroy'])
->name('polygons.delete');

// Edit Polygon
Route::get('/edit-polygons/{id}', [App\Http\Controllers\PolygonsController::class, 'edit'])
->name('polygons.edit');

//Route untuk update polygon
Route::patch('/update-polygons/{id}', [App\Http\Controllers\PolygonsController::class, 'update'])->name('polygons.update');

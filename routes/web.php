<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PointsController;
use App\Http\Controllers\PolylinesController;
use App\Http\Controllers\PolygonsController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\BpsController;

// ─── HALAMAN PUBLIK ──────────────────────────────────────────────────────────
Route::get('/',        [PageController::class, 'home'])->name('home');
Route::get('/tentang', [PageController::class, 'tentang'])->name('tentang');
Route::get('/peta',    [PageController::class, 'mapAll'])->name('map.all');
Route::get('/statistik-bps', [BpsController::class, 'index'])->name('bps.index');

// ─── AUTH ─────────────────────────────────────────────────────────────────────
Route::get('/login',     [PageController::class, 'loginForm'])->name('login');
Route::post('/login',    [PageController::class, 'loginPost'])->name('login.post');
Route::post('/logout',   [PageController::class, 'logout'])->name('logout');
Route::get('/register',  [PageController::class, 'registerForm'])->name('register');
Route::post('/register', [PageController::class, 'registerPost'])->name('register.post');

// ─── PROTECTED (butuh login) ─────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');

    // Points CRUD
    Route::get('/points',              [PointsController::class, 'index'])->name('points.index');
    Route::post('/points',             [PointsController::class, 'store'])->name('points.store');
    Route::get('/points/{id}/edit',    [PointsController::class, 'edit'])->name('points.edit');
    Route::put('/points/{id}',         [PointsController::class, 'update'])->name('points.update');
    Route::delete('/points/{id}',      [PointsController::class, 'destroy'])->name('points.destroy');

    // Polylines CRUD
    Route::get('/polylines',              [PolylinesController::class, 'index'])->name('polylines.index');
    Route::post('/polylines',             [PolylinesController::class, 'store'])->name('polylines.store');
    Route::get('/polylines/{id}/edit',    [PolylinesController::class, 'edit'])->name('polylines.edit');
    Route::put('/polylines/{id}',         [PolylinesController::class, 'update'])->name('polylines.update');
    Route::delete('/polylines/{id}',      [PolylinesController::class, 'destroy'])->name('polylines.destroy');

    // Polygons CRUD
    Route::get('/polygons',              [PolygonsController::class, 'index'])->name('polygons.index');
    Route::post('/polygons',             [PolygonsController::class, 'store'])->name('polygons.store');
    Route::get('/polygons/{id}/edit',    [PolygonsController::class, 'edit'])->name('polygons.edit');
    Route::put('/polygons/{id}',         [PolygonsController::class, 'update'])->name('polygons.update');
    Route::delete('/polygons/{id}',      [PolygonsController::class, 'destroy'])->name('polygons.destroy');

    // BPS - kelola data statistik (admin)
    Route::get('/bps-manage',         [BpsController::class, 'manage'])->name('bps.manage');
    Route::post('/bps-manage',        [BpsController::class, 'store'])->name('bps.store');
    Route::delete('/bps-manage/{id}', [BpsController::class, 'destroy'])->name('bps.destroy');

});

// ─── API GeoJSON (untuk Leaflet, publik) ─────────────────────────────────────
Route::get('/api/points',    [ApiController::class, 'getPoints']);
Route::get('/api/polylines', [ApiController::class, 'getPolylines']);
Route::get('/api/polygons',  [ApiController::class, 'getPolygons']);
Route::get('/api/bps-lahan', [ApiController::class, 'getBpsLahan']);

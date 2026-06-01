@extends('layouts.template')

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fc;
        color: #333;
    }

    #map {
        height: 0vh;
        width: 100%;
    }

    .main-card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        background: #fff;
    }

    .main-header {
        background-color: #d291bc;
        color: white;
        padding: 16px 20px;
    }

    .main-header h3 {
        margin: 0;
        font-size: 22px;
        font-weight: 600;
    }

    .main-body {
        padding: 20px;
        line-height: 1.7;
        color: #555;
    }

    .stats-card {
        border: none;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: 0.2s ease;
    }

    .stats-card:hover {
        transform: translateY(-2px);
    }

    .stats-header {
        background-color: #f3e5f0;
        color: #7a4b68;
        padding: 14px 18px;
        font-weight: 600;
        border-bottom: 1px solid #eee;
    }

    .stats-body {
        padding: 25px;
        text-align: center;
    }

    .stats-body h1 {
        margin: 0;
        font-size: 38px;
        color: #444;
        font-weight: 700;
    }
</style>

@section('content')
    <!-- Map -->
    <div id="map"></div>

    <!-- Container -->
    <div class="container mt-4">

        <!-- Main Card -->
        <div class="card main-card">

            <div class="main-header">
                <h3>Aplikasi Geospasial CRUD</h3>
            </div>

            <div class="main-body">
                <p style="text-align: justify;">
                    Aplikasi ini dibuat untuk memenuhi tugas Praktikum Pemrograman Web Lanjut.
                    Aplikasi ini menggunakan Leaflet JS untuk menampilkan peta interaktif.
                    Data yang ditampilkan pada peta diambil dari database dan ditampilkan
                    dalam bentuk marker. Setiap marker memiliki popup yang menampilkan
                    informasi terkait lokasi tersebut.
                </p>

                <p style="text-align: justify;">
                    Selain itu, aplikasi ini juga memiliki fitur untuk menambahkan,
                    mengedit, dan menghapus data lokasi melalui antarmuka pengguna
                    yang sederhana. Dengan aplikasi ini, pengguna dapat dengan mudah
                    melihat dan mengelola data lokasi secara visual melalui peta interaktif.
                </p>

                <p style="text-align: justify;">
                    Web GIS ini dapat digunakan untuk berbagai keperluan, seperti
                    pemetaan lokasi bisnis, pemantauan lingkungan, atau pengelolaan aset.
                </p>
            </div>

        </div>

        <!-- Statistik -->
        <div class="row mt-4 g-4">

            <div class="col-md-3">
                <div class="card stats-card">

                    <div class="stats-header">
                        Jumlah Point
                    </div>

                    <div class="stats-body">
                        <h1>{{ $points_count }}</h1>
                    </div>

                </div>
            </div>

            <div class="col-md-3">
                <div class="card stats-card">

                    <div class="stats-header">
                        Jumlah Line
                    </div>

                    <div class="stats-body">
                        <h1>{{ $polylines_count }}</h1>
                    </div>

                </div>
            </div>

            <div class="col-md-3">
                <div class="card stats-card">

                    <div class="stats-header">
                        Jumlah Polygon
                    </div>

                    <div class="stats-body">
                        <h1>{{ $polygons_count }}</h1>
                    </div>

                </div>
            </div>

            <div class="col-md-3">
                <div class="card stats-card">

                    <div class="stats-header">
                        Jumlah User
                    </div>

                    <div class="stats-body">
                        <h1>{{ $users_count }}</h1>
                    </div>

                </div>
            </div>

        </div>

    </div>
@endsection

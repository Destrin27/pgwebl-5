@extends('layouts.app')
@section('title', 'Tentang')
@section('content')
<div class="page-header">
    <div class="header-icon"><i class="fas fa-info-circle"></i></div>
    <div>
        <h4>Tentang SragenLandWatch</h4>
        <p>Platform WebGIS pemantauan perubahan penggunaan lahan Kabupaten Sragen</p>
    </div>
</div>
<div class="sidebar-card mt-4">
    <h5 class="text-success">Tentang Proyek</h5>
    <hr>
    <p>SragenLandWatch adalah sistem informasi geografis berbasis web yang dirancang untuk memantau dan mendokumentasikan perubahan penggunaan lahan di Kabupaten Sragen, Jawa Tengah.</p>
    <p>Dibangun menggunakan <strong>Laravel</strong>, <strong>PostgreSQL + PostGIS</strong>, dan <strong>Leaflet.js</strong> sebagai bagian dari proyek responsi PGWEBL 2026.</p>
    <ul>
        <li>Input data titik, polyline, dan polygon perubahan lahan</li>
        <li>Visualisasi peta interaktif real-time</li>
        <li>Dashboard statistik perubahan lahan per kecamatan</li>
        <li>Integrasi data BPS Kabupaten Sragen</li>
    </ul>
</div>
@endsection

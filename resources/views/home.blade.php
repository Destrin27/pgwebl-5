@extends('layouts.app')
@section('title', 'Beranda')

@section('styles')
<style>
.hero-section {
    background-image:
        linear-gradient(180deg, rgba(8,22,11,0.82), rgba(15,44,20,0.7)),
        url('https://upload.wikimedia.org/wikipedia/commons/e/e4/Alun-Alun_Sragen_%283%29.jpg');
    background-size: cover;
    background-position: center;
    border-radius: 20px;
    color: #fff;
    padding: 3rem 2.5rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}
.hero-section::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 260px; height: 260px;
    background: rgba(255,255,255,0.08);
    border-radius: 50%;
    filter: blur(16px);
}
.hero-section::after {
    content: '';
    position: absolute;
    bottom: -90px; left: -60px;
    width: 260px; height: 260px;
    background: rgba(255,255,255,0.06);
    border-radius: 50%;
    filter: blur(20px);
}
.hero-section h1 { font-size: clamp(2.4rem, 3vw, 3.6rem); font-weight: 800; line-height: 1.05; }
.hero-section .tagline { font-size: 1rem; opacity: 0.9; margin: 0.9rem 0 1.6rem; max-width: 560px; }
.hero-badge {
    background: rgba(255,255,255,0.18);
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 20px;
    padding: 4px 14px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-block;
    margin-bottom: 1rem;
}
.hero-stat {
    background: rgba(255,255,255,0.12);
    border-radius: 12px;
    padding: 1rem;
    text-align: center;
    border: 1px solid rgba(255,255,255,0.15);
}
.hero-stat .num { font-size: 2rem; font-weight: 800; line-height: 1; }
.hero-stat .lbl { font-size: 0.75rem; opacity: 0.78; margin-top: 2px; }
.hero-stat { animation: fadeInUp 0.9s ease both; }
.hero-figure-card {
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid rgba(255,255,255,0.18);
    box-shadow: 0 16px 50px rgba(0,0,0,0.18);
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(8px);
}
.hero-figure-card img {
    width: 100%;
    display: block;
    object-fit: cover;
    min-height: 240px;
}
.hero-figure-card p {
    margin: 0;
    padding: 1rem 1rem 1.15rem;
    font-size: 0.92rem;
    color: rgba(255,255,255,0.94);
    background: rgba(0,0,0,0.16);
}
.feature-card {
    background: #fff;
    border-radius: 16px;
    padding: 1.75rem 1.5rem;
    border: 1.5px solid var(--border);
    height: 100%;
    transition: transform 0.25s, box-shadow 0.25s, border-color 0.25s;
    text-decoration: none;
    color: inherit;
    display: block;
    opacity: 0;
    transform: translateY(22px);
    animation: fadeInUp 0.9s ease forwards;
}
.feature-card:nth-child(1) { animation-delay: .18s; }
.feature-card:nth-child(2) { animation-delay: .26s; }
.feature-card:nth-child(3) { animation-delay: .34s; }
.feature-card:nth-child(4) { animation-delay: .42s; }
.feature-card:nth-child(5) { animation-delay: .50s; }
.feature-card:nth-child(6) { animation-delay: .58s; }
.feature-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(27,94,32,0.16);
    border-color: var(--secondary);
}
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(28px); }
    to { opacity: 1; transform: translateY(0); }
}
.feature-icon {
    width: 56px; height: 56px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem;
    margin-bottom: 1rem;
}
.feature-card h5 { font-weight: 700; font-size: 1rem; color: var(--primary); }
.feature-card p  { font-size: 0.875rem; color: var(--text-muted); margin: 0; }
.feature-card .arrow {
    margin-top: 1rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--secondary);
}

.info-section {
    background: #fff;
    border-radius: 16px;
    padding: 2rem;
    border: 1.5px solid var(--border);
}
.tech-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--bg);
    border: 1.5px solid var(--border);
    border-radius: 20px;
    padding: 6px 14px;
    font-size: 0.83rem;
    font-weight: 600;
    color: var(--primary);
    margin: 4px;
}
</style>
@endsection

@section('content')

<!-- ═══ HERO ═══ -->
<div class="hero-section">
    <div class="row align-items-center g-4">
        <div class="col-lg-7">
            <div class="hero-badge">
                <i class="fas fa-satellite-dish me-1"></i> Responsi PGWEBL 2026
            </div>
            <h1>
                <i class="fas fa-leaf me-2" style="opacity:0.7"></i>
                SragenLandWatch
            </h1>
            <p class="tagline">
                Sistem Pemantauan Perubahan Penggunaan Lahan<br>
                Kabupaten Sragen, Jawa Tengah
            </p>
            @guest
            <a href="/login" class="btn me-2" style="background:#fff;color:var(--primary);font-weight:700;border-radius:10px;padding:10px 24px;">
                <i class="fas fa-sign-in-alt me-2"></i>Masuk & Kelola Data
            </a>
            <a href="/register" class="btn" style="background:rgba(255,255,255,0.2);color:#fff;border:1.5px solid rgba(255,255,255,0.4);font-weight:600;border-radius:10px;padding:10px 24px;">
                Daftar Akun
            </a>
            @else
            <a href="/dashboard" class="btn me-2" style="background:#fff;color:var(--primary);font-weight:700;border-radius:10px;padding:10px 24px;">
                <i class="fas fa-chart-bar me-2"></i>Lihat Dashboard
            </a>
            <a href="/points" class="btn" style="background:rgba(255,255,255,0.2);color:#fff;border:1.5px solid rgba(255,255,255,0.4);font-weight:600;border-radius:10px;padding:10px 24px;">
                Input Data
            </a>
            @endguest
        </div>
        <div class="col-lg-5">
            <div class="row g-3">
                <div class="col-6">
                    <div class="hero-stat">
                        <div class="num">{{ $totalPoints }}</div>
                        <div class="lbl"><i class="fas fa-map-marker-alt me-1"></i>Titik Lokasi</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="hero-stat">
                        <div class="num">{{ $totalPolygons }}</div>
                        <div class="lbl"><i class="fas fa-draw-polygon me-1"></i>Area Perubahan</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="hero-stat">
                        <div class="num">{{ $totalPolylines }}</div>
                        <div class="lbl"><i class="fas fa-route me-1"></i>Jalur Perubahan</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="hero-stat">
                        <div class="num">{{ number_format($totalLuas, 1) }}</div>
                        <div class="lbl"><i class="fas fa-ruler-combined me-1"></i>Total Ha</div>
                    </div>
                </div>
            </div>
            <div class="hero-figure-card mt-4">
                <img src="{{ asset('images/sragen-landwatch-hero.svg') }}"
                     alt="Ilustrasi SragenLandWatch untuk monitoring lahan">
                <p>Ilustrasi SragenLandWatch untuk mendukung pemantauan lahan modern berbasis data spasial.</p>
            </div>
        </div>
    </div>
</div>

<!-- ═══ FITUR UTAMA ═══ -->
<h5 class="fw-bold mb-3" style="color:var(--primary)">
    <i class="fas fa-th-large me-2"></i>Fitur Utama
</h5>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <a href="/points" class="feature-card">
            <div class="feature-icon" style="background:#E8F5E9;">
                <i class="fas fa-map-marker-alt" style="color:#1B5E20"></i>
            </div>
            <h5>Titik Lokasi</h5>
            <p>Catat titik koordinat lokasi perubahan lahan secara tepat. Klik peta untuk menandai lokasi, isi formulir, dan simpan.</p>
            <div class="arrow"><i class="fas fa-arrow-right me-1"></i>Buka peta titik</div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="/polylines" class="feature-card">
            <div class="feature-icon" style="background:#E8F5E9;">
                <i class="fas fa-route" style="color:#2E7D32"></i>
            </div>
            <h5>Jalur Perubahan</h5>
            <p>Gambar jalur atau koridor perubahan seperti alih fungsi lahan sepanjang jalan atau irigasi. Panjang dihitung otomatis.</p>
            <div class="arrow"><i class="fas fa-arrow-right me-1"></i>Buka peta jalur</div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="/polygons" class="feature-card">
            <div class="feature-icon" style="background:#E8F5E9;">
                <i class="fas fa-draw-polygon" style="color:#388E3C"></i>
            </div>
            <h5>Area Lahan</h5>
            <p>Gambar poligon area perubahan penggunaan lahan. Luas area dalam hektar dihitung otomatis menggunakan PostGIS.</p>
            <div class="arrow"><i class="fas fa-arrow-right me-1"></i>Buka peta area</div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="/dashboard" class="feature-card">
            <div class="feature-icon" style="background:#FFF8E1;">
                <i class="fas fa-chart-bar" style="color:#F9A825"></i>
            </div>
            <h5>Dashboard Statistik</h5>
            <p>Pantau rekap perubahan per jenis, per kecamatan, dan per tahun. Visualisasi grafik interaktif dengan Chart.js.</p>
            <div class="arrow"><i class="fas fa-arrow-right me-1"></i>Lihat dashboard</div>
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="feature-card">
            <div class="feature-icon" style="background:#E8F5E9;">
                <i class="fas fa-search" style="color:#1B5E20"></i>
            </div>
            <h5>Insight Analitik</h5>
            <p>Identifikasi cepat pola perubahan lahan dan area prioritas pemantauan dengan visualisasi data yang jelas.</p>
            <div class="arrow"><i class="fas fa-arrow-right me-1"></i>Lihat insight</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="feature-card">
            <div class="feature-icon" style="background:#E8F5E9;">
                <i class="fas fa-user-friends" style="color:#2E7D32"></i>
            </div>
            <h5>Kolaborasi Tim</h5>
            <p>Berbagi temuan dan data lapangan dengan tim untuk perencanaan lingkungan dan pembangunan wilayah Sragen.</p>
            <div class="arrow"><i class="fas fa-arrow-right me-1"></i>Pelajari lebih lanjut</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="feature-card">
            <div class="feature-icon" style="background:#E8F5E9;">
                <i class="fas fa-leaf" style="color:#388E3C"></i>
            </div>
            <h5>Monitoring Lahan Hijau</h5>
            <p>Pantau perubahan tutupan lahan hijau dan deteksi tren alih fungsi untuk mendukung konservasi.</p>
            <div class="arrow"><i class="fas fa-arrow-right me-1"></i>Telusuri fitur</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <a href="/points" class="feature-card">
            <div class="feature-icon" style="background:#E3F2FD;">
                <i class="fas fa-edit" style="color:#1565C0"></i>
            </div>
            <h5>Edit & Hapus Data</h5>
            <p>Kelola seluruh data spasial yang sudah diinput. Edit atribut dan geometri, hapus data tidak diperlukan, upload foto lokasi.</p>
            <div class="arrow"><i class="fas fa-arrow-right me-1"></i>Kelola data</div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="/statistik-bps" class="feature-card">
            <div class="feature-icon" style="background:#E3F2FD;">
                <i class="fas fa-landmark" style="color:#1565C0"></i>
            </div>
            <h5>Statistik BPS</h5>
            <p>Bandingkan data hasil pemantauan lapangan dengan statistik resmi luas lahan BPS antar tahun, per kecamatan.</p>
            <div class="arrow"><i class="fas fa-arrow-right me-1"></i>Lihat statistik BPS</div>
        </a>
    </div>
    <div class="col-md-4">
        <div class="feature-card" style="cursor:default;">
            <div class="feature-icon" style="background:#FCE4EC;">
                <i class="fas fa-server" style="color:#C62828"></i>
            </div>
            <h5>Integrasi GeoServer</h5>
            <p>Layer WMS dari GeoServer tersedia di peta sebagai overlay tematik. Mendukung toggle layer dan kontrol transparansi.</p>
            <div class="arrow" style="color:#888;"><i class="fas fa-info-circle me-1"></i>Lihat Bab 10 panduan</div>
        </div>
    </div>
</div>

<!-- ═══ TECH STACK ═══ -->
<div class="info-section">
    <h6 class="fw-700 mb-3" style="color:var(--primary);font-weight:700;">
        <i class="fas fa-layer-group me-2"></i>Technology Stack
    </h6>
    <div>
        <span class="tech-pill"><i class="fab fa-laravel"></i> Laravel 10</span>
        <span class="tech-pill"><i class="fas fa-database"></i> PostgreSQL + PostGIS</span>
        <span class="tech-pill"><i class="fas fa-map"></i> Leaflet.js</span>
        <span class="tech-pill"><i class="fas fa-globe"></i> GeoServer WMS</span>
        <span class="tech-pill"><i class="fas fa-chart-pie"></i> Chart.js</span>
        <span class="tech-pill"><i class="fab fa-bootstrap"></i> Bootstrap 5</span>
    </div>
    <p class="mt-3 mb-0" style="font-size:0.875rem;color:var(--text-muted);">
        <i class="fas fa-map-marked-alt me-1"></i>
        <strong>Lokasi Kajian:</strong> Kabupaten Sragen, Jawa Tengah — 20 kecamatan
    </p>
</div>

@endsection

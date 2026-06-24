@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="dashboard-hero">
    <div class="hero-overlay"></div>
    <div class="hero-copy fade-in-up">
        <span class="hero-badge">SragenLandWatch • Analisis Spasial</span>
        <h1>Dashboard Pemantauan Lahan dengan Nuansa Ilmiah</h1>
        <p>Menggabungkan data lapangan, statistik perubahan lahan, dan visualisasi spasial agar keputusan pengelolaan Sragen lebih akurat.</p>
        <div class="hero-actions">
            <a href="/peta" class="btn btn-success-custom me-2">Buka Peta Interaktif</a>
            <a href="/statistik-bps" class="btn btn-outline-success">Lihat Data BPS</a>
        </div>
    </div>
    <div class="hero-decor"></div>
</div>

<div class="page-header fade-in-up" style="animation-delay:.15s;">
    <div class="header-icon"><i class="fas fa-chart-bar"></i></div>
    <div>
        <h4>Dashboard SragenLandWatch</h4>
        <p>Statistik dan rekap perubahan penggunaan lahan Kabupaten Sragen</p>
    </div>
</div>

<!-- ═══ STAT CARDS ═══ -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#1B5E20,#388E3C);">
            <div class="stat-icon"><i class="fas fa-map-marker-alt"></i></div>
            <div class="stat-number">{{ $totalPoints }}</div>
            <div class="stat-label">Titik Lokasi</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#2E7D32,#66BB6A);">
            <div class="stat-icon"><i class="fas fa-draw-polygon"></i></div>
            <div class="stat-number">{{ $totalPolygons }}</div>
            <div class="stat-label">Area Perubahan</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#E65100,#FF9800);">
            <div class="stat-icon"><i class="fas fa-route"></i></div>
            <div class="stat-number">{{ $totalPolylines }}</div>
            <div class="stat-label">Jalur Perubahan</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#1565C0,#42A5F5);animation-delay:.25s;">
            <div class="stat-icon"><i class="fas fa-ruler-combined"></i></div>
            <div class="stat-number">{{ number_format($totalLuas, 1) }}</div>
            <div class="stat-label">Total Luas (Ha)</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4 dashboard-highlights fade-in-up" style="animation-delay:.3s;">
    <div class="col-md-4">
        <div class="highlight-card">
            <h6>Data Terperinci</h6>
            <p>Setiap titik, jalur, dan area direkam dengan detail untuk analisis lintas waktu.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="highlight-card">
            <h6>Visualisasi Ilmiah</h6>
            <p>Grafik dan peta dikemas rapi dengan gaya ilmiah dan warna yang mudah dibaca.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="highlight-card">
            <h6>Memudahkan Keputusan</h6>
            <p>Dashboard ini mendukung tim lapangan dan perencana dalam melihat tren perubahan lahan.</p>
        </div>
    </div>
</div>

<!-- ═══ GRAFIK ═══ -->
<div class="row g-4 mb-4">
    <!-- Pie: Jenis Perubahan -->
    <div class="col-md-5">
        <div class="sidebar-card h-100">
            <h6 class="fw-bold mb-3" style="color:var(--primary);">
                <i class="fas fa-chart-pie me-2"></i>Komposisi Perubahan Lahan
            </h6>
            <canvas id="chartPie" height="220"></canvas>
        </div>
    </div>

    <!-- Bar: Per Tahun -->
    <div class="col-md-7">
        <div class="sidebar-card h-100">
            <h6 class="fw-bold mb-3" style="color:var(--primary);">
                <i class="fas fa-chart-bar me-2"></i>Jumlah Perubahan per Tahun
            </h6>
            <canvas id="chartTahun" height="200"></canvas>
        </div>
    </div>
</div>

<!-- Bar: Per Kecamatan -->
<div class="sidebar-card mb-4">
    <h6 class="fw-bold mb-3" style="color:var(--primary);">
        <i class="fas fa-map me-2"></i>Perubahan per Kecamatan
    </h6>
    <canvas id="chartKecamatan" height="120"></canvas>
</div>

<!-- ═══ PERBANDINGAN DATA BPS ═══ -->
<div class="page-header" style="background:linear-gradient(135deg,#1565C0,#1976D2);">
    <div class="header-icon"><i class="fas fa-landmark"></i></div>
    <div>
        <h4>Perbandingan dengan Data BPS</h4>
        <p>Statistik resmi luas lahan Kabupaten Sragen, tahun {{ $tahunLama }} vs {{ $tahunBaru }}</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="sidebar-card h-100">
            <h6 class="fw-bold mb-3" style="color:var(--primary);">
                <i class="fas fa-chart-bar me-2"></i>Total Luas Lahan Kabupaten (BPS)
            </h6>
            <canvas id="chartBps" height="220"></canvas>
            <p style="font-size:0.75rem;color:var(--text-muted);margin-top:8px;margin-bottom:0;">
                <i class="fas fa-info-circle me-1"></i>Sumber: BPS Kabupaten Sragen. Lihat detail per kecamatan di halaman
                <a href="/statistik-bps">Statistik BPS</a>.
            </p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="sidebar-card h-100">
            <h6 class="fw-bold mb-3" style="color:var(--primary);">
                <i class="fas fa-draw-polygon me-2"></i>Rekap Area Hasil Pemantauan per Kategori
            </h6>
            <canvas id="chartKategori" height="220"></canvas>
        </div>
    </div>
</div>

<!-- ═══ TABEL REKAP ═══ -->
<div class="sidebar-card">
    <h6 class="fw-bold mb-3" style="color:var(--primary);">
        <i class="fas fa-table me-2"></i>Rekap Detail Perubahan Penggunaan Lahan
    </h6>

    @if(count($rekapPerubahan) > 0)
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Penggunaan Lama</th>
                    <th></th>
                    <th>Penggunaan Baru</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-end">Total Luas (Ha)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rekapPerubahan as $i => $r)
                <tr>
                    <td class="text-muted">{{ $i + 1 }}</td>
                    <td><span class="badge badge-lama">{{ $r->penggunaan_lama }}</span></td>
                    <td><i class="fas fa-arrow-right arrow-change"></i></td>
                    <td><span class="badge badge-baru">{{ $r->penggunaan_baru }}</span></td>
                    <td class="text-center fw-bold">{{ $r->jumlah }}</td>
                    <td class="text-end">{{ number_format($r->total_luas, 2) }} Ha</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-4" style="color:var(--text-muted);">
        <i class="fas fa-database fa-2x mb-2 d-block" style="opacity:0.3;"></i>
        Belum ada data polygon. Tambahkan data area perubahan terlebih dahulu.
    </div>
    @endif
</div>

@endsection

@section('styles')
<style>
    .dashboard-hero {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        min-height: 360px;
        margin-bottom: 1.75rem;
        display: grid;
        place-items: center;
        background-image: linear-gradient(180deg, rgba(20,60,25,0.68), rgba(15,29,16,0.85)),
            url('https://upload.wikimedia.org/wikipedia/commons/e/e4/Alun-Alun_Sragen_%283%29.jpg');
        background-size: cover;
        background-position: center;
        box-shadow: 0 30px 80px rgba(0,0,0,0.2);
    }
    .dashboard-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at top left, rgba(255,255,255,0.12), transparent 32%);
        pointer-events: none;
    }
    .hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(15,33,15,0.42), rgba(8,22,11,0.85));
    }
    .hero-copy {
        position: relative;
        z-index: 2;
        max-width: 740px;
        text-align: center;
        color: #fff;
        padding: 2rem 1.5rem;
        animation: fadeInUp 0.9s ease both;
    }
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.18);
        color: #E8F5E9;
        padding: 0.65rem 1rem;
        border-radius: 999px;
        font-size: 0.9rem;
        letter-spacing: 0.02em;
        margin-bottom: 1rem;
        backdrop-filter: blur(8px);
    }
    .hero-copy h1 {
        font-size: clamp(2.35rem, 2.5vw, 3.8rem);
        line-height: 1.02;
        margin-bottom: 1rem;
        letter-spacing: -0.02em;
    }
    .hero-copy p {
        font-size: 1.05rem;
        max-width: 720px;
        margin: 0 auto 1.5rem;
        opacity: 0.9;
        line-height: 1.7;
    }
    .hero-actions a {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .hero-actions a:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 36px rgba(0,0,0,0.22);
    }
    .hero-decor {
        position: absolute;
        bottom: -10%;
        left: 10%;
        width: 160px;
        height: 160px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
        filter: blur(32px);
        animation: pulseGlow 6s ease-in-out infinite;
    }
    .highlight-card {
        background: rgba(255,255,255,0.95);
        border-radius: 18px;
        padding: 1.4rem 1.45rem;
        box-shadow: 0 18px 40px rgba(0,0,0,0.1);
        border: 1px solid rgba(28,93,35,0.12);
        min-height: 160px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .highlight-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 22px 46px rgba(0,0,0,0.12);
    }
    .highlight-card h6 {
        margin-bottom: 0.75rem;
        color: var(--primary);
        font-weight: 700;
    }
    .highlight-card p {
        margin: 0;
        color: var(--text-muted);
        line-height: 1.7;
        font-size: 0.95rem;
    }
    .fade-in-up {
        opacity: 0;
        transform: translateY(16px);
        animation: fadeInUp 0.85s ease both;
    }
    .dashboard-highlights > .col-md-4:nth-child(1) .highlight-card { animation-delay: .35s; }
    .dashboard-highlights > .col-md-4:nth-child(2) .highlight-card { animation-delay: .45s; }
    .dashboard-highlights > .col-md-4:nth-child(3) .highlight-card { animation-delay: .55s; }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(24px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes pulseGlow {
        0%,100% { transform: scale(1); opacity: .28; }
        50% { transform: scale(1.2); opacity: .45; }
    }
</style>
@endsection

@section('scripts')
<script>
// ─── Animasi angka naik ───────────────────────────────────────────────────────
function countUp(selector, endValue, duration) {
    var element = document.querySelector(selector);
    if (!element) return;
    var startValue = 0;
    var range = endValue - startValue;
    var startTime = null;
    function step(timestamp) {
        if (!startTime) startTime = timestamp;
        var progress = Math.min((timestamp - startTime) / duration, 1);
        element.textContent = Math.floor(startValue + range * progress).toLocaleString();
        if (progress < 1) window.requestAnimationFrame(step);
    }
    window.requestAnimationFrame(step);
}

document.addEventListener('DOMContentLoaded', function () {
    countUp('.stat-number:first-of-type', {{ $totalPoints }} , 850);
    countUp('.stat-number:nth-of-type(2)', {{ $totalPolygons }}, 850);
    countUp('.stat-number:nth-of-type(3)', {{ $totalPolylines }}, 850);
    countUp('.stat-number:nth-of-type(4)', {{ number_format($totalLuas, 0) }}, 850);
});

// ─── Data dari Laravel ────────────────────────────────────────────────────────
var rekapData     = @json($rekapPerubahan);
var rekapKategori  = @json($rekapKategori);
var tahunData      = @json($perTahun);
var kecamatanData  = @json($perKecamatan);
var bpsTahunData   = @json($bpsTotalPerTahun);

var hijauPalette = [
    '#1B5E20','#2E7D32','#388E3C','#43A047','#4CAF50',
    '#66BB6A','#81C784','#A5D6A7','#C8E6C9','#E8F5E9'
];

// ─── Pie Chart – Komposisi ────────────────────────────────────────────────────
var pieLabels = rekapData.map(r => r.penggunaan_lama + ' → ' + r.penggunaan_baru);
var pieValues = rekapData.map(r => parseInt(r.jumlah));

new Chart(document.getElementById('chartPie'), {
    type: 'doughnut',
    data: {
        labels: pieLabels,
        datasets: [{ data: pieValues, backgroundColor: hijauPalette, borderWidth: 2, borderColor: '#fff' }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 11 }, boxWidth: 12, padding: 8 } }
        }
    }
});

// ─── Bar Chart – Per Tahun ────────────────────────────────────────────────────
new Chart(document.getElementById('chartTahun'), {
    type: 'bar',
    data: {
        labels: tahunData.map(r => r.tahun_perubahan),
        datasets: [{
            label: 'Jumlah Perubahan',
            data: tahunData.map(r => r.jumlah),
            backgroundColor: '#388E3C',
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#E8F5E9' } },
            x: { grid: { display: false } }
        }
    }
});

// ─── Bar Chart – Per Kecamatan ────────────────────────────────────────────────
new Chart(document.getElementById('chartKecamatan'), {
    type: 'bar',
    data: {
        labels: kecamatanData.map(r => r.kecamatan),
        datasets: [{
            label: 'Jumlah Perubahan',
            data: kecamatanData.map(r => r.jumlah),
            backgroundColor: kecamatanData.map((r, i) => hijauPalette[i % hijauPalette.length]),
            borderRadius: 5,
            borderSkipped: false,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#E8F5E9' } },
            y: { grid: { display: false } }
        }
    }
});

// ─── Bar Chart – Perbandingan BPS antar tahun ────────────────────────────────
new Chart(document.getElementById('chartBps'), {
    type: 'bar',
    data: {
        labels: bpsTahunData.map(r => r.tahun),
        datasets: [
            { label: 'Lahan Sawah (Ha)', data: bpsTahunData.map(r => parseFloat(r.sawah)), backgroundColor: '#2E7D32', borderRadius: 5 },
            { label: 'Lahan Bukan Sawah (Ha)', data: bpsTahunData.map(r => parseFloat(r.bukan_sawah)), backgroundColor: '#FF9800', borderRadius: 5 },
            { label: 'Bukan Pertanian (Ha)', data: bpsTahunData.map(r => parseFloat(r.bukan_pertanian)), backgroundColor: '#90A4AE', borderRadius: 5 },
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom', labels: { font: { size: 11 }, boxWidth: 12 } } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#E3F2FD' } },
            x: { grid: { display: false } }
        }
    }
});

// ─── Doughnut Chart – Rekap kategori objek (data lapangan) ───────────────────
new Chart(document.getElementById('chartKategori'), {
    type: 'doughnut',
    data: {
        labels: rekapKategori.map(r => r.kategori_objek),
        datasets: [{
            data: rekapKategori.map(r => parseFloat(r.total_luas)),
            backgroundColor: hijauPalette,
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 11 }, boxWidth: 12, padding: 8 } },
            tooltip: { callbacks: { label: (ctx) => ctx.label + ': ' + ctx.raw.toFixed(2) + ' Ha' } }
        }
    }
});
</script>
@endsection

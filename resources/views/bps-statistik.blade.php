@extends('layouts.app')
@section('title', 'Statistik Lahan')

@section('content')
<div class="page-header" style="background:linear-gradient(135deg,#1565C0,#1976D2);">
    <div class="header-icon"><i class="fas fa-landmark"></i></div>
    <div>
        <h4>Statistik Lahan BPS Kabupaten Sragen</h4>
        <p>Luas wilayah resmi BPS 2025 diintegrasikan dengan data pemantauan perubahan penggunaan lahan lapangan</p>
    </div>
</div>

<!-- ═══ FILTER TAHUN ═══ -->
<div class="sidebar-card mb-4">
    <form method="GET" action="/statistik-bps" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label fw-semibold">Tahun Awal (pembanding)</label>
            <select name="tahun_awal" class="form-select">
                @if($tahunTersedia->isEmpty())
                    <option>– belum ada data –</option>
                @else
                    @foreach($tahunTersedia as $t)
                    <option value="{{ $t }}" {{ (string)$tahunAwal === (string)$t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Tahun Akhir (terbaru)</label>
            <select name="tahun_akhir" class="form-select">
                @if($tahunTersedia->isEmpty())
                    <option>– belum ada data –</option>
                @else
                    @foreach($tahunTersedia as $t)
                    <option value="{{ $t }}" {{ (string)$tahunAkhir === (string)$t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-success-custom w-100">
                <i class="fas fa-sync-alt me-2"></i>Bandingkan
            </button>
        </div>
    </form>
</div>

@if(!$totalAkhir || !$totalAwal)
<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle me-2"></i>
    Belum ada data BPS. Silakan jalankan seeder atau input manual di
    <a href="/bps-manage" class="fw-bold">Kelola Data BPS</a>.
</div>
@else

<!-- ═══ STAT CARDS ═══ -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#2E7D32,#66BB6A);">
            <div class="stat-icon"><i class="fas fa-seedling"></i></div>
            <div class="stat-number" style="font-size:1.4rem;">
                {{ number_format($totalAkhir->pertanian, 0) }} Ha
            </div>
            <div class="stat-label">
                Lahan Pertanian ({{ $tahunAkhir }})
                @php $s1 = $totalAkhir->pertanian - $totalAwal->pertanian; @endphp
                <br><span style="font-size:0.75rem;opacity:0.9;">
                    {{ $s1 <= 0 ? '▼' : '▲' }} {{ number_format(abs($s1), 1) }} Ha sejak {{ $tahunAwal }}
                </span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#E65100,#FF9800);">
            <div class="stat-icon"><i class="fas fa-city"></i></div>
            <div class="stat-number" style="font-size:1.4rem;">
                {{ number_format($totalAkhir->terbangun, 0) }} Ha
            </div>
            <div class="stat-label">
                Lahan Terbangun ({{ $tahunAkhir }})
                @php $s2 = $totalAkhir->terbangun - $totalAwal->terbangun; @endphp
                <br><span style="font-size:0.75rem;opacity:0.9;">
                    {{ $s2 >= 0 ? '▲' : '▼' }} {{ number_format(abs($s2), 1) }} Ha sejak {{ $tahunAwal }}
                </span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#5D4037,#8D6E63);">
            <div class="stat-icon"><i class="fas fa-tree"></i></div>
            <div class="stat-number" style="font-size:1.4rem;">
                {{ number_format($totalAkhir->lainnya, 0) }} Ha
            </div>
            <div class="stat-label">
                Lahan Lainnya ({{ $tahunAkhir }})
                @php $s3 = $totalAkhir->lainnya - $totalAwal->lainnya; @endphp
                <br><span style="font-size:0.75rem;opacity:0.9;">
                    {{ $s3 <= 0 ? '▼' : '▲' }} {{ number_format(abs($s3), 1) }} Ha sejak {{ $tahunAwal }}
                </span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#1565C0,#42A5F5);">
            <div class="stat-icon"><i class="fas fa-ruler-combined"></i></div>
            <div class="stat-number" style="font-size:1.4rem;">
                {{ number_format($totalAkhir->total, 0) }} Ha
            </div>
            <div class="stat-label">Total Luas Kabupaten Sragen</div>
        </div>
    </div>
</div>

<!-- ═══ GRAFIK ═══ -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="sidebar-card h-100">
            <h6 class="fw-bold mb-3" style="color:var(--primary);">
                <i class="fas fa-chart-bar me-2"></i>
                Komposisi Penggunaan Lahan: {{ $tahunAwal }} vs {{ $tahunAkhir }}
            </h6>
            <canvas id="chartKomposisi" height="240"></canvas>
            <p style="font-size:0.75rem;color:var(--text-muted);margin-top:8px;margin-bottom:0;">
                <i class="fas fa-info-circle me-1"></i>
                Berdasarkan luas wilayah BPS Sragen 2025 dengan estimasi proporsi penggunaan lahan.
            </p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="sidebar-card h-100">
            <h6 class="fw-bold mb-3" style="color:var(--primary);">
                <i class="fas fa-draw-polygon me-2"></i>
                Data Lapangan: Sebaran Penggunaan Baru
            </h6>
            <canvas id="chartLapangan" height="240"></canvas>
            <p style="font-size:0.75rem;color:var(--text-muted);margin-top:8px;margin-bottom:0;">
                <i class="fas fa-info-circle me-1"></i>
                Dari input polygon pengguna SragenLandWatch – mencerminkan kondisi penggunaan lahan terkini.
            </p>
        </div>
    </div>
</div>

<!-- ═══ TABEL PERBANDINGAN PER KECAMATAN ═══ -->
<div class="sidebar-card mb-4">
    <h6 class="fw-bold mb-3" style="color:var(--primary);">
        <i class="fas fa-table me-2"></i>
        Perbandingan Penggunaan Lahan per Kecamatan ({{ $tahunAwal }} vs {{ $tahunAkhir }})
    </h6>
    <div class="table-responsive">
        <table class="table table-hover mb-0" style="font-size:0.85rem;">
            <thead style="background:#E8F5E9;">
                <tr>
                    <th>Kecamatan</th>
                    <th class="text-end">Pertanian {{ $tahunAwal }}</th>
                    <th class="text-end">Pertanian {{ $tahunAkhir }}</th>
                    <th class="text-end">Selisih</th>
                    <th class="text-end">Terbangun {{ $tahunAwal }}</th>
                    <th class="text-end">Terbangun {{ $tahunAkhir }}</th>
                    <th class="text-end">Selisih</th>
                    <th class="text-end">Total (Ha)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($perbandingan as $row)
                <tr>
                    <td class="fw-bold">{{ $row['kecamatan'] }}</td>
                    <td class="text-end">
                        {{ $row['pertanian_awal'] !== null ? number_format($row['pertanian_awal'], 1) : '-' }}
                    </td>
                    <td class="text-end">{{ number_format($row['pertanian_akhir'], 1) }}</td>
                    <td class="text-end">
                        @if($row['pertanian_selisih'] !== null)
                            <span style="color:{{ $row['pertanian_selisih'] < 0 ? '#C62828' : '#1B5E20' }};font-weight:600;">
                                {{ $row['pertanian_selisih'] > 0 ? '+' : '' }}{{ number_format($row['pertanian_selisih'], 1) }}
                            </span>
                        @else <span class="text-muted">-</span> @endif
                    </td>
                    <td class="text-end">
                        {{ $row['terbangun_awal'] !== null ? number_format($row['terbangun_awal'], 1) : '-' }}
                    </td>
                    <td class="text-end">{{ number_format($row['terbangun_akhir'], 1) }}</td>
                    <td class="text-end">
                        @if($row['terbangun_selisih'] !== null)
                            <span style="color:{{ $row['terbangun_selisih'] > 0 ? '#E65100' : '#1B5E20' }};font-weight:600;">
                                {{ $row['terbangun_selisih'] > 0 ? '+' : '' }}{{ number_format($row['terbangun_selisih'], 1) }}
                            </span>
                        @else <span class="text-muted">-</span> @endif
                    </td>
                    <td class="text-end fw-bold">{{ number_format($row['total_akhir'], 1) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <p style="font-size:0.78rem;color:var(--text-muted);margin-top:10px;margin-bottom:0;">
        <i class="fas fa-info-circle me-1"></i>
        Satuan: hektar (Ha). Merah = penyusutan lahan pertanian. Oranye = pertambahan lahan terbangun
        (indikasi konversi lahan pertanian → terbangun).
    </p>
</div>

<!-- ═══ INTEGRASI BPS vs LAPANGAN ═══ -->
<div class="sidebar-card mb-4">
    <h6 class="fw-bold mb-3" style="color:var(--primary);">
        <i class="fas fa-link me-2"></i>
        Integrasi: Luas Wilayah BPS vs Data Pemantauan Lapangan SragenLandWatch
    </h6>
    <p style="font-size:0.82rem;color:var(--text-muted);margin-bottom:12px;">
        Seberapa banyak wilayah setiap kecamatan yang sudah berhasil dipantau dan diinput
        perubahan lahannya oleh pengguna SragenLandWatch.
    </p>
    <div class="table-responsive">
        <table class="table table-hover mb-0" style="font-size:0.84rem;">
            <thead style="background:#E8F5E9;">
                <tr>
                    <th>Kecamatan</th>
                    <th class="text-end">Luas Wilayah (Ha)</th>
                    <th class="text-end">Est. Pertanian (Ha)</th>
                    <th class="text-end">Est. Terbangun (Ha)</th>
                    <th class="text-end">Termonitor (Ha)</th>
                    <th class="text-end">Jumlah Area</th>
                    <th class="text-end">% Terpantau</th>
                    <th style="min-width:120px;">Progress</th>
                </tr>
            </thead>
            <tbody>
                @foreach($integrasiLahan as $row)
                <tr>
                    <td class="fw-bold">{{ $row->kecamatan }}</td>
                    <td class="text-end">{{ number_format($row->luas_total_ha, 1) }}</td>
                    <td class="text-end">{{ number_format($row->luas_pertanian_ha, 1) }}</td>
                    <td class="text-end">{{ number_format($row->luas_terbangun_ha, 1) }}</td>
                    <td class="text-end" style="color:{{ $row->luas_termonitor > 0 ? '#1B5E20' : '#999' }};font-weight:600;">
                        {{ number_format($row->luas_termonitor, 2) }}
                    </td>
                    <td class="text-end">
                        @if($row->jumlah_area > 0)
                            <span class="badge" style="background:#E8F5E9;color:#1B5E20;">
                                {{ $row->jumlah_area }} area
                            </span>
                        @else
                            <span class="text-muted">–</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <span style="color:{{ $row->persen_termonitor > 0 ? '#1B5E20' : '#999' }};font-weight:700;">
                            {{ $row->persen_termonitor }}%
                        </span>
                    </td>
                    <td>
                        <div style="background:#E8F5E9;border-radius:10px;height:8px;">
                            <div style="background:var(--secondary);border-radius:10px;height:8px;
                                        width:{{ min($row->persen_termonitor, 100) }}%;"></div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background:#F1F8E9;font-weight:700;font-size:0.87rem;">
                    <td>TOTAL KABUPATEN SRAGEN</td>
                    <td class="text-end">{{ number_format($integrasiLahan->sum('luas_total_ha'), 1) }} Ha</td>
                    <td class="text-end">{{ number_format($integrasiLahan->sum('luas_pertanian_ha'), 1) }} Ha</td>
                    <td class="text-end">{{ number_format($integrasiLahan->sum('luas_terbangun_ha'), 1) }} Ha</td>
                    <td class="text-end">{{ number_format($integrasiLahan->sum('luas_termonitor'), 2) }} Ha</td>
                    <td class="text-end">{{ $integrasiLahan->sum('jumlah_area') }} area</td>
                    <td class="text-end" colspan="2">
                        @php
                            $tw = $integrasiLahan->sum('luas_total_ha');
                            $tm = $integrasiLahan->sum('luas_termonitor');
                            $persen = $tw > 0 ? round($tm / $tw * 100, 2) : 0;
                        @endphp
                        {{ $persen }}% terpantau
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <p style="font-size:0.75rem;color:var(--text-muted);margin-top:8px;margin-bottom:0;">
        <i class="fas fa-info-circle me-1"></i>
        Luas wilayah dari BPS Sragen 2025. Est. = estimasi proporsi penggunaan lahan (52% pertanian, 18% terbangun, 30% lainnya).
        Data lapangan dari polygon yang diinput pengguna SragenLandWatch.
    </p>
</div>

<div class="alert alert-info" style="font-size:0.85rem;">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Catatan metodologi:</strong> Luas wilayah bersumber dari publikasi resmi BPS Kabupaten Sragen 2025.
    Proporsi penggunaan lahan (pertanian/terbangun/lainnya) merupakan estimasi berdasarkan karakteristik
    wilayah Sragen sebagai kabupaten agraris. Data akan semakin akurat seiring bertambahnya input
    perubahan lahan oleh pengguna SragenLandWatch di lapangan.
</div>

@endif
@endsection

@section('scripts')
@if($totalAkhir && $totalAwal)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
var lapangan = @json($lapangan);

// ─── Bar Chart: Komposisi Lahan ───────────────────────────────────────────────
new Chart(document.getElementById('chartKomposisi'), {
    type: 'bar',
    data: {
        labels: ['{{ $tahunAwal }}', '{{ $tahunAkhir }}'],
        datasets: [
            {
                label: 'Lahan Pertanian',
                data: [{{ $totalAwal->pertanian }}, {{ $totalAkhir->pertanian }}],
                backgroundColor: '#2E7D32',
                borderRadius: 6
            },
            {
                label: 'Lahan Terbangun',
                data: [{{ $totalAwal->terbangun }}, {{ $totalAkhir->terbangun }}],
                backgroundColor: '#FF9800',
                borderRadius: 6
            },
            {
                label: 'Lahan Lainnya',
                data: [{{ $totalAwal->lainnya }}, {{ $totalAkhir->lainnya }}],
                backgroundColor: '#90A4AE',
                borderRadius: 6
            },
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 11 }, boxWidth: 12 } },
            tooltip: {
                callbacks: {
                    label: (ctx) => ctx.dataset.label + ': ' + ctx.raw.toLocaleString('id-ID') + ' Ha'
                }
            }
        },
        scales: {
            y: { beginAtZero: true, grid: { color: '#E8F5E9' },
                 ticks: { callback: (v) => v.toLocaleString('id-ID') + ' Ha' } },
            x: { grid: { display: false } }
        }
    }
});

// ─── Doughnut Chart: Data Lapangan per Penggunaan Baru ───────────────────────
@if($lapangan->isNotEmpty())
new Chart(document.getElementById('chartLapangan'), {
    type: 'doughnut',
    data: {
        labels: lapangan.map(r => r.penggunaan_baru),
        datasets: [{
            data: lapangan.map(r => parseFloat(r.total_luas)),
            backgroundColor: [
                '#1B5E20','#E65100','#1565C0','#5D4037',
                '#388E3C','#FF9800','#42A5F5','#8D6E63',
                '#66BB6A','#FFB74D'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 11 }, boxWidth: 12, padding: 8 } },
            tooltip: {
                callbacks: {
                    label: (ctx) => ctx.label + ': ' + parseFloat(ctx.raw).toFixed(2) + ' Ha'
                }
            }
        }
    }
});
@else
document.getElementById('chartLapangan').parentElement.innerHTML +=
    '<div class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x mb-2 d-block"></i>Belum ada data polygon diinput</div>';
document.getElementById('chartLapangan').style.display = 'none';
@endif
</script>
@endif
@endsection

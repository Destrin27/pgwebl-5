@extends('layouts.app')
@section('title', 'Peta Semua Data')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
<style>
    /* ── HERO ── */
    .hero-banner {
        position: relative;
        display: grid;
        place-items: center;
        min-height: 420px;
        margin-bottom: 1.5rem;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.18);
    }
    .hero-banner img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: saturate(1.05) contrast(1.02) brightness(0.92);
    }
    .hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(28,71,30,0.28), rgba(15,33,15,0.84));
    }
    .hero-copy {
        position: absolute;
        z-index: 2;
        max-width: 900px;
        color: #fff;
        text-align: center;
        padding: 1.5rem 1.25rem;
    }
    .hero-badge {
        display: inline-flex;
        gap: 0.5rem;
        align-items: center;
        background: rgba(255,255,255,0.12);
        padding: 0.55rem 0.95rem;
        border-radius: 999px;
        font-size: 0.86rem;
        margin-bottom: 1rem;
    }
    .hero-copy h1 {
        font-size: clamp(2rem, 4vw, 3.2rem);
        line-height: 1.05;
        margin: 0 0 1rem;
        font-weight: 800;
    }
    .hero-copy p {
        font-size: 1rem;
        max-width: 64rem;
        margin: 0 auto 1rem;
        color: rgba(255,255,255,0.92);
    }
    .hero-meta {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 1rem;
        margin-top: 1rem;
        font-size: 0.95rem;
        color: rgba(255,255,255,0.85);
    }
    .hero-meta span {
        background: rgba(255,255,255,0.14);
        padding: 0.7rem 1rem;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,0.12);
    }
    .hero-credit {
        margin-top: 1rem;
        font-size: 0.78rem;
        opacity: 0.82;
    }

    /* ── PETA ── */
    #map {
        height: 680px;
        border-radius: 18px;
        border: 2px solid var(--border);
        box-shadow: 0 4px 24px rgba(27,94,32,0.18);
    }
    .map-panel { position: relative; }
    .map-panel:fullscreen #map,
    .map-panel:-webkit-full-screen #map {
        height: 100vh;
        width: 100vw;
        border-radius: 0;
    }

    /* ── FULLSCREEN BUTTON ── */
    .fullscreen-toggle {
        position: absolute;
        top: 16px;
        left: 16px;
        z-index: 1100;
        border: none;
        border-radius: 12px;
        background: rgba(27,94,32,0.92);
        color: #fff;
        font-weight: 700;
        font-size: 0.82rem;
        padding: 9px 13px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.2);
        cursor: pointer;
        transition: transform 0.2s, background 0.2s;
    }
    .fullscreen-toggle:hover {
        transform: translateY(-1px);
        background: rgba(27,94,32,1);
    }

    /* ── LEGENDA di dalam peta ── */
    .map-legend {
        position: absolute;
        bottom: 36px;
        left: 16px;
        z-index: 1100;
        background: rgba(255,255,255,0.97);
        border-radius: 14px;
        border: 1.5px solid rgba(27,94,32,0.18);
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        min-width: 210px;
        overflow: hidden;
    }
    .legend-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: linear-gradient(135deg, #1B5E20, #2E7D32);
        color: #fff;
        padding: 9px 14px;
        cursor: pointer;
        user-select: none;
        font-size: 0.85rem;
        font-weight: 700;
    }
    .legend-header:hover { background: linear-gradient(135deg, #145214, #256325); }
    .legend-chevron {
        transition: transform 0.3s ease;
        font-size: 0.8rem;
    }
    .legend-chevron.collapsed { transform: rotate(-90deg); }
    .legend-body {
        overflow: hidden;
        max-height: 500px;
        padding: 12px 14px;
        transition: max-height 0.3s ease, padding 0.3s ease;
    }
    .legend-body.hidden {
        max-height: 0 !important;
        padding: 0 14px !important;
    }
    .legend-section-title {
        font-size: 0.72rem;
        font-weight: 700;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin: 8px 0 6px;
        padding-bottom: 4px;
        border-bottom: 1px solid #eee;
    }
    .legend-section-title:first-child { margin-top: 0; }
    .legend-row {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 6px;
        font-size: 0.82rem;
        color: #2d4a2d;
        line-height: 1.3;
    }
    .legend-row:last-child { margin-bottom: 0; }
    .ls       { width:20px; height:20px; flex-shrink:0; border-radius:50%; border:1.5px solid rgba(0,0,0,0.15); }
    .ls-line  { width:20px; height:4px;  flex-shrink:0; border-radius:2px; }
    .ls-poly  { width:20px; height:14px; flex-shrink:0; border-radius:3px; }
    .ls-wms   { width:20px; height:14px; flex-shrink:0; border-radius:3px; border:1.5px dashed; }
    .ls-batas { width:20px; height:0;    flex-shrink:0; border-top:2.5px dashed #1B5E20; }

    /* ── LAYER CONTROL COLLAPSIBLE ── */
    .leaflet-control-layers {
        border-radius: 12px !important;
        border: 1.5px solid rgba(27,94,32,0.2) !important;
        box-shadow: 0 4px 20px rgba(0,0,0,0.12) !important;
        overflow: hidden !important;
        min-width: 200px !important;
        padding: 0 !important;
    }
    .leaflet-control-layers-toggle { display: none !important; }
    .layer-control-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: linear-gradient(135deg, #1B5E20, #2E7D32);
        color: #fff;
        padding: 9px 14px;
        cursor: pointer;
        user-select: none;
        font-size: 0.85rem;
        font-weight: 700;
    }
    .layer-control-header:hover { background: linear-gradient(135deg, #145214, #256325); }
    .layer-chevron {
        transition: transform 0.3s ease;
        font-size: 0.8rem;
    }
    .layer-chevron.collapsed { transform: rotate(-90deg); }
    .leaflet-control-layers-list {
        overflow: hidden !important;
        max-height: 500px !important;
        padding: 10px 14px !important;
        background: rgba(255,255,255,0.97) !important;
        transition: max-height 0.3s ease, padding 0.3s ease !important;
    }
    .leaflet-control-layers-list.hidden {
        max-height: 0 !important;
        padding: 0 14px !important;
    }
    .leaflet-control-layers-separator {
        border-top: 1px solid #e0e0e0 !important;
        margin: 6px 0 !important;
    }
    .leaflet-control-layers-base label,
    .leaflet-control-layers-overlays label {
        font-size: 0.83rem !important;
        color: #2d4a2d !important;
        padding: 3px 0 !important;
        display: flex !important;
        align-items: center !important;
        gap: 6px !important;
        cursor: pointer !important;
    }
    .leaflet-control-layers-base label:hover,
    .leaflet-control-layers-overlays label:hover { color: #1B5E20 !important; }

    /* ── STATS ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        text-align: center;
        margin-top: 1rem;
    }
    .stats-grid > div {
        background: var(--bg);
        border-radius: 10px;
        padding: 10px 6px;
        border: 1px solid var(--border);
    }
    .stat-value {
        display: block;
        font-size: 1.7rem;
        font-weight: 800;
        color: var(--primary);
        line-height: 1;
    }
    .stat-label { font-size: 0.75rem; color: var(--text-muted); margin-top: 4px; }

    /* ── MAP HELP ── */
    .map-help {
        background: rgba(255,255,255,0.96);
        border-radius: 12px;
        border: 1px solid rgba(27,94,32,0.14);
        padding: 12px 14px;
        color: #325333;
        font-size: 0.87rem;
        line-height: 1.55;
    }

    /* ── SCIENCE CARDS ── */
    .science-card {
        background: linear-gradient(135deg, rgba(26,74,35,0.98), rgba(42,125,60,0.96));
        color: #fff;
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        border: 1px solid rgba(255,255,255,0.12);
        height: 100%;
    }
    .science-card h6 { margin-bottom: .9rem; font-size: 1rem; font-weight: 700; }
    .science-card p  { margin-bottom: .9rem; color: rgba(255,255,255,0.82); line-height: 1.65; font-size: 0.88rem; }
    .science-card ul { list-style: none; padding: 0; margin: 0; color: rgba(255,255,255,0.82); font-size: 0.86rem; }
    .science-card ul li { margin-bottom: .5rem; padding-left: 1.1rem; position: relative; line-height: 1.5; }
    .science-card ul li::before { content: '•'; position: absolute; left: 0; color: #A5D6A7; font-size: 1.1rem; line-height: 1; }
    .science-card strong { color: #fff; }

    /* ── TOOLTIP KECAMATAN ── */
    .tooltip-kecamatan {
        background: rgba(27,94,32,0.88) !important;
        border: none !important;
        color: #fff !important;
        font-size: 0.72rem !important;
        font-weight: 600 !important;
        padding: 3px 8px !important;
        border-radius: 6px !important;
        box-shadow: none !important;
    }
    .tooltip-kecamatan::before { display: none !important; }

    /* MarkerCluster custom colors */
    .marker-cluster-small { background-color: rgba(76,175,80,0.92); }
    .marker-cluster-medium { background-color: rgba(255,193,7,0.94); }
    .marker-cluster-large { background-color: rgba(244,67,54,0.94); }
    .marker-cluster div { box-shadow: 0 2px 8px rgba(0,0,0,0.25); color: #fff; font-weight: 800; }
</style>
@endsection

@section('content')

{{-- HERO --}}
<div class="hero-banner">
    <img src="https://upload.wikimedia.org/wikipedia/commons/e/e4/Alun-Alun_Sragen_%283%29.jpg"
         alt="Alun-Alun Sragen" loading="lazy">
    <div class="hero-overlay"></div>
    <div class="hero-copy">
        <span class="hero-badge">
            <i class="fas fa-satellite-dish"></i>
            Kabupaten Sragen • Geospatial Monitoring
        </span>
        <h1>Peta Data Perubahan Lahan Sragen</h1>
        <p>Visualisasi titik, jalur, dan area perubahan lahan dengan layer interaktif, batas kecamatan, basemap satelit, dan WMS GeoServer.</p>
        <div class="hero-meta">
            <span><strong>Area:</strong> 941.55 km²</span>
            <span><strong>Kecamatan:</strong> 20</span>
            <span><strong>Populasi:</strong> ~1.02 juta</span>
        </div>
        <p class="hero-credit">Foto: Nerissa Lyra / Wikimedia Commons (CC BY-SA 4.0)</p>
    </div>
</div>

{{-- PAGE HEADER --}}
<div class="page-header">
    <div class="header-icon"><i class="fas fa-globe-americas"></i></div>
    <div>
        <h4>Peta Seluruh Data SragenLandWatch</h4>
        <p>Semua titik, jalur, area, batas kecamatan, dan layer WMS GeoServer dalam satu tampilan.</p>
    </div>
</div>

<div class="row g-4">

    {{-- PETA --}}
    <div class="col-lg-8">
        <div class="map-panel position-relative">
            <div id="map"></div>

            {{-- Fullscreen --}}
            <button type="button" class="fullscreen-toggle" id="btnFullscreen">
                <i class="fas fa-expand"></i> Layar Penuh
            </button>

            {{-- LEGENDA --}}
            <div class="map-legend" id="mapLegend">
                <div class="legend-header" id="legendToggle">
                    <span><i class="fas fa-layer-group me-2"></i>Legenda</span>
                    <i class="fas fa-chevron-down legend-chevron" id="legendChevron"></i>
                </div>
                <div class="legend-body" id="legendBody">
                    <div class="legend-section-title">Layer GeoJSON</div>
                    <div class="legend-row">
                        <span class="ls" style="background:#4CAF50;"></span> Titik Lokasi
                    </div>
                    <div class="legend-row">
                        <span class="ls-line" style="background:#E65100;"></span> Jalur Perubahan
                    </div>
                    <div class="legend-row">
                        <span class="ls-poly" style="background:#A5D6A7;border:2px solid #2E7D32;"></span> Area Perubahan
                    </div>
                    <div class="legend-section-title">Batas Wilayah</div>
                    <div class="legend-row">
                        <span class="ls-batas"></span> Batas Kecamatan
                    </div>
                    <div class="legend-section-title">WMS GeoServer</div>
                    <div class="legend-row">
                        <span class="ls-wms" style="background:rgba(21,101,192,0.15);border-color:#1565C0;"></span> WMS – Titik / Jalur / Area
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SIDEBAR --}}
    <div class="col-lg-4">
        <div class="sidebar-card mb-3">
            <h5><i class="fas fa-chart-bar me-2"></i>Jumlah Data</h5>
            <hr>
            <div class="stats-grid">
                <div>
                    <span class="stat-value" id="countPoints">-</span>
                    <div class="stat-label">Titik</div>
                </div>
                <div>
                    <span class="stat-value" id="countPolylines">-</span>
                    <div class="stat-label">Jalur</div>
                </div>
                <div>
                    <span class="stat-value" id="countPolygons">-</span>
                    <div class="stat-label">Polygon</div>
                </div>
            </div>
        </div>

        <div class="map-help mb-3">
            <i class="fas fa-info-circle me-1 text-success"></i>
            <strong>Cara pakai:</strong>
            <ul style="margin:8px 0 0;padding-left:1.2rem;font-size:0.85rem;line-height:1.9;">
                <li>Klik fitur di peta untuk popup detail</li>
                <li>Klik header <strong>Layer Control</strong> untuk sembunyikan daftar layer</li>
                <li>Klik header <strong>Legenda</strong> untuk sembunyikan legenda</li>
                <li>Ganti basemap: OSM / Satelit / Topografi</li>
                <li>Batas kecamatan tampil otomatis dengan label</li>
            </ul>
        </div>
    </div>
</div>

{{-- SCIENCE CARDS --}}
<div class="row g-4 mt-2">
    <div class="col-lg-4">
        <div class="science-card">
            <h6><i class="fas fa-database me-2"></i>Data Spasial</h6>
            <p>Analisis spasial Sragen mencakup titik lokasi, jalur infrastruktur, dan area perubahan lahan yang direkam menggunakan PostGIS.</p>
            <ul>
                <li><strong>Format:</strong> GeoJSON & PostGIS Geometry</li>
                <li><strong>Proyeksi:</strong> EPSG:4326 (WGS84)</li>
                <li><strong>Sumber:</strong> Input lapangan + GeoServer WMS</li>
            </ul>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="science-card">
            <h6><i class="fas fa-layer-group me-2"></i>Metodologi</h6>
            <p>Peta menggabungkan layer GeoJSON interaktif (popup & foto) dan layer WMS dari GeoServer untuk visualisasi tematik.</p>
            <ul>
                <li>Layer GeoJSON — interaktif, popup & foto</li>
                <li>Layer WMS GeoServer — render dari server</li>
                <li>Batas kecamatan dari OpenStreetMap</li>
            </ul>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="science-card">
            <h6><i class="fas fa-map-marked-alt me-2"></i>Fakta Sragen</h6>
            <p>Kabupaten Sragen di Jawa Tengah dengan 20 kecamatan, luas 941.55 km², dan situs purbakala Sangiran (UNESCO).</p>
            <ul>
                <li><strong>Populasi:</strong> ~1.02 juta jiwa (2024)</li>
                <li><strong>Ibu kota:</strong> Sragen</li>
                <li><strong>Ikon:</strong> Situs Purbakala Sangiran</li>
            </ul>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster-src.js"></script>
<script>
// ── INISIALISASI PETA ─────────────────────────────────────────────────────────
var map = L.map('map', { zoomControl: false }).setView([-7.4375, 111.0142], 11);

// ── BASEMAPS ──────────────────────────────────────────────────────────────────
var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors', maxZoom: 19
}).addTo(map);

var satelliteLayer = L.tileLayer(
    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
    attribution: 'Tiles &copy; Esri', maxZoom: 19
});

var topoLayer = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
    attribution: 'Map data: &copy; OpenStreetMap | Map style: &copy; OpenTopoMap', maxZoom: 17
});

var baseMaps = {
    'OpenStreetMap': osmLayer,
    'Satelit Esri':  satelliteLayer,
    'Topografi':     topoLayer
};

// ── LAYER GROUPS ──────────────────────────────────────────────────────────────
var layerPoints    = L.markerClusterGroup({ chunkedLoading: true, spiderfyOnMaxZoom: true, showCoverageOnHover: false });
var layerPolylines = L.layerGroup();
var layerPolygons  = L.layerGroup();
var layerBatas     = L.layerGroup();

// ── WMS GEOSERVER ─────────────────────────────────────────────────────────────
var wmsUrl = 'http://localhost:8080/geoserver/sragenlandwatch/wms';

var wmsPoints = L.tileLayer.wms(wmsUrl, {
    layers: 'sragenlandwatch:points', format: 'image/png',
    transparent: true, version: '1.1.0', attribution: 'GeoServer WMS'
});
var wmsPolylines = L.tileLayer.wms(wmsUrl, {
    layers: 'sragenlandwatch:polylines', format: 'image/png',
    transparent: true, version: '1.1.0', attribution: 'GeoServer WMS'
});
var wmsPolygons = L.tileLayer.wms(wmsUrl, {
    layers: 'sragenlandwatch:polygons', format: 'image/png',
    transparent: true, version: '1.1.0', attribution: 'GeoServer WMS'
});

// ── OVERLAY CONTROL ───────────────────────────────────────────────────────────
var overlayMaps = {
    'Titik Lokasi (GeoJSON)':    layerPoints,
    'Jalur Perubahan (GeoJSON)': layerPolylines,
    'Area Perubahan (GeoJSON)':  layerPolygons,
    'Batas Kecamatan':           layerBatas,
    'WMS – Titik':               wmsPoints,
    'WMS – Jalur':               wmsPolylines,
    'WMS – Area':                wmsPolygons
};

L.control.layers(baseMaps, overlayMaps, {
    collapsed: false, position: 'topright'
}).addTo(map);

L.control.zoom({ position: 'topright' }).addTo(map);
L.control.scale({ position: 'bottomleft', imperial: false }).addTo(map);

// ── LAYER CONTROL COLLAPSIBLE ─────────────────────────────────────────────────
setTimeout(function() {
    var layerControlEl = document.querySelector('.leaflet-control-layers');
    var layerList      = document.querySelector('.leaflet-control-layers-list');

    if (!layerControlEl || !layerList) return;

    var header = document.createElement('div');
    header.className = 'layer-control-header';
    header.innerHTML = '<span><i class="fas fa-sliders-h me-2"></i>Layer Control</span>'
                     + '<i class="fas fa-chevron-down layer-chevron" id="layerChevron"></i>';
    layerControlEl.insertBefore(header, layerList);

    var layerVisible = true;

    header.addEventListener('click', function() {
        layerVisible = !layerVisible;
        var chevron = document.getElementById('layerChevron');
        if (layerVisible) {
            layerList.classList.remove('hidden');
            chevron.classList.remove('collapsed');
        } else {
            layerList.classList.add('hidden');
            chevron.classList.add('collapsed');
        }
    });
}, 300);

// ── LEGENDA TOGGLE ────────────────────────────────────────────────────────────
var legendBody    = document.getElementById('legendBody');
var legendChevron = document.getElementById('legendChevron');
var legendVisible = true;

legendBody.style.maxHeight = legendBody.scrollHeight + 'px';

document.getElementById('legendToggle').addEventListener('click', function() {
    legendVisible = !legendVisible;
    if (legendVisible) {
        legendBody.classList.remove('hidden');
        legendChevron.classList.remove('collapsed');
    } else {
        legendBody.classList.add('hidden');
        legendChevron.classList.add('collapsed');
    }
});

// ── FULLSCREEN ────────────────────────────────────────────────────────────────
document.getElementById('btnFullscreen').addEventListener('click', function() {
    var panel = document.getElementById('map').parentNode;
    if (!document.fullscreenElement) {
        panel.requestFullscreen();
    } else {
        document.exitFullscreen();
    }
});

document.addEventListener('fullscreenchange', function() {
    var btn   = document.getElementById('btnFullscreen');
    var panel = document.getElementById('map').parentNode;
    if (document.fullscreenElement === panel) {
        btn.innerHTML = '<i class="fas fa-compress"></i> Keluar Layar Penuh';
    } else {
        btn.innerHTML = '<i class="fas fa-expand"></i> Layar Penuh';
    }
    setTimeout(function() { map.invalidateSize(); }, 300);
});

// ── BATAS KECAMATAN ───────────────────────────────────────────────────────────
fetch('https://raw.githubusercontent.com/ans-4175/peta-indonesia-geojson/master/jawa-tengah.geojson')
    .then(function(r) {
        if (!r.ok) throw new Error('GitHub gagal');
        return r.json();
    })
    .then(function(data) {
        var fiturSragen = data.features.filter(function(f) {
            var nama = (
                f.properties.district ||
                f.properties.name ||
                f.properties.KABUPATEN || ''
            ).toLowerCase();
            return nama.includes('sragen');
        });

        if (fiturSragen.length === 0) throw new Error('Fitur Sragen tidak ditemukan');

        L.geoJSON({ type: 'FeatureCollection', features: fiturSragen }, {
            style: {
                color: '#1B5E20', weight: 2,
                fillColor: '#C8E6C9', fillOpacity: 0.12,
                dashArray: '6, 4'
            },
            onEachFeature: function(feature, layer) {
                var nama = feature.properties.district
                        || feature.properties.name
                        || feature.properties.KECAMATAN
                        || 'Kecamatan';
                layer.bindTooltip(nama, {
                    permanent: true,
                    direction: 'center',
                    className: 'tooltip-kecamatan'
                });
                layer.bindPopup(
                    '<div class="popup-title"><i class="fas fa-map me-1 text-success"></i>' + nama + '</div>' +
                    '<div class="popup-row"><span class="popup-label">Kabupaten</span><span>Sragen</span></div>' +
                    '<div class="popup-row"><span class="popup-label">Provinsi</span><span>Jawa Tengah</span></div>'
                );
            }
        }).addTo(layerBatas);

        layerBatas.addTo(map);
    })
    .catch(function() {
        // Fallback: Nominatim OSM
        fetch('https://nominatim.openstreetmap.org/search?q=Kabupaten+Sragen+Jawa+Tengah&format=geojson&polygon_geojson=1&limit=1')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (!data.features || data.features.length === 0) return;
                L.geoJSON(data, {
                    style: {
                        color: '#1B5E20', weight: 2.5,
                        fillColor: '#C8E6C9', fillOpacity: 0.1,
                        dashArray: '6, 4'
                    }
                }).bindTooltip('Kabupaten Sragen', {
                    permanent: false, sticky: true,
                    className: 'tooltip-kecamatan'
                }).addTo(layerBatas);
                layerBatas.addTo(map);
            })
            .catch(function(err) {
                console.warn('Semua sumber batas wilayah gagal:', err);
            });
    });

// ── POPUP HELPER ──────────────────────────────────────────────────────────────
function createPopupHtml(type, props) {
    var html = '<div style="min-width:220px">';

    if (type === 'point') {
        html += '<div class="popup-title"><i class="fas fa-map-marker-alt text-success me-1"></i>' + props.nama_lokasi + '</div>';
        html += '<div class="popup-row"><span class="popup-label">Kecamatan</span><span>' + props.kecamatan + '</span></div>';
        html += '<div class="popup-row"><span class="popup-label">Perubahan</span><span>'
            + '<span class="badge badge-lama">' + props.penggunaan_lama + '</span>'
            + ' <i class="fas fa-arrow-right arrow-change mx-1"></i> '
            + '<span class="badge badge-baru">' + props.penggunaan_baru + '</span>'
            + '</span></div>';
        html += '<div class="popup-row"><span class="popup-label">Tahun</span><span>' + props.tahun_perubahan + '</span></div>';
        if (props.keterangan)
            html += '<div class="popup-row"><span class="popup-label">Keterangan</span><span>' + props.keterangan + '</span></div>';
        if (props.foto)
            html += '<img src="' + props.foto + '" class="img-fluid rounded mt-2" style="max-height:120px;width:100%;object-fit:cover">';
    }

    if (type === 'polyline') {
        html += '<div class="popup-title"><i class="fas fa-route text-warning me-1"></i>' + props.nama_jalur + '</div>';
        html += '<div class="popup-row"><span class="popup-label">Kecamatan</span><span>' + props.kecamatan + '</span></div>';
        html += '<div class="popup-row"><span class="popup-label">Jenis</span><span>' + props.jenis_perubahan + '</span></div>';
        html += '<div class="popup-row"><span class="popup-label">Panjang</span><span>'
            + (props.panjang_meter ? props.panjang_meter + ' m' : '-') + '</span></div>';
        html += '<div class="popup-row"><span class="popup-label">Tahun</span><span>' + props.tahun_perubahan + '</span></div>';
        if (props.keterangan)
            html += '<div class="popup-row"><span class="popup-label">Keterangan</span><span>' + props.keterangan + '</span></div>';
    }

    if (type === 'polygon') {
        html += '<div class="popup-title"><i class="fas fa-draw-polygon text-primary me-1"></i>' + props.nama_area + '</div>';
        html += '<div class="popup-row"><span class="popup-label">Kecamatan</span><span>' + props.kecamatan + '</span></div>';
        html += '<div class="popup-row"><span class="popup-label">Perubahan</span><span>'
            + '<span class="badge badge-lama">' + props.penggunaan_lama + '</span>'
            + ' <i class="fas fa-arrow-right arrow-change mx-1"></i> '
            + '<span class="badge badge-baru">' + props.penggunaan_baru + '</span>'
            + '</span></div>';
        html += '<div class="popup-row"><span class="popup-label">Luas</span><span>'
            + (props.luas_ha ? parseFloat(props.luas_ha).toFixed(2) + ' Ha' : '-') + '</span></div>';
        html += '<div class="popup-row"><span class="popup-label">Tahun</span><span>' + props.tahun_perubahan + '</span></div>';
        if (props.keterangan)
            html += '<div class="popup-row"><span class="popup-label">Keterangan</span><span>' + props.keterangan + '</span></div>';
        if (props.foto)
            html += '<img src="' + props.foto + '" class="img-fluid rounded mt-2" style="max-height:120px;width:100%;object-fit:cover">';
    }

    html += '</div>';
    return html;
}

// ── STYLE HELPERS ─────────────────────────────────────────────────────────────
var pointIcon = L.icon({
    iconUrl:   'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
    iconSize:  [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34]
});

function getPolylineStyle() {
    return { color: '#E65100', weight: 4, opacity: 0.85 };
}

function getPolygonStyle() {
    return { color: '#2E7D32', weight: 2, fillColor: '#66BB6A', fillOpacity: 0.35 };
}

// ── LOAD DATA GEOJSON ─────────────────────────────────────────────────────────
var countPoints    = 0;
var countPolylines = 0;
var countPolygons  = 0;

Promise.all([
    fetch('/api/points').then(function(r)    { return r.json(); }),
    fetch('/api/polylines').then(function(r) { return r.json(); }),
    fetch('/api/polygons').then(function(r)  { return r.json(); })
]).then(function(results) {
    var pointsData    = results[0];
    var polylinesData = results[1];
    var polygonsData  = results[2];

    L.geoJSON(pointsData, {
        pointToLayer: function(feature, latlng) {
            return L.marker(latlng, { icon: pointIcon });
        },
        onEachFeature: function(feature, layer) {
            layer.bindPopup(createPopupHtml('point', feature.properties));
            countPoints++;
        }
    }).addTo(layerPoints);

    L.geoJSON(polylinesData, {
        style: getPolylineStyle,
        onEachFeature: function(feature, layer) {
            layer.bindPopup(createPopupHtml('polyline', feature.properties));
            countPolylines++;
        }
    }).addTo(layerPolylines);

    L.geoJSON(polygonsData, {
        style: getPolygonStyle,
        onEachFeature: function(feature, layer) {
            layer.bindPopup(createPopupHtml('polygon', feature.properties));
            countPolygons++;
        }
    }).addTo(layerPolygons);

    layerPoints.addTo(map);
    layerPolylines.addTo(map);
    layerPolygons.addTo(map);

    document.getElementById('countPoints').textContent    = countPoints;
    document.getElementById('countPolylines').textContent = countPolylines;
    document.getElementById('countPolygons').textContent  = countPolygons;

    var allGroup = L.featureGroup([layerPoints, layerPolylines, layerPolygons]);
    var bounds   = allGroup.getBounds();
    if (bounds.isValid()) {
        map.fitBounds(bounds.pad(0.12));
    }

}).catch(function(error) {
    console.error('Gagal memuat data peta:', error);
});
</script>
@endsection

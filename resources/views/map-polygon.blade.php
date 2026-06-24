@extends('layouts.app')
@section('title', 'Area Perubahan Lahan')

@section('styles')
<style>
    /* ── LEGENDA di dalam peta ── */
    .map-panel { position: relative; }
    .map-legend {
        position: absolute;
        bottom: 16px;
        left: 16px;
        z-index: 1100;
        background: rgba(255,255,255,0.97);
        border-radius: 14px;
        border: 1.5px solid rgba(27,94,32,0.18);
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        min-width: 200px;
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
    .ls-poly  { width:20px; height:14px; flex-shrink:0; border-radius:3px; }
    .ls-batas { width:20px; height:0;    flex-shrink:0; border-top:2.5px dashed #1B5E20; }

    /* ── LAYER CONTROL COLLAPSIBLE ── */
    .leaflet-control-layers {
        border-radius: 12px !important;
        border: 1.5px solid rgba(27,94,32,0.2) !important;
        box-shadow: 0 4px 20px rgba(0,0,0,0.12) !important;
        overflow: hidden !important;
        min-width: 190px !important;
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
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="header-icon"><i class="fas fa-draw-polygon"></i></div>
    <div>
        <h4>Peta Area Perubahan Lahan (Polygon)</h4>
        <p>Gambar batas area perubahan lahan menggunakan tool polygon. Luas dihitung otomatis.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="map-panel position-relative">
            <div id="map"></div>

            {{-- LEGENDA --}}
            <div class="map-legend" id="mapLegend">
                <div class="legend-header" id="legendToggle">
                    <span><i class="fas fa-layer-group me-2"></i>Legenda</span>
                    <i class="fas fa-chevron-down legend-chevron" id="legendChevron"></i>
                </div>
                <div class="legend-body" id="legendBody">
                    <div class="legend-row">
                        <span class="ls-poly" style="background:#A5D6A7;border:2px solid #2E7D32;"></span> Area Perubahan
                    </div>
                    <div class="legend-row">
                        <span class="ls-batas"></span> Batas Kecamatan
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <span style="font-size:0.8rem;font-weight:600;color:var(--text-muted);">
                <i class="fas fa-pencil-alt me-1"></i>
                Gunakan tombol polygon di sudut kiri atas peta untuk menggambar area. Klik ganda untuk selesai.
            </span>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="sidebar-card">
            <h5><i class="fas fa-plus-circle me-2"></i>Tambah Area Baru</h5>
            <hr>

            <form action="/polygons" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama Area <span class="text-danger">*</span></label>
                    <input type="text" name="nama_area" class="form-control" required
                           value="{{ old('nama_area') }}" placeholder="Contoh: Lahan Sawah Masaran Barat">
                </div>

                <div class="mb-3">
                    <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                    <select name="kecamatan" class="form-select" required>
                        <option value="">-- Pilih Kecamatan --</option>
                        @foreach(['Masaran','Sambungmacan','Gondang','Sragen','Karangmalang',
                                  'Sidoharjo','Tanon','Gemolong','Miri','Sumberlawang',
                                  'Mondokan','Sukodono','Gesi','Tangen','Jenar',
                                  'Plupuh','Ngrampal','Kalijambe','Sambirejo','Kedawung'] as $kec)
                        <option value="{{ $kec }}" {{ old('kecamatan') == $kec ? 'selected' : '' }}>{{ $kec }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kategori Area <span class="text-danger">*</span></label>
                    <select name="kategori_objek" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option>Permukiman</option>
                        <option>Sawah</option>
                        <option>Lahan Terbuka</option>
                        <option>Kebun/Perkebunan</option>
                        <option>Hutan</option>
                        <option>Tegalan/Ladang</option>
                        <option>Kawasan Industri</option>
                        <option>Tambak/Kolam</option>
                        <option>Lainnya</option>
                    </select>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label">Lahan Sebelumnya <span class="text-danger">*</span></label>
                        <select name="penggunaan_lama" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option>Sawah Irigasi</option>
                            <option>Sawah Tadah Hujan</option>
                            <option>Hutan</option>
                            <option>Kebun/Perkebunan</option>
                            <option>Ladang/Tegalan</option>
                            <option>Semak Belukar</option>
                            <option>Lahan Terbuka</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Kondisi Sekarang <span class="text-danger">*</span></label>
                        <select name="penggunaan_baru" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option>Permukiman</option>
                            <option>Sawah</option>
                            <option>Lahan Terbuka</option>
                            <option>Kebun/Perkebunan</option>
                            <option>Hutan</option>
                            <option>Tegalan/Ladang</option>
                            <option>Kawasan Industri</option>
                            <option>Tambak/Kolam</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tahun Perubahan <span class="text-danger">*</span></label>
                    <input type="number" name="tahun_perubahan" class="form-control"
                           min="1990" max="2030" required
                           value="{{ old('tahun_perubahan', date('Y')) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Foto Lokasi</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                    <div style="font-size:0.78rem;color:var(--text-muted);margin-top:4px;">Maks 2MB</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="2"
                              placeholder="Deskripsi singkat...">{{ old('keterangan') }}</textarea>
                </div>

                <input type="hidden" name="geojson" id="geojson">

                <!-- Info gambar area -->
                <div class="klik-info mb-3" id="info-gambar">
                    <i class="fas fa-pencil-alt me-1"></i>
                    Gambar polygon di peta terlebih dahulu
                </div>

                <!-- Preview luas -->
                <div id="preview-luas" style="display:none;"
                     class="alert alert-info py-2 text-center mb-3">
                    <i class="fas fa-ruler-combined me-1"></i>
                    Luas estimasi: <strong id="luas-text"></strong>
                </div>

                <button type="submit" class="btn btn-success-custom w-100" id="btnSimpan" disabled>
                    <i class="fas fa-save me-2"></i>Simpan Area
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
var map = L.map('map').setView([-7.4258, 111.0149], 11);

var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors', maxZoom: 19
}).addTo(map);

var satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
    attribution: 'Tiles &copy; Esri'
});

// ─── Layer Batas Kecamatan ────────────────────────────────────────────────────
var layerBatas = L.layerGroup();

var layersControl = L.control.layers(
    { 'OpenStreetMap': osm, 'Satelit': satellite },
    { 'Batas Kecamatan': layerBatas }
).addTo(map);

// ─── LAYER CONTROL COLLAPSIBLE ────────────────────────────────────────────────
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

// ─── LEGENDA TOGGLE ───────────────────────────────────────────────────────────
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

// ─── BATAS KECAMATAN (GitHub dulu, fallback Nominatim) ───────────────────────
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
                fillColor: '#C8E6C9', fillOpacity: 0.08,
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
                        fillColor: '#C8E6C9', fillOpacity: 0.08,
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

// ─── Leaflet Draw ────────────────────────────────────────────────────────────
var drawnItems = new L.FeatureGroup().addTo(map);

var drawControl = new L.Control.Draw({
    draw: {
        polygon:   { shapeOptions: { color: '#388E3C', fillColor: '#66BB6A', fillOpacity: 0.3 } },
        rectangle: { shapeOptions: { color: '#388E3C', fillColor: '#66BB6A', fillOpacity: 0.3 } },
        polyline:  false,
        circle:    false,
        marker:    false,
        circlemarker: false,
    },
    edit: { featureGroup: drawnItems }
});
map.addControl(drawControl);

map.on(L.Draw.Event.CREATED, function (e) {
    drawnItems.clearLayers();
    drawnItems.addLayer(e.layer);

    var geoJson = e.layer.toGeoJSON().geometry;
    var geoJsonStr = JSON.stringify(geoJson);
    document.getElementById('geojson').value = geoJsonStr;

    // Estimasi luas sederhana (client-side, PostGIS yang akurat)
    var latLngs = e.layer.getLatLngs()[0];
    var areaM2  = L.GeometryUtil ? 0 : 0; // fallback tanpa plugin
    var approxHa = (latLngs.length > 2) ? 'Dihitung server (PostGIS)' : '-';

    document.getElementById('info-gambar').className  = 'klik-info selected mb-3';
    document.getElementById('info-gambar').innerHTML  =
        '<i class="fas fa-check-circle me-1"></i>Area berhasil digambar (' + latLngs.length + ' titik)';

    document.getElementById('preview-luas').style.display = '';
    document.getElementById('luas-text').textContent       = 'akan dihitung PostGIS (hektar)';
    document.getElementById('btnSimpan').disabled          = false;
});

map.on(L.Draw.Event.DELETED, function () {
    document.getElementById('geojson').value           = '';
    document.getElementById('btnSimpan').disabled      = true;
    document.getElementById('preview-luas').style.display = 'none';
    document.getElementById('info-gambar').className   = 'klik-info mb-3';
    document.getElementById('info-gambar').innerHTML   =
        '<i class="fas fa-pencil-alt me-1"></i>Gambar polygon di peta terlebih dahulu';
});

// ─── Warna berdasarkan kategori area ─────────────────────────────────────────
function getPolygonStyle(kategori) {
    var colors = {
        'Permukiman':        { color: '#E65100', fillColor: '#FFB74D' },
        'Sawah':              { color: '#2E7D32', fillColor: '#A5D6A7' },
        'Lahan Terbuka':       { color: '#757575', fillColor: '#E0E0E0' },
        'Kebun/Perkebunan':    { color: '#558B2F', fillColor: '#C5E1A5' },
        'Hutan':               { color: '#1B5E20', fillColor: '#81C784' },
        'Tegalan/Ladang':      { color: '#8D6E63', fillColor: '#D7CCC8' },
        'Kawasan Industri':    { color: '#5D4037', fillColor: '#BCAAA4' },
        'Tambak/Kolam':        { color: '#0277BD', fillColor: '#81D4FA' },
    };
    return colors[kategori] || { color: '#2E7D32', fillColor: '#66BB6A' };
}

// ─── Load existing polygons ──────────────────────────────────────────────────
var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

fetch('/api/polygons')
    .then(r => r.json())
    .then(data => {
        L.geoJSON(data, {
            style: function (feature) {
                var s = getPolygonStyle(feature.properties.kategori_objek);
                return { color: s.color, fillColor: s.fillColor, fillOpacity: 0.4, weight: 2 };
            },
            onEachFeature: function (feature, layer) {
                var p    = feature.properties;
                var foto = p.foto
                    ? '<img src="' + p.foto + '" class="img-fluid rounded mt-2" style="max-height:100px;width:100%;object-fit:cover">'
                    : '';
                layer.bindPopup(
                    '<div style="min-width:220px">' +
                    '<div class="popup-title"><i class="fas fa-draw-polygon text-success me-1"></i>' + p.nama_area + '</div>' +
                    '<div class="popup-row"><span class="popup-label">Kecamatan</span><span>' + p.kecamatan + '</span></div>' +
                    '<div class="popup-row"><span class="popup-label">Kategori</span><span class="badge badge-baru">' + p.kategori_objek + '</span></div>' +
                    '<div class="popup-row"><span class="popup-label">Perubahan</span>' +
                        '<span><span class="badge badge-lama">' + p.penggunaan_lama + '</span> → ' +
                        '<span class="badge badge-baru">' + p.penggunaan_baru + '</span></span></div>' +
                    '<div class="popup-row"><span class="popup-label">Luas</span><span>' + (p.luas_ha ? parseFloat(p.luas_ha).toFixed(2) + ' Ha' : '-') + '</span></div>' +
                    '<div class="popup-row"><span class="popup-label">Tahun</span><span>' + p.tahun_perubahan + '</span></div>' +
                    foto +
                    '<div class="popup-actions">' +
                    '<a href="/polygons/' + p.id + '/edit" class="btn btn-sm btn-warning-custom"><i class="fas fa-edit"></i> Edit</a>' +
                    '<form action="/polygons/' + p.id + '" method="POST" class="d-inline" onsubmit="return confirm(\'Yakin hapus area ini?\')">' +
                    '<input type="hidden" name="_token" value="' + csrfToken + '">' +
                    '<input type="hidden" name="_method" value="DELETE">' +
                    '<button class="btn btn-sm btn-danger-custom ms-1"><i class="fas fa-trash"></i> Hapus</button>' +
                    '</form></div></div>'
                );
            }
        }).addTo(map);
    })
    .catch(err => console.error('Gagal load data polygon:', err));
</script>
@endsection

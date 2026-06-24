@extends('layouts.app')
@section('title', 'Jalur Perubahan Lahan')

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
    .ls-line  { width:20px; height:4px;  flex-shrink:0; border-radius:2px; }
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
    <div class="header-icon"><i class="fas fa-route"></i></div>
    <div>
        <h4>Peta Jalur Perubahan Lahan (Polyline)</h4>
        <p>Gambar jalur/koridor perubahan lahan menggunakan tool polyline. Panjang dihitung otomatis.</p>
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
                        <span class="ls-line" style="background:#E65100;"></span> Jalur Perubahan
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
                Gunakan tombol garis di sudut kiri atas peta. Klik titik-titik jalur, klik ganda untuk selesai.
            </span>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="sidebar-card">
            <h5><i class="fas fa-plus-circle me-2"></i>Tambah Jalur Baru</h5>
            <hr>

            <form action="/polylines" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama Jalur <span class="text-danger">*</span></label>
                    <input type="text" name="nama_jalur" class="form-control" required
                           value="{{ old('nama_jalur') }}"
                           placeholder="Contoh: Koridor Jl. Raya Masaran–Sragen">
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
                    <label class="form-label">Kategori Jalur <span class="text-danger">*</span></label>
                    <select name="kategori_objek" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option>Jalan Aspal</option>
                        <option>Jalan Tanah/Makadam</option>
                        <option>Jalan Setapak</option>
                        <option>Selokan/Drainase</option>
                        <option>Rel Kereta</option>
                        <option>Saluran Irigasi</option>
                        <option>Sungai/Kanal</option>
                        <option>Lainnya</option>
                    </select>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label">Jenis Sebelumnya</label>
                        <select name="jenis_lama" class="form-select">
                            <option value="">-- Tidak ada --</option>
                            <option>Jalan Tanah</option>
                            <option>Jalan Setapak</option>
                            <option>Saluran Irigasi Alami</option>
                            <option>Belum Ada Jalur</option>
                            <option>Selokan Tanah</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Kondisi Sekarang <span class="text-danger">*</span></label>
                        <select name="jenis_perubahan" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option>Jalan Aspal</option>
                            <option>Jalan Cor Beton</option>
                            <option>Jalan Diperkeras</option>
                            <option>Selokan Permanen</option>
                            <option>Rel Kereta Baru</option>
                            <option>Saluran Irigasi Permanen</option>
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
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="2"
                              placeholder="Deskripsi jalur perubahan...">{{ old('keterangan') }}</textarea>
                </div>

                <input type="hidden" name="geojson" id="geojson">

                <div class="klik-info mb-3" id="info-gambar">
                    <i class="fas fa-pencil-alt me-1"></i>
                    Gambar jalur di peta terlebih dahulu
                </div>

                <div id="preview-panjang" style="display:none;"
                     class="alert alert-info py-2 text-center mb-3">
                    <i class="fas fa-ruler me-1"></i>
                    Panjang: <strong id="panjang-text"></strong>
                </div>

                <button type="submit" class="btn btn-success-custom w-100" id="btnSimpan" disabled>
                    <i class="fas fa-save me-2"></i>Simpan Jalur
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
var map = L.map('map').setView([-7.4258, 111.0149], 11);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors', maxZoom: 19
}).addTo(map);

var drawnItems = new L.FeatureGroup().addTo(map);

var drawControl = new L.Control.Draw({
    draw: {
        polyline: {
            shapeOptions: { color: '#E65100', weight: 4 },
            metric: true
        },
        polygon:    false,
        rectangle:  false,
        circle:     false,
        marker:     false,
        circlemarker: false,
    },
    edit: { featureGroup: drawnItems }
});
map.addControl(drawControl);

// ─── Layer Batas Kecamatan ────────────────────────────────────────────────────
var layerBatas = L.layerGroup();

var layersControl = L.control.layers(
    {},
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

map.on(L.Draw.Event.CREATED, function (e) {
    drawnItems.clearLayers();
    drawnItems.addLayer(e.layer);

    var geoJson    = e.layer.toGeoJSON().geometry;
    document.getElementById('geojson').value = JSON.stringify(geoJson);

    var latLngs = e.layer.getLatLngs();
    var totalKm = 0;
    for (var i = 0; i < latLngs.length - 1; i++) {
        totalKm += latLngs[i].distanceTo(latLngs[i + 1]);
    }

    document.getElementById('info-gambar').className = 'klik-info selected mb-3';
    document.getElementById('info-gambar').innerHTML =
        '<i class="fas fa-check-circle me-1"></i>Jalur berhasil digambar (' + latLngs.length + ' titik)';

    document.getElementById('preview-panjang').style.display = '';
    document.getElementById('panjang-text').textContent =
        (totalKm / 1000).toFixed(3) + ' km (PostGIS yang akurat)';

    document.getElementById('btnSimpan').disabled = false;
});

map.on(L.Draw.Event.DELETED, function () {
    document.getElementById('geojson').value           = '';
    document.getElementById('btnSimpan').disabled      = true;
    document.getElementById('preview-panjang').style.display = 'none';
    document.getElementById('info-gambar').className   = 'klik-info mb-3';
    document.getElementById('info-gambar').innerHTML   =
        '<i class="fas fa-pencil-alt me-1"></i>Gambar jalur di peta terlebih dahulu';
});

// ─── Warna berdasarkan kategori jalur ────────────────────────────────────────
function getPolylineStyle(kategori) {
    var colors = {
        'Jalan Aspal':           '#424242',
        'Jalan Tanah/Makadam':    '#8D6E63',
        'Jalan Setapak':          '#A1887F',
        'Selokan/Drainase':       '#0277BD',
        'Rel Kereta':             '#37474F',
        'Saluran Irigasi':        '#00838F',
        'Sungai/Kanal':           '#01579B',
    };
    return colors[kategori] || '#E65100';
}

var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

fetch('/api/polylines')
    .then(r => r.json())
    .then(data => {
        L.geoJSON(data, {
            style: function (feature) {
                return { color: getPolylineStyle(feature.properties.kategori_objek), weight: 4, opacity: 0.85 };
            },
            onEachFeature: function (feature, layer) {
                var p = feature.properties;
                layer.bindPopup(
                    '<div style="min-width:200px">' +
                    '<div class="popup-title"><i class="fas fa-route text-warning me-1"></i>' + p.nama_jalur + '</div>' +
                    '<div class="popup-row"><span class="popup-label">Kecamatan</span><span>' + p.kecamatan + '</span></div>' +
                    '<div class="popup-row"><span class="popup-label">Kategori</span><span class="badge badge-baru">' + p.kategori_objek + '</span></div>' +
                    '<div class="popup-row"><span class="popup-label">Perubahan</span>' +
                        '<span>' + (p.jenis_lama ? '<span class="badge badge-lama">' + p.jenis_lama + '</span> → ' : '') +
                        '<span class="badge badge-baru">' + p.jenis_perubahan + '</span></span></div>' +
                    '<div class="popup-row"><span class="popup-label">Panjang</span><span>' + (p.panjang_meter ? p.panjang_meter + ' m' : '-') + '</span></div>' +
                    '<div class="popup-row"><span class="popup-label">Tahun</span><span>' + p.tahun_perubahan + '</span></div>' +
                    '<div class="popup-actions">' +
                    '<a href="/polylines/' + p.id + '/edit" class="btn btn-sm btn-warning-custom"><i class="fas fa-edit"></i> Edit</a>' +
                    '<form action="/polylines/' + p.id + '" method="POST" class="d-inline" onsubmit="return confirm(\'Yakin hapus jalur ini?\')">' +
                    '<input type="hidden" name="_token" value="' + csrfToken + '">' +
                    '<input type="hidden" name="_method" value="DELETE">' +
                    '<button class="btn btn-sm btn-danger-custom ms-1"><i class="fas fa-trash"></i> Hapus</button>' +
                    '</form></div></div>'
                );
            }
        }).addTo(map);
    });
</script>
@endsection

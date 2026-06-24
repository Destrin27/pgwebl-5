@extends('layouts.app')
@section('title', 'Edit Area Lahan')

@section('content')
<div class="page-header" style="background:linear-gradient(135deg,#E65100,#F57C00);">
    <div class="header-icon"><i class="fas fa-edit"></i></div>
    <div>
        <h4>Edit Area: {{ $data->nama_area }}</h4>
        <p>Hapus polygon lama dan gambar ulang, atau update hanya atributnya.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div id="map"></div>
        <div class="mt-3 alert alert-warning py-2" style="font-size:0.85rem;">
            <i class="fas fa-exclamation-triangle me-1"></i>
            Untuk ubah geometri: hapus polygon lama (tombol edit → delete) lalu gambar ulang.
        </div>
    </div>

    <div class="col-lg-4">
        <div class="sidebar-card">
            <h5 style="color:#E65100;"><i class="fas fa-edit me-2"></i>Edit Area Polygon</h5>
            <hr>

            <form action="/polygons/{{ $data->id }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama Area <span class="text-danger">*</span></label>
                    <input type="text" name="nama_area" class="form-control" required value="{{ $data->nama_area }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Kecamatan</label>
                    <select name="kecamatan" class="form-select" required>
                        @foreach(['Masaran','Sambungmacan','Gondang','Sragen','Karangmalang',
                                  'Sidoharjo','Tanon','Gemolong','Miri','Sumberlawang',
                                  'Mondokan','Sukodono','Gesi','Tangen','Jenar',
                                  'Plupuh','Ngrampal','Kalijambe','Sambirejo','Kedawung'] as $kec)
                        <option value="{{ $kec }}" {{ $data->kecamatan == $kec ? 'selected' : '' }}>{{ $kec }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kategori Area <span class="text-danger">*</span></label>
                    <select name="kategori_objek" class="form-select" required>
                        @foreach(['Permukiman','Sawah','Lahan Terbuka','Kebun/Perkebunan','Hutan','Tegalan/Ladang','Kawasan Industri','Tambak/Kolam','Lainnya'] as $opt)
                        <option value="{{ $opt }}" {{ $data->kategori_objek == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label">Lahan Sebelumnya</label>
                        <select name="penggunaan_lama" class="form-select" required>
                            @foreach(['Sawah Irigasi','Sawah Tadah Hujan','Hutan','Kebun/Perkebunan','Ladang/Tegalan','Semak Belukar','Lahan Terbuka'] as $opt)
                            <option value="{{ $opt }}" {{ $data->penggunaan_lama == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Kondisi Sekarang</label>
                        <select name="penggunaan_baru" class="form-select" required>
                            @foreach(['Permukiman','Sawah','Lahan Terbuka','Kebun/Perkebunan','Hutan','Tegalan/Ladang','Kawasan Industri','Tambak/Kolam'] as $opt)
                            <option value="{{ $opt }}" {{ $data->penggunaan_baru == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tahun Perubahan</label>
                    <input type="number" name="tahun_perubahan" class="form-control"
                           min="1990" max="2030" required value="{{ $data->tahun_perubahan }}">
                </div>

                @if($data->foto)
                <div class="mb-3">
                    <label class="form-label">Foto Saat Ini</label>
                    <img src="{{ asset('storage/' . $data->foto) }}"
                         class="img-fluid rounded mb-2" style="max-height:80px;object-fit:cover;width:100%;">
                </div>
                @endif

                <div class="mb-3">
                    <label class="form-label">Ganti Foto</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="2">{{ $data->keterangan }}</textarea>
                </div>

                <input type="hidden" name="geojson" id="geojson" value="{{ $data->geojson }}">

                <div class="klik-info selected mb-3" id="info-geom">
                    <i class="fas fa-check-circle me-1"></i>
                    Geometri tersedia — luas: {{ number_format($data->luas_ha, 2) }} Ha
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning-custom flex-grow-1">
                        <i class="fas fa-save me-1"></i>Update Data
                    </button>
                    <a href="/polygons" class="btn" style="background:#eee;color:#555;border-radius:8px;font-weight:600;">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
var existingGeoJson = {!! $data->geojson !!};

var map = L.map('map').setView([-7.4258, 111.0149], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

var drawnItems = new L.FeatureGroup().addTo(map);

// Load existing polygon
var existing = L.geoJSON({ type: 'Feature', geometry: existingGeoJson }, {
    style: { color: '#E65100', fillColor: '#FFCC80', fillOpacity: 0.4, weight: 2 }
}).addTo(drawnItems);

// Fit map to existing polygon
map.fitBounds(existing.getBounds(), { padding: [30, 30] });

var drawControl = new L.Control.Draw({
    draw: {
        polygon:   { shapeOptions: { color: '#388E3C', fillColor: '#66BB6A', fillOpacity: 0.3 } },
        rectangle: { shapeOptions: { color: '#388E3C', fillColor: '#66BB6A', fillOpacity: 0.3 } },
        polyline: false, circle: false, marker: false, circlemarker: false,
    },
    edit: { featureGroup: drawnItems }
});
map.addControl(drawControl);

map.on(L.Draw.Event.CREATED, function (e) {
    drawnItems.clearLayers();
    drawnItems.addLayer(e.layer);
    var geoJson = e.layer.toGeoJSON().geometry;
    document.getElementById('geojson').value = JSON.stringify(geoJson);
    document.getElementById('info-geom').innerHTML =
        '<i class="fas fa-check-circle me-1"></i>Geometri baru digambar — luas akan dihitung ulang';
});
</script>
@endsection

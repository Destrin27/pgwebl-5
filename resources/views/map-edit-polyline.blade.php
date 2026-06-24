@extends('layouts.app')
@section('title', 'Edit Jalur Perubahan')

@section('content')
<div class="page-header" style="background:linear-gradient(135deg,#E65100,#F57C00);">
    <div class="header-icon"><i class="fas fa-edit"></i></div>
    <div>
        <h4>Edit Jalur: {{ $data->nama_jalur }}</h4>
        <p>Perbarui data jalur perubahan lahan. Gambar ulang jika perlu mengubah geometri.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div id="map"></div>
    </div>

    <div class="col-lg-4">
        <div class="sidebar-card">
            <h5 style="color:#E65100;"><i class="fas fa-edit me-2"></i>Edit Jalur</h5>
            <hr>

            <form action="/polylines/{{ $data->id }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama Jalur <span class="text-danger">*</span></label>
                    <input type="text" name="nama_jalur" class="form-control" required value="{{ $data->nama_jalur }}">
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
                    <label class="form-label">Kategori Jalur <span class="text-danger">*</span></label>
                    <select name="kategori_objek" class="form-select" required>
                        @foreach(['Jalan Aspal','Jalan Tanah/Makadam','Jalan Setapak','Selokan/Drainase','Rel Kereta','Saluran Irigasi','Sungai/Kanal','Lainnya'] as $opt)
                        <option value="{{ $opt }}" {{ $data->kategori_objek == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label">Jenis Sebelumnya</label>
                        <select name="jenis_lama" class="form-select">
                            <option value="">-- Tidak ada --</option>
                            @foreach(['Jalan Tanah','Jalan Setapak','Saluran Irigasi Alami','Belum Ada Jalur','Selokan Tanah'] as $opt)
                            <option value="{{ $opt }}" {{ $data->jenis_lama == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Kondisi Sekarang</label>
                        <select name="jenis_perubahan" class="form-select" required>
                            @foreach(['Jalan Aspal','Jalan Cor Beton','Jalan Diperkeras','Selokan Permanen','Rel Kereta Baru','Saluran Irigasi Permanen'] as $opt)
                            <option value="{{ $opt }}" {{ $data->jenis_perubahan == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tahun Perubahan</label>
                    <input type="number" name="tahun_perubahan" class="form-control"
                           min="1990" max="2030" required value="{{ $data->tahun_perubahan }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="2">{{ $data->keterangan }}</textarea>
                </div>

                <input type="hidden" name="geojson" id="geojson" value="{{ $data->geojson }}">

                <div class="klik-info selected mb-3">
                    <i class="fas fa-check-circle me-1"></i>
                    Jalur tersedia — panjang: {{ $data->panjang_meter ? number_format($data->panjang_meter) . ' m' : '-' }}
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning-custom flex-grow-1">
                        <i class="fas fa-save me-1"></i>Update Jalur
                    </button>
                    <a href="/polylines" class="btn" style="background:#eee;color:#555;border-radius:8px;font-weight:600;">
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

var existing = L.geoJSON({ type: 'Feature', geometry: existingGeoJson }, {
    style: { color: '#E65100', weight: 4, opacity: 0.8 }
}).addTo(drawnItems);

map.fitBounds(existing.getBounds(), { padding: [40, 40] });

var drawControl = new L.Control.Draw({
    draw: {
        polyline: { shapeOptions: { color: '#388E3C', weight: 4 } },
        polygon: false, rectangle: false, circle: false, marker: false, circlemarker: false,
    },
    edit: { featureGroup: drawnItems }
});
map.addControl(drawControl);

map.on(L.Draw.Event.CREATED, function (e) {
    drawnItems.clearLayers();
    drawnItems.addLayer(e.layer);
    document.getElementById('geojson').value = JSON.stringify(e.layer.toGeoJSON().geometry);
});
</script>
@endsection

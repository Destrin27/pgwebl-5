@extends('layouts.app')
@section('title', 'Edit Titik Lokasi')

@section('content')
<div class="page-header" style="background:linear-gradient(135deg,#E65100,#F57C00);">
    <div class="header-icon"><i class="fas fa-edit"></i></div>
    <div>
        <h4>Edit Data Titik: {{ $data->nama_lokasi }}</h4>
        <p>Perbarui informasi titik lokasi perubahan lahan. Geser marker untuk ubah posisi.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div id="map"></div>
        <div class="mt-3 alert alert-info py-2" style="font-size:0.85rem;">
            <i class="fas fa-info-circle me-1"></i>
            Drag (seret) marker merah untuk mengubah posisi koordinat.
        </div>
    </div>

    <div class="col-lg-4">
        <div class="sidebar-card">
            <h5 style="color:#E65100;"><i class="fas fa-edit me-2"></i>Edit Data Titik</h5>
            <hr>

            <form action="/points/{{ $data->id }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama Lokasi <span class="text-danger">*</span></label>
                    <input type="text" name="nama_lokasi" class="form-control" required
                           value="{{ $data->nama_lokasi }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
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
                    <label class="form-label">Kategori Bangunan / Objek <span class="text-danger">*</span></label>
                    <select name="kategori_objek" class="form-select" required>
                        @foreach(['Rumah','Tanah Kosong','Hotel','Ruko/Toko','Gudang','Kantor','Pabrik/Industri','Fasilitas Umum','Tempat Ibadah','Sekolah','Lainnya'] as $opt)
                        <option value="{{ $opt }}" {{ $data->kategori_objek == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label">Lahan Sebelumnya</label>
                        <select name="penggunaan_lama" class="form-select" required>
                            @foreach(['Sawah Irigasi','Sawah Tadah Hujan','Hutan','Kebun/Perkebunan','Ladang/Tegalan','Semak Belukar','Tanah Kosong','Bangunan Lama'] as $opt)
                            <option value="{{ $opt }}" {{ $data->penggunaan_lama == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Kondisi Sekarang</label>
                        <select name="penggunaan_baru" class="form-select" required>
                            @foreach(['Rumah','Tanah Kosong','Hotel','Ruko/Toko','Gudang','Kantor','Pabrik/Industri','Fasilitas Umum','Tempat Ibadah','Sekolah'] as $opt)
                            <option value="{{ $opt }}" {{ $data->penggunaan_baru == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tahun Perubahan <span class="text-danger">*</span></label>
                    <input type="number" name="tahun_perubahan" class="form-control"
                           min="1990" max="2030" required value="{{ $data->tahun_perubahan }}">
                </div>

                @if($data->foto)
                <div class="mb-3">
                    <label class="form-label">Foto Saat Ini</label>
                    <img src="{{ asset('storage/' . $data->foto) }}"
                         class="img-fluid rounded mb-2" style="max-height:100px;object-fit:cover;width:100%;">
                </div>
                @endif

                <div class="mb-3">
                    <label class="form-label">Ganti Foto (opsional)</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="2">{{ $data->keterangan }}</textarea>
                </div>

                <input type="hidden" name="lat" id="lat" value="{{ $data->lat }}">
                <input type="hidden" name="lng" id="lng" value="{{ $data->lng }}">

                <div class="klik-info selected mb-3" id="info-pos">
                    <i class="fas fa-map-marker-alt me-1"></i>
                    Posisi: {{ number_format($data->lat, 5) }}, {{ number_format($data->lng, 5) }}
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning-custom flex-grow-1">
                        <i class="fas fa-save me-1"></i>Update Data
                    </button>
                    <a href="/points" class="btn" style="background:#eee;color:#555;border-radius:8px;font-weight:600;">
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
var lat = {{ $data->lat }};
var lng = {{ $data->lng }};

var map = L.map('map').setView([lat, lng], 14);

var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors', maxZoom: 19
}).addTo(map);

var satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
    attribution: 'Tiles &copy; Esri'
});

L.control.layers({ 'OpenStreetMap': osm, 'Satelit': satellite }).addTo(map);

var redIcon = L.icon({
    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
    iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34]
});

var marker = L.marker([lat, lng], { icon: redIcon, draggable: true })
    .addTo(map)
    .bindPopup('Geser marker untuk ubah posisi')
    .openPopup();

marker.on('dragend', function (e) {
    var pos = e.target.getLatLng();
    document.getElementById('lat').value = pos.lat;
    document.getElementById('lng').value = pos.lng;
    document.getElementById('info-pos').innerHTML =
        '<i class="fas fa-map-marker-alt me-1"></i>Posisi: ' + pos.lat.toFixed(5) + ', ' + pos.lng.toFixed(5);
});
</script>
@endsection

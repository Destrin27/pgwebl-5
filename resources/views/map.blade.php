@extends('layouts.template')

@section('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    {{-- Leaflet Draw CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }

        #map {
            height: 90vh;
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <!-- Map -->
    <div id="map"></div>

    {{-- Modal Form Input Untuk Point --}}
    <div class="modal" tabindex="-1" id="modalInputPoint">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Point</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('points.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter name">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="geometry_point" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geometry_point" name="geometry_point" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Form Input Untuk Polyline --}}
    <div class="modal" tabindex="-1" id="modalInputPolyline">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Polyline</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('polylines.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter name">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="geometry_polyline" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geometry_polyline" name="geometry_polyline" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Form Input Untuk Polygon --}}
    <div class="modal" tabindex="-1" id="modalInputPolygon">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Polygon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('polygons.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter name">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="geometry_polygon" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geometry_polygon" name="geometry_polygon" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

{{-- leaflet draw js --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

{{-- Terraformer JS --}}
<script src="https://unpkg.com/@terraformer/wkt"></script>

{{-- JQuery Js --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

@section('scripts')
    <script>
        var map = L.map('map').setView([-7.7956, 110.3695], 13);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);


        /* Digitize Function */
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            draw: {
                position: 'topleft',
                polyline: true,
                polygon: true,
                rectangle: true,
                circle: false,
                marker: true,
                circlemarker: false
            },
            edit: false
        });

        map.addControl(drawControl);

        map.on('draw:created', function(e) {
            var type = e.layerType,
                layer = e.layer;

            console.log(type);

            var drawnJSONObject = layer.toGeoJSON();
            var objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);

            console.log(drawnJSONObject);
            console.log(objectGeometry);

            if (type === 'polyline') {
                //Set Value Geometry Polyline To Modal Form Input
                $('#geometry_polyline').val(objectGeometry);

                //Show Modal Form Input
                $('#modalInputPolyline').modal('show');

                // Modal dismiss reload page
                $('#modalInputPolyline').on('hidden.bs.modal', function() {
                    location.reload();
                });
            } else if (type === 'polygon' || type === 'rectangle') {

                //Set Value Geometry Polygon To Modal Form Input
                $('#geometry_polygon').val(objectGeometry);

                //Show Modal Form Input
                $('#modalInputPolygon').modal('show');

                // Modal dismiss reload page
                $('#modalInputPolygon').on('hidden.bs.modal', function() {
                    location.reload();
                });
            } else if (type === 'marker') {
                console.log("Create " + type);

                //Set Value Geometry Point To Modal Form Input
                $('#geometry_point').val(objectGeometry);

                //Show Modal Form Input
                $('#modalInputPoint').modal('show');

                // Modal dismiss reload page
                $('#modalInputPoint').on('hidden.bs.modal', function() {
                    location.reload();
                });
            } else {
                console.log('__undefined__');
            }

            drawnItems.addLayer(layer);
        });

        // GeoJSON Point
        var points = L.geoJSON(null, {
            // Style

            // onEachFeature
            onEachFeature: function(feature, layer) {
                // variable popup content
                var popup_content = "Nama: " + feature.properties.name + "<br>" +
                    "Deskripsi: " + feature.properties.description + "<br>" +
                    "created_at: " + feature.properties.created_at + "<br>" +
                    "updated_at: " + feature.properties.updated_at;

                layer.on({
                    click: function(e) {
                        points.bindPopup(popup_content);
                    },
                });
            },

        });
        $.getJSON("{{ route('geojson_points') }}", function(data) {
            points.addData(data); // Menambahkan data ke dalam GeoJSON Point Sarana Prasarana
            map.addLayer(points); // Menambahkan GeoJSON Point Sarana Prasarana ke dalam peta
        });

        // GeoJSON Polyline
        var polylines = L.geoJSON(null, {
            // Style

            // onEachFeature
            onEachFeature: function(feature, layer) {
                // variable popup content
                var popup_content = "Nama: " + feature.properties.name + "<br>" +
                    "Deskripsi: " + feature.properties.description + "<br>" +
                    "created_at: " + feature.properties.created_at + "<br>" +
                    "updated_at: " + feature.properties.updated_at;

                layer.on({
                    click: function(e) {
                        polylines.bindPopup(popup_content);
                    },
                });
            },

        });
        $.getJSON("{{ route('geojson_polylines') }}", function(data) {
            polylines.addData(data); // Menambahkan data ke dalam GeoJSON Polyline
            map.addLayer(polylines); // Menambahkan GeoJSON Polyline ke dalam peta
        });

        // GeoJSON Polygon
        var polygons = L.geoJSON(null, {
            // Style

            // onEachFeature
            onEachFeature: function(feature, layer) {
                // variable popup content
                var popup_content = "Nama: " + feature.properties.name + "<br>" +
                    "Deskripsi: " + feature.properties.description + "<br>" +
                    "created_at: " + feature.properties.created_at + "<br>" +
                    "updated_at: " + feature.properties.updated_at;

                layer.on({
                    click: function(e) {
                        polygons.bindPopup(popup_content);
                    },
                });
            },

        });
        $.getJSON("{{ route('geojson_polygons') }}", function(data) {
            polygons.addData(data); // Menambahkan data ke dalam GeoJSON Polygon
            map.addLayer(polygons); // Menambahkan GeoJSON Polygon ke dalam peta
        });

        // Control Layer
        var baseMaps = {

        };

        var overlayMaps = {
            "Points": points,
            "Polylines": polylines,
            "Polygons": polygons,
        };

        var controllayer = L.control.layers(baseMaps, overlayMaps);
        controllayer.addTo(map);
    </script>
@endsection

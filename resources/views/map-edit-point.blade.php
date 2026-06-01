@extends('layouts.template')

@section('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    {{-- Leaflet Draw CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">

    <style>
        body,
        html {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        #map {
            height: calc(100vh - 56px);
            width: 100%;
        }
    </style>
@endsection


@section('content')
    <!-- Map -->
    <div id="map"></div>


    {{-- Modal Form Edit --}}
    <div class="modal" tabindex="-1" id="modalEdit">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">Edit Data</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>
                <form action="{{ route('points.update', $id) }}" method="post" enctype="multipart/form-data">

                    @csrf
                    @method('PATCH')

                    <div class="modal-body">

                        {{-- Name --}}
                        <div class="mb-3">

                            <label for="name" class="form-label">Name</label>

                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter name">

                        </div>


                        {{-- Description --}}
                        <div class="mb-3">

                            <label for="description" class="form-label">Description</label>

                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>

                        </div>


                        {{-- Geometry --}}
                        <div class="mb-3">

                            <label for="geometry" class="form-label">Geometry</label>

                            <textarea class="form-control" id="geometry" name="geometry" rows="3"></textarea>

                        </div>


                        {{-- Image --}}
                        <div class="mb-3">

                            <label for="image" class="form-label">Image</label>

                            <input class="form-control" type="file" id="image" name="image"
                                onchange="document.getElementById('preview-image').src = window.URL.createObjectURL(this.files[0])">

                        </div>


                        {{-- Preview Image --}}
                        <div class="mb-3">

                            <img src="" alt="" id="preview-image" class="img-thumbnail" width="400">

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>

                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
@endsection



@section('scripts')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    {{-- Leaflet Draw JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

    {{-- Terraformer JS --}}
    <script src="https://unpkg.com/@terraformer/wkt"></script>

    {{-- JQuery JS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>


    <script>
        // ===============================
        // Inisialisasi Map
        // ===============================
        var map = L.map('map').setView([-7.7956, 110.3695], 13);


        // ===============================
        // Basemap OpenStreetMap
        // ===============================
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {

            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'

        }).addTo(map);



        /* ===============================
        Digitize Function
        =============================== */

        var drawnItems = new L.FeatureGroup();

        map.addLayer(drawnItems);


        var drawControl = new L.Control.Draw({

            draw: false,

            edit: {

                featureGroup: drawnItems,
                edit: true,
                remove: false

            }

        });

        map.addControl(drawControl);



        // ===============================
        // Event Edit Geometry
        // ===============================
        map.on('draw:edited', function(e) {

            var layers = e.layers;

            layers.eachLayer(function(layer) {

                // Convert layer ke GeoJSON
                var drawnJSONObject = layer.toGeoJSON();

                console.log(drawnJSONObject);


                // Convert GeoJSON ke WKT
                var objectGeometry = Terraformer.geojsonToWKT(
                    drawnJSONObject.geometry
                );

                console.log(objectGeometry);


                // Menampilkan properties
                var properties = drawnJSONObject.properties;

                console.log(properties);

                drawnItems.addLayer(layer);

                // Mengisi form modal edit dengan data yang sudah diubah
                $('#name').val(properties.name);
                $('#description').val(properties.description);
                $('#geometry').val(objectGeometry);
                $('#preview-image').attr('src', "{{ asset('storage/images/') }}/" + properties.image);


                // menampilkan modal edit
                $('#modalEdit').modal('show');

            });

        });



        // ===============================
        // GeoJSON Point
        // ===============================
        var points = L.geoJSON(null, {

            onEachFeature: function(feature, layer) {

                drawnItems.addLayer(layer);

                var properties = feature.properties;

                var objectGeometry = Terraformer.geojsonToWKT(
                    feature.geometry
                );

                layer.on({

                    click: function(e) {
                        // Mengisi form modal edit dengan data yang sudah diubah
                $('#name').val(properties.name);
                $('#description').val(properties.description);
                $('#geometry').val(objectGeometry);
                $('#preview-image').attr('src', "{{ asset('storage/images/') }}/" + properties.image);


                // menampilkan modal edit
                $('#modalEdit').modal('show');

                    },

                });

            },

        });



        // ===============================
        // Load GeoJSON
        // ===============================
        $.getJSON("{{ route('geojson_point', $id) }}", function(data) {

            points.addData(data);

            map.addLayer(points);

        });



        // ===============================
        // Layer Control
        // ===============================
        var baseMaps = {};


        var overlayMaps = {

            "Points": points,
            "Polylines": polylines,
            "Polygons": polygons,

        };
    </script>
@endsection

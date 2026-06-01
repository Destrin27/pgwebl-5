@extends('layouts.template')

@section('styles')

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- DataTables CSS -->
    <link rel="stylesheet"
        href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fc;
        }

        #map {
            height: 0vh;
            width: 100%;
        }

        /* Card */
        .card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        /* Header */
        .card-header {
            background-color: #d291bc;
            color: white;
            padding: 14px 20px;
        }

        .card-header h3 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }

        /* Table */
        .table {
            margin-bottom: 0;
            vertical-align: middle;
        }

        .table thead {
            background-color: #f3d6e6;
        }

        .table thead th {
            color: #6b4c5a;
            font-weight: 600;
            text-align: center;
            border-color: #ead3df;
        }

        .table tbody td {
            vertical-align: middle;
            text-align: center;
            color: #444;
            padding: 14px;
        }

        /* Hover */
        .table tbody tr:hover {
            background-color: #fff5fa;
            transition: 0.2s ease;
        }

        /* Foto */
        .img-table {
            width: 180px;
            height: auto;
            max-height: 120px;
            object-fit: contain;
            border-radius: 10px;
            border: 2px solid #f3d6e6;
            padding: 3px;
            background: white;
        }

        /* Tidak ada foto */
        .no-image {
            color: #888;
            font-style: italic;
        }

        /* DataTables */
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 8px;
            border: 1px solid #d291bc;
            padding: 5px 10px;
        }

        .dataTables_wrapper .dataTables_length select {
            border-radius: 8px;
            border: 1px solid #d291bc;
            padding: 5px;
        }

        .page-item.active .page-link {
            background-color: #d291bc !important;
            border-color: #d291bc !important;
        }

        .page-link {
            color: #d291bc;
        }
    </style>

@endsection

@section('content')

    <!-- Map -->
    <div id="map"></div>

    <!-- ================= POINT ================= -->
    <div class="container mt-3">

        <div class="card">

            <div class="card-header">
                <h3>Tabel Data Point</h3>
            </div>

            <div class="card-body">

                <table id="tablePoint" class="table table-striped table-bordered">

                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Foto</th>
                            <th>Tanggal Dibuat</th>
                        </tr>
                    </thead>

                    <tbody>

                        @php
                            $no = 1;
                        @endphp

                        @foreach ($points as $point)

                            <tr>

                                <td>{{ $no++ }}</td>

                                <td>{{ $point->name }}</td>

                                <td>{{ $point->description }}</td>

                                <td>
                                    @if ($point->image)
                                        <img src="{{ asset('storage/images/' . $point->image) }}"
                                            alt="{{ $point->name }}"
                                            class="img-table">
                                    @else
                                        <span class="no-image">
                                            Tidak ada foto
                                        </span>
                                    @endif
                                </td>

                                <td>{{ $point->created_at }}</td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <!-- ================= POLYLINE ================= -->
    <div class="container mt-4">

        <div class="card">

            <div class="card-header">
                <h3>Tabel Data Polyline</h3>
            </div>

            <div class="card-body">

                <table id="tablePolyline" class="table table-striped table-bordered">

                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Foto</th>
                            <th>Tanggal Dibuat</th>
                        </tr>
                    </thead>

                    <tbody>

                        @php
                            $no = 1;
                        @endphp

                        @foreach ($polylines as $polyline)

                            <tr>

                                <td>{{ $no++ }}</td>

                                <td>{{ $polyline->name }}</td>

                                <td>{{ $polyline->description }}</td>

                                <td>
                                    @if ($polyline->image)
                                        <img src="{{ asset('storage/images/' . $polyline->image) }}"
                                            alt="{{ $polyline->name }}"
                                            class="img-table">
                                    @else
                                        <span class="no-image">
                                            Tidak ada foto
                                        </span>
                                    @endif
                                </td>

                                <td>{{ $polyline->created_at }}</td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <!-- ================= POLYGON ================= -->
    <div class="container mt-4 mb-4">

        <div class="card">

            <div class="card-header">
                <h3>Tabel Data Polygon</h3>
            </div>

            <div class="card-body">

                <table id="tablePolygon" class="table table-striped table-bordered">

                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Foto</th>
                            <th>Tanggal Dibuat</th>
                        </tr>
                    </thead>

                    <tbody>

                        @php
                            $no = 1;
                        @endphp

                        @foreach ($polygons as $polygon)

                            <tr>

                                <td>{{ $no++ }}</td>

                                <td>{{ $polygon->name }}</td>

                                <td>{{ $polygon->description }}</td>

                                <td>
                                    @if ($polygon->image)
                                        <img src="{{ asset('storage/images/' . $polygon->image) }}"
                                            alt="{{ $polygon->name }}"
                                            class="img-table">
                                    @else
                                        <span class="no-image">
                                            Tidak ada foto
                                        </span>
                                    @endif
                                </td>

                                <td>{{ $polygon->created_at }}</td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection

@section('scripts')

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- DataTables Bootstrap -->
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {

            $('#tablePoint').DataTable({
                pageLength: 5,
                lengthMenu: [5, 10, 15, 20],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    paginate: {
                        previous: "Prev",
                        next: "Next"
                    }
                }
            });

            $('#tablePolyline').DataTable({
                pageLength: 5,
                lengthMenu: [5, 10, 15, 20],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    paginate: {
                        previous: "Prev",
                        next: "Next"
                    }
                }
            });

            $('#tablePolygon').DataTable({
                pageLength: 5,
                lengthMenu: [5, 10, 15, 20],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    paginate: {
                        previous: "Prev",
                        next: "Next"
                    }
                }
            });

        });
    </script>

@endsection

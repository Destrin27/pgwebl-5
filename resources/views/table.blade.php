@extends('layouts.template')

@section('styles')

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family: Arial, sans-serif;
}

#map{
    height: 0vh;
    width: 100%;
}
</style>

@endsection

@section('content')

<!-- Map -->
<div id="map"></div>

<!-- Container -->
<div class="container mt-1">

    <div class="card">
        <div class="card-header">
            <h3>Tabel Data</h3>
        </div>

        <div class="card-body">

            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Koordinat</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Stadion Kridosono</td>
                        <td>-7.78781, 110.374296</td>
                        <td>Kota Baru</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Bandara Adisutjipto</td>
                        <td>-7.794337, 110.427189</td>
                        <td>Bandara Internasional</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Alun-alun Utara</td>
                        <td>-7.803989, 110.364382</td>
                        <td>Halaman depan Kraton Jogja</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Mandala Krida</td>
                        <td>-7.795931, 110.384316</td>
                        <td>Stadion Sepak Bola</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Gedung Pusat UGM</td>
                        <td>-7.768272, 110.378373</td>
                        <td>Kantor Pusat UGM</td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>Gembira Loka Zoo</td>
                        <td>-7.806497, 110.396805</td>
                        <td>Kebun Binatang</td>
                    </tr>
                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection
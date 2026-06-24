@extends('layouts.app')
@section('title', 'Kelola Data BPS')

@section('content')

@php
    $data = $data ?? collect();
@endphp

<div class="page-header">
    <h4>Kelola Data BPS</h4>
</div>

<div class="row g-4">

    <!-- FORM -->
    <div class="col-lg-4">
        <div class="sidebar-card">

            <h5>Tambah Data</h5>

            <form action="/bps-manage" method="POST">
                @csrf

                <input type="text" name="kecamatan" class="form-control mb-2" placeholder="Kecamatan" required>
                <input type="number" name="tahun" class="form-control mb-2" value="{{ date('Y') }}" required>

                <input type="number" step="0.01" name="luas_sawah_ha" class="form-control mb-2" placeholder="Pertanian">
                <input type="number" step="0.01" name="luas_bukan_sawah_ha" class="form-control mb-2" placeholder="Terbangun">
                <input type="number" step="0.01" name="luas_bukan_pertanian_ha" class="form-control mb-2" placeholder="Lainnya">

                <button class="btn btn-success w-100">Simpan</button>
            </form>

        </div>
    </div>

    <!-- TABLE -->
    <div class="col-lg-8">
        <div class="sidebar-card">

            <div class="d-flex justify-content-between mb-2">
                <h5>Data BPS</h5>

                <span class="badge bg-success">
                    {{ $data->count() }} record
                </span>
            </div>

            @if($data->count() > 0)

            <div class="table-responsive">
                <table class="table table-bordered table-sm">

                    <thead>
                        <tr>
                            <th>Tahun</th>
                            <th>Kecamatan</th>
                            <th>Pertanian</th>
                            <th>Terbangun</th>
                            <th>Lainnya</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($data as $row)
                        <tr>
                            <td>{{ $row->tahun }}</td>
                            <td>{{ $row->kecamatan }}</td>
                            <td>{{ $row->luas_pertanian_ha }}</td>
                            <td>{{ $row->luas_terbangun_ha }}</td>
                            <td>{{ $row->luas_lainnya_ha }}</td>
                            <td>{{ $row->luas_total_ha }}</td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

            @else
                <p class="text-muted">Belum ada data</p>
            @endif

        </div>
    </div>

</div>

@endsection

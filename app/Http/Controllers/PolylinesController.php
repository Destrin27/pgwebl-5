<?php

namespace App\Http\Controllers;

use App\Models\polylinesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PolylinesController extends Controller
{
    public function index()
    {
        return view('map-polyline');
    }

    public function store(Request $req)
    {
        $req->validate([
            'nama_jalur'      => 'required|string|max:255',
            'kecamatan'       => 'required|string',
            'kategori_objek'  => 'required|string',
            'jenis_lama'      => 'nullable|string',
            'jenis_perubahan' => 'required|string',
            'panjang_meter'   => 'nullable|integer',
            'tahun_perubahan' => 'required|integer|min:1990|max:2030',
            'keterangan'      => 'nullable|string',
            'geojson'         => 'required|string',
        ]);

        // Hitung panjang otomatis dari PostGIS (meter)
        $result = DB::select(
            'SELECT ST_Length(ST_Transform(ST_GeomFromGeoJSON(?), 32749)) as panjang',
            [$req->geojson]
        );
        $panjang = isset($result[0]) ? round($result[0]->panjang) : ($req->panjang_meter ?? 0);

        DB::statement(
            "INSERT INTO polylines (nama_jalur, kecamatan, kategori_objek, jenis_lama, jenis_perubahan,
             panjang_meter, tahun_perubahan, keterangan, geom, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ST_SetSRID(ST_GeomFromGeoJSON(?),4326), NOW(), NOW())",
            [
                $req->nama_jalur, $req->kecamatan, $req->kategori_objek, $req->jenis_lama,
                $req->jenis_perubahan, $panjang, $req->tahun_perubahan, $req->keterangan,
                $req->geojson,
            ]
        );

        return redirect()->route('polylines.index')->with('success', 'Data jalur berhasil disimpan!');
    }

    public function edit($id)
    {
        $data = DB::select(
            'SELECT *, ST_AsGeoJSON(geom) as geojson FROM polylines WHERE id = ?',
            [$id]
        );

        if (empty($data)) {
            return redirect()->route('polylines.index')->with('error', 'Data tidak ditemukan.');
        }

        return view('map-edit-polyline', ['data' => $data[0]]);
    }

    public function update(Request $req, $id)
    {
        $req->validate([
            'nama_jalur'      => 'required|string|max:255',
            'kecamatan'       => 'required|string',
            'kategori_objek'  => 'required|string',
            'jenis_lama'      => 'nullable|string',
            'jenis_perubahan' => 'required|string',
            'tahun_perubahan' => 'required|integer|min:1990|max:2030',
            'keterangan'      => 'nullable|string',
            'geojson'         => 'required|string',
        ]);

        $result  = DB::select(
            'SELECT ST_Length(ST_Transform(ST_GeomFromGeoJSON(?), 32749)) as panjang',
            [$req->geojson]
        );
        $panjang = isset($result[0]) ? round($result[0]->panjang) : 0;

        DB::statement(
            "UPDATE polylines SET nama_jalur=?, kecamatan=?, kategori_objek=?, jenis_lama=?, jenis_perubahan=?,
             panjang_meter=?, tahun_perubahan=?, keterangan=?,
             geom=ST_SetSRID(ST_GeomFromGeoJSON(?),4326), updated_at=NOW() WHERE id=?",
            [
                $req->nama_jalur, $req->kecamatan, $req->kategori_objek, $req->jenis_lama,
                $req->jenis_perubahan, $panjang, $req->tahun_perubahan, $req->keterangan,
                $req->geojson, $id,
            ]
        );

        return redirect()->route('polylines.index')->with('success', 'Data jalur berhasil diupdate!');
    }

    public function destroy($id)
    {
        polylinesModel::findOrFail($id)->delete();
        return redirect()->route('polylines.index')->with('success', 'Data jalur berhasil dihapus!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\polygonsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PolygonsController extends Controller
{
    public function index()
    {
        return view('map-polygon');
    }

    public function store(Request $req)
    {
        $req->validate([
            'nama_area'       => 'required|string|max:255',
            'kecamatan'       => 'required|string',
            'kategori_objek'  => 'required|string',
            'penggunaan_lama' => 'required|string',
            'penggunaan_baru' => 'required|string',
            'tahun_perubahan' => 'required|integer|min:1990|max:2030',
            'keterangan'      => 'nullable|string',
            'foto'            => 'nullable|image|max:2048',
            'geojson'         => 'required|string',
        ]);

        $fotoPath = null;
        if ($req->hasFile('foto')) {
            $fotoPath = $req->file('foto')->store('images', 'public');
        }

        // Hitung luas otomatis (hektar) menggunakan UTM zone 49S
        $result = DB::select(
            'SELECT ST_Area(ST_Transform(ST_GeomFromGeoJSON(?), 32749))/10000 as luas',
            [$req->geojson]
        );
        $luas = isset($result[0]) ? $result[0]->luas : 0;

        DB::statement(
            "INSERT INTO polygons (nama_area, kecamatan, kategori_objek, penggunaan_lama, penggunaan_baru,
             luas_ha, tahun_perubahan, keterangan, foto, geom, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ST_SetSRID(ST_GeomFromGeoJSON(?),4326), NOW(), NOW())",
            [
                $req->nama_area, $req->kecamatan, $req->kategori_objek, $req->penggunaan_lama,
                $req->penggunaan_baru, $luas, $req->tahun_perubahan,
                $req->keterangan, $fotoPath, $req->geojson,
            ]
        );

        return redirect()->route('polygons.index')->with('success', 'Area perubahan lahan berhasil disimpan!');
    }

    public function edit($id)
    {
        $data = DB::select(
            'SELECT *, ST_AsGeoJSON(geom) as geojson FROM polygons WHERE id = ?',
            [$id]
        );

        if (empty($data)) {
            return redirect()->route('polygons.index')->with('error', 'Data tidak ditemukan.');
        }

        return view('map-edit-polygon', ['data' => $data[0]]);
    }

    public function update(Request $req, $id)
    {
        $req->validate([
            'nama_area'       => 'required|string|max:255',
            'kecamatan'       => 'required|string',
            'kategori_objek'  => 'required|string',
            'penggunaan_lama' => 'required|string',
            'penggunaan_baru' => 'required|string',
            'tahun_perubahan' => 'required|integer|min:1990|max:2030',
            'keterangan'      => 'nullable|string',
            'foto'            => 'nullable|image|max:2048',
            'geojson'         => 'required|string',
        ]);

        $polygon  = polygonsModel::findOrFail($id);
        $fotoPath = $polygon->foto;

        if ($req->hasFile('foto')) {
            if ($fotoPath) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $req->file('foto')->store('images', 'public');
        }

        $result = DB::select(
            'SELECT ST_Area(ST_Transform(ST_GeomFromGeoJSON(?), 32749))/10000 as luas',
            [$req->geojson]
        );
        $luas = isset($result[0]) ? $result[0]->luas : 0;

        DB::statement(
            "UPDATE polygons SET nama_area=?, kecamatan=?, kategori_objek=?, penggunaan_lama=?,
             penggunaan_baru=?, luas_ha=?, tahun_perubahan=?, keterangan=?, foto=?,
             geom=ST_SetSRID(ST_GeomFromGeoJSON(?),4326), updated_at=NOW() WHERE id=?",
            [
                $req->nama_area, $req->kecamatan, $req->kategori_objek, $req->penggunaan_lama,
                $req->penggunaan_baru, $luas, $req->tahun_perubahan,
                $req->keterangan, $fotoPath, $req->geojson, $id,
            ]
        );

        return redirect()->route('polygons.index')->with('success', 'Data area berhasil diupdate!');
    }

    public function destroy($id)
    {
        $polygon = polygonsModel::findOrFail($id);
        if ($polygon->foto) {
            Storage::disk('public')->delete($polygon->foto);
        }
        $polygon->delete();
        return redirect()->route('polygons.index')->with('success', 'Data area berhasil dihapus!');
    }
}

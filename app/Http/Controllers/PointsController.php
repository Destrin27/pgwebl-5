<?php

namespace App\Http\Controllers;

use App\Models\pointsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PointsController extends Controller
{
    public function index()
    {
        return view('map-point');
    }

    // ───────────────────────── STORE ─────────────────────────
    public function store(Request $req)
    {
        $req->validate([
            'nama_lokasi'     => 'required|string|max:255',
            'kecamatan'       => 'required|string',
            'kategori_objek'  => 'required|string',
            'penggunaan_lama' => 'required|string',
            'penggunaan_baru' => 'required|string',
            'tahun_perubahan' => 'required|integer|min:1990|max:2030',
            'keterangan'      => 'nullable|string',
            'foto'            => 'nullable|image|max:2048',
            'lat'             => 'required|numeric',
            'lng'             => 'required|numeric',
        ]);

        $fotoPath = null;
        if ($req->hasFile('foto')) {
            $fotoPath = $req->file('foto')->store('images', 'public');
        }

        DB::statement("
            INSERT INTO points (
                nama_lokasi,
                kecamatan,
                kategori_objek,
                penggunaan_lama,
                penggunaan_baru,
                tahun_perubahan,
                keterangan,
                foto,
                geom,
                created_at,
                updated_at
            )
            VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?,
                ST_SetSRID(ST_MakePoint(?, ?), 4326),
                NOW(), NOW()
            )
        ", [
            $req->nama_lokasi,
            $req->kecamatan,
            $req->kategori_objek,
            $req->penggunaan_lama,
            $req->penggunaan_baru,
            $req->tahun_perubahan,
            $req->keterangan,
            $fotoPath,
            $req->lng,
            $req->lat
        ]);

        return redirect()->route('points.index')
            ->with('success', 'Data titik berhasil disimpan!');
    }

    // ───────────────────────── EDIT ─────────────────────────
    public function edit($id)
    {
        $data = DB::select(
            'SELECT *, ST_Y(geom) as lat, ST_X(geom) as lng FROM points WHERE id = ?',
            [$id]
        );

        if (empty($data)) {
            return redirect()->route('points.index')
                ->with('error', 'Data tidak ditemukan.');
        }

        return view('map-edit-point', ['data' => $data[0]]);
    }

    // ───────────────────────── UPDATE (FIXED TOTAL) ─────────────────────────
    public function update(Request $req, $id)
    {
        $req->validate([
            'nama_lokasi'     => 'required|string|max:255',
            'kecamatan'       => 'required|string',
            'kategori_objek'  => 'required|string',
            'penggunaan_lama' => 'required|string',
            'penggunaan_baru' => 'required|string',
            'tahun_perubahan' => 'required|integer|min:1990|max:2030',
            'keterangan'      => 'nullable|string',
            'foto'            => 'nullable|image|max:2048',
            'lat'             => 'required|numeric',
            'lng'             => 'required|numeric',
        ]);

        $point = pointsModel::findOrFail($id);
        $fotoPath = $point->foto;

        if ($req->hasFile('foto')) {
            if ($fotoPath) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $req->file('foto')->store('images', 'public');
        }

        DB::statement("
            UPDATE points SET
                nama_lokasi = ?,
                kecamatan = ?,
                kategori_objek = ?,
                penggunaan_lama = ?,
                penggunaan_baru = ?,
                tahun_perubahan = ?,
                keterangan = ?,
                foto = ?,
                geom = ST_SetSRID(ST_MakePoint(?, ?), 4326),
                updated_at = NOW()
            WHERE id = ?
        ", [
            $req->nama_lokasi,
            $req->kecamatan,
            $req->kategori_objek,
            $req->penggunaan_lama,
            $req->penggunaan_baru,
            $req->tahun_perubahan,
            $req->keterangan,
            $fotoPath,
            $req->lng,
            $req->lat,
            $id
        ]);

        return redirect()->route('points.index')
            ->with('success', 'Data titik berhasil diupdate!');
    }

    // ───────────────────────── DELETE ─────────────────────────
    public function destroy($id)
    {
        $point = pointsModel::findOrFail($id);

        if ($point->foto) {
            Storage::disk('public')->delete($point->foto);
        }

        $point->delete();

        return redirect()->route('points.index')
            ->with('success', 'Data titik berhasil dihapus!');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getPoints()
    {
        $rows = DB::select(
            'SELECT id, nama_lokasi, kecamatan, kategori_objek, penggunaan_lama, penggunaan_baru,
             tahun_perubahan, keterangan, foto,
             ST_Y(geom) as lat, ST_X(geom) as lng
             FROM points ORDER BY created_at DESC'
        );

        $features = [];
        foreach ($rows as $r) {
            $features[] = [
                'type'     => 'Feature',
                'geometry' => [
                    'type'        => 'Point',
                    'coordinates' => [(float) $r->lng, (float) $r->lat],
                ],
                'properties' => [
                    'id'              => $r->id,
                    'nama_lokasi'     => $r->nama_lokasi,
                    'kecamatan'       => $r->kecamatan,
                    'kategori_objek'  => $r->kategori_objek,
                    'penggunaan_lama' => $r->penggunaan_lama,
                    'penggunaan_baru' => $r->penggunaan_baru,
                    'tahun_perubahan' => $r->tahun_perubahan,
                    'keterangan'      => $r->keterangan,
                    'foto'            => $r->foto ? asset('storage/' . $r->foto) : null,
                ],
            ];
        }

        return response()->json(['type' => 'FeatureCollection', 'features' => $features]);
    }

    public function getPolylines()
    {
        $rows = DB::select(
            'SELECT id, nama_jalur, kecamatan, kategori_objek, jenis_lama, jenis_perubahan,
             panjang_meter, tahun_perubahan, keterangan,
             ST_AsGeoJSON(geom) as geojson
             FROM polylines ORDER BY created_at DESC'
        );

        $features = [];
        foreach ($rows as $r) {
            if (! $r->geojson) continue;
            $features[] = [
                'type'       => 'Feature',
                'geometry'   => json_decode($r->geojson),
                'properties' => [
                    'id'              => $r->id,
                    'nama_jalur'      => $r->nama_jalur,
                    'kecamatan'       => $r->kecamatan,
                    'kategori_objek'  => $r->kategori_objek,
                    'jenis_lama'      => $r->jenis_lama,
                    'jenis_perubahan' => $r->jenis_perubahan,
                    'panjang_meter'   => $r->panjang_meter,
                    'tahun_perubahan' => $r->tahun_perubahan,
                    'keterangan'      => $r->keterangan,
                ],
            ];
        }

        return response()->json(['type' => 'FeatureCollection', 'features' => $features]);
    }

    public function getPolygons()
    {
        $rows = DB::select(
            'SELECT id, nama_area, kecamatan, kategori_objek, penggunaan_lama, penggunaan_baru,
             luas_ha, tahun_perubahan, keterangan, foto,
             ST_AsGeoJSON(geom) as geojson
             FROM polygons ORDER BY created_at DESC'
        );

        $features = [];
        foreach ($rows as $r) {
            if (! $r->geojson) continue;
            $features[] = [
                'type'       => 'Feature',
                'geometry'   => json_decode($r->geojson),
                'properties' => [
                    'id'              => $r->id,
                    'nama_area'       => $r->nama_area,
                    'kecamatan'       => $r->kecamatan,
                    'kategori_objek'  => $r->kategori_objek,
                    'penggunaan_lama' => $r->penggunaan_lama,
                    'penggunaan_baru' => $r->penggunaan_baru,
                    'luas_ha'         => $r->luas_ha,
                    'tahun_perubahan' => $r->tahun_perubahan,
                    'keterangan'      => $r->keterangan,
                    'foto'            => $r->foto ? asset('storage/' . $r->foto) : null,
                ],
            ];
        }

        return response()->json(['type' => 'FeatureCollection', 'features' => $features]);
    }

    public function getBpsLahan(Request $request)
    {
        $tahun = $request->query('tahun');

        $query = DB::table('bps_lahan')->orderBy('kecamatan');
        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        return response()->json($query->get());
    }
}

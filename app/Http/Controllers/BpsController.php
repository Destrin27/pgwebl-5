<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BpsController extends Controller
{
    // =========================
    // STATISTIK PAGE
    // =========================
    public function index(Request $request)
    {
        $tahunTersedia = DB::table('bps_lahan')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        if ($tahunTersedia->isEmpty()) {
            return view('bps-statistik', [
                'tahunTersedia'  => collect(),
                'tahunAwal'      => null,
                'tahunAkhir'     => null,
                'totalAwal'      => null,
                'totalAkhir'     => null,
                'perbandingan'   => [],
                'lapangan'       => collect(),
                'integrasiLahan' => collect(),
            ]);
        }

        $tahunAkhir = $request->get('tahun_akhir', $tahunTersedia->first());
        $tahunAwal  = $request->get('tahun_awal', $tahunTersedia->last());

        $totalAkhir = DB::table('bps_lahan')
            ->where('tahun', $tahunAkhir)
            ->selectRaw('
                COALESCE(SUM(luas_pertanian_ha),0) as pertanian,
                COALESCE(SUM(luas_terbangun_ha),0) as terbangun,
                COALESCE(SUM(luas_lainnya_ha),0) as lainnya,
                COALESCE(SUM(luas_total_ha),0) as total
            ')
            ->first();

        $totalAwal = DB::table('bps_lahan')
            ->where('tahun', $tahunAwal)
            ->selectRaw('
                COALESCE(SUM(luas_pertanian_ha),0) as pertanian,
                COALESCE(SUM(luas_terbangun_ha),0) as terbangun,
                COALESCE(SUM(luas_lainnya_ha),0) as lainnya,
                COALESCE(SUM(luas_total_ha),0) as total
            ')
            ->first();

        $dataAwal = DB::table('bps_lahan')
            ->where('tahun', $tahunAwal)
            ->get()
            ->keyBy('kecamatan');

        $dataAkhir = DB::table('bps_lahan')
            ->where('tahun', $tahunAkhir)
            ->get()
            ->keyBy('kecamatan');

        $perbandingan = [];

        foreach ($dataAkhir as $kec => $akhir) {
            $awal = $dataAwal->get($kec);

            $perbandingan[] = [
                'kecamatan'         => $kec,
                'pertanian_awal'    => $awal->luas_pertanian_ha ?? null,
                'pertanian_akhir'   => $akhir->luas_pertanian_ha,
                'pertanian_selisih' => $awal ? ($akhir->luas_pertanian_ha - $awal->luas_pertanian_ha) : null,

                'terbangun_awal'    => $awal->luas_terbangun_ha ?? null,
                'terbangun_akhir'   => $akhir->luas_terbangun_ha,
                'terbangun_selisih' => $awal ? ($akhir->luas_terbangun_ha - $awal->luas_terbangun_ha) : null,

                'total_akhir'       => $akhir->luas_total_ha,
            ];
        }

        $lapangan = DB::table('polygons')
            ->select('penggunaan_baru')
            ->selectRaw('COALESCE(SUM(luas_ha),0) as total_luas, COUNT(*) as jumlah')
            ->groupBy('penggunaan_baru')
            ->orderByDesc('total_luas')
            ->get();

        $integrasiLahan = DB::table('bps_lahan as b')
            ->where('b.tahun', $tahunAkhir)
            ->leftJoin(
                DB::raw('(SELECT kecamatan,
                    COALESCE(SUM(luas_ha),0) as luas_termonitor,
                    COUNT(*) as jumlah_area
                    FROM polygons GROUP BY kecamatan) as p'),
                'b.kecamatan', '=', 'p.kecamatan'
            )
            ->select(
                'b.kecamatan',
                'b.luas_total_ha',
                'b.luas_pertanian_ha',
                'b.luas_terbangun_ha',
                DB::raw('COALESCE(p.luas_termonitor,0) as luas_termonitor'),
                DB::raw('COALESCE(p.jumlah_area,0) as jumlah_area'),
                DB::raw('CASE WHEN b.luas_total_ha > 0
                    THEN ROUND((COALESCE(p.luas_termonitor,0)/b.luas_total_ha*100)::numeric,2)
                    ELSE 0 END as persen_termonitor')
            )
            ->orderBy('b.kecamatan')
            ->get();

        return view('bps-statistik', compact(
            'tahunTersedia',
            'tahunAwal',
            'tahunAkhir',
            'totalAwal',
            'totalAkhir',
            'perbandingan',
            'lapangan',
            'integrasiLahan'
        ));
    }

    // =========================
    // MANAGE PAGE
    // =========================
    public function manage()
    {
        $data = DB::table('bps_lahan')
            ->orderBy('tahun', 'desc')
            ->orderBy('kecamatan', 'asc')
            ->get();

        return view('bps-manage', compact('data'));
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BpsLahanSeeder extends Seeder
{
    public function run(): void
    {
        // Data resmi BPS: Luas Wilayah Kabupaten Sragen 2025
        // Sumber: "Luas Daerah Menurut Kecamatan di Kabupaten Sragen 2025"
        // Proporsi penggunaan lahan (estimasi karakteristik Sragen):
        //   52% Lahan Pertanian (sawah irigasi, tegalan, kebun)
        //   18% Lahan Terbangun (permukiman, industri, jalan)
        //   30% Lahan Lainnya  (hutan rakyat, semak, sungai, dll)

        $kecamatan = [
            ['Kalijambe',    48.47],
            ['Plupuh',       50.41],
            ['Masaran',      46.84],
            ['Kedawung',     53.06],
            ['Sambirejo',    45.82],
            ['Gondang',      46.83],
            ['Sambungmacan', 43.81],
            ['Ngrampal',     39.84],
            ['Karangmalang', 46.01],
            ['Sragen',       27.64],
            ['Sidoharjo',    49.03],
            ['Tanon',        52.69],
            ['Gemolong',     39.91],
            ['Miri',         56.86],
            ['Sumberlawang', 79.43],
            ['Mondokan',     50.73],
            ['Sukodono',     47.48],
            ['Gesi',         40.81],
            ['Tangen',       56.92],
            ['Jenar',        71.98],
        ];

        $rows = [];
        $now  = now();

        foreach ($kecamatan as [$kec, $luasKm2]) {
            $luasHa        = round($luasKm2 * 100, 2); // km² → hektar
            $pertanianHa   = round($luasHa * 0.52, 2); // 52% pertanian
            $terbangunHa   = round($luasHa * 0.18, 2); // 18% terbangun
            $lainnyaHa     = round($luasHa - $pertanianHa - $terbangunHa, 2); // sisa

            $rows[] = [
                'kecamatan'        => $kec,
                'tahun'            => 2025,
                'luas_pertanian_ha'=> $pertanianHa,
                'luas_terbangun_ha'=> $terbangunHa,
                'luas_lainnya_ha'  => $lainnyaHa,
                'luas_total_ha'    => $luasHa,
                'sumber'           => 'BPS Sragen 2025 – estimasi proporsi penggunaan lahan',
                'created_at'       => $now,
                'updated_at'       => $now,
            ];
        }

        DB::table('bps_lahan')->upsert(
            $rows,
            ['kecamatan', 'tahun'],
            ['luas_pertanian_ha', 'luas_terbangun_ha', 'luas_lainnya_ha',
             'luas_total_ha', 'sumber', 'updated_at']
        );

        $this->command->info('✅ Data BPS Sragen 2025 berhasil dimasukkan!');
    }
}

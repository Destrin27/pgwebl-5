<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('points', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lokasi');
            $table->string('kecamatan');
            $table->string('kategori_objek'); // jenis bangunan saat ini, misal: Rumah, Hotel, Ruko
            $table->string('penggunaan_lama'); // misal: Sawah Irigasi
            $table->string('penggunaan_baru'); // misal: Rumah (Permukiman)
            $table->integer('tahun_perubahan');
            $table->text('keterangan')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });

        // Tambah kolom geometry Point dengan SRID 4326 (WGS84)
        DB::statement('ALTER TABLE points ADD COLUMN geom geometry(Point, 4326)');
        DB::statement('CREATE INDEX points_geom_idx ON points USING GIST(geom)');
    }

    public function down(): void
    {
        Schema::dropIfExists('points');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('polylines', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jalur');
            $table->string('kecamatan');
            $table->string('kategori_objek'); // jenis jalur saat ini, misal: Jalan Aspal, Selokan, Rel
            $table->string('jenis_lama')->nullable();   // misal: Jalan Tanah
            $table->string('jenis_perubahan'); // jenis jalur sekarang / kondisi terbaru, misal: Jalan Aspal (Diperkeras)
            $table->integer('panjang_meter')->nullable(); // dihitung PostGIS
            $table->integer('tahun_perubahan');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE polylines ADD COLUMN geom geometry(LineString, 4326)');
        DB::statement('CREATE INDEX polylines_geom_idx ON polylines USING GIST(geom)');
    }

    public function down(): void
    {
        Schema::dropIfExists('polylines');
    }
};

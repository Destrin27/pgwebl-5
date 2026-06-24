<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('polygons', function (Blueprint $table) {
            $table->id();
            $table->string('nama_area');
            $table->string('kecamatan');
            $table->string('kategori_objek'); // jenis area saat ini, misal: Permukiman, Sawah, Kebun
            $table->string('penggunaan_lama');
            $table->string('penggunaan_baru');
            $table->decimal('luas_ha', 10, 4)->nullable(); // luas dalam hektar, dihitung PostGIS
            $table->integer('tahun_perubahan');
            $table->text('keterangan')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE polygons ADD COLUMN geom geometry(Polygon, 4326)');
        DB::statement('CREATE INDEX polygons_geom_idx ON polygons USING GIST(geom)');
    }

    public function down(): void
    {
        Schema::dropIfExists('polygons');
    }
};

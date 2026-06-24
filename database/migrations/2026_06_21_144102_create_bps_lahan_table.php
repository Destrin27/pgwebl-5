<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('bps_lahan', function (Blueprint $table) {
        $table->id();

        $table->string('kecamatan');
        $table->integer('tahun');

        $table->double('luas_sawah_ha')->default(0);
        $table->double('luas_bukan_sawah_ha')->default(0);
        $table->double('luas_bukan_pertanian_ha')->default(0);
        $table->double('luas_total_ha')->default(0);

        $table->string('sumber')->nullable();

        $table->timestamps();
        $table->unique(['kecamatan', 'tahun']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bps_lahan');
    }

};

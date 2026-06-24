<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bps_lahan', function (Blueprint $table) {
            $table->unique(['kecamatan', 'tahun'], 'bps_lahan_kecamatan_tahun_unique');
        });
    }

    public function down(): void
    {
        Schema::table('bps_lahan', function (Blueprint $table) {
            $table->dropUnique('bps_lahan_kecamatan_tahun_unique');
        });
    }
};

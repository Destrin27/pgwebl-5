<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bps_lahan', function (Blueprint $table) {
            $table->renameColumn('luas_sawah_ha',           'luas_pertanian_ha');
            $table->renameColumn('luas_bukan_sawah_ha',     'luas_terbangun_ha');
            $table->renameColumn('luas_bukan_pertanian_ha', 'luas_lainnya_ha');
        });
    }

    public function down(): void
    {
        Schema::table('bps_lahan', function (Blueprint $table) {
            $table->renameColumn('luas_pertanian_ha', 'luas_sawah_ha');
            $table->renameColumn('luas_terbangun_ha', 'luas_bukan_sawah_ha');
            $table->renameColumn('luas_lainnya_ha',   'luas_bukan_pertanian_ha');
        });
    }
};

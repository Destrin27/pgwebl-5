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
    // Kolom kategori_objek sudah ada di migration utama, skip
    if (!Schema::hasColumn('points', 'kategori_objek')) {
        Schema::table('points', function (Blueprint $table) {
            $table->string('kategori_objek')->nullable();
        });
    }
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('points', function (Blueprint $table) {
            //
        });
    }
};

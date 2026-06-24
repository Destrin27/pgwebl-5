<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class pointsModel extends Model
{
    protected $table    = 'points';
    protected $fillable = [
        'nama_lokasi', 'kecamatan', 'kategori_objek', 'penggunaan_lama',
        'penggunaan_baru', 'tahun_perubahan', 'keterangan',
        'foto', 'geom',
    ];
}

<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class polygonsModel extends Model
{
    protected $table    = 'polygons';
    protected $fillable = [
        'nama_area', 'kecamatan', 'kategori_objek', 'penggunaan_lama',
        'penggunaan_baru', 'luas_ha', 'tahun_perubahan',
        'keterangan', 'foto', 'geom',
    ];
}

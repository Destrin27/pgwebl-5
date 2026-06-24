<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class polylinesModel extends Model
{
    protected $table    = 'polylines';
    protected $fillable = [
        'nama_jalur', 'kecamatan', 'kategori_objek', 'jenis_lama',
        'jenis_perubahan', 'panjang_meter', 'tahun_perubahan', 'keterangan', 'geom',
    ];
}

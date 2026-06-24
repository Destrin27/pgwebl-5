<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class bpsLahanModel extends Model
{
    protected $table    = 'bps_lahan';
    protected $fillable = [
        'kecamatan', 'tahun',
        'luas_pertanian_ha',
        'luas_terbangun_ha',
        'luas_lainnya_ha',
        'luas_total_ha',
        'sumber',
    ];
}

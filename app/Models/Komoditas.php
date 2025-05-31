<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komoditas extends Model
{
    protected $fillable = [
        'nama_komoditas',
        'harga_komoditas',
        'jumlah_komoditas',
        'kebutuhan_rumah_tangga',
        'tempat_survey',
        'tgl_pelaksanaan',
        'minggu_dilakukan_survey',
    ];
}

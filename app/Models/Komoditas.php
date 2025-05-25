<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komoditas extends Model
{
    protected $fillable = [
        'nama_komoditas',
        'harga_komoditas',
        'jumlah_komoditas',
        'tempat_survey',
        'tgl_pelaksanaan',
    ];
}

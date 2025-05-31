<?php

namespace App\Http\Controllers;

use App\Models\Komoditas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetugasController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $ketersediaanPangan = Komoditas::sum('jumlah_komoditas');
        $kebutuhanPangan = Komoditas::sum('kebutuhan_rumah_tangga');

        return view('dashboard.petugas.dashboard_petugas', compact('user', 'ketersediaanPangan', 'kebutuhanPangan'));
    }
}

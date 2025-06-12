<?php

namespace App\Http\Controllers;

use App\Models\Komoditas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EksekutifController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $ketersediaanPangan = Komoditas::sum('jumlah_komoditas');
        $kebutuhanPangan = Komoditas::sum('kebutuhan_rumah_tangga');

        $surveyBelumDiAcc = Komoditas::where('status_verifikasi', 'belum_diverifikasi')->count();

        $daftarPasar = Komoditas::select('tempat_survey')->distinct()->pluck('tempat_survey');
        $nama_komoditas = DB::table('komoditas')->select('nama_komoditas')->distinct()->pluck('nama_komoditas');

        return view('dashboard.eksekutif.dashboard_eksekutif', compact('user', 'ketersediaanPangan', 'kebutuhanPangan', 'surveyBelumDiAcc', 'daftarPasar', 'nama_komoditas'));
    }
}

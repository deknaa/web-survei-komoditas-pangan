<?php

namespace App\Http\Controllers;

use App\Models\Komoditas;
use Illuminate\Http\Request;

class NeracaController extends Controller
{
    public function index()
    {
        $komoditasList = Komoditas::select('nama_komoditas')->distinct()->pluck('nama_komoditas');
        return view('dashboard.petugas.neraca_pangan', compact('komoditasList'));
    }

    public function search(Request $request)
    {
        $query = Komoditas::query();

        if ($request->nama_komoditas) {
            $query->where('nama_komoditas', $request->nama_komoditas);
        }

        if ($request->tahun) {
            $query->whereYear('tgl_pelaksanaan', $request->tahun);
        }

        if ($request->bulan) {
            $query->whereMonth('tgl_pelaksanaan', $request->bulan);
        }

        if ($request->pasar) {
            $query->where('tempat_survey', $request->pasar);
        }

        if ($request->minggu) {
            $query->where('minggu_dilakukan_survey', $request->minggu);
        }

        return response()->json($query->get());
    }
}

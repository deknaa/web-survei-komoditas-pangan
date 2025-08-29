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

    public function create()
    {
        $komoditasList = Komoditas::select('nama_komoditas')->distinct()->pluck('nama_komoditas');
        return view('dashboard.petugas.neraca_pangan_add', compact('komoditasList'));
    }

    public function hitung(Request $request)
    {
        // Ambil data komoditas sesuai filter
        $ketersediaan = Komoditas::where('nama_komoditas', $request->nama_komoditas)
            ->whereYear('tgl_pelaksanaan', $request->tahun)
            ->whereMonth('tgl_pelaksanaan', $request->bulan)
            ->when($request->minggu, fn($q) => $q->where('minggu_dilakukan_survey', $request->minggu))
            ->when($request->pasar, fn($q) => $q->where('tempat_survey', $request->pasar))
            ->sum('jumlah_komoditas');

        $kebutuhan = Komoditas::where('nama_komoditas', $request->nama_komoditas)
            ->whereYear('tgl_pelaksanaan', $request->tahun)
            ->whereMonth('tgl_pelaksanaan', $request->bulan)
            ->when($request->minggu, fn($q) => $q->where('minggu_dilakukan_survey', $request->minggu))
            ->when($request->pasar, fn($q) => $q->where('tempat_survey', $request->pasar))
            ->sum('kebutuhan_rumah_tangga');

        $neraca = $ketersediaan - $kebutuhan;

        return response()->json([
            'ketersediaan' => number_format($ketersediaan, 3, ',', '.'),
            'kebutuhan'    => number_format($kebutuhan, 3, ',', '.'),
            'neraca'       => number_format($neraca, 3, ',', '.'),
            'satuan'       => 'Ton'
        ]);
    }
}

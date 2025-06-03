<?php

namespace App\Http\Controllers;

use App\Models\Komoditas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PetugasController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $ketersediaanPangan = Komoditas::sum('jumlah_komoditas');
        $kebutuhanPangan = Komoditas::sum('kebutuhan_rumah_tangga');

        $surveyBelumDiAcc = Komoditas::where('status_verifikasi', 'belum_diverifikasi')->count();

        $daftarPasar = Komoditas::select('tempat_survey')->distinct()->pluck('tempat_survey');
        $nama_komoditas = DB::table('komoditas')->select('nama_komoditas')->distinct()->pluck('nama_komoditas');

        return view('dashboard.petugas.dashboard_petugas', compact('user', 'ketersediaanPangan', 'kebutuhanPangan', 'surveyBelumDiAcc', 'daftarPasar', 'nama_komoditas'));
    }

    public function getKomoditasList(Request $request)
    {
        $pasar = $request->pasar;
        $tanggal = $request->tanggal;

        $komoditas = Komoditas::where('tempat_survey', $pasar)
            ->whereDate('tgl_pelaksanaan', '>=', $tanggal)
            ->select('nama_komoditas')
            ->distinct()
            ->pluck('nama_komoditas');

        return response()->json($komoditas);
    }

    public function getHargaKomoditas(Request $request)
    {
        $pasar = $request->pasar;
        $tanggal = $request->tanggal;
        $komoditas = $request->komoditas;

        $data = Komoditas::where('tempat_survey', $pasar)
            ->where('nama_komoditas', $komoditas)
            ->whereDate('tgl_pelaksanaan', '>=', $tanggal)
            ->orderBy('tgl_pelaksanaan')
            ->get(['tgl_pelaksanaan', 'harga_komoditas']);

        $labels = $data->pluck('tgl_pelaksanaan')->map(function ($tgl) {
            return \Carbon\Carbon::parse($tgl)->format('d M Y');
        });

        $harga = $data->pluck('harga_komoditas');

        return response()->json([
            'labels' => $labels,
            'datasets' => [[
                'label' => "Harga {$komoditas}",
                'data' => $harga,
                'borderColor' => 'rgba(255, 99, 132, 1)',
                'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                'fill' => true,
            ]]
        ]);
    }

    public function getData(Request $request)
    {
        $data = DB::table('komoditas')
            ->select('tempat_survey', 'minggu_dilakukan_survey', DB::raw('SUM(jumlah_komoditas) as total'))
            ->where('nama_komoditas', $request->nama_komoditas)
            ->whereYear('tgl_pelaksanaan', $request->tahun)
            ->whereMonth('tgl_pelaksanaan', $request->bulan)
            ->groupBy('tempat_survey', 'minggu_dilakukan_survey')
            ->get();

        $result = [];

        foreach ($data as $item) {
            $pasar = $item->tempat_survey;
            if (!isset($result[$pasar])) {
                $result[$pasar] = [
                    'week1' => 0,
                    'week2' => 0,
                    'week3' => 0,
                    'week4' => 0,
                ];
            }
            $result[$pasar]['week' . $item->minggu_dilakukan_survey] = $item->total;
        }

        return response()->json($result);
    }
}

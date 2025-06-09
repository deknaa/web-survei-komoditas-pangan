<?php

namespace App\Http\Controllers;

use App\Models\Komoditas;
use Illuminate\Http\Request;

class KomoditasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $komoditas = Komoditas::all();
        return view('komoditas.index', compact('komoditas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $komoditas = ['Beras', 'Gula Pasir', 'Tepung Terigu', 'Minyak Goreng', 'Daging Babi', 'Daging Sapi', 'Daging Ayam', 'Telur Ayam', 'Cabai Besar/Merah', 'Cabai Rawit', 'Bawang Merah', 'Bawang Putih', 'Jeruk', 'Pisang', 'Jagung', 'Ubi Jalar', 'Tomat', 'Ikan Tongkol', 'Ikan Lele', 'Kelapa'];

        return view('komoditas.create', compact('komoditas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $komoditas = $request->validate([
            'nama_komoditas' => 'required|string',
            'harga_komoditas' => 'required|numeric',
            'jumlah_komoditas' => 'required|numeric',
            'kebutuhan_rumah_tangga' => 'required|numeric',
            'tempat_survey' => 'required|string|in:pasar_kediri,pasar_baturiti,pasar_pesiapan,pasar_tabanan',
            'tgl_pelaksanaan' => 'required|date',
            'minggu_dilakukan_survey' => 'required|numeric',
        ]);

        Komoditas::create($komoditas);

        return redirect()->route('komoditas.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Komoditas $komoditas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Komoditas $komoditas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'nama_komoditas' => 'nullable|string',
            'harga_komoditas' => 'nullable|numeric',
            'jumlah_komoditas' => 'nullable|numeric',
            'tempat_survey' => 'nullable|string',
            'tgl_pelaksanaan' => 'nullable|date',
        ]);

        $komoditas = Komoditas::where('id', $id)->firstOrFail();
        $komoditas->update($data);

        return redirect()->route('komoditas.index')->with('Data komoditas berhasil diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $komoditas = Komoditas::where('id', $id)->firstOrFail();
        $komoditas->delete();

        return redirect()->route('komoditas.index')->with('sucess', 'Data Komoditas berhasil di hapus');
    }
}

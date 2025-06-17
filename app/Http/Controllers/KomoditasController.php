<?php

namespace App\Http\Controllers;

use App\Models\Komoditas;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class KomoditasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $komoditas = Komoditas::orderBy('tgl_pelaksanaan', 'desc')->paginate(10); // 10 data per halaman
        $namaKomoditas = ['Beras', 'Gula Pasir', 'Tepung Terigu', 'Minyak Goreng', 'Daging Babi', 'Daging Sapi', 'Daging Ayam', 'Telur Ayam', 'Cabai Besar/Merah', 'Cabai Rawit', 'Bawang Merah', 'Bawang Putih', 'Jeruk', 'Pisang', 'Jagung', 'Ubi Jalar', 'Tomat', 'Ikan Tongkol', 'Ikan Lele', 'Kelapa'];

        return view('komoditas.index', compact('komoditas', 'namaKomoditas'));
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
        $request->validate([
            'nama_komoditas' => 'required',
            'harga_komoditas' => 'required|numeric',
            'jumlah_komoditas' => 'required|numeric',
            'kebutuhan_rumah_tangga' => 'required|numeric',
            'tempat_survey' => 'required',
            'tgl_pelaksanaan' => 'required|date',
            'minggu_dilakukan_survey' => 'required|numeric',
        ]);

        // Cek apakah data untuk komoditas dan minggu tersebut sudah ada
        $existing = Komoditas::where('nama_komoditas', $request->nama_komoditas)
            ->where('minggu_dilakukan_survey', $request->minggu_dilakukan_survey)
            ->where('tempat_survey', $request->tempat_survey)
            ->whereYear('tgl_pelaksanaan', Carbon::parse($request->tgl_pelaksanaan)->year)
            ->whereMonth('tgl_pelaksanaan', Carbon::parse($request->tgl_pelaksanaan)->month)
            ->first();

        if ($existing) {
            return back()
                ->withErrors(['msg' => 'Data untuk komoditas ini pada minggu dan tempat tersebut sudah ada.'])
                ->withInput();
        }

        // Simpan data baru
        Komoditas::create([
            'nama_komoditas' => $request->nama_komoditas,
            'harga_komoditas' => $request->harga_komoditas,
            'jumlah_komoditas' => $request->jumlah_komoditas,
            'kebutuhan_rumah_tangga' => $request->kebutuhan_rumah_tangga,
            'tempat_survey' => $request->tempat_survey,
            'tgl_pelaksanaan' => $request->tgl_pelaksanaan,
            'minggu_dilakukan_survey' => $request->minggu_dilakukan_survey,
        ]);

        return redirect()->route('komoditas.index')->with('success', 'Data komoditas berhasil disimpan.');
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
        'kebutuhan_rumah_tangga' => 'nullable|numeric',
        'tempat_survey' => 'nullable|string',
        'tgl_pelaksanaan' => 'nullable|date',
        'minggu_dilakukan_survey' => 'nullable|numeric',
    ]);

    $komoditas = Komoditas::where('id', $id)->firstOrFail();
    $komoditas->update($data);

    return redirect()->route('komoditas.index')->with('success', 'Data komoditas berhasil diperbaharui.');
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

    public function verifikasi($id)
    {
        if (auth()->user()->role !== 'eksekutif') {
            abort(403, 'Unauthorized action.');
        }

        $komoditas = Komoditas::findOrFail($id);
        $komoditas->status_verifikasi = 'sudah_diverifikasi';
        $komoditas->save();

        return redirect()->back()->with('success', 'Data berhasil diverifikasi.');
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

        $data = $query->orderBy('tgl_pelaksanaan', 'desc')->get();

        return response()->json($data);
    }

    public function export(Request $request)
    {
        // Ambil data berdasarkan filter yang sama dengan search
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

        $data = $query->orderBy('tgl_pelaksanaan', 'desc')->get();

        // Buat spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set judul dokumen
        $sheet->setTitle('Data Neraca Pangan');

        // Header informasi
        $sheet->setCellValue('A1', 'LAPORAN DATA NERACA PANGAN');
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Info filter
        $filterInfo = $this->buildFilterInfo($request);
        $sheet->setCellValue('A2', $filterInfo);
        $sheet->mergeCells('A2:I2');
        $sheet->getStyle('A2')->getFont()->setSize(12);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A3', 'Tanggal Export: ' . date('d F Y H:i:s'));
        $sheet->mergeCells('A3:I3');
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header tabel
        $headers = [
            'A5' => 'No',
            'B5' => 'Nama Komoditas',
            'C5' => 'Harga (Rp)',
            'D5' => 'Jumlah',
            'E5' => 'Kebutuhan RT',
            'F5' => 'Tempat Survey',
            'G5' => 'Tanggal Pelaksanaan',
            'H5' => 'Minggu Survey',
            'I5' => 'Status Verifikasi'
        ];

        foreach ($headers as $cell => $header) {
            $sheet->setCellValue($cell, $header);
        }

        // Style header
        $sheet->getStyle('A5:I5')->getFont()->setBold(true);
        $sheet->getStyle('A5:I5')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('4472C4');
        $sheet->getStyle('A5:I5')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A5:I5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Data
        $row = 6;
        foreach ($data as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $item->nama_komoditas);
            $sheet->setCellValue('C' . $row, number_format($item->harga_komoditas, 0, ',', '.'));
            $sheet->setCellValue('D' . $row, $item->jumlah_komoditas);
            $sheet->setCellValue('E' . $row, $item->kebutuhan_rumah_tangga);
            $sheet->setCellValue('F' . $row, $this->getPasarName($item->tempat_survey));
            $sheet->setCellValue('G' . $row, date('d/m/Y', strtotime($item->tgl_pelaksanaan)));
            $sheet->setCellValue('H' . $row, $item->minggu_dilakukan_survey);
            $sheet->setCellValue('I' . $row, $this->getStatusText($item->status_verifikasi ?? 'pending'));
            $row++;
        }

        // Style data
        if ($data->count() > 0) {
            $dataRange = 'A6:I' . ($row - 1);
            $sheet->getStyle($dataRange)->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle('C6:C' . ($row - 1))->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('A6:A' . ($row - 1))->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('G6:I' . ($row - 1))->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // Auto size columns
        foreach (range('A', 'I') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Border untuk header
        $sheet->getStyle('A5:I5')->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Summary di bagian bawah
        if ($data->count() > 0) {
            $summaryRow = $row + 2;
            $sheet->setCellValue('A' . $summaryRow, 'RINGKASAN DATA');
            $sheet->mergeCells('A' . $summaryRow . ':C' . $summaryRow);
            $sheet->getStyle('A' . $summaryRow)->getFont()->setBold(true);

            $summaryRow++;
            $sheet->setCellValue('A' . $summaryRow, 'Total Data:');
            $sheet->setCellValue('B' . $summaryRow, $data->count() . ' record');

            $summaryRow++;
            $sheet->setCellValue('A' . $summaryRow, 'Rata-rata Harga:');
            $avgPrice = $data->avg('harga_komoditas');
            $sheet->setCellValue('B' . $summaryRow, 'Rp ' . number_format($avgPrice, 0, ',', '.'));

            $summaryRow++;
            $sheet->setCellValue('A' . $summaryRow, 'Harga Tertinggi:');
            $maxPrice = $data->max('harga_komoditas');
            $sheet->setCellValue('B' . $summaryRow, 'Rp ' . number_format($maxPrice, 0, ',', '.'));

            $summaryRow++;
            $sheet->setCellValue('A' . $summaryRow, 'Harga Terendah:');
            $minPrice = $data->min('harga_komoditas');
            $sheet->setCellValue('B' . $summaryRow, 'Rp ' . number_format($minPrice, 0, ',', '.'));
        }

        // Generate filename
        $filename = $this->generateFilename($request);

        // Set header untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private function buildFilterInfo($request)
    {
        $filters = [];

        if ($request->nama_komoditas) {
            $filters[] = "Komoditas: " . $request->nama_komoditas;
        } else {
            $filters[] = "Komoditas: Semua";
        }

        if ($request->tahun) {
            $filters[] = "Tahun: " . $request->tahun;
        }

        if ($request->bulan) {
            $bulanName = DateTime::createFromFormat('!m', $request->bulan)->format('F');
            $filters[] = "Bulan: " . $bulanName;
        }

        if ($request->pasar) {
            $filters[] = "Pasar: " . $this->getPasarName($request->pasar);
        } else {
            $filters[] = "Pasar: Semua";
        }

        if ($request->minggu) {
            $filters[] = "Minggu: Ke-" . $request->minggu;
        } else {
            $filters[] = "Minggu: Semua";
        }

        return implode(' | ', $filters);
    }

    private function generateFilename($request)
    {
        $parts = ['neraca_pangan'];

        if ($request->nama_komoditas) {
            $parts[] = strtolower(str_replace(' ', '_', $request->nama_komoditas));
        }

        if ($request->tahun) {
            $parts[] = $request->tahun;
        }

        if ($request->bulan) {
            $parts[] = 'bulan_' . str_pad($request->bulan, 2, '0', STR_PAD_LEFT);
        }

        if ($request->pasar) {
            $parts[] = $request->pasar;
        }

        if ($request->minggu) {
            $parts[] = 'minggu_' . $request->minggu;
        }

        $parts[] = date('Y_m_d_H_i_s');

        return implode('_', $parts) . '.xlsx';
    }

    private function getPasarName($pasar)
    {
        $pasarNames = [
            'pasar_kediri' => 'Pasar Kediri',
            'pasar_baturiti' => 'Pasar Baturiti',
            'pasar_pesiapan' => 'Pasar Pesiapan',
            'pasar_tabanan' => 'Pasar Tabanan'
        ];

        return $pasarNames[$pasar] ?? ucwords(str_replace('_', ' ', $pasar));
    }

    private function getStatusText($status)
    {
        switch ($status) {
            case 'sudah_diverifikasi':
                return 'Terverifikasi';
            case 'belum_diverifikasi':
                return 'Belum Terverifikasi';
            default:
                return 'Belum Terverifikasi';
        }
    }
}

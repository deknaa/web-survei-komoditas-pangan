<?php

namespace App\Exports;

use Maatwebsite\Excel\Classes\PHPExcel;

class KomoditasExport
{
    protected $komoditas;

    public function __construct($komoditas)
    {
        $this->komoditas = $komoditas;
    }

    /**
     * Export ke Excel menggunakan versi 1.1
     */
    public function export()
    {
        // Create new PHPExcel object
        $excel = new PHPExcel();
        
        // Set document properties
        $excel->getProperties()
            ->setCreator(auth()->user()->name)
            ->setLastModifiedBy(auth()->user()->name)
            ->setTitle('Data Komoditas Pangan')
            ->setSubject('Export Data Komoditas')
            ->setDescription('Data komoditas pangan dari sistem monitoring')
            ->setKeywords('komoditas pangan export excel')
            ->setCategory('Report');

        // Add some data
        $excel->setActiveSheetIndex(0);
        $sheet = $excel->getActiveSheet();
        
        // Set sheet title
        $sheet->setTitle('Data Komoditas Pangan');
        
        // Headers
        $headers = [
            'A1' => 'No',
            'B1' => 'Nama Komoditas',
            'C1' => 'Harga Komoditas (Rp)',
            'D1' => 'Ketersediaan (Ton)',
            'E1' => 'Kebutuhan Rumah Tangga (Ton)',
            'F1' => 'Selisih (Ton)',
            'G1' => 'Tempat Survey',
            'H1' => 'Tanggal Pelaksanaan',
            'I1' => 'Minggu Survey',
            'J1' => 'Status Verifikasi',
            'K1' => 'Diexport Pada',
            'L1' => 'Diexport Oleh'
        ];
        
        // Set headers
        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }
        
        // Style headers
        $sheet->getStyle('A1:L1')->getFont()->setBold(true);
        $sheet->getStyle('A1:L1')->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()->setRGB('4472C4');
        $sheet->getStyle('A1:L1')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A1:L1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        // Data rows
        $row = 2;
        foreach ($this->komoditas as $index => $item) {
            $selisih = $item->jumlah_komoditas - $item->kebutuhan_rumah_tangga;
            
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $item->nama_komoditas);
            $sheet->setCellValue('C' . $row, number_format($item->harga_komoditas, 0, ',', '.'));
            $sheet->setCellValue('D' . $row, number_format($item->jumlah_komoditas, 2, ',', '.'));
            $sheet->setCellValue('E' . $row, number_format($item->kebutuhan_rumah_tangga, 2, ',', '.'));
            $sheet->setCellValue('F' . $row, number_format($selisih, 2, ',', '.') . ($selisih >= 0 ? ' (Surplus)' : ' (Defisit)'));
            $sheet->setCellValue('G' . $row, ucwords(str_replace('_', ' ', $item->tempat_survey)));
            $sheet->setCellValue('H' . $row, date('d/m/Y', strtotime($item->tgl_pelaksanaan)));
            $sheet->setCellValue('I' . $row, 'Minggu ke-' . $item->minggu_dilakukan_survey);
            $sheet->setCellValue('J' . $row, $item->status_verifikasi == 'sudah_diverifikasi' ? 'Terverifikasi' : 'Belum Terverifikasi');
            $sheet->setCellValue('K' . $row, now()->format('d/m/Y H:i:s'));
            $sheet->setCellValue('L' . $row, auth()->user()->name);
            
            $row++;
        }
        
        // Auto size columns
        foreach (range('A', 'L') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        
        // Set borders
        $lastRow = $row - 1;
        $sheet->getStyle('A1:L' . $lastRow)->getBorders()->getAllBorders()
            ->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        
        return $excel;
    }
}
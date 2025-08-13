<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 15px;
            size: A4 landscape;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
            margin: 0;
            padding: 0;
            color: #333;
            line-height: 1.3;
        }
        
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #2c5aa0;
            padding-bottom: 15px;
        }
        
        .header h1 {
            color: #2c5aa0;
            margin: 0;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .header .subtitle {
            color: #666;
            margin: 8px 0 5px 0;
            font-size: 12px;
            font-style: italic;
        }
        
        .header .department {
            color: #2c5aa0;
            font-size: 10px;
            font-weight: bold;
            margin-top: 5px;
        }
        
        .export-info {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 12px 15px;
            border-left: 5px solid #2c5aa0;
            margin-bottom: 20px;
            border-radius: 0 5px 5px 0;
        }
        
        .export-info-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .export-info-table td {
            padding: 4px 10px;
            font-size: 9px;
            border: none;
        }
        
        .export-info-table td:first-child {
            font-weight: bold;
            width: 120px;
            color: #2c5aa0;
        }
        
        .export-info-table td:nth-child(3) {
            font-weight: bold;
            width: 120px;
            color: #2c5aa0;
        }
        
        .summary-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
        }
        
        .summary-section h3 {
            margin: 0 0 12px 0;
            color: #2c5aa0;
            font-size: 13px;
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
        }
        
        .summary-grid {
            display: table;
            width: 100%;
            table-layout: fixed;
        }
        
        .summary-row {
            display: table-row;
        }
        
        .summary-col {
            display: table-cell;
            text-align: center;
            padding: 8px;
            vertical-align: middle;
        }
        
        .stat-number {
            font-size: 18px;
            font-weight: bold;
            color: #2c5aa0;
            display: block;
            margin-bottom: 3px;
        }
        
        .stat-label {
            font-size: 8px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 8px;
        }
        
        .data-table th {
            background: linear-gradient(135deg, #2c5aa0 0%, #1e3d72 100%);
            color: white;
            padding: 8px 4px;
            text-align: center;
            font-weight: bold;
            font-size: 8px;
            border: 1px solid #1e3d72;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .data-table td {
            padding: 6px 4px;
            border: 1px solid #ddd;
            font-size: 7px;
            text-align: center;
            vertical-align: middle;
        }
        
        .data-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .data-table tr:nth-child(odd) {
            background-color: #ffffff;
        }
        
        .data-table tbody tr:hover {
            background-color: #e3f2fd;
        }
        
        /* Column specific styles */
        .col-no { width: 3%; }
        .col-nama { width: 15%; text-align: left !important; }
        .col-harga { width: 11%; text-align: right !important; }
        .col-tersedia { width: 8%; }
        .col-kebutuhan { width: 8%; }
        .col-selisih { width: 12%; }
        .col-tempat { width: 13%; text-align: left !important; }
        .col-tanggal { width: 8%; }
        .col-minggu { width: 6%; }
        .col-status { width: 12%; }
        
        .text-left { text-align: left !important; }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        
        .status-badge {
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 6px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .status-verified {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .status-unverified {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .surplus {
            color: #28a745;
            font-weight: bold;
        }
        
        .deficit {
            color: #dc3545;
            font-weight: bold;
        }
        
        .neutral {
            color: #6c757d;
            font-weight: bold;
        }
        
        .currency {
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 25px;
            text-align: center;
            font-size: 7px;
            color: #666;
            border-top: 2px solid #dee2e6;
            padding-top: 10px;
        }
        
        .footer-info {
            margin-bottom: 5px;
        }
        
        .page-info {
            color: #999;
            font-style: italic;
        }
        
        .no-data {
            text-align: center;
            padding: 50px 20px;
            color: #666;
            background-color: #f8f9fa;
            border-radius: 8px;
            border: 2px dashed #dee2e6;
        }
        
        .no-data h3 {
            color: #495057;
            font-size: 16px;
            margin-bottom: 10px;
        }
        
        .no-data p {
            font-size: 10px;
            line-height: 1.5;
        }
        
        /* Filter info section */
        .filter-info {
            background-color: #e3f2fd;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            border-left: 4px solid #1976d2;
        }
        
        .filter-info h4 {
            margin: 0 0 8px 0;
            color: #1976d2;
            font-size: 10px;
            font-weight: bold;
        }
        
        .filter-item {
            display: inline-block;
            background-color: white;
            padding: 3px 8px;
            margin: 2px 5px 2px 0;
            border-radius: 12px;
            font-size: 7px;
            border: 1px solid #90caf9;
            color: #1976d2;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="subtitle">Sistem Informasi Monitoring Harga dan Ketersediaan Pangan</div>
        <div class="department">Dinas Ketahanan Pangan dan Pertanian</div>
    </div>
    
    <!-- Export Information -->
    <div class="export-info">
        <table class="export-info-table">
            <tr>
                <td>Tanggal Export:</td>
                <td><strong>{{ $exported_at }}</strong></td>
                <td>Diexport Oleh:</td>
                <td><strong>{{ $exported_by }}</strong></td>
            </tr>
            <tr>
                <td>Total Data:</td>
                <td><strong>{{ $komoditas->count() }} record</strong></td>
                <td>Status Data:</td>
                <td><strong>Data Aktual Per Tanggal Export</strong></td>
            </tr>
        </table>
    </div>
    
    <!-- Filter Information (if any filters applied) -->
    @php
        $hasFilters = request()->filled(['tempat_survey', 'status_verifikasi', 'tanggal_dari', 'tanggal_sampai']);
    @endphp
    
    @if($hasFilters)
    <div class="filter-info">
        <h4>Filter yang Diterapkan:</h4>
        @if(request()->filled('tempat_survey'))
            <span class="filter-item">Tempat: {{ ucwords(str_replace('_', ' ', request('tempat_survey'))) }}</span>
        @endif
        @if(request()->filled('status_verifikasi'))
            <span class="filter-item">Status: {{ request('status_verifikasi') == 'sudah_diverifikasi' ? 'Terverifikasi' : 'Belum Terverifikasi' }}</span>
        @endif
        @if(request()->filled('tanggal_dari'))
            <span class="filter-item">Dari: {{ date('d/m/Y', strtotime(request('tanggal_dari'))) }}</span>
        @endif
        @if(request()->filled('tanggal_sampai'))
            <span class="filter-item">Sampai: {{ date('d/m/Y', strtotime(request('tanggal_sampai'))) }}</span>
        @endif
    </div>
    @endif
    
    @if($komoditas->count() > 0)
    <!-- Summary Statistics -->
    <div class="summary-section">
        <h3>Ringkasan Data Komoditas</h3>
        <div class="summary-grid">
            <div class="summary-row">
                <div class="summary-col">
                    <span class="stat-number">{{ $komoditas->count() }}</span>
                    <span class="stat-label">Total Komoditas</span>
                </div>
                <div class="summary-col">
                    <span class="stat-number">{{ $komoditas->where('status_verifikasi', 'sudah_diverifikasi')->count() }}</span>
                    <span class="stat-label">Terverifikasi</span>
                </div>
                <div class="summary-col">
                    <span class="stat-number">{{ $komoditas->where('status_verifikasi', 'belum_diverifikasi')->count() }}</span>
                    <span class="stat-label">Belum Verifikasi</span>
                </div>
                <div class="summary-col">
                    <span class="stat-number">{{ $komoditas->groupBy('tempat_survey')->count() }}</span>
                    <span class="stat-label">Lokasi Survey</span>
                </div>
                <div class="summary-col">
                    <span class="stat-number">Rp {{ number_format($komoditas->avg('harga_komoditas'), 0, ',', '.') }}</span>
                    <span class="stat-label">Rata-rata Harga</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-nama">Nama Komoditas</th>
                <th class="col-harga">Harga (Rp)</th>
                <th class="col-tersedia">Tersedia (Ton)</th>
                <th class="col-kebutuhan">Kebutuhan (Ton)</th>
                <th class="col-selisih">Selisih & Status</th>
                <th class="col-tempat">Tempat Survey</th>
                <th class="col-tanggal">Tanggal</th>
                <th class="col-minggu">Minggu</th>
                <th class="col-status">Status Verifikasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($komoditas as $index => $item)
            @php
                $selisih = $item->jumlah_komoditas - $item->kebutuhan_rumah_tangga;
                if ($selisih > 0) {
                    $selisihClass = 'surplus';
                    $selisihText = 'Surplus';
                    $selisihIcon = '↑';
                } elseif ($selisih < 0) {
                    $selisihClass = 'deficit';
                    $selisihText = 'Defisit';
                    $selisihIcon = '↓';
                } else {
                    $selisihClass = 'neutral';
                    $selisihText = 'Seimbang';
                    $selisihIcon = '=';
                }
            @endphp
            <tr>
                <td class="col-no">{{ $index + 1 }}</td>
                <td class="col-nama text-left">
                    <strong>{{ $item->nama_komoditas }}</strong>
                </td>
                <td class="col-harga text-right currency">
                    {{ number_format($item->harga_komoditas, 0, ',', '.') }}
                </td>
                <td class="col-tersedia">
                    {{ number_format($item->jumlah_komoditas, 1, ',', '.') }}
                </td>
                <td class="col-kebutuhan">
                    {{ number_format($item->kebutuhan_rumah_tangga, 1, ',', '.') }}
                </td>
                <td class="col-selisih">
                    <div class="{{ $selisihClass }}">
                        <strong>{{ $selisihIcon }} {{ number_format(abs($selisih), 1, ',', '.') }}</strong>
                    </div>
                    <small>({{ $selisihText }})</small>
                </td>
                <td class="col-tempat text-left">
                    {{ ucwords(str_replace('_', ' ', $item->tempat_survey)) }}
                </td>
                <td class="col-tanggal">
                    {{ date('d/m/Y', strtotime($item->tgl_pelaksanaan)) }}
                </td>
                <td class="col-minggu">
                    Ke-{{ $item->minggu_dilakukan_survey }}
                </td>
                <td class="col-status">
                    @if($item->status_verifikasi == 'sudah_diverifikasi')
                        <span class="status-badge status-verified">✓ Terverifikasi</span>
                    @else
                        <span class="status-badge status-unverified">⏳ Pending</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Summary Footer -->
    <div style="margin-top: 15px; font-size: 8px; color: #666;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 33%; text-align: left;">
                    <strong>Total Ketersediaan:</strong> {{ number_format($komoditas->sum('jumlah_komoditas'), 1, ',', '.') }} Ton
                </td>
                <td style="width: 34%; text-align: center;">
                    <strong>Total Kebutuhan:</strong> {{ number_format($komoditas->sum('kebutuhan_rumah_tangga'), 1, ',', '.') }} Ton
                </td>
                <td style="width: 33%; text-align: right;">
                    @php
                        $totalSelisih = $komoditas->sum('jumlah_komoditas') - $komoditas->sum('kebutuhan_rumah_tangga');
                    @endphp
                    <strong>Selisih Total:</strong> 
                    <span class="{{ $totalSelisih >= 0 ? 'surplus' : 'deficit' }}">
                        {{ number_format($totalSelisih, 1, ',', '.') }} Ton
                    </span>
                </td>
            </tr>
        </table>
    </div>
    
    @else
    <!-- No Data Section -->
    <div class="no-data">
        <h3>📊 Tidak Ada Data untuk Diekspor</h3>
        <p>Mohon periksa filter yang diterapkan atau pastikan data komoditas sudah tersedia di sistem.</p>
        <p><strong>Kemungkinan penyebab:</strong></p>
        <ul style="text-align: left; display: inline-block; margin-top: 10px;">
            <li>Filter tanggal terlalu spesifik</li>
            <li>Tidak ada data untuk tempat survey yang dipilih</li>
            <li>Status verifikasi tidak sesuai dengan data yang ada</li>
        </ul>
    </div>
    @endif
    
    <!-- Footer -->
    <div class="footer">
        <div class="footer-info">
            <strong>Sistem Informasi Komoditas Pangan</strong> | 
            Dinas Ketahanan Pangan dan Pertanian | 
            {{ now()->format('Y') }}
        </div>
        <div class="page-info">
            Dokumen ini digenerate secara otomatis pada {{ $exported_at }}
        </div>
    </div>
</body>
</html>
@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-2 text-gray-800">
                    <i class="fas fa-balance-scale text-primary me-2"></i>
                    Neraca Pangan
                </h1>
                <p class="text-muted mb-0">Sistem monitoring dan analisis data pangan regional</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary btn-sm" id="exportBtn">
                    <i class="fas fa-download me-1"></i>
                    Export Data
                </button>
                <button class="btn btn-primary btn-sm" id="refreshBtn">
                    <i class="fas fa-sync-alt me-1"></i>
                    Refresh
                </button>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-gradient-primary text-white">
                <div class="d-flex align-items-center">
                    <i class="fas fa-filter me-2"></i>
                    <h6 class="mb-0">Filter Pencarian Data</h6>
                </div>
            </div>
            <div class="card-body bg-light">
                <form id="filterForm">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <label for="nama_komoditas" class="form-label fw-bold">
                                <i class="fas fa-seedling text-success me-1"></i>
                                Nama Komoditas
                            </label>
                            <select id="nama_komoditas" class="form-select form-select-modern">
                                <option value="">Semua Komoditas</option>
                                @foreach ($komoditasList as $k)
                                    <option value="{{ $k }}">{{ $k }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label for="tahun" class="form-label fw-bold">
                                <i class="fas fa-calendar text-info me-1"></i>
                                Tahun
                            </label>
                            <select id="tahun" class="form-select form-select-modern">
                                @for ($i = date('Y'); $i >= 2020; $i--)
                                    <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label for="bulan" class="form-label fw-bold">
                                <i class="fas fa-calendar-alt text-warning me-1"></i>
                                Bulan
                            </label>
                            <select id="bulan" class="form-select form-select-modern">
                                @foreach (range(1, 12) as $b)
                                    <option value="{{ $b }}" {{ $b == date('n') ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $b)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label for="pasar" class="form-label fw-bold">
                                <i class="fas fa-store text-danger me-1"></i>
                                Pasar
                            </label>
                            <select id="pasar" class="form-select form-select-modern">
                                <option value="">Semua Pasar</option>
                                <option value="pasar_kediri">Pasar Kediri</option>
                                <option value="pasar_baturiti">Pasar Baturiti</option>
                                <option value="pasar_pesiapan">Pasar Pesiapan</option>
                                <option value="pasar_tabanan">Pasar Tabanan</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label for="minggu" class="form-label fw-bold">
                                <i class="fas fa-clock text-secondary me-1"></i>
                                Minggu Ke
                            </label>
                            <select id="minggu" class="form-select form-select-modern">
                                <option value="">Semua Minggu</option>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}">Minggu ke-{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-lg-1 col-md-6 d-flex align-items-end">
                            <button type="button" id="cariData" class="btn btn-primary w-100 btn-search">
                                <i class="fas fa-search me-1"></i>
                                <span class="d-none d-md-inline">Cari</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Summary Card -->
        <div class="row mb-4" id="summaryCards" style="display: none;">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-left-primary shadow-sm h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Data
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalData">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-database fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-left-info shadow-sm h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Avg Price
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="avgPrice">Rp 0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="card shadow-sm">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-table me-2"></i>
                    Data Neraca Pangan
                </h6>
                <div class="d-flex gap-2">
                    <span class="badge bg-secondary" id="dataCount">0 data</span>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-cog me-1"></i>
                            Options
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" id="selectAll"><i class="fas fa-check-square me-2"></i>Select All</a></li>
                            <li><a class="dropdown-item" href="#" id="clearSelection"><i class="fas fa-square me-2"></i>Clear Selection</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" id="printTable"><i class="fas fa-print me-2"></i>Print Table</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless mb-0" id="tabelKomoditas">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center" style="width: 50px;">
                                    <input type="checkbox" id="checkAll" class="form-check-input">
                                </th>
                                <th class="text-center" style="width: 60px;">No</th>
                                <th>
                                    <i class="fas fa-seedling me-1"></i>
                                    Nama Komoditas
                                </th>
                                <th class="text-end">
                                    <i class="fas fa-money-bill-wave me-1"></i>
                                    Harga
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-weight me-1"></i>
                                    Jumlah
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-home me-1"></i>
                                    Kebutuhan RT
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-store me-1"></i>
                                    Pasar
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-calendar me-1"></i>
                                    Tanggal
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-clock me-1"></i>
                                    Minggu Ke
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-search fa-3x text-gray-400 mb-3"></i>
                                        <h5 class="text-gray-500">Pencarian Data</h5>
                                        <p class="text-muted">Gunakan filter di atas untuk mencari data neraca pangan</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Styles -->
    <style>
        .form-select-modern {
            border: 2px solid #e3e6f0;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            background-color: #fff;
        }
        
        .form-select-modern:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .btn-search {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-search:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(78, 115, 223, 0.3);
        }
        
        .card {
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        
        .table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            padding: 1rem 0.75rem;
            vertical-align: middle;
        }
        
        .table tbody tr {
            transition: all 0.2s ease;
        }
        
        .table tbody tr:hover {
            background-color: rgba(78, 115, 223, 0.05);
            transform: translateX(2px);
        }
        
        .table tbody td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
            border-bottom: 1px solid #e3e6f0;
        }
        
        .empty-state {
            padding: 2rem;
        }
        
        .loading-state {
            padding: 3rem;
        }
        
        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-verified {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .pasar-badge {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 0.3rem 0.6rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .price-highlight {
            font-weight: 700;
            color: #28a745;
        }
        
        .border-left-primary {
            border-left: 4px solid #4e73df !important;
        }
        
        .border-left-success {
            border-left: 4px solid #1cc88a !important;
        }
        
        .border-left-warning {
            border-left: 4px solid #f6c23e !important;
        }
        
        .border-left-info {
            border-left: 4px solid #36b9cc !important;
        }
        
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.875rem;
            }
            
            .card-body {
                padding: 1rem;
            }
        }
    </style>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchBtn = document.getElementById('cariData');
            const refreshBtn = document.getElementById('refreshBtn');
            const exportBtn = document.getElementById('exportBtn');
            const checkAllBtn = document.getElementById('checkAll');
            
            // Search functionality
            searchBtn.addEventListener('click', function() {
                performSearch();
            });
            
            // Refresh functionality
            refreshBtn.addEventListener('click', function() {
                location.reload();
            });
            
            // Export functionality
            exportBtn.addEventListener('click', function() {
                exportData();
            });
            
            // Check all functionality
            checkAllBtn.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
                checkboxes.forEach(cb => cb.checked = this.checked);
            });
            
            // Auto search on filter change
            const filters = ['nama_komoditas', 'tahun', 'bulan', 'pasar', 'minggu'];
            filters.forEach(id => {
                document.getElementById(id).addEventListener('change', function() {
                    if (this.value) {
                        performSearch();
                    }
                });
            });
            
            function performSearch() {
                const nama_komoditas = document.getElementById('nama_komoditas').value;
                const tahun = document.getElementById('tahun').value;
                const bulan = document.getElementById('bulan').value;
                const pasar = document.getElementById('pasar').value;
                const minggu = document.getElementById('minggu').value;
                
                // Show loading state
                showLoadingState();
                
                fetch("{{ route('komoditas.search') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        nama_komoditas,
                        tahun,
                        bulan,
                        pasar,
                        minggu
                    })
                })
                .then(res => res.json())
                .then(data => {
                    displayResults(data);
                    updateSummary(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorState();
                });
            }
            
            function showLoadingState() {
                document.querySelector('#tabelKomoditas tbody').innerHTML = `
                    <tr>
                        <td colspan="10" class="text-center">
                            <div class="loading-state">
                                <div class="spinner-border text-primary mb-3" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <h5 class="text-gray-600">Sedang memuat data...</h5>
                                <p class="text-muted">Mohon tunggu sebentar</p>
                            </div>
                        </td>
                    </tr>
                `;
            }
            
            function showErrorState() {
                document.querySelector('#tabelKomoditas tbody').innerHTML = `
                    <tr>
                        <td colspan="10" class="text-center">
                            <div class="empty-state">
                                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                                <h5 class="text-danger">Error Memuat Data</h5>
                                <p class="text-muted">Terjadi kesalahan saat memuat data. Silakan coba lagi.</p>
                                <button class="btn btn-primary btn-sm" onclick="location.reload()">
                                    <i class="fas fa-sync-alt me-1"></i>
                                    Refresh Halaman
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }
            
            function displayResults(data) {
                let html = '';
                
                if (data.length === 0) {
                    html = `
                        <tr>
                            <td colspan="10" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-inbox fa-3x text-gray-400 mb-3"></i>
                                    <h5 class="text-gray-500">Data Tidak Ditemukan</h5>
                                    <p class="text-muted">Tidak ada data yang sesuai dengan filter pencarian</p>
                                </div>
                            </td>
                        </tr>
                    `;
                } else {
                    data.forEach((item, index) => {
                        const statusClass = getStatusClass(item.status_verifikasi);
                        const statusText = getStatusText(item.status_verifikasi);
                        
                        html += `
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input row-checkbox">
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary">${index + 1}</span>
                                </td>
                                <td class="fw-bold">${item.nama_komoditas}</td>
                                <td class="text-end price-highlight">
                                    Rp ${parseInt(item.harga_komoditas).toLocaleString('id-ID')}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark">${item.jumlah_komoditas}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info text-white">${item.kebutuhan_rumah_tangga}</span>
                                </td>
                                <td class="text-center">
                                    <span class="pasar-badge">${item.tempat_survey.replace('_', ' ').toUpperCase()}</span>
                                </td>
                                <td class="text-center">
                                    <small class="text-muted">${formatDate(item.tgl_pelaksanaan)}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">Minggu ${item.minggu_dilakukan_survey}</span>
                                </td>
                                <td class="text-center">
                                    <span class="status-badge ${statusClass}">${statusText}</span>
                                </td>
                            </tr>
                        `;
                    });
                }
                
                document.querySelector('#tabelKomoditas tbody').innerHTML = html;
                document.getElementById('dataCount').textContent = `${data.length} data`;
            }
            
            function updateSummary(data) {
                const totalData = data.length;
                const verifiedData = data.filter(item => item.status_verifikasi === 'verified').length;
                const pendingData = data.filter(item => item.status_verifikasi === 'pending').length;
                const avgPrice = data.length > 0 ? 
                    data.reduce((sum, item) => sum + parseInt(item.harga_komoditas), 0) / data.length : 0;
                
                document.getElementById('totalData').textContent = totalData;
                document.getElementById('avgPrice').textContent = `Rp ${Math.round(avgPrice).toLocaleString('id-ID')}`;
                
                document.getElementById('summaryCards').style.display = totalData > 0 ? 'flex' : 'none';
            }
            
            function getStatusClass(status) {
                switch(status) {
                    case 'verified': return 'status-verified';
                    case 'pending': return 'status-pending';
                    case 'rejected': return 'status-rejected';
                    default: return 'status-pending';
                }
            }
            
            function getStatusText(status) {
                switch(status) {
                    case 'verified': return 'Terverifikasi';
                    case 'pending': return 'Menunggu';
                    case 'rejected': return 'Ditolak';
                    default: return 'Menunggu';
                }
            }
            
            function formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                });
            }
            
            function exportData() {
                // Implement export functionality
                alert('Fitur export akan segera tersedia');
            }
        });
    </script>
@endsection
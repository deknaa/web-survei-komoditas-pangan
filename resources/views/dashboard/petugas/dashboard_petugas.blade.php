@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Welcome Alert -->
        <div class="alert alert-primary border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="alert-icon me-3">
                    <i class="fas fa-user-circle fa-2x text-primary"></i>
                </div>
                <div>
                    <h5 class="alert-heading mb-1">Selamat Datang!</h5>
                    <p class="mb-0">Halo <strong>{{ $user->name }}</strong>, semoga hari Anda menyenangkan! 👋</p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <!-- Ketersediaan Pangan Card -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                    <div class="card-body p-4">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-2 tracking-wide">
                                    Ketersediaan Pangan
                                </div>
                                <div class="h4 mb-2 font-weight-bold text-gray-800">
                                    {{ number_format($ketersediaanPangan, 0, ',', '.') }} <small class="text-muted">Ton</small>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-primary">
                                    <i class="fas fa-boxes text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-primary bg-gradient py-2">
                        <div class="text-white text-xs text-center">
                            <i class="fas fa-arrow-up me-1"></i>
                            Status: Tersedia
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kebutuhan Pangan Card -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                    <div class="card-body p-4">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-2 tracking-wide">
                                    Kebutuhan Pangan
                                </div>
                                <div class="h4 mb-2 font-weight-bold text-gray-800">
                                    {{ number_format($kebutuhanPangan, 0, ',', '.') }} <small class="text-muted">Ton</small>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-success">
                                    <i class="fas fa-chart-line text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-success bg-gradient py-2">
                        <div class="text-white text-xs text-center">
                            <i class="fas fa-chart-bar me-1"></i>
                            Target Tercapai
                        </div>
                    </div>
                </div>
            </div>

            <!-- Survey Pending Card -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                    <div class="card-body p-4">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-2 tracking-wide">
                                    Survey Pending
                                </div>
                                <div class="h4 mb-2 font-weight-bold text-gray-800">
                                    {{ $surveyBelumDiAcc }} <small class="text-muted">Item</small>
                                </div>
                                <div class="text-xs text-muted">
                                    Menunggu persetujuan
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-warning">
                                    <i class="fas fa-clock text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-warning bg-gradient py-2">
                        <div class="text-white text-xs text-center">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Perlu Tindakan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white border-bottom-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-primary">
                        <i class="fas fa-chart-area me-2"></i>
                        Grafik Harga Komoditas
                    </h5>
                    <span class="badge bg-primary">Live Data</span>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Controls -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold text-gray-700">
                            <i class="fas fa-store me-1"></i>
                            Pasar
                        </label>
                        <select id="pasar" class="form-select form-select-modern">
                            <option value="">Pilih Pasar</option>
                            @foreach ($daftarPasar as $pasar)
                                <option value="{{ $pasar }}">{{ ucwords(str_replace('_', ' ', $pasar)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold text-gray-700">
                            <i class="fas fa-calendar-alt me-1"></i>
                            Tanggal Mulai
                        </label>
                        <input type="date" id="tanggal" class="form-control form-control-modern" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold text-gray-700">
                            <i class="fas fa-seedling me-1"></i>
                            Komoditas
                        </label>
                        <select id="komoditas" class="form-select form-select-modern">
                            <option value="">Pilih Komoditas</option>
                        </select>
                    </div>
                </div>

                <!-- Chart Container -->
                <div class="chart-container">
                    <canvas id="chart"></canvas>
                </div>
            </div>
        </div>

        <!-- Neraca Pangan Section -->
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-success">
                        <i class="fas fa-balance-scale me-2"></i>
                        Neraca Pangan
                    </h5>
                    <span class="badge bg-success">Laporan Mingguan</span>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Controls -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold text-gray-700">Nama Komoditas</label>
                        <select id="nama_komoditas" class="form-select form-select-modern">
                            @foreach ($nama_komoditas as $komoditas)
                                <option value="{{ $komoditas }}">{{ $komoditas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold text-gray-700">Tahun</label>
                        <select id="tahun" class="form-select form-select-modern">
                            @for ($y = 2020; $y <= now()->year; $y++)
                                <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold text-gray-700">Bulan</label>
                        <select id="bulan" class="form-select form-select-modern">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-borderless" id="dataTable">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center">#</th>
                                <th><i class="fas fa-map-marker-alt me-1"></i>Tempat Survey</th>
                                <th class="text-center">Minggu 1</th>
                                <th class="text-center">Minggu 2</th>
                                <th class="text-center">Minggu 3</th>
                                <th class="text-center">Minggu 4</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-search fa-2x mb-2 d-block text-gray-400"></i>
                                    Silakan pilih filter terlebih dahulu
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
        .tracking-wide {
            letter-spacing: 0.5px;
        }
        
        .icon-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        
        .progress-sm {
            height: 4px;
        }
        
        .form-control-modern,
        .form-select-modern {
            border: 2px solid #e3e6f0;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control-modern:focus,
        .form-select-modern:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .chart-container {
            position: relative;
            height: 400px;
            background: linear-gradient(135deg, #f8f9fc 0%, #ffffff 100%);
            border-radius: 0.5rem;
            padding: 1rem;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(78, 115, 223, 0.05);
            transform: translateY(-1px);
            transition: all 0.2s ease;
        }
        
        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        
        .alert-icon {
            background: rgba(78, 115, 223, 0.1);
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .badge {
            font-size: 0.7rem;
            padding: 0.4rem 0.8rem;
        }
        
        .table thead th {
            border-bottom: 2px solid #e3e6f0;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.8rem;
            padding: 1rem 0.75rem;
        }
        
        .table tbody td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
        }
    </style>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('chart').getContext('2d');
        let chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: []
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#4e73df',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    }
                },
                elements: {
                    line: {
                        tension: 0.4
                    },
                    point: {
                        radius: 6,
                        hoverRadius: 8
                    }
                }
            }
        });

        function fetchKomoditasList() {
            const pasar = document.getElementById('pasar').value;
            const tanggal = document.getElementById('tanggal').value;

            if (!pasar || !tanggal) return;

            // Show loading state
            const select = document.getElementById('komoditas');
            select.innerHTML = '<option value="">Loading...</option>';

            fetch(`/petugas/api/komoditas-list?pasar=${pasar}&tanggal=${tanggal}`)
                .then(res => res.json())
                .then(data => {
                    select.innerHTML = '<option value="">Pilih Komoditas</option>';
                    data.forEach(k => {
                        const option = document.createElement('option');
                        option.value = k;
                        option.textContent = k;
                        select.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching komoditas:', error);
                    select.innerHTML = '<option value="">Error loading data</option>';
                });
        }

        function fetchChartData() {
            const pasar = document.getElementById('pasar').value;
            const tanggal = document.getElementById('tanggal').value;
            const komoditas = document.getElementById('komoditas').value;

            if (!pasar || !tanggal || !komoditas) return;

            fetch(`/petugas/api/harga-komoditas?pasar=${pasar}&tanggal=${tanggal}&komoditas=${komoditas}`)
                .then(res => res.json())
                .then(data => {
                    chart.data.labels = data.labels;
                    chart.data.datasets = data.datasets;
                    chart.update('active');
                })
                .catch(error => {
                    console.error('Error fetching chart data:', error);
                });
        }

        // Debounce function
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        const debouncedKomoditasList = debounce(fetchKomoditasList, 500);
        const debouncedChartFetch = debounce(fetchChartData, 500);

        document.getElementById('pasar').addEventListener('change', debouncedKomoditasList);
        document.getElementById('tanggal').addEventListener('change', debouncedKomoditasList);
        document.getElementById('komoditas').addEventListener('change', debouncedChartFetch);

        window.addEventListener('DOMContentLoaded', fetchKomoditasList);
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function loadData() {
                let nama_komoditas = document.getElementById('nama_komoditas').value;
                let tahun = document.getElementById('tahun').value;
                let bulan = document.getElementById('bulan').value;

                // Show loading state
                document.querySelector('#dataTable tbody').innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="mt-2">Memuat data...</div>
                        </td>
                    </tr>
                `;

                fetch("{{ route('pangan.data') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            nama_komoditas: nama_komoditas,
                            tahun: tahun,
                            bulan: bulan
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        let tbody = '';
                        let i = 1;
                        for (let pasar in data) {
                            tbody += `<tr>
                                <td class="text-center"><span class="badge bg-primary">${i++}</span></td>
                                <td class="fw-bold">${pasar.replace('_', ' ').toUpperCase()}</td>
                                <td class="text-center"><span class="badge bg-light text-dark">${data[pasar].week1 ?? 0}</span></td>
                                <td class="text-center"><span class="badge bg-light text-dark">${data[pasar].week2 ?? 0}</span></td>
                                <td class="text-center"><span class="badge bg-light text-dark">${data[pasar].week3 ?? 0}</span></td>
                                <td class="text-center"><span class="badge bg-light text-dark">${data[pasar].week4 ?? 0}</span></td>
                            </tr>`;
                        }
                        if (i === 1) {
                            tbody = `<tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-exclamation-circle fa-2x mb-2 d-block text-gray-400"></i>
                                    Data tidak ditemukan
                                </td>
                            </tr>`;
                        }

                        document.querySelector('#dataTable tbody').innerHTML = tbody;
                    })
                    .catch(error => {
                        console.error('Error loading data:', error);
                        document.querySelector('#dataTable tbody').innerHTML = `
                            <tr>
                                <td colspan="6" class="text-center text-danger py-4">
                                    <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>
                                    Error memuat data
                                </td>
                            </tr>
                        `;
                    });
            }

            // Load data on page load
            loadData();

            // Auto refresh when filters change
            ['nama_komoditas', 'tahun', 'bulan'].forEach(id => {
                document.getElementById(id).addEventListener('change', loadData);
            });
        });
    </script>
@endsection
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-gray-800 mb-0">Neraca Pangan</h1>
            <p class="text-muted small">Sistem monitoring dan analisis data pangan regional</p>
        </div>
        <div>
            <button class="btn btn-outline-secondary btn-sm mr-2" id="exportBtn">
                <i class="fas fa-download"></i> Export
            </button>
            <button class="btn btn-primary btn-sm" id="refreshBtn">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0">Filter Data</h6>
        </div>
        <div class="card-body">
            <form id="filterForm">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <label class="form-label">Komoditas</label>
                        <select id="nama_komoditas" class="form-control">
                            <option value="">Semua Komoditas</option>
                            @foreach ($komoditasList as $k)
                                <option value="{{ $k }}">{{ $k }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-3">
                        <label class="form-label">Tahun</label>
                        <select id="tahun" class="form-control">
                            @for ($i = date('Y'); $i >= 2020; $i--)
                                <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-3">
                        <label class="form-label">Bulan</label>
                        <select id="bulan" class="form-control">
                            @foreach (range(1, 12) as $b)
                                <option value="{{ $b }}" {{ $b == date('n') ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $b)->format('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <label class="form-label">Pasar</label>
                        <select id="pasar" class="form-control">
                            <option value="">Semua Pasar</option>
                            <option value="pasar_kediri">Pasar Kediri</option>
                            <option value="pasar_baturiti">Pasar Baturiti</option>
                            <option value="pasar_pesiapan">Pasar Pesiapan</option>
                            <option value="pasar_tabanan">Pasar Tabanan</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-3">
                        <label class="form-label">Minggu</label>
                        <select id="minggu" class="form-control">
                            <option value="">Semua</option>
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">Minggu {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="button" id="cariData" class="btn btn-primary">
                            <i class="fas fa-search"></i> Cari Data
                        </button>
                        <button type="button" id="resetFilter" class="btn btn-secondary ml-2">
                            <i class="fas fa-times"></i> Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4" id="summaryCards" style="display: none;">
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-primary" id="totalData">0</h5>
                    <p class="card-text small text-muted">Total Data</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-success" id="avgPrice">Rp 0</h5>
                    <p class="card-text small text-muted">Rata-rata Harga</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Data Neraca Pangan</h6>
            <span class="badge badge-secondary" id="dataCount">0 data</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0" id="tabelKomoditas">
                    <thead class="thead-light">
                        <tr>
                            <th width="50">No</th>
                            <th>Komoditas</th>
                            <th class="text-right">Harga</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-center">Kebutuhan RT</th>
                            <th class="text-center">Pasar</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Minggu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-search fa-2x mb-3"></i>
                                    <p>Gunakan filter untuk mencari data</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchBtn = document.getElementById('cariData');
    const refreshBtn = document.getElementById('refreshBtn');
    const exportBtn = document.getElementById('exportBtn');
    const resetBtn = document.getElementById('resetFilter');
    
    // Search functionality
    searchBtn.addEventListener('click', performSearch);
    
    // Refresh functionality
    refreshBtn.addEventListener('click', function() {
        location.reload();
    });
    
    // Export functionality
    exportBtn.addEventListener('click', function() {
        exportData();
    });
    
    // Reset filter
    resetBtn.addEventListener('click', function() {
        document.getElementById('filterForm').reset();
        document.getElementById('tahun').value = new Date().getFullYear();
        document.getElementById('bulan').value = new Date().getMonth() + 1;
        document.querySelector('#tabelKomoditas tbody').innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-5">
                    <div class="text-muted">
                        <i class="fas fa-search fa-2x mb-3"></i>
                        <p>Gunakan filter untuk mencari data</p>
                    </div>
                </td>
            </tr>
        `;
        document.getElementById('summaryCards').style.display = 'none';
        document.getElementById('dataCount').textContent = '0 data';
    });
    
    function performSearch() {
        const formData = {
            nama_komoditas: document.getElementById('nama_komoditas').value,
            tahun: document.getElementById('tahun').value,
            bulan: document.getElementById('bulan').value,
            pasar: document.getElementById('pasar').value,
            minggu: document.getElementById('minggu').value
        };
        
        // Show loading
        showLoading();
        
        fetch("{{ route('komoditas.search') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            displayResults(data);
            updateSummary(data);
        })
        .catch(error => {
            console.error('Error:', error);
            showError();
        });
    }
    
    function showLoading() {
        document.querySelector('#tabelKomoditas tbody').innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat data...</p>
                </td>
            </tr>
        `;
    }
    
    function showError() {
        document.querySelector('#tabelKomoditas tbody').innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-4">
                    <div class="text-danger">
                        <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                        <p>Terjadi kesalahan saat memuat data</p>
                        <button class="btn btn-sm btn-primary" onclick="location.reload()">
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
                    <td colspan="8" class="text-center py-4">
                        <div class="text-muted">
                            <i class="fas fa-inbox fa-2x mb-3"></i>
                            <p>Data tidak ditemukan</p>
                        </div>
                    </td>
                </tr>
            `;
        } else {
            data.forEach((item, index) => {
                html += `
                    <tr>
                        <td>${index + 1}</td>
                        <td class="font-weight-bold">${item.nama_komoditas}</td>
                        <td class="text-right text-success font-weight-bold">
                            Rp ${parseInt(item.harga_komoditas).toLocaleString('id-ID')}
                        </td>
                        <td class="text-center">
                            <span class="badge badge-light">${item.jumlah_komoditas}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-info">${item.kebutuhan_rumah_tangga}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-secondary">${getPasarName(item.tempat_survey)}</span>
                        </td>
                        <td class="text-center">
                            <small>${formatDate(item.tgl_pelaksanaan)}</small>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-primary">${item.minggu_dilakukan_survey}</span>
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
        const avgPrice = data.length > 0 ? 
            data.reduce((sum, item) => sum + parseInt(item.harga_komoditas), 0) / data.length : 0;
        
        document.getElementById('totalData').textContent = totalData;
        document.getElementById('avgPrice').textContent = 
            `Rp ${Math.round(avgPrice).toLocaleString('id-ID')}`;
        
        document.getElementById('summaryCards').style.display = totalData > 0 ? 'flex' : 'none';
    }
    
    function getPasarName(pasar) {
        const pasarNames = {
            'pasar_kediri': 'Kediri',
            'pasar_baturiti': 'Baturiti',
            'pasar_pesiapan': 'Pesiapan',
            'pasar_tabanan': 'Tabanan'
        };
        return pasarNames[pasar] || pasar;
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
        const formData = {
            nama_komoditas: document.getElementById('nama_komoditas').value,
            tahun: document.getElementById('tahun').value,
            bulan: document.getElementById('bulan').value,
            pasar: document.getElementById('pasar').value,
            minggu: document.getElementById('minggu').value
        };
        
        // Show loading on export button
        const originalText = exportBtn.innerHTML;
        exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exporting...';
        exportBtn.disabled = true;
        
        // Create form for download
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('komoditas.export') }}";
        form.style.display = 'none';
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = "{{ csrf_token() }}";
        form.appendChild(csrfInput);
        
        // Add form data
        Object.keys(formData).forEach(key => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = formData[key] || '';
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
        
        // Reset button after delay
        setTimeout(() => {
            exportBtn.innerHTML = originalText;
            exportBtn.disabled = false;
        }, 2000);
    }
});
</script>

<style>
.card {
    border: 1px solid #e3e6f0;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.875rem;
    color: #5a5c69;
}

.table td {
    font-size: 0.875rem;
    vertical-align: middle;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.75rem;
    }
    
    .card-header {
        padding: 0.75rem;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>
@endsection
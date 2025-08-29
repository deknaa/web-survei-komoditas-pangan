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
                <a href="{{ route('neraca-pangan.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Neraca Pangan
                </a>
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
                                    <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>
                                        {{ $i }}</option>
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
                                @for ($i = 1; $i <= 4; $i++)
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

        <!-- Hasil Perhitungan -->
        <div class="card mt-4 d-none" id="hasilCard">
            <div class="card-header">
                <h6 class="mb-0">Hasil Perhitungan Neraca Pangan</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="#">
                    @csrf
                    <input type="hidden" name="nama_komoditas" id="input_komoditas">
                    <input type="hidden" name="tahun" id="input_tahun">
                    <input type="hidden" name="bulan" id="input_bulan">
                    <input type="hidden" name="minggu" id="input_minggu">
                    <input type="hidden" name="pasar" id="input_pasar">

                    <div class="form-group">
                        <label>Jumlah Ketersediaan Pangan</label>
                        <input type="text" class="form-control" id="ketersediaan" name="ketersediaan" readonly>
                        <small class="form-text text-muted">(Stok + Produksi Domestik + Pangan Masuk - Pangan
                            Keluar)</small>
                    </div>

                    <div class="form-group">
                        <label>Jumlah Kebutuhan Pangan</label>
                        <input type="text" class="form-control" id="kebutuhan" name="kebutuhan" readonly>
                        <small class="form-text text-muted">(Konsumsi Rumah Tangga + Konsumsi Non Rumah Tangga)</small>
                    </div>

                    <div class="form-group">
                        <label>Neraca Pangan</label>
                        <input type="text" class="form-control" id="neraca" name="neraca" readonly>
                        <small class="form-text text-muted">(Ketersediaan - Kebutuhan)</small>
                    </div>

                    <div class="form-group">
                        <label>Satuan</label>
                        <input type="text" class="form-control" id="satuan" name="satuan" value="Ton"
                            readonly>
                    </div>

                    <div class="mt-3 text-right">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        document.getElementById('cariData').addEventListener('click', function() {
            let data = {
                nama_komoditas: document.getElementById('nama_komoditas').value,
                tahun: document.getElementById('tahun').value,
                bulan: document.getElementById('bulan').value,
                minggu: document.getElementById('minggu').value,
                pasar: document.getElementById('pasar').value,
                _token: '{{ csrf_token() }}'
            };

            fetch("{{ route('neraca-pangan.hitung') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': data._token
                    },
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(res => {
                    document.getElementById('hasilCard').classList.remove('d-none');
                    document.getElementById('ketersediaan').value = res.ketersediaan;
                    document.getElementById('kebutuhan').value = res.kebutuhan;
                    document.getElementById('neraca').value = res.neraca;
                    document.getElementById('satuan').value = res.satuan;

                    // isi hidden input untuk disimpan
                    document.getElementById('input_komoditas').value = data.nama_komoditas;
                    document.getElementById('input_tahun').value = data.tahun;
                    document.getElementById('input_bulan').value = data.bulan;
                    document.getElementById('input_minggu').value = data.minggu;
                    document.getElementById('input_pasar').value = data.pasar;
                })
                .catch(err => console.error(err));
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

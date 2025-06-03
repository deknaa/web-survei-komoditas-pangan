@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="alert alert-success" role="alert">
            Halo, Selamat Datang.
            <b>{{ $user->name }}</b> 👋
        </div>

        <!-- Content Row -->
        <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Ketersediaan Pangan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $ketersediaanPangan }} Ton</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-boxes fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success shaww h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Kebutuhan Pangan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $kebutuhanPangan }} Ton</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Requests Card Example -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Survey Yang Belum di ACC</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $surveyBelumDiAcc }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart Harga Komoditas --}}
        <label>Pasar:</label>
        <select id="pasar">
            <option value="">Pilih Pasar</option>
            @foreach ($daftarPasar as $pasar)
                <option value="{{ $pasar }}">{{ ucwords(str_replace('_', ' ', $pasar)) }}</option>
            @endforeach
        </select>

        <label>Tanggal Mulai:</label>
        <input type="date" id="tanggal" value="{{ date('Y-m-d') }}">

        <label>Nama Komoditas:</label>
        <select id="komoditas">
            <option value="">Pilih Komoditas</option>
        </select>

        <canvas id="chart" style="max-width: 700px; max-height: 400px;"></canvas>

        {{-- Neraca Pangan --}}
        <h3>Neraca Pangan</h3>
        <div class="row mb-3">
            <div class="col">
                <label>Nama Komoditas</label>
                <select id="nama_komoditas" class="form-control">
                    @foreach ($nama_komoditas as $komoditas)
                        <option value="{{ $komoditas }}">{{ $komoditas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label>Tahun</label>
                <select id="tahun" class="form-control">
                    @for ($y = 2020; $y <= now()->year; $y++)
                        <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col">
                <label>Bulan</label>
                <select id="bulan" class="form-control">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>
        </div>

        <table class="table table-bordered" id="dataTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tempat Survey</th>
                    <th>Minggu 1</th>
                    <th>Minggu 2</th>
                    <th>Minggu 3</th>
                    <th>Minggu 4</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6" class="text-center">Silakan pilih filter terlebih dahulu</td>
                </tr>
            </tbody>
        </table>
    </div>
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
                responsive: true
            }
        });

        function fetchKomoditasList() {
            const pasar = document.getElementById('pasar').value;
            const tanggal = document.getElementById('tanggal').value;

            if (!pasar || !tanggal) return;

            fetch(`/petugas/api/komoditas-list?pasar=${pasar}&tanggal=${tanggal}`)
                .then(res => res.json())
                .then(data => {
                    const select = document.getElementById('komoditas');
                    select.innerHTML = '<option value="">Pilih Komoditas</option>';
                    data.forEach(k => {
                        const option = document.createElement('option');
                        option.value = k;
                        option.textContent = k;
                        select.appendChild(option);
                    });
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
                    chart.update();
                });
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
                    <td>${i++}</td>
                    <td>${pasar.replace('_', ' ').toUpperCase()}</td>
                    <td>${data[pasar].week1 ?? 0}</td>
                    <td>${data[pasar].week2 ?? 0}</td>
                    <td>${data[pasar].week3 ?? 0}</td>
                    <td>${data[pasar].week4 ?? 0}</td>
                </tr>`;
                        }
                        if (i === 1) {
                            tbody = `<tr><td colspan="6" class="text-center">Data tidak ditemukan</td></tr>`;
                        }

                        document.querySelector('#dataTable tbody').innerHTML = tbody;
                    });
            }

            // Panggil pertama kali saat halaman dimuat
            loadData();

            // Jika user mengubah filter, otomatis refresh data
            ['nama_komoditas', 'tahun', 'bulan'].forEach(id => {
                document.getElementById(id).addEventListener('change', loadData);
            });
        });
    </script>
@endsection

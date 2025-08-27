@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Kelola Komoditas Pangan</h1>
            <div class="d-flex">
                @if (Auth::user()->role === 'petugas')
                    <a href="{{ route('komoditas.create') }}"
                        class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2">
                        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Data Komoditas
                    </a>
                @endif

                {{-- @if (Auth::user()->role === 'eksekutif')
                <div class="dropdown">
                    <button class="btn btn-success btn-sm dropdown-toggle shadow-sm" type="button" id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-download fa-sm text-white-50"></i> Export Data
                    </button>
                    <div class="dropdown-menu" aria-labelledby="exportDropdown">
                        <a class="dropdown-item" href="{{ route('komoditas.exports', ['format' => 'excel']) }}">
                            <i class="fas fa-file-excel text-success"></i> Export Excel
                        </a>
                        <a class="dropdown-item" href="{{ route('komoditas.exports', ['format' => 'pdf']) }}">
                            <i class="fas fa-file-pdf text-danger"></i> Export PDF
                        </a>
                    </div>
                </div>
                @endif --}}
            </div>
        </div>

        <!-- Filter Section for Export -->
        @if (Auth::user()->role === 'eksekutif')
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Filter Export Data</h6>
                    <button class="btn btn-sm btn-outline-primary" type="button" data-toggle="collapse"
                        data-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                        <i class="fas fa-filter"></i> Toggle Filter
                    </button>
                </div>
                <div class="collapse" id="filterCollapse">
                    <div class="card-body">
                        <form method="GET" action="{{ route('komoditas.exports') }}" id="exportForm">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="tempat_survey_filter">Tempat Survey</label>
                                        <select class="form-control" name="tempat_survey" id="tempat_survey_filter">
                                            <option value="">Semua Tempat</option>
                                            <option value="pasar_kediri">Pasar Kediri</option>
                                            <option value="pasar_baturiti">Pasar Baturiti</option>
                                            <option value="pasar_pesiapan">Pasar Pesiapan</option>
                                            <option value="pasar_tabanan">Pasar Tabanan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="status_verifikasi_filter">Status Verifikasi</label>
                                        <select class="form-control" name="status_verifikasi" id="status_verifikasi_filter">
                                            <option value="">Semua Status</option>
                                            <option value="sudah_diverifikasi">Terverifikasi</option>
                                            <option value="belum_diverifikasi">Belum Terverifikasi</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="tanggal_dari">Tanggal Dari</label>
                                        <input type="date" class="form-control" name="tanggal_dari" id="tanggal_dari">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="tanggal_sampai">Tanggal Sampai</label>
                                        <input type="date" class="form-control" name="tanggal_sampai"
                                            id="tanggal_sampai">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-success" onclick="exportData('excel')">
                                            <i class="fas fa-file-excel"></i> Export Excel
                                        </button>
                                        <button type="button" class="btn btn-danger" onclick="exportData('pdf')">
                                            <i class="fas fa-file-pdf"></i> Export PDF
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Komoditas Pangan</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Komoditas</th>
                                <th>Harga Komoditas</th>
                                <th>Ketersediaan</th>
                                <th>Kebutuhan Rumah Tangga</th>
                                <th>Tempat Survey</th>
                                <th>Tanggal Pelaksanaan</th>
                                <th>Minggu Survey Pelaksanaan</th>
                                <th>Status Verifikasi</th>
                                <th>Petugas Yang Menambahkan Data</th>
                                <th>OPSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($komoditas as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama_komoditas }}</td>
                                    <td>Rp. {{ number_format($item->harga_komoditas, 0, ',', '.') }}</td>
                                    <td>{{ $item->jumlah_komoditas }} Ton</td>
                                    <td>{{ $item->kebutuhan_rumah_tangga }} Ton</td>
                                    <td>{{ ucwords(str_replace('_', ' ', $item->tempat_survey)) }}</td>
                                    <td>{{ date('d/m/Y', strtotime($item->tgl_pelaksanaan)) }}</td>
                                    <td>Minggu ke-{{ $item->minggu_dilakukan_survey }}</td>
                                    <td>
                                        @if ($item->status_verifikasi == 'sudah_diverifikasi')
                                            <span class="badge badge-success">Terverifikasi</span>
                                        @else
                                            <span class="badge badge-warning">Belum Terverifikasi</span>
                                        @endif
                                    </td>
                                    <td>{{ ucwords($item->user->name ?? 'Tidak Diketahui') }}</td>
                                    <td class="d-flex">
                                        <button type="button" class="btn btn-success mr-1" data-toggle="modal"
                                            data-target="#editKomoditasModal{{ $item->id }}">
                                            Edit
                                        </button>
                                        <form id="form-hapus-{{ $item->id }}"
                                            action="{{ route('komoditas.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-hapus"
                                                data-id="{{ $item->id }}">Hapus</button>
                                        </form>
                                        @if (auth()->user()->role === 'eksekutif' && $item->status_verifikasi !== 'sudah_diverifikasi')
                                            <form action="{{ route('komoditas.verifikasi', $item->id) }}" method="POST"
                                                class="mr-1">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-warning">Verifikasi</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Modal -->
                                <div class="modal fade" id="editKomoditasModal{{ $item->id }}" data-backdrop="static"
                                    data-keyboard="false" tabindex="-1"
                                    aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('komoditas.update', $item->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel{{ $item->id }}">Edit
                                                        Data Komoditas</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text"
                                                                for="nama_komoditas{{ $item->id }}">Pilih
                                                                Komoditas</label>
                                                        </div>
                                                        <select class="custom-select"
                                                            id="nama_komoditas{{ $item->id }}" name="nama_komoditas"
                                                            required>
                                                            <option value="" disabled>Pilih Komoditas</option>
                                                            @foreach ($namaKomoditas as $komoditasNama)
                                                                <option value="{{ $komoditasNama }}"
                                                                    {{ $item->nama_komoditas == $komoditasNama ? 'selected' : '' }}>
                                                                    {{ $komoditasNama }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('nama_komoditas')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="harga_komoditas{{ $item->id }}"
                                                            class="form-label">Harga Komoditas</label>
                                                        <input type="number" class="form-control"
                                                            id="harga_komoditas{{ $item->id }}"
                                                            name="harga_komoditas" value="{{ $item->harga_komoditas }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="jumlah_komoditas{{ $item->id }}"
                                                            class="form-label">Jumlah Komoditas Tersedia</label>
                                                        <input type="number" class="form-control"
                                                            id="jumlah_komoditas{{ $item->id }}"
                                                            name="jumlah_komoditas"
                                                            value="{{ $item->jumlah_komoditas }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="kebutuhan_rumah_tangga{{ $item->id }}"
                                                            class="form-label">Jumlah Kebutuhan Rumah Tangga</label>
                                                        <input type="number" class="form-control"
                                                            id="kebutuhan_rumah_tangga{{ $item->id }}"
                                                            name="kebutuhan_rumah_tangga"
                                                            value="{{ $item->kebutuhan_rumah_tangga }}" required>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text"
                                                                for="tempat_survey{{ $item->id }}">Pasar</label>
                                                        </div>
                                                        <select class="custom-select"
                                                            id="tempat_survey{{ $item->id }}" name="tempat_survey" required>
                                                            <option value="pasar_kediri"
                                                                {{ $item->tempat_survey == 'pasar_kediri' ? 'selected' : '' }}>
                                                                Pasar Kediri</option>
                                                            <option value="pasar_baturiti"
                                                                {{ $item->tempat_survey == 'pasar_baturiti' ? 'selected' : '' }}>
                                                                Pasar Baturiti</option>
                                                            <option value="pasar_pesiapan"
                                                                {{ $item->tempat_survey == 'pasar_pesiapan' ? 'selected' : '' }}>
                                                                Pasar Pesiapan</option>
                                                            <option value="pasar_tabanan"
                                                                {{ $item->tempat_survey == 'pasar_tabanan' ? 'selected' : '' }}>
                                                                Pasar Tabanan</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="tgl_pelaksanaan{{ $item->id }}"
                                                            class="form-label">Tanggal Pelaksanaan</label>
                                                        <input type="date" class="form-control"
                                                            id="tgl_pelaksanaan{{ $item->id }}"
                                                            name="tgl_pelaksanaan" value="{{ $item->tgl_pelaksanaan }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="minggu_dilakukan_survey{{ $item->id }}"
                                                            class="form-label">Minggu Survey Dilaksanakan</label>
                                                        <input type="number" class="form-control"
                                                            id="minggu_dilakukan_survey{{ $item->id }}"
                                                            name="minggu_dilakukan_survey"
                                                            value="{{ $item->minggu_dilakukan_survey }}" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger"
                                                        data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-success">Update Data</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center">
                        {{ $komoditas->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.btn-hapus').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-hapus-' + id).submit();
                    }
                });
            });
        });

        // Function untuk export data dengan filter
        function exportData(format) {
            const form = document.getElementById('exportForm');
            const formatInput = document.createElement('input');
            formatInput.type = 'hidden';
            formatInput.name = 'format';
            formatInput.value = format;
            form.appendChild(formatInput);

            // Show loading
            Swal.fire({
                title: 'Memproses Export...',
                text: 'Mohon tunggu sebentar',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            form.submit();

            // Hide loading after a delay
            setTimeout(() => {
                Swal.close();
            }, 2000);
        }
    </script>
@endsection

@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Kelola Komoditas Pangan</h1>
            <a href="{{ route('komoditas.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-download fa-sm text-white-50"></i> Tambah Data Komoditas
            </a>
        </div>

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
                                <th>OPSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($komoditas as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama_komoditas }}</td>
                                    <td>Rp. {{ $item->harga_komoditas }}</td>
                                    <td>{{ $item->jumlah_komoditas }} Ton</td>
                                    <td>{{ $item->kebutuhan_rumah_tangga }} Ton</td>
                                    <td>{{ ucwords(str_replace('_', ' ', $item->tempat_survey)) }}</td>
                                    <td>{{ $item->tgl_pelaksanaan }}</td>
                                    <td>Minggu ke-{{ $item->minggu_dilakukan_survey }}</td>
                                    <td>{{ $item->status_verifikasi == 'sudah_diverifikasi' ? 'Terverifikasi' : 'Belum Terverifikasi' }}</td>
                                    <td class="d-flex">
                                        <button type="button" class="btn btn-success mr-1" data-toggle="modal"
                                            data-target="#editKomoditasModal{{ $item->id }}">
                                            Edit
                                        </button>
                                        <form id="form-hapus-{{ $item->id }}"
                                            action="{{ route('komoditas.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-hapus" data-id="{{ $item->id }}">Hapus</button>
                                        </form>
                                        @if (auth()->user()->role === 'eksekutif' && $item->status_verifikasi !== 'sudah_diverifikasi')
                                            <form action="{{ route('komoditas.verifikasi', $item->id) }}" method="POST" class="mr-1">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-warning">Verifikasi</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Modal -->
                                <div class="modal fade" id="editKomoditasModal{{ $item->id }}" data-backdrop="static"
                                    data-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel{{ $item->id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('komoditas.update', $item->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel{{ $item->id }}">Edit Data Komoditas</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text" for="nama_komoditas{{ $item->id }}">Pilih Komoditas</label>
                                                        </div>
                                                        <select class="custom-select" id="nama_komoditas{{ $item->id }}" name="nama_komoditas" required>
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
                                                        <label for="harga_komoditas{{ $item->id }}" class="form-label">Harga Komoditas</label>
                                                        <input type="number" class="form-control" id="harga_komoditas{{ $item->id }}" name="harga_komoditas" value="{{ $item->harga_komoditas }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="jumlah_komoditas{{ $item->id }}" class="form-label">Jumlah Komoditas Tersedia</label>
                                                        <input type="number" class="form-control" id="jumlah_komoditas{{ $item->id }}" name="jumlah_komoditas" value="{{ $item->jumlah_komoditas }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="kebutuhan_rumah_tangga{{ $item->id }}" class="form-label">Jumlah Kebutuhan Rumah Tangga</label>
                                                        <input type="number" class="form-control" id="kebutuhan_rumah_tangga{{ $item->id }}" name="kebutuhan_rumah_tangga" value="{{ $item->kebutuhan_rumah_tangga }}">
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text" for="tempat_survey{{ $item->id }}">Pasar</label>
                                                        </div>
                                                        <select class="custom-select" id="tempat_survey{{ $item->id }}" name="tempat_survey">
                                                            <option value="pasar_kediri" {{ $item->tempat_survey == 'pasar_kediri' ? 'selected' : '' }}>Pasar Kediri</option>
                                                            <option value="pasar_baturiti" {{ $item->tempat_survey == 'pasar_baturiti' ? 'selected' : '' }}>Pasar Baturiti</option>
                                                            <option value="pasar_pesiapan" {{ $item->tempat_survey == 'pasar_pesiapan' ? 'selected' : '' }}>Pasar Pesiapan</option>
                                                            <option value="pasar_tabanan" {{ $item->tempat_survey == 'pasar_tabanan' ? 'selected' : '' }}>Pasar Tabanan</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="tgl_pelaksanaan{{ $item->id }}" class="form-label">Tanggal Pelaksanaan</label>
                                                        <input type="date" class="form-control" id="tgl_pelaksanaan{{ $item->id }}" name="tgl_pelaksanaan" value="{{ $item->tgl_pelaksanaan }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="minggu_dilakukan_survey{{ $item->id }}" class="form-label">Minggu Survey Dilaksanakan</label>
                                                        <input type="number" class="form-control" id="minggu_dilakukan_survey{{ $item->id }}" name="minggu_dilakukan_survey" value="{{ $item->minggu_dilakukan_survey }}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
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
    </script>
@endsection

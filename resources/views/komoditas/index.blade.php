@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Kelola Komoditas Pangan</h1>
            <a href="{{ route('komoditas.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                    class="fas fa-download fa-sm text-white-50"></i> Tambah Data Komoditas</a>
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
                            @foreach ($komoditas as $komoditas)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $komoditas->nama_komoditas }}</td>
                                    <td>Rp. {{ $komoditas->harga_komoditas }}</td>
                                    <td>{{ $komoditas->jumlah_komoditas }} Ton</td>
                                    <td>{{ $komoditas->kebutuhan_rumah_tangga }} Ton</td>
                                    <td>{{ ucwords(str_replace('_', ' ', $komoditas->tempat_survey)) }}</td>
                                    <td>{{ $komoditas->tgl_pelaksanaan }}</td>
                                    <td>Minggu ke-{{ $komoditas->minggu_dilakukan_survey }}</td>
                                    <td>{{ $komoditas->status_verifikasi == 'sudah_diverifikasi' ? 'Terverifikasi' : 'Belum Terverifikasi' }}
                                    </td>
                                    <td class="d-flex">
                                        <button type="button" class="btn btn-success mr-1" data-toggle="modal"
                                            data-target="#editKomoditasModal{{ $komoditas->id }}">
                                            Edit
                                        </button>
                                        <form id="form-hapus-{{ $komoditas->id }}"
                                            action="{{ route('komoditas.destroy', $komoditas->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-hapus"
                                                data-id="{{ $komoditas->id }}">Hapus</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal -->
                                <div class="modal fade" id="editKomoditasModal{{ $komoditas->id }}" data-backdrop="static"
                                    data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Edit Data Komoditas</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('komoditas.update', $komoditas->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text" for="nama_komoditas">Pilih
                                                                Komoditas</label>
                                                        </div>
                                                        <select class="custom-select" id="nama_komoditas"
                                                            name="nama_komoditas" required>
                                                            <option value="" disabled selected>Pilih Komoditas
                                                            </option>
                                                            @foreach ($namaKomoditas as $item)
                                                                <option value="{{ $item }}"
                                                                    {{ $komoditas->nama_komoditas == $item ? 'selected' : '' }}>
                                                                    {{ $item }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('nama_komoditas')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="harga_komoditas" class="form-label">Harga
                                                            Komoditas</label>
                                                        <input type="number" class="form-control" id="harga_komoditas"
                                                            placeholder="Harga Komoditas" name="harga_komoditas"
                                                            value="{{ $komoditas->harga_komoditas }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="jumlah_komoditas" class="form-label">Jumlah Komoditas
                                                            Tersedia</label>
                                                        <input type="number" class="form-control" id="jumlah_komoditas"
                                                            placeholder="Jumlah Komoditas Tersedia" name="jumlah_komoditas"
                                                            value="{{ $komoditas->jumlah_komoditas }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="kebutuhan_rumah_tangga" class="form-label">Jumlah
                                                            Kebutuhan Rumah Tangga
                                                            Tersedia</label>
                                                        <input type="number" class="form-control"
                                                            id="kebutuhan_rumah_tangga"
                                                            placeholder="Jumlah Kebutuhan Rumah Tangga"
                                                            name="kebutuhan_rumah_tangga"
                                                            value="{{ $komoditas->kebutuhan_rumah_tangga }}">
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text" for="tempat_survey">
                                                                Pasar</label>
                                                        </div>
                                                        <select class="custom-select" id="tempat_survey"
                                                            name="tempat_survey">
                                                            <option value="pasar_kediri"
                                                                {{ $komoditas->tempat_survey == 'pasar_kediri' ? 'selected' : '' }}>
                                                                Pasar Kediri</option>
                                                            <option value="pasar_baturiti"
                                                                {{ $komoditas->tempat_survey == 'pasar_baturiti' ? 'selected' : '' }}>
                                                                Pasar Baturiti</option>
                                                            <option value="pasar_pesiapan"
                                                                {{ $komoditas->tempat_survey == 'pasar_pesiapan' ? 'selected' : '' }}>
                                                                Pasar Pesiapan</option>
                                                            <option value="pasar_tabanan"
                                                                {{ $komoditas->tempat_survey == 'pasar_tabanan' ? 'selected' : '' }}>
                                                                Pasar Tabanan</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="tgl_pelaksanaan" class="form-label">Tanggal
                                                            Pelaksanaan</label>
                                                        <input type="date" class="form-control" id="tgl_pelaksanaan"
                                                            placeholder="Tanggal Pelaksanaan" name="tgl_pelaksanaan"
                                                            value="{{ $komoditas->tgl_pelaksanaan }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="minggu_dilakukan_survey" class="form-label">Minggu
                                                            Survey Dilaksanakan</label>
                                                        <input type="number" class="form-control"
                                                            id="minggu_dilakukan_survey"
                                                            placeholder="Minggu Survey Dilaksanakan"
                                                            name="minggu_dilakukan_survey"
                                                            value="{{ $komoditas->minggu_dilakukan_survey }}">
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
                </div>
            </div>
        </div>
    </div>

    <script>
        const editModal = document.getElementById('editModal')
        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget
            const id = button.getAttribute('data-id')
            const nama = button.getAttribute('data-nama')

            // Set data ke dalam input modal
            document.getElementById('edit-id').value = id
            document.getElementById('edit-nama').value = nama
        })
    </script>
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

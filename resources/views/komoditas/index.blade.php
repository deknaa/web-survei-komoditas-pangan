@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Kelola Komoditas Pangan</h1>
        <a href="{{ route('komoditas.create') }}" class="btn btn-primary mb-3">Buat Data Komoditas</a>

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
                                <th>Tempat Survey</th>
                                <th>Tanggal Pelaksanaan</th>
                                <th>OPSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($komoditas as $komoditas)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $komoditas->nama_komoditas }}</td>
                                    <td>Rp. {{ $komoditas->harga_komoditas }}</td>
                                    <td>{{ $komoditas->jumlah_komoditas }} {{ $komoditas->nama_komoditas }}</td>
                                    <td>{{ $komoditas->tempat_survey }}</td>
                                    <td>{{ $komoditas->tgl_pelaksanaan }}</td>
                                    <td>
                                        <button type="button" class="btn btn-success" data-toggle="modal"
                                            data-target="#staticBackdrop">
                                            Edit
                                        </button>
                                        <form action="{{ route('komoditas.destroy', $komoditas->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edit Data Komoditas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('komoditas.update', $komoditas->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="nama_komoditas" class="form-label">Nama Komoditas</label>
                            <input type="text" class="form-control" id="nama_komoditas" placeholder="Nama Komoditas"
                                name="nama_komoditas" value="{{ $komoditas->nama_komoditas }}">
                        </div>
                        <div class="mb-3">
                            <label for="harga_komoditas" class="form-label">Harga Komoditas</label>
                            <input type="number" class="form-control" id="harga_komoditas" placeholder="Harga Komoditas"
                                name="harga_komoditas" value="{{ $komoditas->harga_komoditas }}">
                        </div>
                        <div class="mb-3">
                            <label for="jumlah_komoditas" class="form-label">Jumlah Komoditas Tersedia</label>
                            <input type="number" class="form-control" id="jumlah_komoditas"
                                placeholder="Jumlah Komoditas Tersedia" name="jumlah_komoditas"
                                value="{{ $komoditas->jumlah_komoditas }}">
                        </div>
                        <div class="mb-3">
                            <label for="tempat_survey" class="form-label">Tempat Survey Komoditas</label>
                            <input type="text" class="form-control" id="tempat_survey"
                                placeholder="Tempat Survey Komoditas" name="tempat_survey"
                                value="{{ $komoditas->tempat_survey }}">
                        </div>
                        <div class="mb-3">
                            <label for="tgl_pelaksanaan" class="form-label">Tanggal Pelaksanaan</label>
                            <input type="date" class="form-control" id="tgl_pelaksanaan"
                                placeholder="Tanggal Pelaksanaan" name="tgl_pelaksanaan"
                                value="{{ $komoditas->tgl_pelaksanaan }}">
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
@endsection

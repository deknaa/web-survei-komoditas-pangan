@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Buat Komoditas Pangan</h1>


        <form action="{{ route('komoditas.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nama_komoditas" class="form-label">Nama Komoditas</label>
                <input type="text" class="form-control" id="nama_komoditas" placeholder="Nama Komoditas" name="nama_komoditas">
            </div>
            <div class="mb-3">
                <label for="harga_komoditas" class="form-label">Harga Komoditas</label>
                <input type="number" class="form-control" id="harga_komoditas" placeholder="Harga Komoditas" name="harga_komoditas">
            </div>
            <div class="mb-3">
                <label for="jumlah_komoditas" class="form-label">Jumlah Komoditas Tersedia</label>
                <input type="number" class="form-control" id="jumlah_komoditas" placeholder="Jumlah Komoditas Tersedia" name="jumlah_komoditas">
            </div>
            <div class="mb-3">
                <label for="tempat_survey" class="form-label">Tempat Survey Komoditas</label>
                <input type="text" class="form-control" id="tempat_survey" placeholder="Tempat Survey Komoditas" name="tempat_survey">
            </div>
            <div class="mb-3">
                <label for="tgl_pelaksanaan" class="form-label">Tanggal Pelaksanaan</label>
                <input type="date" class="form-control" id="tgl_pelaksanaan" placeholder="Tanggal Pelaksanaan" name="tgl_pelaksanaan">
            </div>
            <div>
                <button class="btn btn-primary">Simpan Data</button>
            </div>
        </form>

    </div>
@endsection

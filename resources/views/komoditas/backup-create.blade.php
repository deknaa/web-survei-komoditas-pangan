@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Buat Komoditas Pangan</h1>


        <form action="{{ route('komoditas.store') }}" method="POST">
            @csrf
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="nama_komoditas">Pilih Komoditas</label>
                </div>
                <select class="custom-select" id="nama_komoditas" name="nama_komoditas" required>
                    <option value="" disabled selected>Pilih Komoditas</option>
                    @foreach ($komoditas as $item)
                        <option value="{{ $item }}" {{ old('nama_komoditas') == $item ? 'selected' : '' }}>
                            {{ $item }}
                        </option>
                    @endforeach
                </select>
                @error('nama_komoditas')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="harga_komoditas" class="form-label">Harga Komoditas</label>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="harga_komoditas">Rp</span>
                    </div>
                    <input type="number" id="harga_komoditas" name="harga_komoditas" class="form-control"
                        placeholder="Harga Komoditas" aria-label="harga_komoditas" aria-describedby="harga_komoditas"
                        required>
                </div>
            </div>
            <div class="mb-3">
                <label for="jumlah_komoditas" class="form-label">Jumlah Komoditas Tersedia</label>
                <div class="input-group mb-3">
                    <input type="text" id="jumlah_komoditas" name="jumlah_komoditas" class="form-control"
                        placeholder="Jumlah Komoditas Tersedia" aria-label="Jumlah Komoditas Tersedia"
                        aria-describedby="jumlah_komoditas" required>
                    <div class="input-group-append">
                        <span class="input-group-text" id="jumlah_komoditas">Ton</span>
                    </div>
                </div>

            </div>
            <div class="mb-3">
                <label for="kebutuhan_rumah_tangga" class="form-label">Jumlah Kebutuhan Rumah Tangga</label>
                <div class="input-group mb-3">
                    <input type="text" id="kebutuhan_rumah_tangga" name="kebutuhan_rumah_tangga" class="form-control"
                        placeholder="Jumlah Kebutuhan Rumah Tangga" aria-label="Jumlah Komoditas Tersedia"
                        aria-describedby="kebutuhan_rumah_tangga" required>
                    <div class="input-group-append">
                        <span class="input-group-text" id="kebutuhan_rumah_tangga">Ton</span>
                    </div>
                </div>
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="tempat_survey">Pilih Pasar</label>
                </div>
                <select class="custom-select" id="tempat_survey" name="tempat_survey" required>
                    <option selected>Pilih Pasar Tempat Survey</option>
                    <option value="pasar_kediri">Pasar Kediri</option>
                    <option value="pasar_baturiti">Pasar Baturiti</option>
                    <option value="pasar_pesiapan">Pasar Pesiapan</option>
                    <option value="pasar_tabanan">Pasar Tabanan</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="tgl_pelaksanaan" class="form-label">Tanggal Pelaksanaan</label>
                <input type="date" class="form-control" id="tgl_pelaksanaan" placeholder="Tanggal Pelaksanaan"
                    name="tgl_pelaksanaan" required>
            </div>
            <div class="mb-3">
                <label for="minggu_dilakukan_survey" class="form-label">Minggu Survey dilakukan</label>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="minggu_dilakukan_survey">Minggu ke</label>
                    </div>
                    <select class="custom-select" id="minggu_dilakukan_survey" name="minggu_dilakukan_survey">
                        <option selected>Pilih Minggu Ke Berapa Survey Dilakukan...</option>
                        <option value="1">Minggu ke-1</option>
                        <option value="2">Minggu ke-2</option>
                        <option value="3">Minggu ke-3</option>
                        <option value="4">Minggu ke-4</option>
                    </select>
                </div>
            </div>
            <div>
                <button class="btn btn-primary">Simpan Data</button>
            </div>
        </form>

    </div>
@endsection
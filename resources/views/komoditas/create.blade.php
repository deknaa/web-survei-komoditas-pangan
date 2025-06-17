@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <h4 class="mb-4 font-weight-bold text-gray-800">Tambah Komoditas Pangan</h4>

        @if ($errors->has('msg'))
            <div class="alert alert-danger">
                {{ $errors->first('msg') }}
            </div>
        @endif

        <form action="{{ route('komoditas.store') }}" method="POST">
            @csrf

            {{-- Informasi Komoditas --}}
            <div class="card shadow mb-4">
                <div class="card-header font-weight-bold">
                    Informasi Komoditas
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nama_komoditas">Pilih Komoditas <span class="text-danger">*</span></label>
                            <select class="form-control" id="nama_komoditas" name="nama_komoditas" required>
                                <option value="" disabled selected>Pilih Komoditas</option>
                                @foreach ($komoditas as $item)
                                    <option value="{{ $item }}"
                                        {{ old('nama_komoditas') == $item ? 'selected' : '' }}>
                                        {{ $item }}
                                    </option>
                                @endforeach
                            </select>
                            @error('nama_komoditas')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="harga_komoditas">Harga Komoditas (Rp) <span class="text-danger">*</span></label>
                            <input type="number" id="harga_komoditas" name="harga_komoditas" class="form-control"
                                placeholder="Masukkan harga komoditas" required>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ketersediaan & Kebutuhan --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header font-weight-bold">Ketersediaan</div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="jumlah_komoditas">Jumlah Tersedia (Ton)</label>
                                <input type="text" id="jumlah_komoditas" name="jumlah_komoditas" class="form-control"
                                    required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header font-weight-bold">Kebutuhan</div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="kebutuhan_rumah_tangga">Kebutuhan Rumah Tangga (Ton)</label>
                                <input type="text" id="kebutuhan_rumah_tangga" name="kebutuhan_rumah_tangga"
                                    class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detail Survey --}}
            <div class="card shadow mb-4">
                <div class="card-header font-weight-bold">Detail Survey</div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tempat_survey">Pilih Pasar <span class="text-danger">*</span></label>
                            <select class="form-control" id="tempat_survey" name="tempat_survey" required>
                                <option value="" disabled selected>Pilih Pasar Tempat Survey</option>
                                <option value="pasar_kediri">Pasar Kediri</option>
                                <option value="pasar_baturiti">Pasar Baturiti</option>
                                <option value="pasar_pesiapan">Pasar Pesiapan</option>
                                <option value="pasar_tabanan">Pasar Tabanan</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="tgl_pelaksanaan">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tgl_pelaksanaan" name="tgl_pelaksanaan" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="minggu_dilakukan_survey">Minggu Survey Dilakukan <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" id="minggu_dilakukan_survey" name="minggu_dilakukan_survey"
                                required>
                                <option value="" disabled selected>Pilih Minggu Ke...</option>
                                <option value="1">Minggu ke-1</option>
                                <option value="2">Minggu ke-2</option>
                                <option value="3">Minggu ke-3</option>
                                <option value="4">Minggu ke-4</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tombol Simpan --}}
            <div class="text-end">
                <button type="submit" class="btn btn-primary">Simpan Data</button>
            </div>

        </form>
    </div>
@endsection

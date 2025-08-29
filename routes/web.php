<?php

use App\Http\Controllers\EksekutifController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KomoditasController;
use App\Http\Controllers\NeracaController;
use App\Http\Controllers\PetugasController;

Route::get('/', function () {
    return view('auth.login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/api/komoditas-list', [PetugasController::class, 'getKomoditasList']);
    Route::get('/api/harga-komoditas', [PetugasController::class, 'getHargaKomoditas']);
    Route::post('/api/neraca-pangan', [PetugasController::class, 'getData'])->name('pangan.data');

    Route::get('neraca-pangan', [NeracaController::class, 'index'])->name('neraca-pangan');
    Route::post('/neraca-pangan/cari', [NeracaController::class, 'search'])->name('komoditas.search');
    Route::get('neraca-pangan/add', [NeracaController::class, 'create'])->name('neraca-pangan.create');
    Route::post('/neraca-pangan/hitung', [NeracaController::class, 'hitung'])->name('neraca-pangan.hitung');

    // Komoditas
    Route::resource('komoditas', KomoditasController::class);
    Route::post('/search', [KomoditasController::class, 'search'])->name('komoditas.search');
    Route::post('/export', [KomoditasController::class, 'export'])->name('komoditas.export');
    Route::post('komoditas/export', [KomoditasController::class, 'export'])->name('komoditas.export');
});

Route::middleware(['auth', 'eksekutifRole'])->prefix('eksekutif')->group(function () {
    Route::get('/dashboard', [EksekutifController::class, 'index'])->name('dashboard.eksekutif');
    Route::put('/komoditas/{id}/verifikasi', [KomoditasController::class, 'verifikasi'])->name('komoditas.verifikasi');

    Route::get('komoditas-export', [KomoditasController::class, 'exports'])->name('komoditas.exports');
});

Route::middleware(['auth', 'petugasRole'])->prefix('petugas')->group(function () {
    Route::get('/dashboard', [PetugasController::class, 'index'])->name('dashboard');
});

require __DIR__ . '/auth.php';

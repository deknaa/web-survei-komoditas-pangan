<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KomoditasController;
use App\Http\Controllers\PetugasController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'eksekutifRole'])->prefix('eksekutif')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.eksekutif.dashboard_eksekutif');
    })->name('dashboard.eksekutif');
});

Route::middleware(['auth', 'petugasRole'])->prefix('petugas')->group(function () {
    Route::get('/dashboard', [PetugasController::class, 'index'])->name('dashboard');

    // Komoditas
    Route::resource('komoditas', KomoditasController::class);
    Route::get('/api/komoditas-list', [PetugasController::class, 'getKomoditasList']);
    Route::get('/api/harga-komoditas', [PetugasController::class, 'getHargaKomoditas']);
    Route::post('/api/neraca-pangan', [PetugasController::class, 'getData'])->name('pangan.data');
});

require __DIR__ . '/auth.php';

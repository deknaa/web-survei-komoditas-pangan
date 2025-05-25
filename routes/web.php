<?php

use App\Http\Controllers\KomoditasController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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
    Route::get('/dashboard', function () {
        return view('dashboard.petugas.dashboard_petugas');
    })->name('dashboard');

    Route::resource('komoditas', KomoditasController::class);
});

require __DIR__.'/auth.php';

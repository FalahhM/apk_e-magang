<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MagangController;
use App\Http\Controllers\SesiController;
use Illuminate\Support\Facades\Route;

// Rute untuk Guest
Route::middleware(['guest'])->group(function() {
    Route::get('/', [SesiController::class, 'index'])->name('login');
    Route::post('/', [SesiController::class, 'login']);
});

// Rute untuk Redirect Home
Route::get('/home', function() {
    return redirect('/admin');
});

// Rute untuk Authenticated User dengan akses admin
Route::middleware(['auth', 'userAkses:admin'])->group(function() {
    Route::get('/admin', [AdminController::class, 'index']);
    Route::get('/pengajuanmagang', [AdminController::class, 'pengajuan']);
    Route::get('/pengajuanmagang', [AdminController::class, 'tampilPengajuan'])->name('tampilPengajuan');
    Route::post('/logout', [SesiController::class, 'logout']);
});

// Rute untuk Registrasi
Route::get('/register', [SesiController::class, 'formregister'])->name('register');
Route::post('/register', [SesiController::class, 'register']);

// Rute untuk Magang dengan middleware auth
Route::middleware(['auth'])->group(function() {
    Route::get('/kampus', [MagangController::class, 'nampil']);
    Route::get('/formpengajuan', [MagangController::class, 'pengajuan']);
    Route::post('/mahasiswa', [MagangController::class, 'storeMahasiswa'])->name('storeMahasiswa');
    Route::get('/mahasiswa/{id}/edit', [MagangController::class, 'editMahasiswa']);
    Route::put('/mahasiswa/{id}', [MagangController::class, 'updateMahasiswa']);
    Route::delete('hapusMahasiswa/{id}', [MagangController::class, 'hapusMahasiswa'])->name('hapusMahasiswa');
    Route::get('/tampilanawal', [DashboardController::class, 'index'])->name('tampilanawal');
});

// Rute untuk Store Pengajuan
Route::post('/storePengajuan', [MagangController::class, 'storePengajuan'])->name('storePengajuan');

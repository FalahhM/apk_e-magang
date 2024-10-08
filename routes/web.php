<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MagangController;
use App\Http\Controllers\SesiController;
use Illuminate\Support\Facades\Route;

// Rute untuk Guest (Login dan Register)
Route::middleware(['guest'])->group(function() {
    Route::get('/', [SesiController::class, 'index'])->name('login');
    Route::post('/', [SesiController::class, 'login']);
    Route::get('/register', [SesiController::class, 'formregister'])->name('register');
    Route::post('/register', [SesiController::class, 'register']);
});

// Rute untuk Authenticated User dengan akses admin
Route::middleware(['auth', 'userAkses:admin'])->group(function() {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.home');
    Route::get('/pengajuanmagang', [AdminController::class, 'tampilPengajuan'])->name('tampilPengajuan');
    Route::post('/logout', [SesiController::class, 'logout'])->name('logout');
    Route::get('/detailpengajuan/{id}', [AdminController::class, 'detailpengajuan'])->name('detailpengajuan');
    Route::post('/prosespengajuan{id}', [AdminController::class, 'prosesPengajuan'])->name('prosesPengajuan');
    Route::get('/detailpengajuan/{id}/cetak', [AdminController::class, 'cetakProsesPDF'])->name('cetakProsesPDF');
    Route::post('/terimapengajuan{id}', [AdminController::class, 'terimaPengajuan'])->name('terimaPengajuan');
    Route::get('/pengajuan/{id}/surat-terima', [AdminController::class, 'lihatSuratTerima'])->name('lihatSuratTerima');
    Route::post('/tolakpengajuan{id}', [AdminController::class, 'tolakPengajuan'])->name('tolakPengajuan');
    Route::get('/pengajuan/{id}/surat-tolak', [AdminController::class, 'lihatSuratTolak'])->name('lihatSuratTolak');
});

// Rute untuk User Authenticated (Akses untuk Magang)
Route::middleware(['auth'])->group(function() {
    Route::get('/kampusdashboard', [MagangController::class, 'kampusDashboard'])->name('kampusdashboard');
    Route::get('/kampus', [MagangController::class, 'nampil'])->name('kampus');
    Route::post('/logout', [SesiController::class, 'logout'])->name('logout');
    Route::get('/formpengajuan', [MagangController::class, 'pengajuan'])->name('formPengajuan');
    Route::post('/storepengajuan', [MagangController::class, 'storePengajuan'])->name('storePengajuan');
});


Route::get('/email/verify/{id}/{token}', [SesiController::class, 'verifyEmail'])->name('email.verify');

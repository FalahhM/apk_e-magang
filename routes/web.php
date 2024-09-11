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
    Route::get('/pengajuanmagang', [AdminController::class, 'tampilPengajuan'])->name('tampilPengajuan');
    Route::post('/logout', [SesiController::class, 'logout']);
});

// Rute untuk Registrasi
Route::get('/register', [SesiController::class, 'formregister'])->name('register');
Route::post('/register', [SesiController::class, 'register']);

// Rute untuk Magang dengan middleware auth
Route::middleware(['auth'])->group(function() {
    Route::get('/tampilanawal', [DashboardController::class, 'index'])->name('tampilanawal');
});

Route::get('/kampus', [MagangController::class, 'nampil'])->middleware('auth');
Route::get('/formpengajuan', [MagangController::class, 'pengajuan'])->name('formPengajuan');
Route::post('/storepengajuan', [MagangController::class, 'storePengajuan'])->name('storePengajuan');

// Rute untuk Store Pengajuan
Route::get('/detailpengajuan{id}', [AdminController::class, 'detailpengajuan'])->name('detailpengajuan');
Route::post('/pengajuan{id}/terima', [AdminController::class, 'terimapengajuan'])->name('pengajuan.terima');
Route::post('/pengajuan{id}/tolak', [AdminController::class, 'tolakpengajuan'])->name('pengajuan.tolak');

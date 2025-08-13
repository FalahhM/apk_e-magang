<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DospemController;
use App\Http\Controllers\MagangController;
use App\Http\Controllers\SesiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MahasiswaController;
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
    // Halaman absensi
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index'); // Langkah 1
    Route::post('/absensi/store', [AbsensiController::class, 'store'])->name('absensi.store'); // Simpan absen
    Route::get('/absensi/rekap', [AbsensiController::class, 'rekap'])->name('absensi.rekap');
    Route::get('/absensi/rekap/pdf/{id}', [AbsensiController::class, 'exportPDF'])->name('absensi.rekap.pdf');
    Route::get('/absensi/rekap/export/{id}', [AbsensiController::class, 'exportPDF'])->name('absensi.export');
    Route::get('/absensi/filter', [AbsensiController::class, 'filter'])->name('absensi.filter');
    Route::post('/absensi/store-ajax', [AbsensiController::class, 'storeAjax'])->name('absensi.store.ajax');
    Route::get('/absensi/export-all-pdf', [AbsensiController::class, 'exportALLPDF'])->name('absensi.exportALLPDF');
    // halaman laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::post('/laporan/store', [LaporanController::class, 'store'])->name('laporan.store');
    Route::get('/laporan/sertifikat/{id}', [LaporanController::class, 'lihatSertifikat'])->name('laporan.lihat');
    Route::post('/laporan/update/{id}', [LaporanController::class, 'update'])->name('laporan.update');
    Route::post('/laporan/kirim/{id}', [LaporanController::class, 'kirim'])->name('laporan.kirim');
});

// Rute untuk User Authenticated (Akses untuk Magang)
Route::middleware(['auth'])->group(function() {
    Route::get('/kampusdashboard', [MagangController::class, 'kampusDashboard'])->name('kampusdashboard');
    Route::get('/kampus', [MagangController::class, 'nampil'])->name('kampus');
    Route::post('/logout', [SesiController::class, 'logout'])->name('logout');
    Route::get('/formpengajuan', [MagangController::class, 'pengajuan'])->name('formPengajuan');
    Route::post('/storepengajuan', [MagangController::class, 'storePengajuan'])->name('storePengajuan');
});

// mahasiswa
Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/mahasiswadashboard', [MahasiswaController::class, 'index'])->name('mahasiswadashboard');


    // menu absen
    Route::get('mahasiswa/absensi', [MahasiswaController::class, 'absensiForm'])->name('mahasiswa.absensi');
    Route::post('mahasiswa/absensi', [MahasiswaController::class, 'absensiStore'])->name('mahasiswa.absensi.store');


    // menu laporan kegiatan magang
    Route::get('mahasiswa/laporan', [MahasiswaController::class, 'laporanIndex'])->name('mahasiswa.laporan.index');
    Route::get('mahasiswa/laporan/create', [MahasiswaController::class, 'laporanCreate'])->name('mahasiswa.laporan.create');
    Route::post('mahasiswa/laporan/store', [MahasiswaController::class, 'laporanStore'])->name('mahasiswa.laporan.store');

    Route::post('/mahasiswa/laporan/pembimbing', [MahasiswaController::class, 'simpanPembimbing'])->name('mahasiswa.laporan.simpanPembimbing');


    // Cetak semua laporan mahasiswa
    // web.php
    Route::get('/mahasiswa/laporan/cetak-semua', [MahasiswaController::class, 'laporanCetakSemua'])
        ->name('mahasiswa.laporan.cetakSemua')
        ->middleware(['auth', 'role:mahasiswa']);


    Route::get('mahasiswa/laporan/{id}/upload', [MahasiswaController::class, 'laporanUploadForm'])->name('mahasiswa.laporan.uploadForm');
    Route::post('mahasiswa/laporan/{id}/upload', [MahasiswaController::class, 'laporanUploadStore'])->name('mahasiswa.laporan.uploadStore');

    // Edit
    Route::get('/mahasiswa/laporan/{id}/edit', [MahasiswaController::class, 'laporanEdit'])->name('mahasiswa.laporan.edit');
    Route::put('/mahasiswa/laporan/{id}', [MahasiswaController::class, 'laporanUpdate'])->name('mahasiswa.laporan.update');

    // Hapus
    Route::delete('/mahasiswa/laporan/{id}', [MahasiswaController::class, 'laporanDestroy'])->name('mahasiswa.laporan.destroy');
});

// dospem
Route::middleware(['auth', 'role:dospem'])->group(function () {
    Route::get('/dospemdashboard', [DospemController::class, 'index'])->name('dospemdashboard');
    Route::get('/dospem/absensi', [DospemController::class, 'absensiMahasiswaBimbingan'])->name('dospem.absensi');
    Route::get('/dospem/absensi/{id}', [DospemController::class, 'detailAbsensiMahasiswa'])->name('dospem.absensi.detail');

});

Route::get('/check-verification', function () {
    $user = session('user_email') 
        ? \App\Models\User::where('email', session('user_email'))->first()
        : auth()->user();

    if ($user && $user->email_verified_at) {
        return response()->json(['verified' => true]);
    }

    return response()->json(['verified' => false]);
});

Route::get('/email/verify/{id}/{token}', [SesiController::class, 'verifyEmail'])->name('email.verify');

<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MagangController;
use App\Http\Controllers\SesiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function(){
    Route::get('/',[SesiController::class,'index'])->name('login');
    Route::post('/',[SesiController::class,'login']);
});
Route::get('/home',function(){
    return redirect('/admin');
});

Route::middleware(['auth'])->group(function(){
    Route::get('/admin',[AdminController::class,'index'])->middleware('userAkses:admin');
    Route::get('/kampus',[AdminController::class,'kampus'])->middleware('userAkses:kampus');
    Route::post('/logout',[SesiController::class,'logout']);
});

Route::get('/register',[SesiController::class,'formregister'])->name('register');
Route::post('/register',[SesiController::class,'register']);
    
Route::get('/kampus', [MagangController::class, 'nampil'])->middleware('auth');
Route::get('/formpengajuan', [MagangController::class, 'pengajuan'])->middleware('auth');
Route::post('/mahasiswa', [MagangController::class, 'storeMahasiswa'])->middleware('auth');
Route::get('/mahasiswa/{id}/edit', [MagangController::class, 'editMahasiswa'])->middleware('auth');
Route::put('/mahasiswa/{id}', [MagangController::class, 'updateMahasiswa'])->middleware('auth');
Route::delete('hapusMahasiswa/{id}',[MagangController::class,'hapusMahasiswa'])->middleware('auth')->name('hapusMahasiswa');

Route::middleware(['auth'])->group(function() {
    Route::get('/tampilanawal', [DashboardController::class, 'index'])->name('tampilanawal');
});

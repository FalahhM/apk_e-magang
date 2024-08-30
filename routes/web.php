<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MagangController;
use App\Http\Controllers\SesiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

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
    Route::get('/logout',[SesiController::class,'logout']);
});

Route::get('/register',[SesiController::class,'formregister'])->name('register');
Route::post('/register',[SesiController::class,'register']);

Route::get('/kampus',[MagangController::class,'nampil'])->middleware('auth');
Route::resource('mahasiswas', MagangController::class);
Route::get('/datatabel', [MagangController::class, 'datatabel'])->name('datatabel');

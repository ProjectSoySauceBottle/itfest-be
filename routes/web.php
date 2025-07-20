<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MejaController;
use App\Http\Controllers\PesanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KonfirmasiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('home');
// });
Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

Route::prefix('admin')->group(function () {
    Route::resource('/menu', MenuController::class)->names('admin.menu');
    Route::resource('/meja', MejaController::class)->names('admin.meja');
    Route::resource('/pesanan', PesanController::class)->names('admin.pesanan');
});

// Route::resource('meja', MejaController::class); 
Route::get('/pesan/meja/{id}', [PesanController::class, 'form'])->name('pesan.meja');
Route::post('/pesan/{meja}/simpan', [PesanController::class, 'simpan'])->name('pesan.simpan');
Route::get('/pesan/success', function () {
    return view('user.pesan.success');
})->name('pesan.success');

// Konfirmasi Pesanan
Route::post('/pesan/konfirmasi/{id}', [KonfirmasiController::class, 'konfirmasi'])->name('pesan.konfirmasi');
Route::get('/pesan/konfirmasi/{id}', [KonfirmasiController::class, 'tampilkanKonfirmasi'])->name('pesan.tampilkan_konfirmasi');
Route::post('/pesan/simpan_db/{id}', [KonfirmasiController::class, 'simpan_db'])->name('pesan.simpan_db');

// Route::get('/pesan/{pesanan}/metode', [PesanController::class, 'metode'])->name('pesan.metode');
// Route::post('/pesan/{pesanan}/metode', [PesanController::class, 'simpanMetode'])->name('pesan.metode.simpan');
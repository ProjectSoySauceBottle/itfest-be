<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\MejaController;
use App\Http\Controllers\Api\PesananController;
use App\Http\Controllers\Api\StatistikController;
use App\Http\Controllers\Api\MenuRekomendasiController;
use App\Http\Controllers\Auth\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/', function () {
    return response()->json(['message' => 'API Only']);
});

// Auth (login)
Route::post('/admin/login', [AuthController::class, 'login']);

// Public View (menu, meja)
Route::get('/menus', [MenuController::class, 'index']);
Route::get('/menus/{id}', [MenuController::class, 'show']);

Route::get('/mejas', [MejaController::class, 'index']);
Route::get('/mejas/{id}', [MejaController::class, 'show']);

// Public create pesanan
Route::post('/pesanans', [PesananController::class, 'store']);

// ML-based recommendation
Route::get('/statistik/menu', [StatistikController::class, 'menu']);
Route::get('/rekomendasi-menu', [StatistikController::class, 'rekomendasi']);
Route::get('/menu/rekomendasi', [MenuRekomendasiController::class, 'index']);

// Payment
Route::post('/pesanans/{id}/bayar', [PesananController::class, 'bayar']);
Route::get('/pesanan/{id}/qr-cash', [PesananController::class, 'generateCashQr'])->name('pesanan.qrCash');
Route::get('/pesanans/{id}/konfirmasi-kasir', [PesananController::class, 'konfirmasiKasir'])->name('konfirmasi.kasir');

// Auth (Login)
Route::middleware('auth:sanctum')->group(function () {

    // CRUD menu (admin)
    Route::post('/menus', [MenuController::class, 'store']);
    Route::put('/menus/{id}', [MenuController::class, 'update']);
    Route::delete('/menus/{id}', [MenuController::class, 'destroy']);

    // CRUD meja (admin)
    Route::post('/mejas', [MejaController::class, 'store']);
    Route::put('/mejas/{id}', [MejaController::class, 'update']);
    Route::delete('/mejas/{id}', [MejaController::class, 'destroy']);

    // View pesanan (admin)
    Route::get('/pesanans', [PesananController::class, 'index']);
    Route::get('/pesanans/{id}', [PesananController::class, 'show']);

    // (Opsional) Admin create/update/delete pesanan
    Route::put('/pesanans/{id}', [PesananController::class, 'update']);
    Route::delete('/pesanans/{id}', [PesananController::class, 'destroy']);
});



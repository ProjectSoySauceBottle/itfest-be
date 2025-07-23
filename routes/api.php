<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\MejaController;
use App\Http\Controllers\Api\PesananController;
use App\Http\Controllers\Api\StatistikController;
use App\Http\Controllers\Api\MenuRekomendasiController;

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

// Menu
Route::apiResource('menus', MenuController::class);

// Meja
Route::apiResource('mejas', MejaController::class);

// Pesanan
Route::apiResource('pesanans', PesananController::class);

// Machine Learning 
Route::get('/statistik/menu', [StatistikController::class, 'menu']);
Route::get('/rekomendasi-menu', [StatistikController::class, 'rekomendasi']);

// Menu Rekomendasi dari Flask
Route::get('/menu/rekomendasi', [MenuRekomendasiController::class, 'index']);

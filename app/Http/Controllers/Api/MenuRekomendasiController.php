<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\menu;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class MenuRekomendasiController extends Controller
{
    public function index()
    {
        try {
            Log::info('Mengirim request ke Flask API...');
            // Panggil API Flask
            $response = Http::timeout(5)->get('http://127.0.0.1:5000/rekomendasi');
            Log::info('Response dari Flask:', $response->json());

            if (!$response->ok()) {
                return response()->json(['error' => 'Gagal mengambil data dari AI'], 500);
            }

            $menuIds = $response->json()['rekomendasi_menu_id'];

            // Ambil data menu dari database berdasarkan ID
            $menus = Menu::whereIn('id', $menuIds)->get();

            // Kembalikan response
            return response()->json([
                'status' => 'success',
                'recommended_menus' => $menus
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MenuRekomendasiController extends Controller
{
    public function index()
    {
        try {
            Log::info('Mengirim request ke Flask API...');
            // Panggil API Flask
            $url = rtrim((string) config('services.rekomendasi.url'), '/');

            if ($url === '') {
                return $this->fallback();
            }

            $response = Http::timeout(5)->get($url . '/rekomendasi');
            Log::info('Response dari Flask:', $response->json());

            if (!$response->ok()) {
                return $this->fallback();
            }

            $menuIds = $response->json()['rekomendasi_menu_id'];

            // Ambil data menu dari database berdasarkan ID
            $menus = Menu::whereIn('menu_id', $menuIds)->get();

            // Kembalikan response
            return response()->json([
                'status' => 'success',
                'recommended_menus' => $menus
            ]);
        } catch (\Throwable $e) {
            return $this->fallback();
        }
    }

    /**
     * The Flask recommender is a separate optional process (rekomendasi_api/).
     * When it is not running, rank by units sold in SQL instead of failing, so
     * the menu page still renders recommended items.
     */
    private function fallback()
    {
        $ids = DB::table('pesanandetails')
            ->select('menu_id')
            ->groupBy('menu_id')
            ->orderByRaw('SUM(jumlah_pesanan) DESC')
            ->limit(5)
            ->pluck('menu_id');

        $menus = $ids->isEmpty() ? Menu::limit(5)->get() : Menu::whereIn('menu_id', $ids)->get();

        return response()->json([
            'status' => 'success',
            'recommended_menus' => $menus,
        ]);
    }
}

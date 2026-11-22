<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Menu;
use Illuminate\Http\Request;

class StatistikController extends Controller
{
    public function menu() 
    {
        $data = DB::table('pesanandetails')
            ->select('menu_id', DB::raw('SUM(jumlah_pesanan) as total_terjual'))
            ->groupBy('menu_id')
            ->orderByDesc('total_terjual')
            ->get();

        return response()->json($data);
    }

    public function rekomendasi()
    {
        $menus = $this->recommendedMenus();

        if ($menus !== null) {
            return response()->json($menus);
        }

        return response()->json([
            'message' => 'Gagal mengambil data dari sistem rekomendasi.'
        ], 503);
    }

    /**
     * Ask the Python recommender for the top-selling menu ids.
     *
     * The service is optional: it is a separate Flask process (see
     * rekomendasi_api/) that is not deployed alongside the API. When it is
     * unreachable we fall back to the same ranking in SQL so the endpoint keeps
     * working instead of returning a 200 with nothing in it.
     */
    private function recommendedMenus(): ?\Illuminate\Support\Collection
    {
        $url = rtrim((string) config('services.rekomendasi.url'), '/');

        if ($url !== '') {
            try {
                $response = Http::timeout(3)->get($url . '/rekomendasi');

                if ($response->successful()) {
                    $ids = $response->json('rekomendasi_menu_id');

                    if (is_array($ids) && $ids !== []) {
                        return Menu::whereIn('menu_id', $ids)->get();
                    }
                }
            } catch (\Throwable $e) {
                // fall through to the SQL ranking
            }
        }

        $ids = DB::table('pesanandetails')
            ->select('menu_id')
            ->groupBy('menu_id')
            ->orderByRaw('SUM(jumlah_pesanan) DESC')
            ->limit(5)
            ->pluck('menu_id');

        return $ids->isEmpty() ? null : Menu::whereIn('menu_id', $ids)->get();
    }
}

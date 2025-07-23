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
            ->select('menu_id', DB::raw('SUM(jumlah) as total_terjual'))
            ->groupBy('menu_id')
            ->orderByDesc('total_terjual')
            ->get();

        return response()->json($data);
    }

    public function rekomendasi()
    {
        $response = Http::get('http://localhost:5000/rekomendasi');

        if ($response->successful()) {
            $ids = $response->json('rekomendasi_menu_id');

            $menus = Menu::whereIn('menu_id', $ids)->get();

            return response()->json($menus);
        }

        return response()->json([
            'message' => 'Gagal mengambil data dari sistem rekomendasi.'
        ], 500);
    }
}

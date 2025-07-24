<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\menu;
use App\Models\PesananDetail;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function index()
    {
        $data = Pesanan::with('menu', 'meja')->get();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'meja_id' => 'required|exists:mejas,meja_id',
            'metode_bayar' => 'required|in:cash,cashless',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,menu_id',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $totalHarga = 0;
            $totalItem = 0;

            // Hitung total harga & total pesanan
            foreach ($request->items as $item) {
                $menu = Menu::findOrFail($item['menu_id']);
                $totalHarga += $menu->harga * $item['jumlah'];
                $totalItem += $item['jumlah'];
            }

            // Simpan ke tabel `pesanans`
            $pesanan = Pesanan::create([
                'meja_id' => $request->meja_id,
                'jumlah_pesanan' => $totalItem,
                'total_harga' => $totalHarga,
                'metode_bayar' => $request->metode_bayar,
                'status' => 'pending'
            ]);

            // Simpan ke tabel `pesanan_details`
            foreach ($request->items as $item) {
                $menu = Menu::findOrFail($item['menu_id']);
                PesananDetail::create([
                    'pesanan_id' => $pesanan->pesanan_id,
                    'menu_id' => $menu->menu_id,
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $menu->harga * $item['jumlah']
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Pesanan berhasil dibuat.',
                'data' => $pesanan->load('pesananDetails.menu')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal membuat pesanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $pesanan = Pesanan::with('details.menu')->findOrFail($id);
        return response()->json($pesanan);
    }

    public function update(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update($request->only('status', 'metode_bayar'));
        return response()->json(['message' => 'Pesanan diperbarui', 'data' => $pesanan]);
    }

    public function destroy($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->delete();
        return response()->json(['message' => 'Pesanan dihapus']);
    }
}

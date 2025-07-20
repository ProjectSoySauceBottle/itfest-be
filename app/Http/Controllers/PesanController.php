<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meja;
use App\Models\menu;
use App\Models\Pesanan;
use App\Models\pesanan_detail;

class PesanController extends Controller
{
    public function index()
    {
        $pesanans = Pesanan::with(['meja', 'menu'])->latest()->paginate(10);
        return view('admin.pesanan.index', compact('pesanans'));
    }

    public function form($id) 
    {
        $meja = Meja::findOrFail($id);
        $menus = Menu::all();
        return view('user.pesan.form', compact('meja', 'menus'));
    }

    public function simpan(Request $request, $meja_id)
    {
        $request->validate([
            'menu_id' => 'required|array',
            'menu_id.*' => 'required|exists:menus,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required|integer|min:1',
            'metode_pembayaran' => 'required|in:cash,qr',
        ]);

        $menu_data = [];
        foreach ($request->menu_id as $i => $menu_id) {
            $menu = Menu::findOrFail($menu_id);
            $jumlah = $request->jumlah[$i];
            $menu_data[$menu_id] = [
                'nama' => $menu->nama,
                'harga' => $menu->harga,
                'jumlah' => $jumlah,
                'subtotal' => $menu->harga * $jumlah,
            ];
        }

        // Simpan ke session untuk konfirmasi
        session([
            'pesanan_' . $meja_id => $menu_data,
            'metode_pembayaran_' . $meja_id => $request->metode_pembayaran,
        ]);

        return redirect()->route('pesan.tampilkan_konfirmasi', $meja_id);
    }

    // public function metode($id)
    // {
    //     $pesanan = Pesanan::findOrFail($id);
    //     return view('user.pesan.metode', compact('pesanan'));
    // }

    // public function simpanMetode(Request $request, $id)
    // {
    //     $request->validate([
    //         'metode' => 'required|in:cash,qr',
    //     ]);

    //     $pesanan = Pesanan::findOrFail($id);
    //     $pesanan->metode_pembayaran = $request->metode;
    //     $pesanan->save();

    //     return redirect()->route('pesan.success');
    // }

}

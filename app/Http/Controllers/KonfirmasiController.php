<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;

class KonfirmasiController extends Controller
{
    public function konfirmasi(Request $request, $meja_id)
    {
        // Simpan ke session
        session([
            'pesanan_' . $meja_id => $request->menu_data,
            'metode_pembayaran_' . $meja_id => $request->metode_pembayaran,
        ]);

        return redirect()->route('pesan.tampilkan_konfirmasi', $meja_id);
    }

    public function tampilkanKonfirmasi($meja_id)
    {
        $menu_data = session('pesanan_' . $meja_id, []);
        $metode = session('metode_pembayaran_' . $meja_id, 'cash');

        if (empty($menu_data)) {
            return redirect()->back()->with('error', 'Tidak ada pesanan yang dipilih.');
        }

        return view('user.pesan.konfirmasi', compact('menu_data', 'meja_id', 'metode'));
    }

    public function simpan_db(Request $request, $meja_id)
    {
        $pesanan_data = session('pesanan_' . $meja_id);
        $metode = session('metode_pembayaran_' . $meja_id);

        if (!$pesanan_data || !$metode) {
            return redirect()->route('pesan.tampilkan_konfirmasi', $meja_id)->with('error', 'Data pesanan tidak ditemukan.');
        }

        foreach ($pesanan_data as $menu_id => $item) {
            if ((int) $item['jumlah'] > 0) {
                Pesanan::create([
                    'meja_id' => $meja_id,
                    'menu_id' => $menu_id,
                    'jumlah_pesanan' => $item['jumlah'],
                    'total_harga' => $item['harga'] * $item['jumlah'],
                    'metode_bayar' => $metode,
                    'status' => 'pending',
                ]);
            }
        }

        // Kosongkan session
        session()->forget(['pesanan_' . $meja_id, 'metode_pembayaran_' . $meja_id]);

        return redirect()->route('pesan.success')->with('success', 'Pesanan berhasil disimpan.');
    }
}

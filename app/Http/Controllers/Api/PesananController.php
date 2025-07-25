<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Menu;
use App\Models\PesananDetail;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Midtrans\Config;
use Midtrans\Snap;

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
            'status_bayar' => 'required|in:pending,paid',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,menu_id',
            'items.*.jumlah' => 'required|integer|min:1',
            'status' => 'dalam_antrian'
        ]);

        DB::beginTransaction();

        try {
            $totalHarga = 0;
            $totalItem = 0;

            foreach ($request->items as $item) {
                $menu = Menu::findOrFail($item['menu_id']);
                $totalHarga += $menu->harga * $item['jumlah'];
                $totalItem += $item['jumlah'];
            }

            $pesanan = Pesanan::create([
                'meja_id' => $request->meja_id,
                'jumlah_pesanan' => $totalItem,
                'total_harga' => $totalHarga,
                'metode_bayar' => $request->metode_bayar,
                'status_bayar' => $request->status_bayar,
                'status' => 'pending' // bisa juga diganti jika Anda punya status lain
            ]);

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

            // Logic jika cash
            if ($pesanan->metode_bayar === 'cash') {
                $qrKasirUrl = route('pesanan.qrCash', ['id' => $pesanan->pesanan_id]);

                return response()->json([
                    'message' => 'Pesanan berhasil. Silakan tunjukkan QR ini ke kasir.',
                    'qr_code_kasir' => $qrKasirUrl,
                    'status_bayar' => 'pending',
                    'pesanan_id' => $pesanan->pesanan_id,
                ]);
            }

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

    public function cashless_payment(Request $request)
    {
        $order_id = 'ORDER-' . time();

        $pesanan = Pesanan::create([
            'meja_id' => $request->meja_id,
            'jumlah_pesanan' => collect($request->items)->sum('jumlah'),
            'total_harga' => $request->total_harga,
            'metode_bayar' => $request->metode_bayar,
            'status_bayar' => 'pending',
        ]);

        foreach ($request->items as $item) {
            $menu = Menu::find($item['menu_id']);

            Pesanandetail::create([
                'pesanan_id' => $pesanan->pesanan_id,
                'menu_id' => $item['menu_id'],
                'jumlah_pesanan' => $item['jumlah'],
                'harga_satuan' => $menu->harga,
                'total_harga' => $menu->harga * $item['jumlah'],
            ]);
        }

        // 3. Buat Snap Token Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.sanitize');
        Config::$is3ds = config('midtrans.enable_3ds');

        $payload = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => $request->total_harga,
            ],
            'customer_details' => [
                'nomor_meja' => 'Meja ' . $request->meja_id,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($payload);

            return response()->json([
                'success' => true,
                'snap' => $snapToken,
                'order_id' => $order_id,
                'pesanan_id' => $pesanan->pesanan_id
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update_status(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->status_bayar = $request->status_bayar;
        $pesanan->save();

        return response()->json(['message' => 'Status updated']);
    }


    public function bayar(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->status_bayar === 'paid') {
            return response()->json(['message' => 'Pesanan sudah dibayar'], 400);
        }

        if ($pesanan->metode_bayar === 'cash') {
            // Simulasi QR kasir
            $kasirQr = route('konfirmasi.kasir', ['id' => $pesanan->pesanan_id]);

            return response()->json([
                'message' => 'Silakan scan QR oleh kasir untuk konfirmasi pembayaran.',
                'qr_code' => $kasirQr,
                'metode_bayar' => 'cash'
            ]);
        }

        if ($pesanan->metode_bayar === 'cashless') {
            // Simulasi QRIS (bisa link statis atau dinamis)
            $qrisLink = asset('qris/qris-static.png'); // contoh file QRIS di public/qris/

            // Setelah pembayaran dianggap berhasil, ubah status jadi paid
            $pesanan->status_bayar = 'paid';
            $pesanan->save();

            return response()->json([
                'message' => 'Pembayaran cashless berhasil.',
                'qris' => url(asset('storage/' . $qrisLink)),
                'status' => 'paid',
            ]);
        }

        return response()->json(['message' => 'Metode pembayaran tidak valid'], 400);
    }

    public function generateCashQr($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        // URL untuk dikonfirmasi oleh kasir
        $url = route('konfirmasi.kasir', ['id' => $pesanan->pesanan_id]);

        // Buat QR code dari URL itu
        $qrImage = QrCode::format('jpg')->size(300)->generate($url);

        return response($qrImage)->header('Content-Type', 'image/jpg');
    }

    public function konfirmasiKasir($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->status_bayar === 'paid') {
            return response()->json(['message' => 'Pesanan sudah dibayar']);
        }

        $pesanan->status_bayar = 'paid';
        $pesanan->save();

        return response()->json(['message' => 'Pembayaran berhasil dikonfirmasi oleh kasir']);
    }

    public function estimasi($id)
    {
        // Ambil pesanan ini beserta menu di detail
        $pesanan = Pesanan::with('pesanandetails.menu')->where('pesanan_id', $id)->firstOrFail();

        // Ambil semua pesanan lain selain ini
        $pesananLain = Pesanan::where('pesanan_id', '!=', $id)
            ->with('pesanandetails.menu')
            ->get();

        $totalEstimasi = 0;

        foreach ($pesanan->pesanandetails as $item) {
            $menu = $item->menu;

            $jumlahAntrean = 0;

            foreach ($pesananLain as $pesananAntrean) {
                foreach ($pesananAntrean->pesanandetails as $itemAntrean) {
                    if ($itemAntrean->menu_id == $menu->menu_id) {
                        $jumlahAntrean += $itemAntrean->jumlah;
                    }
                }
            }

            // Hitung estimasi waktu: antrean + pesanan ini × estimasi_pembuatan
            $estimasi = ($jumlahAntrean + $item->jumlah) * $menu->estimasi_pembuatan;
            $totalEstimasi += $estimasi;
        }

        return response()->json([
            'pesanan_id' => $pesanan->pesanan_id,
            'estimasi_dalam_menit' => $totalEstimasi
        ]);
    }

    public function show($id)
    {
        $pesanan = Pesanan::with('details.menu')->findOrFail($id);
        return response()->json($pesanan);
    }

    public function update(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        $request->validate([
            'status' => 'nullable|string',
            'metode_bayar' => 'nullable|in:cash,cashless',
            'status_bayar' => 'nullable|in:pending,paid'
        ]);

        $pesanan->update($request->only('status', 'metode_bayar', 'status_bayar'));

        return response()->json(['message' => 'Pesanan diperbarui', 'data' => $pesanan]);
    }

    public function destroy($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->delete();
        return response()->json(['message' => 'Pesanan dihapus']);
    }

    public function order_by_table($table_number)
    {
        $meja = Meja::findOrFail($table_number);
        $pesanan = Pesanan::where('meja_id', $meja->meja_id)->with('pesananDetails.menu')->get();
        return response()->json($pesanan);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Models\Meja;

class MejaController extends Controller
{
    /**
     * Tampilkan daftar semua meja (admin)
     */
    public function index()
    {
        $mejas = Meja::latest()->paginate(10);
        return view('admin.meja.index', compact('mejas'));
    }

    /**
     * Form tambah meja tidak dipakai karena sudah modal di index.blade.php
     */
    public function create()
    {
        //
    }

    /**
     * Simpan meja baru dan generate QR code
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_meja' => 'required|string|unique:mejas,nomor_meja|max:10',
        ]);

        $meja = Meja::create([
            'nomor_meja' => $request->nomor_meja,
        ]);

        $qrContent = url("/pesan/meja/" . $meja->id);
        $qrPath = 'qr_codes/meja_' . $meja->id . '.svg';

        Storage::disk('public')->put($qrPath, QrCode::format('svg')->size(300)->generate($qrContent));

        $meja->qr_code_path = $qrPath;
        $meja->save();

        return redirect()->route('admin.meja.index')->with('success', 'Meja berhasil ditambahkan & QR code dibuat.');
    }

    /**
     * Tidak digunakan karena pakai modal edit (opsional bisa dibuat nanti)
     */
    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Hapus meja dan QR code-nya
     */
    public function destroy(string $id)
    {
        $meja = Meja::findOrFail($id);

        // Hapus QR code dari storage jika ada
        if ($meja->qr_code_path && Storage::disk('public')->exists($meja->qr_code_path)) {
            Storage::disk('public')->delete($meja->qr_code_path);
        }

        $meja->delete();

        return redirect()->route('admin.meja.index')->with('success', 'Data meja berhasil dihapus.');
    }
}

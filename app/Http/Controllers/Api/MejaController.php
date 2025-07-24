<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MejaController extends Controller
{
    public function index()
    {
        return Meja::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_meja' => 'required|string|unique:mejas,nomor_meja',
        ]);

        // Simpan data meja dulu
        $meja = Meja::create([
            'nomor_meja' => $request->nomor_meja,
        ]);

        // Isi konten QR code (bisa berupa URL atau teks)
        $qrContent = url("/pesanan/meja/" . $meja->meja_id);
        $qrPath = 'qr_codes/meja_' . $meja->meja_id . '.svg';

        Storage::disk('public')->put($qrPath, QrCode::format('svg')->size(300)->generate($qrContent));

        $meja->qr_code_path = $qrPath;
        $meja->save();

        return response()->json([
            'message' => 'Meja berhasil ditambahkan dan QR code berhasil dibuat',
            'data' => $meja
        ]);
    }

    public function show($id)
    {
        $meja = Meja::findOrFail($id);
        return response()->json($meja);
    }

    public function update(Request $request, $id)
    {
        $meja = Meja::findOrFail($id);

        $validated = $request->validate([
            'nomor_meja' => 'required|unique:mejas,nomor_meja,' . $id . ',meja_id',
            'qr_code' => 'nullable|file|image|max:2048',
        ]);

        if ($request->hasFile('qr_code')) {
            if ($meja->qr_code_path) {
                Storage::disk('public')->delete($meja->qr_code_path);
            }
            $path = $request->file('qr_code')->store('qr_codes', 'public');
            $meja->qr_code_path = $path;
        }

        $meja->nomor_meja = $validated['nomor_meja'];
        $meja->save();

        return response()->json($meja);
    }

    public function destroy($id)
    {
        $meja = Meja::findOrFail($id);
        if ($meja->qr_code_path) {
            Storage::disk('public')->delete($meja->qr_code_path);
        }
        $meja->delete();
        return response()->json(['message' => 'Meja deleted']);
    }
}

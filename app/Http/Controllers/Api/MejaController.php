<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use App\Support\Media;
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
        $qrContent = env('FRONTEND_URL') . '?meja=' . $meja->meja_id;
        // PNG, not SVG: the dashboard renders these through next/image, whose
        // optimiser refuses SVG and leaves the table with an empty cell.
        $qrPath = 'qr_codes/meja_' . $meja->meja_id . '.png';

        Storage::disk(Media::DISK)->put($qrPath, QrCode::format('png')->size(300)->generate($qrContent));

        $meja->qr_code_path = Media::url($qrPath);
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
            Media::delete($meja->qr_code_path);
            $meja->qr_code_path = Media::store($request->file('qr_code'), 'qr_codes');
        }

        $meja->nomor_meja = $validated['nomor_meja'];
        $meja->save();

        return response()->json($meja);
    }

    public function destroy($id)
    {
        $meja = Meja::findOrFail($id);
        Media::delete($meja->qr_code_path);
        $meja->delete();
        return response()->json(['message' => 'Meja deleted']);
    }
}

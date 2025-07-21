<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MejaController extends Controller
{
    public function index()
    {
        return Meja::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_meja' => 'required|unique:mejas,nomor_meja',
            'qr_code' => 'nullable|file|image|max:2048', // optional upload file
        ]);

        $path = null;
        if ($request->hasFile('qr_code')) {
            $path = $request->file('qr_code')->store('qr_codes', 'public');
        }

        $meja = Meja::create([
            'nomor_meja' => $validated['nomor_meja'],
            'qr_code_path' => $path,
        ]);

        return response()->json($meja, 201);
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

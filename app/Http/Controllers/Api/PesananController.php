<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;

class PesananController extends Controller
{
    public function index()
    {
        return response()->json(Pesanan::with('menu', 'meja')->get(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'meja_id' => 'required|exists:mejas,id',
            'total_harga' => 'required|numeric',
            'status' => 'required|string',
        ]);

        $pesanan = Pesanan::create($validated);
        return response()->json($pesanan, 201);
    }

    public function show($id)
    {
        $pesanan = Pesanan::with('menu', 'meja')->find($id);
        if (!$pesanan) {
            return response()->json(['message' => 'Pesanan not found'], 404);
        }

        return response()->json($pesanan, 200);
    }

    public function update(Request $request, $id)
    {
        $pesanan = Pesanan::find($id);
        if (!$pesanan) {
            return response()->json(['message' => 'Pesanan not found'], 404);
        }

        $pesanan->update($request->all());
        return response()->json($pesanan, 200);
    }

    public function destroy($id)
    {
        $pesanan = Pesanan::find($id);
        if (!$pesanan) {
            return response()->json(['message' => 'Pesanan not found'], 404);
        }

        $pesanan->delete();
        return response()->json(['message' => 'Pesanan deleted'], 200);
    }
}

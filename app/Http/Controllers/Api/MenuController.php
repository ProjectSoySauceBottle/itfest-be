<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    // Tampilkan semua menu
    public function index()
    {
        return Menu::all();
    }

    // Simpan menu baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_menu' => 'required|string|max:255',
            'tipe' => 'required|in:coffee,non_coffee',
            'harga' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|string', // gambar opsional
        ]);


        $menu = Menu::create($validated);

        return response()->json($menu, 201);
    }

    // Tampilkan satu menu
    public function show($id)
    {
        $meja = Menu::findOrFail($id);
        return response()->json($meja);
    }

    // Update menu
    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $validated = $request->validate([
            'nama_menu' => 'sometimes|string|max:255',
            'tipe' => 'sometimes|in:coffee,non_coffee,snack',
            'deskripsi' => 'nullable|string',
            'harga' => 'sometimes|numeric|min:0',
            'gambar' => 'nullable|string',
        ]);

        // Update data lainnya
        $menu->nama_menu = $validated['nama_menu'];
        $menu->tipe = $validated['tipe'];
        $menu->deskripsi = $validated['deskripsi'];
        $menu->harga = $validated['harga'];
        $menu->gambar = $validated['gambar'];
        $menu->save();

        return response()->json([
            'message' => 'Data menu berhasil diperbarui',
            'data' => $menu
        ]);
    }

    // Hapus menu
    public function destroy(Menu $menu)
    {
        if ($menu->gambar) {
            Storage::delete('public/menus/' . $menu->gambar);
        }
        $menu->delete();
        return response()->json(['message' => 'Menu deleted']);
    }
}

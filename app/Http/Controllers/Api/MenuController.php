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
            'gambar' => 'nullable|image|max:2048', // gambar opsional
            'estimasi_pembuatan' => 'required|integer|min:1'
        ]);

        $menu = new Menu($validated);

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('menus', 'public'); // simpan di storage/app/public/menus
            $validated['gambar'] = $path;
        }

        $menu->save();

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
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($menu->gambar_path) {
                Storage::disk('public')->delete($menu->gambar_path);
            }

            // Simpan gambar baru
            $path = $request->file('gambar')->store('menus', 'public');
            $menu->gambar_path = $path;
        }

        // Update data lainnya
        $menu->nama_menu = $validated['nama_menu'];
        $menu->tipe = $validated['tipe'];
        $menu->deskripsi = $validated['deskripsi'];
        $menu->harga = $validated['harga'];
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

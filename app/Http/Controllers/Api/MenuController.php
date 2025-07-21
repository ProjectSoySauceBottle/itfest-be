<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\menu;
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
        ]);

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('menus', 'public'); // simpan di storage/app/public/menus
            $validated['gambar'] = $path;
        }

        $menu = Menu::create($validated);

        return response()->json($menu, 201);
    }

    // Tampilkan satu menu
    public function show(Menu $menu)
    {
        return $menu;
    }

    // Update menu
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'nama_menu' => 'sometimes|string|max:255',
            'tipe' => 'sometimes|in:coffee,non_coffee',
            'deskripsi' => 'nullable|string',
            'harga' => 'sometimes|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($menu->gambar) {
                Storage::delete('public/menus/' . $menu->gambar);
            }
            $path = $request->file('gambar')->store('public/menus');
            $validated['gambar'] = basename($path);
        }

        $menu->update($validated);
        return response()->json($menu);
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

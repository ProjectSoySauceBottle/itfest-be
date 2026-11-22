<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Support\Media;
use Illuminate\Http\JsonResponse;
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
        // The admin form sends the human label from the category dropdown
        // ("Coffee", "Non Coffee", "Snack") and does not know about
        // estimasi_pembuatan, so both are normalised here instead of rejecting
        // a payload the UI itself produces.
        $request->merge([
            'tipe' => $this->normaliseTipe($request->input('tipe')),
            'estimasi_pembuatan' => $request->input('estimasi_pembuatan') ?: 5,
        ]);

        $validated = $request->validate([
            'nama_menu' => 'required|string|max:255',
            'tipe' => 'required|in:coffee,non_coffee,snack',
            'harga' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|string', // gambar opsional
            'estimasi_pembuatan' => 'required|integer|min:1'
        ]);

        $menu = new Menu($validated);

        $menu->save();

        return response()->json($menu, 201);
    }

    /**
     * Map a category label onto the value the menus table stores.
     */
    private function normaliseTipe(?string $tipe): string
    {
        $key = strtolower(str_replace([' ', '-'], '_', trim((string) $tipe)));

        return match ($key) {
            'non_coffee', 'noncoffee', 'noncoffe' => 'non_coffee',
            'snack', 'snacks', 'makanan' => 'snack',
            default => 'coffee',
        };
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

        // Same normalisation as store(): the edit form also posts the dropdown
        // label ("Coffee"/"Non Coffee"/"Snack") rather than the stored value.
        if ($request->has('tipe')) {
            $request->merge(['tipe' => $this->normaliseTipe($request->input('tipe'))]);
        }

        $validated = $request->validate([
            'nama_menu' => 'sometimes|string|max:255',
            'tipe' => 'sometimes|in:coffee,non_coffee,snack',
            'deskripsi' => 'nullable|string',
            'harga' => 'sometimes|numeric|min:0',
            'gambar' => 'nullable|string',
        ]);

        // Only assign the keys that were actually sent: `sometimes` rules leave
        // the others out of $validated, and reading them blindly throws.
        foreach ($validated as $field => $value) {
            $menu->{$field} = $value;
        }
        $menu->save();

        return response()->json([
            'message' => 'Data menu berhasil diperbarui',
            'data' => $menu
        ]);
    }

    // Hapus menu
    public function destroy($id)
    {
        // Take the id explicitly: the route parameter is {id}, so implicit
        // model binding on a `Menu $menu` parameter never matches and Laravel
        // injects an empty model, making delete() a silent no-op.
        $menu = Menu::findOrFail($id);

        Media::delete($menu->gambar);
        $menu->delete();

        return response()->json(['message' => 'Menu deleted']);
    }
}

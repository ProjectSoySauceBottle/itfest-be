<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048', // max 2MB
        ]);

        $path = $request->file('image')->store('public/images');

        $url = Storage::url($path);

        return response()->json([
            'path' => $path,
            'url' => asset($url),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\Media;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048', // max 2MB
        ]);

        $url = Media::store($request->file('image'), 'images');

        return response()->json([
            'path' => $url,
            'url' => $url,
        ]);
    }
}

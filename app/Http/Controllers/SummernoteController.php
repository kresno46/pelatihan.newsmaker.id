<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SummernoteController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/soal'), $filename);
            return asset('uploads/soal/' . $filename);
        }
        return response()->json(['error' => 'Upload gagal'], 400);
    }

    public function delete(Request $request)
    {
        $src = $request->input('image');
        $path = public_path(parse_url($src, PHP_URL_PATH));

        if (File::exists($path)) {
            File::delete($path);
            return response()->json(['status' => 'deleted']);
        }

        return response()->json(['status' => 'not found'], 404);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminFloorMapController extends Controller
{
    // upload / update floor map
    public function update(Request $request)
    {
        $request->validate([
            'floor_map' => ['required', 'image', 'mimes:jpg,jpeg', 'max:4096'], // max 4 MB
        ]);

        $dir = public_path('floorplans');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $file = $request->file('floor_map');
        $file->move($dir, 'floor-map.jpg'); // overwrite existing file

        return back()->with('success', 'Floor map berhasil diupdate.');
    }
}

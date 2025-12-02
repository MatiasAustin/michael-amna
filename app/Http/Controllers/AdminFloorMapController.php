<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminFloorMapController extends Controller
{
    // update floor map
    public function update(Request $request)
    {
        $request->validate([
            'floor_map' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:8192'], // up to ~8MB, allows JPG/PNG/PDF
        ]);

        // $dir = public_path('floormap'); // old behavior
        $dir = $this->absolutePublicPath('floormap');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $file = $request->file('floor_map');
        $allowedExts = ['jpg', 'png', 'pdf']; // normalize jpeg -> jpg
        $ext = strtolower($file->getClientOriginalExtension());
        $ext = $ext === 'jpeg' ? 'jpg' : $ext;
        if (!in_array($ext, $allowedExts, true)) {
            $ext = 'jpg';
        }

        // delete older floor-map.* files
        foreach ($allowedExts as $allowed) {
            $candidate = $dir . DIRECTORY_SEPARATOR . "floor-map.{$allowed}";
            if (file_exists($candidate)) {
                @unlink($candidate);
            }
        }

        $targetName = "floor-map.{$ext}";
        $file->move($dir, $targetName); // overwrite existing file

        return back()->with('success', 'Floor map berhasil diupdate.');
    }
}

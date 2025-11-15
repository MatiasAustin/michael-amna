<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PhotoController extends Controller
{
    public function index()
    {
        $pictures = Photo::all();
        return view('admin.dashboard', compact('pictures'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'photo.*' => 'required|image|mimes:jpg,jpeg,png,webp,avif|max:15360', // 15MB per file (sebelum kompres)
        ]);

        // Pakai driver GD
        $manager = new ImageManager(new Driver());

        foreach ($request->file('photo') as $file) {

            // Baca gambar
            $img = $manager->read($file->getRealPath());

            // Resize max width/height biar hemat (rasio terjaga)
            $img->scaleDown(width: 2000, height: 2000);

            // Encode berulang dengan quality turun sampai < 5MB
            $qualities = [85, 80, 75, 70, 65, 60, 55, 50, 45, 40];
            $binary = null;

            foreach ($qualities as $q) {
                // toWebp() hasilnya image yang sudah di-encode, bisa di-cast ke string
                $encoded = $img->toWebp($q);
                $binary  = (string) $encoded;

                if (strlen($binary) <= 5 * 1024 * 1024) { // < 5MB
                    break;
                }
            }

            // Nama file
            $filename = uniqid('img_') . '.webp';

            // Path relatif dari public
            $relativePath = 'gallery/' . $filename;

            // Path penuh di server (public/gallery/...)
            $fullPath = public_path($relativePath);

            // Pastikan folder public/gallery ada
            $dir = dirname($fullPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            // Simpan ke public/gallery
            file_put_contents($fullPath, $binary);

            // Simpan ke database -> simpan path relatifnya aja
            Photo::create([
                'filename' => $relativePath,
            ]);
        }

        return back()->with('success', 'Photos uploaded & optimized (<5MB).');
    }

    public function download($id)
    {
        $photo = Photo::findOrFail($id);
        $path = public_path($photo->filename);

        if (file_exists($path)) {
            return response()->download($path);
        }

        return back()->with('error', 'File not found.');
    }

    public function destroy($id)
    {
        $photo = Photo::findOrFail($id);

        if ($photo->filename) {
            $path = public_path($photo->filename);

            if (file_exists($path)) {
                unlink($path);
            }
        }

        $photo->delete();

        return back()->with('success', 'Photo deleted successfully.');
    }
}

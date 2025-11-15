<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;

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
            'photo.*' => 'required|image|mimes:jpg,jpeg,png,webp,avif|nullable|max:15360', // 15MB per file (pre-kompres)
        ]);

        $manager = ImageManager::gd(); // atau ->imagick() jika ada

        foreach ($request->file('photo') as $file) {
            // Baca gambar
            $img = $manager->read($file->getRealPath());

            // Resize max width/height agar hemat ukuran (rasio terjaga)
            $img->scaleDown(width: 2000, height: 2000);

            // Encode berulang dengan quality turun sampai < 5MB
            $qualities = [85,80,75,70,65,60,55,50,45,40];
            $binary = null;
            foreach ($qualities as $q) {
                // simpan sementara ke WebP (ukuran kecil & didukung luas)
                $binary = $img->toWebp($q);
                if (strlen($binary) <= 5 * 1024 * 1024) {
                    break;
                }
            }

            $filename = 'gallery/' . uniqid('img_') . '.webp';
            Storage::disk('public')->put($filename, $binary);

            Photo::create(['filename' => $filename]);
        }

        return back()->with('success', 'Photos uploaded & optimized (<5MB).');
    }

    public function destroy($id)
    {
        $photo = Photo::findOrFail($id);
        if ($photo->filename && file_exists(storage_path('app/public/' . $photo->filename))) {
            unlink(storage_path('app/public/' . $photo->filename));
        }

        $photo->delete();
        return back()->with('success', 'Photo deleted successfully.');
    }
}

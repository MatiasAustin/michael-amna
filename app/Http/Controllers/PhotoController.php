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
            'photo.*' => 'required|image|mimes:jpg,jpeg,png,webp,avif|max:20480',
        ]);

        $manager = new ImageManager(new Driver());
        $targetSizeBytes = 5 * 1024 * 1024;
        $maxDimensions = [2400, 2000, 1600, 1400, 1200, 1000, 800];
        $qualitySteps  = [85, 80, 75, 70, 65, 60, 55, 50, 45, 40, 35, 30];

        foreach ($request->file('photo') as $file) {

            $img = $manager->read($file->getRealPath());
            $binary = null;

            foreach ($maxDimensions as $maxDim) {
                $working = clone $img;
                $working->scaleDown(width: $maxDim, height: $maxDim);

                foreach ($qualitySteps as $quality) {
                    $encoded = $working->toWebp($quality);
                    $binary = (string) $encoded;

                    if (strlen($binary) <= $targetSizeBytes) break 2;
                }
            }

            if ($binary === null || strlen($binary) > $targetSizeBytes) {
                $fallback = clone $img;
                $fallback->scaleDown(width: 800, height: 800);
                $binary = (string) $fallback->toWebp(25);
            }

            // Nama file
            // $filename = uniqid('img_') . '.webp';
            $filename = substr(uniqid(), -8) . '.webp';


            // Path
            $relativePath = 'gallery/' . $filename;
            $fullPath = public_path($relativePath);

            // Folder
            $dir = dirname($fullPath);
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            // Save file
            file_put_contents($fullPath, $binary);

            // DB insert
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
            if (file_exists($path)) unlink($path);
        }

        $photo->delete();

        return back()->with('success', 'Photo deleted successfully.');
    }
}

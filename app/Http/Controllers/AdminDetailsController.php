<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venue;

class AdminDetailsController extends Controller
{
    public function index()
    {
        $venue = Venue::first(); // ambil record pertama

        // floor map
        $floorMapExists = file_exists(public_path('floormap/floor-map.jpg'));
        $floorMapUrl = $floorMapExists ? asset('floormap/floor-map.jpg') : null;

        return view('admin.details', compact('venue', 'floorMapUrl'));
    }

    // simpan / update link embed venue
    public function update(Request $request)
    {
        $data = $request->validate([
            'venue_location' => ['required', 'string', 'max:2000'],
        ]);

        $venue = Venue::first();
        $venue ? $venue->update($data) : Venue::create($data);

        return back()->with('success', 'Venue location updated.');
    }

    // upload / update floor map
    public function updateFloorMap(Request $request)
    {
        $request->validate([
            'floor_map' => ['required', 'image', 'mimes:jpg,jpeg', 'max:4096'], // max 4 MB
        ]);

        $dir = public_path('floormap');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $file = $request->file('floor_map');
        $file->move($dir, 'floor-map.jpg'); // overwrite file lama

        return back()->with('success', 'Floor map berhasil diupdate.');
    }
}

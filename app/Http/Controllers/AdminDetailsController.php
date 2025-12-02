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
        $floorMapUrl = $this->resolveFloorMapUrl();

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

    protected function resolveFloorMapUrl(): ?string
    {
        $candidates = ['jpg', 'png', 'pdf'];
        foreach ($candidates as $ext) {
            $name = "floor-map.{$ext}";
            if (file_exists($this->absolutePublicPath('floormap/' . $name))) {
                return asset('floormap/' . $name);
            }
        }

        return null;
    }
}

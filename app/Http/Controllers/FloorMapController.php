<?php

namespace App\Http\Controllers;

use App\Models\Rsvp;
use Illuminate\Http\Request;

class FloorMapController extends Controller
{
    public function show(Request $request)
    {
        //test deployment
        $rsvp = null;
        if ($request->filled('code')) {
            $code = strtoupper(trim($request->input('code')));

            $rsvp = Rsvp::with('guests')
                ->where('unique_code', $code)
                ->first();
        }

        // $floorMapExists = file_exists(public_path('floormap/floor-map.jpg')); // old behavior
        $floorMapExists = file_exists($this->absolutePublicPath('floormap/floor-map.jpg'));
        $floorMapUrl = $floorMapExists ? asset('floormap/floor-map.jpg') : null;

        return view('floormap', [
            'rsvp'        => $rsvp,
            'floorMapUrl' => $floorMapUrl,
        ]);
    }
}

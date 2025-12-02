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

        $floorMapUrl = $this->resolveFloorMapUrl();

        return view('floormap', [
            'rsvp'        => $rsvp,
            'floorMapUrl' => $floorMapUrl,
        ]);
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

<?php

namespace App\Http\Controllers;

use App\Models\Countdown;
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

        $countdown = Countdown::first();
        $isEventStarted = $countdown && now()->gte($countdown->event_at_utc);

        return view('floormap', [
            'rsvp'           => $rsvp,
            'floorMapUrl'    => $floorMapUrl,
            'isEventStarted' => $isEventStarted,
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

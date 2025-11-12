<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Countdown;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $cd = Countdown::first();

        // untuk input type="datetime-local" (pakai timezone app)
        $datetimeLocal = optional(optional($cd)->event_at_utc)
            ->setTimezone(config('app.timezone'))
            ?->format('Y-m-d\TH:i');

        // ambil semua foto dari tabel photos
        $photos = Photo::latest()->get(); // sama kayak di HomeController
        return view('admin.dashboard', [
            'cd'            => $cd,
            'datetimeLocal' => $datetimeLocal,
            'photos'        => $photos ?? collect(),
        ]);
    }
}

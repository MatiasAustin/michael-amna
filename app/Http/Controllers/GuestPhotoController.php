<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Countdown;

class GuestPhotoController extends Controller
{
     public function index()
    {
        $countdown = Countdown::first();
        $event = optional($countdown)->event_at_utc;
        $countdownFinished = $event ? now()->greaterThanOrEqualTo($event) : false;
        $canUploadPhotos = $countdownFinished || (bool) optional($countdown)->guest_upload_enabled;

        if (! $canUploadPhotos) {
            abort(403, 'Photo uploads open after the countdown or when enabled by admin.');
        }

        // ambil semua foto dari tabel photos
        $photos = Photo::latest()->get(); // sama kayak di HomeController
        return view('photoupload', compact('photos'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Countdown;
use App\Models\Guest;
use App\Models\Rsvp;

class HomeController extends Controller
{
    public function index()
    {
        $countdown = Countdown::first();
        $photos    = Photo::latest()->get();

    $wishes = Rsvp::whereNotNull('message')
    ->where('message', '!=', '')
    ->latest()
    ->get();

    return view('home', compact('countdown', 'photos', 'wishes'));
    }
}

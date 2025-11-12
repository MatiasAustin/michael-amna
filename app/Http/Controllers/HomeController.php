<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Countdown;


class HomeController extends Controller
{
    public function index() {
        $countdown = Countdown::first();          // untuk countdown
        $photos    = Photo::latest()->get();      // untuk gallery

        return view('home', compact('countdown','photos'));
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;


class HomeController extends Controller
{
    public function index() {
        $photos = Photo::latest()->get(); // atau Photo::all()
        return view('home', compact('photos'));
    }

}

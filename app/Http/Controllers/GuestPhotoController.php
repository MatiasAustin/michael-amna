<?php

namespace App\Http\Controllers;

use App\Models\Photo;

class GuestPhotoController extends Controller
{
     public function index()
    {
        // ambil semua foto dari tabel photos
        $photos = Photo::latest()->get(); // sama kayak di HomeController
        return view('photoupload', compact('photos'));
    }
}

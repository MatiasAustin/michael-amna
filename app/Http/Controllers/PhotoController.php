<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;


class PhotoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:5120', // ukuran dalam KB (5120 KB = 5MB)
        ]);

        $path = $request->file('photo')->store('gallery', 'public');

        Photo::create(['filename' => $path]);

        return back()->with('success', 'Foto berhasil diupload!');
    }

}

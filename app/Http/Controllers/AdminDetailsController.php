<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venue;

class AdminDetailsController extends Controller
{
    public function index()
    {
        $venue = Venue::first();                 // ambil record pertama
        return view('admin.details', compact('venue'));
    }

    // simpan / update link embed
    public function update(Request $request)
    {
        $data = $request->validate([
            'venue_location' => ['required','string','max:2000'],
        ]);

        // update atau create 1 baris saja
        $venue = Venue::first();
        $venue ? $venue->update($data) : Venue::create($data);

        return back()->with('success', 'Venue location updated.');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = ['filename'];

    public function store(Request $request) {
    foreach ($request->file('photo') as $file) {
        $filename = $file->store('gallery', 'public');
        Photo::create(['filename' => $filename]);
    }
    return back();
}
}


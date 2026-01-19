<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $fillable = ['venue_location', 'help_email', 'ceremony_start_time'];

    protected $casts = [
        'ceremony_start_time' => 'datetime',
    ];
}


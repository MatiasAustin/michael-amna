<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $fillable = [
        'rsvp_id',
        'full_name',
        'email',
        'table_number',
        'seat_number',
    ];

    public function rsvp()
    {
        return $this->belongsTo(Rsvp::class);
    }
}

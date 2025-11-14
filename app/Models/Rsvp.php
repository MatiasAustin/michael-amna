<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rsvp extends Model
{
     protected $fillable = [
        'full_name',
        'email',
        'attend',
        'message',
        'table_number',
        'seat_number',
    ];

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }
}

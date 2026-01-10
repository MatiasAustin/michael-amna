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
        'dietary',
        'table_number',
        'seat_number',
        'unique_code',
    ];

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }
}

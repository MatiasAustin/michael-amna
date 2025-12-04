<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Countdown extends Model
{
    protected $fillable = ['event_at_utc','headline','guest_upload_enabled'];
    protected $casts = [
        'event_at_utc' => 'datetime',
        'guest_upload_enabled' => 'boolean',
    ];
}

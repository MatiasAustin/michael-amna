<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DayGlanceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'time_label',
        'headline',
        'caption',
        'photo_path',
        'display_order',
    ];
}

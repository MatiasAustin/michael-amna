<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $fillable = ['rsvp_id','first_name','last_name','dietary','accessibility'];
    public function rsvp(){ return $this->belongsTo(Rsvp::class); }
}

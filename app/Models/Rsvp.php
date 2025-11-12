<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rsvp extends Model
{
    protected $fillable = ['contact_name','contact_email','attend','message'];
    public function guests(){ return $this->hasMany(Guest::class); }
}

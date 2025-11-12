<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminRsvpController extends Controller
{
    public function index()
    {
        return view('admin.rsvp');
    }
}

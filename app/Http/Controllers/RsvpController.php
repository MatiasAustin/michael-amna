<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rsvp;
use Illuminate\Support\Arr;

class RsvpController extends Controller {
  public function store(Request $r){
    $data = $r->validate([
      'contact_name'            => 'required|string|max:120',
      'contact_email'           => 'nullable|email|max:160',
      'attend'                  => 'required|in:yes,no',
      'message'                 => 'nullable|string|max:2000',
      'guests'                  => 'required|array|min:1',
      'guests.*.first_name'     => 'required|string|max:120',
      'guests.*.last_name'      => 'nullable|string|max:120',
      'guests.*.dietary'        => 'nullable|string|max:200',
      'guests.*.accessibility'  => 'nullable|string|max:200',
    ]);

    $rsvp = Rsvp::create(Arr::only($data, ['contact_name','contact_email','attend','message']));
    foreach ($data['guests'] as $g) {
      $rsvp->guests()->create($g);
    }

    return back()->with('ok','Thanks! Your reservation is recorded.');
  }
}

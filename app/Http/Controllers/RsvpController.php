<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rsvp;
use App\Models\Guest;

class RsvpController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email'     => ['nullable', 'email', 'max:255'],
            'attend'    => ['required', 'in:yes,no'],
            'dietary'   => ['nullable', 'string'],
            'message'   => ['nullable', 'string'],

            'guests'                => ['nullable', 'array'],
            'guests.*.full_name'    => ['required_with:guests.*.email', 'string', 'max:255'],
            'guests.*.email'        => ['nullable', 'email', 'max:255'],
            'guests.*.dietary'      => ['nullable', 'string'],
        ]);

        // simpan rsvp utama
        $rsvp = Rsvp::create([
            'full_name' => $data['full_name'],
            'email'     => $data['email'] ?? null,
            'attend'    => $data['attend'],
            'dietary'   => $data['dietary'] ?? null,
            'message'   => $data['message'] ?? null,
        ]);

        // simpan guests tambahan (kalau ada)
        if (!empty($data['guests'])) {
            foreach ($data['guests'] as $guestData) {
                // skip kalau full_name kosong total
                if (empty($guestData['full_name'])) {
                    continue;
                }

                Guest::create([
                    'rsvp_id'   => $rsvp->id,
                    'full_name' => $guestData['full_name'],
                    'email'     => $guestData['email'] ?? null,
                    'dietary'   => $guestData['dietary'] ?? null,
                ]);
            }
        }

        // bebas: redirect ke halaman thanks / balik ke RSVP
        // return redirect()
        //     ->route('rsvp') // atau ganti ke route yang kamu punya
        //     ->with('status', 'RSVP received. Thank you!');
        return redirect()->back()->with('success', true);
    }
}

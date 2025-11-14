<?php

namespace App\Http\Controllers;

use App\Models\Rsvp;
use App\Models\Guest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminRsvpController extends Controller
{
   public function index()
    {
        // RSVP utama
        $mains = Rsvp::select(
            'id',
            'full_name',
            'email',
            'attend',
            'table_number',
            'seat_number'
        )
        ->get()
        ->map(function ($r) {
            return [
                'id'           => 'rsvp-'.$r->id,
                'source_type'  => 'RSVP',
                'contact_name' => $r->full_name,
                'email'        => $r->email,
                'attend'       => $r->attend,
                'table_number' => $r->table_number,
                'seat_number'  => $r->seat_number,
            ];
        });

        // Guests tambahan
        $guests = Guest::with('rsvp:id,full_name')
            ->get()
            ->map(function ($g) {
                return [
                    'id'           => 'guest-'.$g->id,
                    'source_type'  => 'Guest',
                    'contact_name' => $g->full_name,
                    'email'        => $g->email,
                    'attend'       => optional($g->rsvp)->attend, // ikut status RSVP utama
                    'table_number' => $g->table_number,
                    'seat_number'  => $g->seat_number,
                ];
            });

        $people = $mains->concat($guests)->sortBy('contact_name')->values();

        return view('admin.rsvp', compact('people'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'rows' => ['required', 'array'],
            'rows.*.id'           => ['required', 'string'],
            'rows.*.table_number' => ['nullable', 'string', 'max:50'],
            'rows.*.seat_number'  => ['nullable', 'string', 'max:50'],
        ]);

        foreach ($data['rows'] as $row) {
            [$type, $id] = explode('-', $row['id'], 2);

            if ($type === 'rsvp') {
                $model = Rsvp::find($id);
            } else {
                $model = Guest::find($id);
            }

            if (!$model) continue;

            $model->update([
                'table_number' => $row['table_number'] ?? null,
                'seat_number'  => $row['seat_number'] ?? null,
            ]);
        }

        return back()->with('status', 'Table assignments updated.');
    }

     public function exportCsv(): StreamedResponse
    {
        // Ambil data yang sama kayak di index()
        $rsvps  = Rsvp::select(['id','full_name','email','attend','message'])->get();
        $guests = Guest::select(['id','rsvp_id','full_name','email'])->get();

        $people = collect();

        foreach ($rsvps as $rsvp) {
            $people->push([
                'source_type' => 'RSVP',
                'name'        => $rsvp->full_name,
                'email'       => $rsvp->email,
                'attend'      => $rsvp->attend,
                'message'     => $rsvp->message,
                'table'       => $rsvp->table ?? null, // kalau nanti kamu tambah kolom table/seat
                'seat'        => $rsvp->seat ?? null,
            ]);
        }

        foreach ($guests as $guest) {
            $people->push([
                'source_type' => 'Guest',
                'name'        => $guest->full_name,
                'email'       => $guest->email,
                'attend'      => null,
                'message'     => null,
                'table'       => $guest->table ?? null,
                'seat'        => $guest->seat ?? null,
            ]);
        }

        $people = $people->sortBy('name')->values();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="guest-list.csv"',
        ];

        // Stream biar langsung di-download
        return response()->stream(function () use ($people) {
            $out = fopen('php://output', 'w');

            // Kalau mau aman di Excel, kadang enak tambah BOM UTF-8
            // fwrite($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header kolom
            fputcsv($out, [
                'Source',
                'Name',
                'Email',
                'Attend',
                'Message',
                'Table',
                'Seat',
            ]); // default delimiter = ','

            foreach ($people as $row) {
                fputcsv($out, [
                    $row['source_type'],
                    $row['name'],
                    $row['email'],
                    $row['attend'],
                    $row['message'],
                    $row['table'],
                    $row['seat'],
                ]);
            }

            fclose($out);
        }, 200, $headers);
    }
}

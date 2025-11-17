<?php

namespace App\Http\Controllers;

use App\Models\Rsvp;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminSeatingController extends Controller
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

        return view('admin.seating', compact('people'));
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

    public function export(): StreamedResponse
    {
        // Ambil list yang sama dengan index()
        $mains = Rsvp::select(
            'id',
            'full_name',
            'email',
            'attend',
            'table_number',
            'seat_number'
        )->get()->map(function ($r) {
            return [
                'Type'         => 'RSVP',
                'Name'         => $r->full_name,
                'Email'        => $r->email,
                'Attend'       => $r->attend,
                'Table'        => $r->table_number,
                'Seat'         => $r->seat_number,
            ];
        });

        $guests = Guest::with('rsvp:id,full_name,attend')
            ->get()
            ->map(function ($g) {
                return [
                    'Type'         => 'Guest',
                    'Name'         => $g->full_name,
                    'Email'        => $g->email,
                    'Attend'       => optional($g->rsvp)->attend,
                    'Table'        => $g->table_number,
                    'Seat'         => $g->seat_number,
                ];
            });

        $rows = $mains->concat($guests)->values();

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');

            // header
            fputcsv($handle, ['Type', 'Name', 'Email', 'Attend', 'Table', 'Seat']);

            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row['Type'],
                    $row['Name'],
                    $row['Email'],
                    $row['Attend'],
                    $row['Table'],
                    $row['Seat'],
                ]);
            }

            fclose($handle);
        };

        return response()->streamDownload($callback, 'guest-list.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}


<?php

namespace App\Http\Controllers;

use App\Models\Rsvp;
use App\Models\Guest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Str;
use App\Mail\RsvpCodeMail;
use Mail;

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
                'seat_number',
                'unique_code',
            )
            ->get()
            ->map(function ($r) {
                return [
                    // dipakai untuk update (punya prefix rsvp-/guest-)
                    'row_id'       => 'rsvp-'.$r->id,

                    // dipakai untuk generate/send code (ID asli rsvps)
                    'rsvp_id'      => $r->id,

                    'source_type'  => 'RSVP',
                    'contact_name' => $r->full_name,
                    'email'        => $r->email,
                    'attend'       => $r->attend,
                    'table_number' => $r->table_number,
                    'seat_number'  => $r->seat_number,
                    'unique_code'  => $r->unique_code,
                ];
            });

        // Guests tambahan
        $guests = Guest::with('rsvp:id,full_name,attend,unique_code')
            ->get()
            ->map(function ($g) {
                return [
                    'row_id'       => 'guest-'.$g->id,
                    'rsvp_id'      => $g->rsvp_id, // supaya tetep tau RSVP mana

                    'source_type'  => 'Guest',
                    'contact_name' => $g->full_name,
                    'email'        => $g->email,
                    'attend'       => optional($g->rsvp)->attend,
                    'table_number' => $g->table_number,
                    'seat_number'  => $g->seat_number,
                    'unique_code'  => $g->unique_code,
                ];
            });

        $people = $mains->concat($guests)->sortBy('contact_name')->values();

        return view('admin.rsvp', compact('people'));
    }


    public function update(Request $request)
    {
        $data = $request->validate([
            'rows'                 => ['required', 'array'],
            'rows.*.row_id'        => ['required', 'string'],
            'rows.*.email'         => ['nullable', 'email', 'max:40'],
            // allow mixed text/number labels
            'rows.*.table_number'  => ['nullable', 'string', 'max:50'],
            'rows.*.seat_number'   => ['nullable', 'string', 'max:50'],
        ]);

        foreach ($data['rows'] as $row) {
            [$type, $id] = explode('-', $row['row_id'], 2);

            if ($type === 'rsvp') {
                $model = Rsvp::find($id);
            } else {
                $model = Guest::find($id);
            }

            if (!$model) {
                continue;
            }

            $tableNumber = isset($row['table_number']) && $row['table_number'] !== ''
                ? $row['table_number']
                : null;
            $seatNumber  = isset($row['seat_number']) && $row['seat_number'] !== ''
                ? $row['seat_number']
                : null;
            $email       = $row['email'] ?? $model->email;

            $model->update([
                'email'        => $email,
                'table_number' => $tableNumber,
                'seat_number'  => $seatNumber,
            ]);
        }

        return back()->with('status', 'Table assignments updated.');
    }


     public function exportCsv(): StreamedResponse
    {
        // Ambil data yang sama kayak di index()
        $rsvps  = Rsvp::select(['id','full_name','email','attend','message','table_number','seat_number'])->get();
        $guests = Guest::select(['id','rsvp_id','full_name','email','table_number','seat_number'])->get();

        $people = collect();

        foreach ($rsvps as $rsvp) {
            $people->push([
                'source_type' => 'RSVP',
                'name'        => $rsvp->full_name,
                'email'       => $rsvp->email,
                'attend'      => $rsvp->attend,
                'message'     => $rsvp->message,
                'table'       => $rsvp->table_number,
                'seat'        => $rsvp->seat_number,
            ]);
        }

        foreach ($guests as $guest) {
            $people->push([
                'source_type' => 'Guest',
                'name'        => $guest->full_name,
                'email'       => $guest->email,
                'attend'      => null,
                'message'     => null,
                'table'       => $guest->table_number,
                'seat'        => $guest->seat_number,
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

    public function generateCode(Rsvp $rsvp)
    {
        // load guests sekalian
        $rsvp->load('guests');

        // kalau belum ada kode, generate baru
        if (!$rsvp->unique_code) {
            $code = strtoupper(Str::random(8));
            $rsvp->unique_code = $code;
            $rsvp->save();
        } else {
            $code = $rsvp->unique_code;
        }

        // update semua guest dengan rsvp_id yg sama
        $rsvp->guests()->update(['unique_code' => $code]);

        return back()->with('success', 'Unique code has been assigned to RSVP & guests.');
    }

    public function sendCode(Request $request, Rsvp $rsvp)
    {
        $rsvp->load('guests');

        // pastikan sudah punya kode
        if (!$rsvp->unique_code) {
            $code = strtoupper(Str::random(8));
            $rsvp->unique_code = $code;
            $rsvp->save();
            $rsvp->guests()->update(['unique_code' => $code]);
        }

        // email dari popup (wajib)
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $targetEmail = $data['email'];

        // opsional: sekalian update email di DB
        $rsvp->email = $targetEmail;
        $rsvp->save();

        Mail::to($targetEmail)->send(new RsvpCodeMail($rsvp));

        return back()->with('success', 'Unique code has been sent to '.$targetEmail.'.');
    }

    public function destroy(Rsvp $rsvp)
    {
        // delete extra guests first, then main RSVP
        $rsvp->guests()->delete();
        $rsvp->delete();

        return back()->with('success', 'RSVP and its guests have been deleted.');
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Countdown;
use Carbon\Carbon;

class AdminCountdownController extends Controller
{
    public function edit()
    {
        $cd = Countdown::first();
        $value = optional($cd?->event_at_utc)
            ->setTimezone(config('app.timezone'))
            ?->format('Y-m-d\TH:i');

        return view('admin.dashboard', [
            'cd' => $cd,
            'datetimeLocal' => $value,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'headline' => ['nullable', 'string', 'max:200'],
            'event_at_local' => ['required', 'date_format:Y-m-d\TH:i'],
            'tz' => ['required', 'timezone'],
            'guest_upload_enabled' => ['nullable', 'boolean'],
        ]);

        $eventLocal = Carbon::createFromFormat('Y-m-d\TH:i', $data['event_at_local'], $data['tz']);
        $eventUtc = $eventLocal->clone()->setTimezone('UTC');

        $cd = Countdown::first() ?? new Countdown();
        $cd->headline = $data['headline'];
        $cd->event_at_utc = $eventUtc;
        $cd->guest_upload_enabled = (bool)($data['guest_upload_enabled'] ?? false);
        $cd->save();

        return back()->with('success', 'Countdown updated successfully!');
    }
}

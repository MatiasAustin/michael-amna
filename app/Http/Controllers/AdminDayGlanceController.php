<?php

namespace App\Http\Controllers;

use App\Models\DayGlanceItem;
use Illuminate\Http\Request;

class AdminDayGlanceController extends Controller
{
    public function index()
    {
        $items = DayGlanceItem::orderBy('display_order')
            ->orderBy('id')
            ->get();

        return view('admin.dayglance', compact('items'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'time_label'     => ['required', 'string', 'max:50'],
            'headline'       => ['required', 'string', 'max:255'],
            'caption'        => ['nullable', 'string', 'max:500'],
            'display_order'  => ['nullable', 'integer', 'min:0'],
            'photo'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $data['display_order'] = $data['display_order'] ?? 0;

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $this->storePhoto($request->file('photo'));
        }

        DayGlanceItem::create($data);

        return back()->with('success', 'Day-at-a-glance item added.');
    }

    public function update(Request $request, DayGlanceItem $item)
    {
        $data = $request->validate([
            'time_label'     => ['required', 'string', 'max:50'],
            'headline'       => ['required', 'string', 'max:255'],
            'caption'        => ['nullable', 'string', 'max:500'],
            'display_order'  => ['nullable', 'integer', 'min:0'],
            'photo'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $data['display_order'] = $data['display_order'] ?? 0;

        if ($request->hasFile('photo')) {
            if ($item->photo_path && file_exists(public_path($item->photo_path))) {
                @unlink(public_path($item->photo_path));
            }
            $data['photo_path'] = $this->storePhoto($request->file('photo'));
        }

        $item->update($data);

        return back()->with('success', 'Day-at-a-glance item updated.');
    }

    public function destroy(DayGlanceItem $item)
    {
        if ($item->photo_path && file_exists(public_path($item->photo_path))) {
            @unlink(public_path($item->photo_path));
        }

        $item->delete();

        return back()->with('success', 'Day-at-a-glance item removed.');
    }

    private function storePhoto($file): string
    {
        $dir = public_path('day-glance');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filename = uniqid('dayglance_') . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $filename);

        return 'day-glance/' . $filename;
    }
}

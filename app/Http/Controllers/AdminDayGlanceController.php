<?php

namespace App\Http\Controllers;

use App\Models\DayGlanceItem;
use Illuminate\Http\Request;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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
            'photo'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:20480'], // 20MB before compression
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
            'photo'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:20480'], // 20MB before compression
        ]);

        $data['display_order'] = $data['display_order'] ?? 0;

        if ($request->hasFile('photo')) {
            // if ($item->photo_path && file_exists(public_path($item->photo_path))) { // old behavior
            //     @unlink(public_path($item->photo_path));
            // }
            if ($item->photo_path && file_exists($this->absolutePublicPath($item->photo_path))) {
                @unlink($this->absolutePublicPath($item->photo_path));
            }
            $data['photo_path'] = $this->storePhoto($request->file('photo'));
        }

        $item->update($data);

        return back()->with('success', 'Day-at-a-glance item updated.');
    }

    public function destroy(DayGlanceItem $item)
    {
        // if ($item->photo_path && file_exists(public_path($item->photo_path))) { // old behavior
        //     @unlink(public_path($item->photo_path));
        // }
        if ($item->photo_path && file_exists($this->absolutePublicPath($item->photo_path))) {
            @unlink($this->absolutePublicPath($item->photo_path));
        }

        $item->delete();

        return back()->with('success', 'Day-at-a-glance item removed.');
    }

    private function storePhoto($file): string
    {
        // $dir = public_path('day-glance'); // old behavior
        $dir = $this->absolutePublicPath('day-glance');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $manager = new ImageManager(new Driver());
        $targetSizeBytes = 5 * 1024 * 1024; // target final <5MB
        $maxDimensions = [2000, 1800, 1600, 1400, 1200, 1000, 800];
        $qualitySteps  = [85, 80, 75, 70, 65, 60, 55, 50, 45, 40, 35];

        $img = $manager->read($file->getRealPath());
        $binary = null;

        foreach ($maxDimensions as $maxDim) {
            $working = clone $img;
            $working->scaleDown(width: $maxDim, height: $maxDim);

            foreach ($qualitySteps as $quality) {
                $encoded = $working->toWebp($quality);
                $binary  = (string) $encoded;

                if (strlen($binary) <= $targetSizeBytes) {
                    break 2; // under target, stop both loops
                }
            }
        }

        if ($binary === null || strlen($binary) > $targetSizeBytes) {
            $fallback = clone $img;
            $fallback->scaleDown(width: 800, height: 800);
            $binary = (string) $fallback->toWebp(30);
        }

        $filename = uniqid('dayglance_') . '.webp';
        file_put_contents($dir . DIRECTORY_SEPARATOR . $filename, $binary);

        return 'day-glance/' . $filename;
    }
}

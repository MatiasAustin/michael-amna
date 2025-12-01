<?php

namespace App\Http\Controllers;

use App\Models\DayGlanceItem;

class DayGlanceController extends Controller
{
    /**
     * Display the public "Our Day at a Glance" page.
     */
    public function index()
    {
        $items = DayGlanceItem::orderBy('display_order')
            ->orderBy('id')
            ->get();

        // Kalau belum ada data di DB, pakai placeholder
        if ($items->isEmpty()) {
            $items = collect([
                DayGlanceItem::make([
                    'time_label'    => '4:45 PM',
                    'headline'      => 'Guests Arrive',
                    'caption'       => 'Let the celebrations begin',
                    'photo_path'    => null,
                    'display_order' => 0,
                ]),
                DayGlanceItem::make([
                    'time_label'    => '5:00 PM',
                    'headline'      => 'Ceremony',
                    'caption'       => 'Where we say "I do"',
                    'photo_path'    => null,
                    'display_order' => 1,
                ]),
                DayGlanceItem::make([
                    'time_label'    => '5:30 PM',
                    'headline'      => 'Cocktail Hour',
                    'caption'       => 'Champagne, canapes and chaos (the good kind)',
                    'photo_path'    => null,
                    'display_order' => 2,
                ]),
                DayGlanceItem::make([
                    'time_label'    => '6:30 PM',
                    'headline'      => 'Reception Starts',
                    'caption'       => 'Dinner, dancing, and unforgettable moments ahead',
                    'photo_path'    => null,
                    'display_order' => 3,
                ]),
                DayGlanceItem::make([
                    'time_label'    => '8:00 PM',
                    'headline'      => 'Formalities (Speeches)',
                    'caption'       => 'Laugh, cry, toast, repeat',
                    'photo_path'    => null,
                    'display_order' => 4,
                ]),
                DayGlanceItem::make([
                    'time_label'    => '11:30 PM',
                    'headline'      => 'Bride and Groom Depart',
                    'caption'       => 'A grand exit – but the party may continue... unofficially',
                    'photo_path'    => null,
                    'display_order' => 5,
                ]),
            ]);
        }

        return view('dayglance', [
            'items' => $items,
        ]);
    }
}

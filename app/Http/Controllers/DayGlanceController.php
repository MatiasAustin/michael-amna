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

        // Provide a friendly placeholder sequence if nothing has been set up yet.
        if ($items->isEmpty()) {
            $items = collect([
                ['time_label' => '4:45 PM', 'headline' => 'Guests Arrive', 'caption' => 'Let the celebrations begin'],
                ['time_label' => '5:00 PM', 'headline' => 'Ceremony', 'caption' => 'Where we say "I do"'],
                ['time_label' => '5:30 PM', 'headline' => 'Cocktail Hour', 'caption' => 'Champagne, canapes and chaos (the good kind)'],
                ['time_label' => '6:30 PM', 'headline' => 'Reception Starts', 'caption' => 'Dinner, dancing, and unforgettable moments ahead'],
                ['time_label' => '8:00 PM', 'headline' => 'Formalities (Speeches)', 'caption' => 'Laugh, cry, toast, repeat'],
                ['time_label' => '11:30 PM', 'headline' => 'Bride and Groom Depart', 'caption' => 'A grand exit – but the party may continue... unofficially'],
            ]);
        }

        return view('dayglance', [
            'items' => $items,
        ]);
    }
}

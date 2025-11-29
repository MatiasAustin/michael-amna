<?php

use App\Models\Rsvp;
use App\Models\Guest;
use App\Models\Venue;
use App\Models\Countdown;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RsvpController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminRsvpController;
use App\Http\Controllers\GuestPhotoController;
use App\Http\Controllers\AdminDetailsController;
// use App\Http\Controllers\AdminSeatingController;
use App\Http\Controllers\AdminCountdownController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DayGlanceController;
use App\Http\Controllers\AdminDayGlanceController;


// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/details', function (Request $request) {
    $venue     = Venue::first();
    $countdown = Countdown::first();
    $rsvps     = Rsvp::with('guests')->get();
    $guests    = Guest::all();

    $rsvp = null;
    if ($request->filled('code')) {
        $code = strtoupper(trim($request->input('code')));

        $rsvp = Rsvp::with('guests')
            ->where('unique_code', $code)
            ->first();
    }

    $floorMapExists = file_exists(public_path('floormap/floor-map.jpg'));
    $floorMapUrl = $floorMapExists ? asset('floormap/floor-map.jpg') : null;

    return view('details', [
        'venue'       => $venue,
        'countdown'   => $countdown,
        'rsvps'       => $rsvps,
        'guests'      => $guests,
        'rsvp'        => $rsvp,
        'floorMapUrl' => $floorMapUrl,
    ]);
})->name('details');

Route::get('/floormap', function (Request $request) {
    $rsvp = null;
    if ($request->filled('code')) {
        $code = strtoupper(trim($request->input('code')));

        $rsvp = Rsvp::with('guests')
            ->where('unique_code', $code)
            ->first();
    }

    $floorMapExists = file_exists(public_path('floorplans/floor-map.jpg'));
    $floorMapUrl = $floorMapExists ? asset('floorplans/floor-map.jpg') : null;


    return view('floormap', [
        'rsvp'        => $rsvp,
        'floorMapUrl' => $floorMapUrl,
    ]);
})->name('floormap');


Route::view('/rsvp', 'rsvp')->name('rsvp');
Route::post('/rsvp', [RsvpController::class, 'store'])->name('rsvp.store');


Route::get('/photoupload', [GuestPhotoController::class, 'index'])->name('photoupload');

Route::get('/day-at-a-glance', [DayGlanceController::class, 'index'])->name('dayglance');


// Admin Routes

Route::get('/admin/gallery', [PhotoController::class, 'index'])->name('gallery.index');
Route::post('/admin/gallery', [PhotoController::class, 'store'])->name('gallery.store');
Route::delete('/admin/gallery/{id}', [PhotoController::class, 'destroy'])->name('gallery.destroy');
Route::get('/admin/gallery/{id}', [PhotoController::class, 'download'])->name('gallery.download');


// Authentication Routes
Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

// Admin Dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');
});

// Countdown Management
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/countdown', [AdminCountdownController::class, 'edit'])
        ->name('admin.countdown.edit');
    Route::post('/admin/countdown', [AdminCountdownController::class, 'update'])
        ->name('admin.countdown.update');
});



// Home Route with Countdown
// Route::get('/', function () {
//     $countdown = \App\Models\Countdown::first();
//     return view('home', compact('countdown'));
// })->name('home');


// Details Management
Route::middleware('auth')->group(function () {
    Route::get('/admin/details',  [AdminDetailsController::class, 'index'])->name('admin.details');
    Route::post('/admin/details', [AdminDetailsController::class, 'update'])->name('admin.details.update');


    // Floor Map upload (masih di controller yang sama)
    Route::post('/admin/details/floor-map', [AdminDetailsController::class, 'updateFloorMap'])
        ->name('admin.details.floorMap');
});

Route::middleware('auth')->group(function () {
    Route::get('/admin/day-at-a-glance', [AdminDayGlanceController::class, 'index'])->name('admin.dayglance.index');
    Route::post('/admin/day-at-a-glance', [AdminDayGlanceController::class, 'store'])->name('admin.dayglance.store');
    Route::put('/admin/day-at-a-glance/{item}', [AdminDayGlanceController::class, 'update'])->name('admin.dayglance.update');
    Route::delete('/admin/day-at-a-glance/{item}', [AdminDayGlanceController::class, 'destroy'])->name('admin.dayglance.destroy');
});

// RSVP Management
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/rsvp', [AdminRsvpController::class, 'index'])
        ->name('admin.rsvp');

    Route::post('/admin/seating', [AdminRsvpController::class, 'update'])->name('admin.rsvp.update');

    Route::get('/admin/seating/export', [AdminRsvpController::class, 'exportCsv'])->name('admin.rsvp.export');

    Route::delete('/admin/rsvp/{rsvp}', [AdminRsvpController::class, 'destroy'])
        ->name('admin.rsvp.destroy');
});

// Generate and Send Unique Codes for RSVPs
Route::post('/admin/rsvp/{rsvp}/generate-code', [AdminRsvpController::class, 'generateCode'])
    ->name('admin.rsvp.generateCode');

Route::post('/admin/rsvp/{rsvp}/send-code', [AdminRsvpController::class, 'sendCode'])
    ->name('admin.rsvp.sendCode');

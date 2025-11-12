<?php

use App\Models\Venue;
use App\Models\Countdown;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RsvpController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminRsvpController;
use App\Http\Controllers\GuestPhotoController;
use App\Http\Controllers\AdminDetailsController;
use App\Http\Controllers\AdminCountdownController;
use App\Http\Controllers\AdminDashboardController;


// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/details', function () {
    $venue = Venue::first();
    return view('details', compact('venue'));
})->name('details');

Route::view('/rsvp', 'rsvp');
Route::post('/rsvp', [RsvpController::class, 'store'])->name('rsvp.store');


Route::get('/photoupload', [GuestPhotoController::class, 'index'])->name('photoupload');


// Admin Routes

Route::get('/admin/gallery', [PhotoController::class, 'index'])->name('gallery.index');
Route::post('/admin/gallery', [PhotoController::class, 'store'])->name('gallery.store');
Route::delete('/admin/gallery/{id}', [PhotoController::class, 'destroy'])->name('gallery.destroy');


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
});

// RSVP Management
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/rsvp', [AdminRsvpController::class, 'index'])
        ->name('admin.rsvp');
});

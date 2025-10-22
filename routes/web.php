<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\AuthController;
use App\Http\middleware\AdminAuth;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/details', 'details');
Route::view('/rsvp', 'rsvp');

Route::post('/photos', [PhotoController::class, 'store'])->name('photos.store');


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware('auth');








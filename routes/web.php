<?php

use App\Http\Controllers\EventDisplayController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route untuk homepage
Route::get('/', HomeController::class)->name('home');

// Route untuk menampilkan semua event dan detail event
Route::controller(EventDisplayController::class)->group(function () {
    Route::get('/events', 'index')->name('events.index');
    Route::get('/events/{event:slug}', 'show')->name('events.show');
});

// Route untuk halaman statis
Route::view('/about', 'pages.about')->name('about');
Route::view('/contact', 'pages.contact')->name('contact');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

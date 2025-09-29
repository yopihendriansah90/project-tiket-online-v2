<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventDisplayController;
use App\Http\Controllers\HomeController;

// Route untuk halaman utama (beranda)
Route::get('/', HomeController::class)->name('home');

// Route untuk halaman daftar semua event
Route::get('/events', [EventDisplayController::class, 'index'])->name('events.index');

// Route untuk halaman detail event
Route::get('/events/{event}', [EventDisplayController::class, 'show'])->name('events.show');

// Route untuk halaman statis
Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');
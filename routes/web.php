<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EventDisplayController;

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/', [EventDisplayController::class, 'index'])->name('events.index');

Route::get('/events/{event}', [EventDisplayController::class, 'show'])->name('events.show');

<?php

use App\Http\Controllers\EventDisplayController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ETicketController;
use App\Http\Controllers\CheckinController;
use Illuminate\Support\Facades\Route;

// Route untuk homepage
Route::get('/', HomeController::class)->name('home');

// Route untuk menampilkan semua event dan detail event
Route::controller(EventDisplayController::class)->group(function () {
    Route::get('/events', 'index')->name('events.index');
    Route::get('/events/{event:slug}', 'show')->name('events.show');
});

// Route untuk membuat order tiket (tanpa pembayaran), pastikan user login
Route::post('/orders', [OrderController::class, 'store'])
    ->name('orders.store')
    ->middleware('auth');

// Route untuk halaman statis
Route::view('/about', 'pages.about')->name('about');
Route::view('/contact', 'pages.contact')->name('contact');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

 // Route pembayaran manual: halaman upload bukti transfer + halaman status submitted
 Route::middleware('auth')->group(function () {
     Route::get('/payments/{order}/create', [PaymentController::class, 'create'])->name('payments.create');
     Route::post('/payments/{order}', [PaymentController::class, 'store'])->name('payments.store');
     Route::get('/payments/{order}/submitted', [PaymentController::class, 'submitted'])->name('payments.submitted');

     // Admin verify payment from preview modal
     Route::post('/admin/payments/{payment}/verify', [PaymentController::class, 'adminVerify'])->name('payments.admin.verify');
 });
 
 Route::middleware(['auth', 'verified'])->group(function () {
     // ETicket Show (akses oleh user pemilik order item, status harus paid)
     Route::get('/etickets/{orderItem}', [ETicketController::class, 'show'])->name('etickets.show');

     // Deep-link ke dashboard dengan tab tertentu via redirect agar data tetap dari controller
     Route::get('/account/orders', fn () => redirect()->route('dashboard', ['tab' => 'orders']))->name('user.orders');
     Route::get('/account/tickets', fn () => redirect()->route('dashboard', ['tab' => 'tickets']))->name('user.tickets');
     Route::get('/account/payments', fn () => redirect()->route('dashboard', ['tab' => 'payments']))->name('user.payments');
     Route::get('/account/notifications', fn () => redirect()->route('dashboard', ['tab' => 'notifications']))->name('user.notifications');
     Route::get('/account/favorites', fn () => redirect()->route('dashboard', ['tab' => 'favorites']))->name('user.favorites');
 });

 // Halaman Check-in panitia (scanner + verifikasi token)
 Route::middleware(['auth', 'role:Event Manager|Super Admin'])->group(function () {
     Route::get('/checkin', [CheckinController::class, 'index'])->name('checkin.index');
     Route::post('/checkin/verify', [CheckinController::class, 'verify'])->name('checkin.verify')->middleware('throttle:30,1');
 });

 require __DIR__.'/auth.php';

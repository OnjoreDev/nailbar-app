<?php

use App\Http\Controllers\BookingsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;
use App\Models\Booking;

Route::get('/', function () {
    return view('welcome');
});

// 1. Breeze Auth Routes
require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 2. Direct Dashboard Alias for Breeze Login Redirect
Route::middleware(['auth', 'role:merchant'])->get('/dashboard', [BookingsController::class, 'merchantDashboard'])->name('dashboard');

// 3. Merchant Dashboard Group & Services CRUD
Route::middleware(['auth', 'role:merchant'])->prefix('merchant')->name('merchant.')->group(function () {
    Route::get('/dashboard', [BookingsController::class, 'merchantDashboard'])->name('dashboard');
    Route::resource('services', ServiceController::class);
});

// 4. Booking & Payment Flow
Route::get('/booking/payment', [PaymentController::class, 'showPayment'])->name('booking.payment');
Route::post('/booking/initiate-stk', [PaymentController::class, 'initiateStkPayment'])->name('booking.initiate-stk');
Route::post('/booking/cash-checkout', [PaymentController::class, 'cashCheckout'])->name('booking.cash-checkout');
Route::get('/booking/success/{booking}', [BookingsController::class, 'success'])->name('booking.success');
// Add this inside routes/web.php
Route::get('/booking/status/{booking}', [PaymentController::class, 'checkStatus'])->name('booking.status');
Route::get('/booking/waiting/{booking}', function (Booking $booking) {
    return view('bookings.waiting', compact('booking'));
})->name('booking.waiting');
Route::get('/{merchant:slug}/fully-booked', [BookingsController::class, 'fullyBooked'])->name('booking.fully-booked');

// 5. Tenant Public Booking Flow (Must be at the very bottom so it doesn't swallow /dashboard or /login)
Route::get('/{merchant:slug}', [BookingsController::class, 'create'])->name('tenant.booking.create');
Route::post('/{merchant:slug}/check-availability', [BookingsController::class, 'checkAvailability'])->name('tenant.booking.check-availability');

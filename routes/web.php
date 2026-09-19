<?php

use App\Http\Controllers\BookingsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/bookings',[BookingsController::class,'index'])->name('bookings.index');
Route::get('/bookings/create',[BookingsController::class,'create'])->name('bookings.create');
Route::post('/bookings',[BookingsController::class,'store'])->name('bookings.store');
Route::delete('/bookings/{id}',[BookingsController::class,'destroy'])->name('bookings.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource("services",ServiceController::class);

require __DIR__.'/auth.php';

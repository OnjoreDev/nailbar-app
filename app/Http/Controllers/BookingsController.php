<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Mail\BookingConfirmedMail;
use App\Mail\BookingFailedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BookingsController extends Controller
{
    public function index(){
        $bookings = Booking::all();
        return view('bookings.index',compact('bookings'));
    }
    // Display the booking form view
    public function create()
    {
        return view('bookings.create');
    }

    // Process the booking creation
    public function store(Request $req)
    {
        // 1. Validate incoming form input
        $validated = $req->validate([
            'name'         => 'required|string|max:100',
            'email'        => 'required|email|max:100',
            'phone'        => 'required|string|max:50',
            'booking_date' => 'required|date|after_or_equal:today',
        ]);

        // 2. Enforce the 10-client daily limit rule
        $existingBookingsCount = Booking::whereDate('booking_date', $validated['booking_date'])->count();

        if ($existingBookingsCount >= 10) {
            // Send email notifying the user that the date is fully booked
            Mail::to($validated['email'])->send(new BookingFailedMail($validated['name'], $validated['booking_date']));

            return back()->withErrors([
                'booking_date' => 'Sorry, this date is fully booked (maximum 10 clients per day). An email notification has been sent to you.'
            ])->withInput();
        }

        // 3. Create the booking record
        $created = Booking::create([
            'name'         => $validated['name'],
            'email'        => $validated['email'],
            'phone'        => $validated['phone'],
            'booking_date' => $validated['booking_date'],
            'status'       => 'confirmed',
        ]);

        if ($created) {
            Log::info("Booking insert successful for ID: {$created->id}");

            // Send confirmation email
            Mail::to($created->email)->send(new BookingConfirmedMail($created));

            return back()->with('success', 'You have booked successfully! A confirmation email has been sent to you.');
        } else {
            Log::error("Booking insert failed.");
            return back()->with('error', 'Insert failed! Please try again.');
        }
    }
}
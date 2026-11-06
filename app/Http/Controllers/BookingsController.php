<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Merchant;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Fixes the Intelephense missing import warning

class BookingsController extends Controller
{
    public function create(Merchant $merchant)
    {
        $services = $merchant->services;
        return view('bookings.create', compact('merchant', 'services'));
    }

    public function checkAvailability(Request $request, Merchant $merchant)
    {
        $request->validate([
            'client_name'   => 'required|string|max:255',
            'client_phone'  => 'required|string|max:20',
            'client_email'  => 'nullable|email|max:255',
            'service_id'    => 'required|exists:services,id',
            'booking_date'  => 'required|date|after_or_equal:today',
            'notes'         => 'nullable|string|max:500',
        ]);

        $dailyBookingsCount = Booking::where('merchant_id', $merchant->id)
            ->where('booking_date', $request->booking_date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        if ($dailyBookingsCount >= 10) {
            return back()->withErrors([
                'booking_date' => 'Sorry! This date is fully booked for ' . $merchant->business_name . ' (Maximum 10 clients reached).'
            ])->withInput();
        }

        session([
            'draft_booking' => [
                'merchant_id'    => $merchant->id,
                'client_name'    => $request->client_name,
                'client_phone'   => $request->client_phone,
                'client_email'   => $request->client_email,
                'service_id'     => $request->service_id,
                'booking_date'   => $request->booking_date,
                'deposit_amount' => 500.00,
                'notes'          => $request->notes,
            ]
        ]);

        return redirect()->route('booking.payment');
    }

    public function success(Booking $booking)
    {
        $booking->load(['service', 'merchant']);
        return view('bookings.success', compact('booking'));
    }

    /**
     * Merchant Dashboard (Explicitly points to merchant.dashboard view)
     */
    public function merchantDashboard(Request $request)
    {
        $merchant = Auth::user()->merchant;

        if (!$merchant) {
            abort(403, 'Merchant profile not found.');
        }

        $date = $request->input('date', today()->toDateString());

        $bookings = Booking::with('service')
            ->where('merchant_id', $merchant->id)
            ->where('booking_date', $date)
            ->latest()
            ->get();

        $totalForDay = $bookings->whereIn('status', ['pending', 'confirmed'])->count();

        return view('merchant.dashboard', compact('merchant', 'bookings', 'date', 'totalForDay'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $merchant = Auth::user()->merchant;
        abort_if($booking->merchant_id !== $merchant->id, 403);

        $request->validate([
            'status'         => 'required|in:pending,confirmed,completed,cancelled',
            'payment_status' => 'required|in:pending,paid,failed',
        ]);

        $booking->update([
            'status'         => $request->status,
            'payment_status' => $request->payment_status,
        ]);

        return back()->with('success', 'Booking status updated successfully.');
    }
}

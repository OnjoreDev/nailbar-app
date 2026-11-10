<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Merchant;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Fixes the Intelephense missing import warning
use Carbon\Carbon;

class BookingsController extends Controller
{
    public function create(Merchant $merchant, Request $request)
    {
        $services = $merchant->services;

        // Get current or requested month/year for the calendar
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // Fetch booking counts per day for this merchant for the month
        $bookedCounts = Booking::where('merchant_id', $merchant->id)
            ->whereBetween('booking_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->whereIn('status', ['pending', 'confirmed'])
            ->selectRaw('DATE(booking_date) as date, count(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        return view('bookings.create', compact('merchant', 'services', 'startOfMonth', 'bookedCounts', 'year', 'month'));
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

        // Check duplicate booking constraint
        $existingBooking = Booking::where('merchant_id', $merchant->id)
            ->where('booking_date', $request->booking_date)
            ->where('client_phone', $request->client_phone)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($existingBooking) {
            return back()->withErrors([
                'client_phone' => 'You already have an active or pending booking for this date at ' . $merchant->business_name . '.'
            ])->withInput();
        }

        // Check daily cap (Max 10 clients)[cite: 7]
        $dailyBookingsCount = Booking::where('merchant_id', $merchant->id)
            ->where('booking_date', $request->booking_date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        if ($dailyBookingsCount >= 10) {
            // Redirect to the fully booked page as requested
            return redirect()->route('booking.fully-booked', [
                'merchant' => $merchant->slug,
                'date' => $request->booking_date
            ]);
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

    public function fullyBooked(Merchant $merchant, Request $request)
    {
        $date = $request->input('date', today()->toDateString());
        return view('bookings.fully-booked', compact('merchant', 'date'));
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

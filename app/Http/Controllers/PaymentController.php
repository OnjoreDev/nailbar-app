<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MpesaService;
use App\Models\Booking;
use Exception;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    // Show the payment view with draft session details
    public function showPayment()
    {
        $draft = session('draft_booking');

        if (!$draft) {
            return redirect()->route('welcome')->with('error', 'No active booking session found.');
        }

        $service = \App\Models\Service::findOrFail($draft['service_id']);

        return view('bookings.payment', compact('draft', 'service'));
    }

    // Trigger the M-Pesa STK Push for the 500 deposit
    public function initiateStkPayment(Request $request, MpesaService $mpesaService)
    {
        $request->validate([
            'phone_number' => 'required|string|max:15',
        ]);

        $draft = session('draft_booking');

        if (!$draft) {
            return redirect()->route('welcome')->with('error', 'Booking session expired.');
        }

        $phone = $request->phone_number;
        $amount = 500; // Default deposit amount

        // Test override: If the phone number matches, set amount to 1 KSh
        if ($phone === '0713420287' || $phone === '254713420287') {
            $amount = 1;
        }

        $accountRef = 'Booking #' . $draft['merchant_id'];

        try {
            // Trigger the STK push via your MpesaService with the dynamic amount
            $response = $mpesaService->initiateStk($amount, $phone, [
                'account_ref' => $accountRef
            ]);

            // Save a pending Booking record linked to this CheckoutRequestID
            $booking = Booking::create([
                'merchant_id'         => $draft['merchant_id'],
                'service_id'          => $draft['service_id'],
                'client_name'         => $draft['client_name'],
                'client_phone'        => $phone,
                'client_email'        => $draft['client_email'] ?? null,
                'booking_date'        => $draft['booking_date'],
                'deposit_amount'      => $amount, // Saves 1.00 or 500.00 accordingly
                'payment_method'      => 'mpesa',
                'payment_status'      => 'pending',
                'status'              => 'pending',
                'notes'               => $draft['notes'] ?? null,
                'checkout_request_id' => $response['CheckoutRequestID'],
                'merchant_request_id' => $response['MerchantRequestID'] ?? null,
            ]);

            session(['current_booking_id' => $booking->id]);

            // Inside initiateStkPayment(), replace `return back()->with('success', ...)` with:
            return redirect()->route('booking.waiting', $booking->id);
        } catch (Exception $e) {
            Log::error("STK Checkout Failed: " . $e->getMessage());
            return back()->withErrors(['phone_number' => 'Could not initiate STK push. Please check your number or try again.']);
        }
    }

    // Handle Cash on Arrival Option
    public function cashCheckout()
    {
        $draft = session('draft_booking');

        if (!$draft) {
            return redirect()->route('welcome')->with('error', 'Booking session expired.');
        }

        $booking = Booking::create([
            'merchant_id'    => $draft['merchant_id'],
            'service_id'     => $draft['service_id'],
            'client_name'    => $draft['client_name'],
            'client_phone'   => $draft['client_phone'],
            'client_email'   => $draft['client_email'] ?? null,
            'booking_date'   => $draft['booking_date'],
            'deposit_amount' => 500.00,
            'payment_method' => 'cash',
            'payment_status' => 'pending',
            'status'         => 'pending',
            'notes'          => $draft['notes'] ?? null,
        ]);

        // Clear draft
        session()->forget('draft_booking');

        return redirect()->route('booking.success', $booking->id);
    }

    public function checkStatus(Booking $booking)
    {
        return response()->json([
            'status'         => $booking->status,
            'payment_status' => $booking->payment_status,
            'redirect_url'   => route('booking.success', $booking->id),
        ]);
    }
}

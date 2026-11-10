<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Mpesa;
use App\Models\Booking;

class MpesaResponseController extends Controller
{
    public function handleResponse(Request $request)
    {
        $rawPayload = $request->getContent();
        Log::info("The response from mpesa: " . $rawPayload);

        $payload = $request->all();

        if (isset($payload['Body']['stkCallback'])) {
            $stkCallback = $payload['Body']['stkCallback'];

            $checkoutRequestId = $stkCallback['CheckoutRequestID'] ?? null;
            $resultCode = $stkCallback['ResultCode'] ?? null;
            $resultDesc = $stkCallback['ResultDesc'] ?? null;

            // 1. Update mpesa_transactions table record
            $mpesaTransaction = Mpesa::where('checkout_request_id', $checkoutRequestId)->first();

            // 2. Find the corresponding Booking record
            $booking = Booking::where('checkout_request_id', $checkoutRequestId)->first();

            if ($resultCode === 0) {
                $callbackMetadata = $stkCallback['CallbackMetadata']['Item'] ?? [];
                $receiptNumber = null;

                foreach ($callbackMetadata as $item) {
                    if (($item['Name'] ?? null) === 'MpesaReceiptNumber') {
                        $receiptNumber = $item['Value'] ?? null;
                    }
                }

                if ($mpesaTransaction) {
                    $mpesaTransaction->update([
                        'status' => 'completed',
                        'mpesa_receipt_number' => $receiptNumber,
                    ]);
                }

                if ($booking) {
                    $booking->update([
                        'payment_status'        => 'paid',
                        'status'                => 'confirmed',
                        'mpesa_receipt_number'  => $receiptNumber,
                    ]);
                }

                Log::info("Booking & Payment Successful for CheckoutID: {$checkoutRequestId}, Receipt: {$receiptNumber}");
            } else {
                if ($mpesaTransaction) {
                    $mpesaTransaction->update(['status' => 'failed']);
                }

                if ($booking) {
                    $booking->update([
                        'payment_status' => 'failed',
                        'status'         => 'cancelled',
                    ]);
                }

                Log::warning("Booking Payment Failed/Cancelled for CheckoutID: {$checkoutRequestId}");
            }
        }

        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Success'
        ]);
    }
}

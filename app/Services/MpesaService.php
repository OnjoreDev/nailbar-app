<?php

namespace App\Services;

use Exception;
use App\Models\Mpesa;
use Illuminate\Support\Facades\Log;

class MpesaService
{

    public function getToken()
    {

        $url = $_ENV["MPESA_BASE_URL"] . "/oauth/v1/generate?grant_type=client_credentials";
        $ck = $_ENV["MPESA_CONSUMER_KEY"];
        $cs = $_ENV["MPESA_CONSUMER_SECRET"];

        $credentials = $ck . ":" . $cs;
        $encrypted_credentials =  base64_encode($credentials);

        $payload = "";

        //initialize curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disable SSL verification for local sandbox testing
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Basic " . $encrypted_credentials
        ]);


        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            throw new Exception("Curl error " . $error);
        }

        //convert the json string to a php array
        $response =  json_decode($result, true);

        if (isset($response["access_token"])) {
            return $response["access_token"];
        } else {
            throw new Exception("Failed to get new mpesa token " . $result);
        }
    }

    //function to initiate stk push
    //function to initiate stk push
    public function initiateStk(int $amount, string $phone, array $meta)
    {
        $token = $this->getToken();

        $url = env("MPESA_BASE_URL") . "/mpesa/stkpush/v1/processrequest";

        $timestamp = date("YmdHis");

        $formattedPhone = preg_replace('/^0/', '254', $phone);

        // Fix phone formatting logic to prevent duplicate '254' prefixes
        if (str_starts_with($formattedPhone, '7') || str_starts_with($formattedPhone, '1')) {
            $formattedPhone = '254' . $formattedPhone;
        }

        $businessShortCode = env("MPESA_BUSINESS_SHORTCODE");
        $passkey = env("MPESA_PASSKEY");
        $password = base64_encode($businessShortCode . $passkey . $timestamp);

        $payload = [
            "Password" => $password,
            "BusinessShortCode" => $businessShortCode,
            "Timestamp" => $timestamp,
            "Amount" => (int)$amount,
            "PartyA" => $formattedPhone,
            "PartyB" => $businessShortCode,
            "TransactionType" => "CustomerPayBillOnline",
            "PhoneNumber" => $formattedPhone,
            "TransactionDesc" => "Deposit to " . $meta['account_ref'],
            "AccountReference" => $meta['account_ref'],
            "CallBackURL" => env("MPESA_CALLBACK_URL")
        ];

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        // Disable SSL verification for local sandbox testing
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer " . $token
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception("Curl error: " . $error);
        }

        curl_close($ch);

        $decodedResponse = json_decode($response, true);

        // Check if Safaricom returned the expected CheckoutRequestID
        if (is_array($decodedResponse) && isset($decodedResponse['CheckoutRequestID'])) {
            // Creation of initial record before it is updated when the callback is hit    
            Mpesa::create([
                "amount" => $amount,
                "phone" => $formattedPhone,
                "checkout_request_id" => $decodedResponse['CheckoutRequestID'],
                "merchant_request_id" => $decodedResponse['MerchantRequestID'] ?? null,
                "status" => "pending"
            ]);
        } else {
            // Log the exact error response from Safaricom for debugging
            Log::error("MPESA STK Push failed response: " . $response);
            throw new Exception("Mpesa STK Push failed: " . ($decodedResponse['errorMessage'] ?? $response));
        }

        return $decodedResponse;
    }
}

<?php

use App\Http\Controllers\API\MpesaResponseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post("/v1/payments/initiate", [PaymentController::class, 'simulateStk']);
Route::post("/v1/callback", [MpesaResponseController::class, 'handleResponse']);

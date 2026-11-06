<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id', // Added merchant_id
        'service_id',
        'client_name',
        'client_phone',
        'client_email',
        'booking_date',
        'deposit_amount',
        'payment_method',
        'payment_status',
        'mpesa_receipt_number',
        'merchant_request_id',
        'checkout_request_id',
        'status',
        'notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'deposit_amount' => 'decimal:2',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }
}

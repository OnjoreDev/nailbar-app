<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id', // Added merchant_id
        'name',
        'price',
        'image',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}

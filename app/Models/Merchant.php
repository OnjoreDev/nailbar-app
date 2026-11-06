<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_name',
        'slug',
        'phone',
        'logo',
        'bio',
    ];

    /**
     * A merchant belongs to a system user login account (Role: merchant).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A merchant offers many services (nail styles, etc.).
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    /**
     * A merchant has many client bookings.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}

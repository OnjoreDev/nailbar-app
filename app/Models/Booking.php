<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name','email','phone','image','service','option','booking_date','partner_id'])]
class Booking extends Model
{
    //
    use HasFactory;

    //cast to a Cabon object to utilize carbon functions
    protected $casts = [
        'booking_date' => 'date'
    ];
}

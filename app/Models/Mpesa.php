<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mpesa extends Model
{
    //table has a different name as opposed to the model 

    protected $table = "mpesa_transactions";

    //table columns where update will occur
    protected $fillable  = [
        "amount",
        "phone",
        "checkout_request_id",
        "merchant_request_id",
        "status",
        "mpesa_receipt_number"
    ];
}

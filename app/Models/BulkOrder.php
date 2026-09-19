<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkOrder extends Model
{

    use HasFactory;
    protected $table = 'bulk_orders';
     protected $fillable = [
        "product_id",
        'first_name', 'phone', 'email', 'quantity',
        'customisation', 'gst', 'preferred_time', 'details'
    ];
}

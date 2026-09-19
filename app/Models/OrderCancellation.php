<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCancellation extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'reason',
        'details',
        'order_item_id',
    ];
}

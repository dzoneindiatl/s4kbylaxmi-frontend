<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    use HasFactory;

    protected $table = "order_status_history";

    /**
     * This status change belongs to one order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * This status change belongs to one status type
     */
    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class);
    }
}

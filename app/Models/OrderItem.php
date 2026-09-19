<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use File;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    const RECEIVED = 'received';
    const CONFIRMED = 'confirmed';
    const SHIPPED = 'shipped';
    const OUT_FOR_DELIVERY = 'out_for_delivery';
    const DELIVERED = 'delivered';
    const CANCELLED = 'cancelled';
    const RETURNED = 'returned';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function statusHistoriesItem()
    {
        return $this->hasMany(OrderStatusHistory::class, 'order_item_id', 'id');
    }
    public function productGraphics()
    {
        return $this->belongsTo(ProductGraphics::class, 'product_id', 'product_id');
    }

    public function courier()
    {
        return $this->belongsTo(Couriers::class);
    }

    public function itemTax()
    {
        return $this->hasOne(OrderItemTax::class);
    }
}

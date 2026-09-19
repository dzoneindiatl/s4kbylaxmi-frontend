<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use File;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    
    public static function getAllStatus(){
        //     return [
        //     "received"=>"Received",
        //     "pending"=>"Pending",
        //     "confirmed"=>"Confirmed",
        //     "cancel_request"=>"Cancel Request",
        //     "cancelled"=>"Cancelled",            
        //     "shipped"=>"Shipped",
        //     "out_for_delivery"=>"Out for delivery",
        //     "delivered"=>"Delivered",            
        //     "exchange_requested"=>"Exchange Request",
        //     "exchanged"=>"Exchanged",                     
        //     "return_request"=>"Return Request",
        //     "returned"=>"Returned",
        //     "refunded"=>"Refunded"
           
        // ];
        return [
            'pending' =>"Pending",
            'accepted'=>"Accepted",
            'processing'=>"Processing",
            'shipped'=>"Shipped",
            'in-transit'=>"In Transit",
            'out-for-delivery'=>"Out For Delivery",
            'delivered'=>"Delivered",
            'return-requested'=>"Return Requested",
            'return-accepted'=>"Return Accepted",
            'refund-pending'=>"Refund Pending",
            'refunded'=>"Refunded",
            'cancelled'=>"Cancelled",
            'cancelled_by_customer'=>"Cancelled By Vustomer"
        ];
    }
    
    
    
    public static function getBasicStatus(){
        return [
            "received"=>"Received",
            "Completed"=>"Completed",
            "captured"=>"Captured",
            "pending"=>"Pending",            
            "cancelled"=>"Cancelled",
            "confirmed"=>"Confirmed"           
        ];
    }

    public static function getShippedStatus()
    {
        return [
            "shipped"=>"Shipped",
            "outfordelivery"=>"Out for delivery",
            "delivered"=>"Delivered"         
        ];
    }

    public static function getExchangedStatus()
    {
        return [
            //"exchanged"=>"Exchanged",               
            //"returned"=>"Returned",
            "refunded"=>"Refunded"           
        ];
    }

    
  
    public function latest_item() {
        return $this->hasOne(OrderItem::class)->latest();
    }

    public function items() {
        return $this->hasMany(OrderItem::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(UserAddress::class,'address_id','id');
    }
    public function currency(){
        return $this->hasOne(Currency::class,'currency_code','currency_code');
    }

    public function getAddressForInvoice($address_id) {
        $address = UserAddress::find($address_id);
        $string = "N/A";
        if(!empty($address)){
            $string = "<strong>".ucfirst($address->name)."</strong><br>".$address->address."<br>";
            if(!empty($address->landmark)){
                $string .= $address->landmark."<br>";
            }
            $string .= $address->city->name.", ".$address->postal_code."<br>".$address->state->name."<br>".$address->country->name."<br>Contact: ".$address->phone_number."<br>Email: ".$address->email;
        }
        return $string;
    }
    public function billingAddress()
    {
        return $this->belongsTo(UserAddress::class, 'billing_address_id');
    }
    
    public function shippingAddress()
    {
        return $this->belongsTo(UserAddress::class, 'shipping_address_id');
    }

    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function refundRequest()
    {
        return $this->hasOne(RefundRequest::class,'order_id','id');
    }

}

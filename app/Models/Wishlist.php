<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use File;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
     protected $fillable = [
        'user_id',
        'product_id',
    ];
    public $table = 'wishlist';


    public function getProduct()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

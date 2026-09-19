<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use File;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public $table = 'cart';

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function productVariantCombination()
    {
        return $this->belongsTo(ProductVariantCombination::class, 'product_variant_combination_id', 'id');
    }

   
}

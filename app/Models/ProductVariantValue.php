<?php 
namespace App\Models; 
use App\Models\ProductGraphics ;
use Eloquent;
/**
 * Review Model
 */
class ProductVariantValue extends Eloquent {
    protected $table = 'product_variant_values';

    protected $fillable = [
        'product_variant_id',
        'variant_value_id',
        'product_id',
    ];
    
	public function variant_value() {
        return $this->belongsTo(VariantValue::class, 'variant_value_id')->select(['id', 'variant_id','name','color_code']);
    }
    public function first_image()
    {
       /*  $primaryImage  = $this->hasOne(ProductGraphics::class, 'variant_id', 'variant_value_id')
                    ->where('graphic_type', 'image')
                    ->where('is_variant_icon', 1)
                    ->where('status', 1)
                    ->orderBy('id','DESC');

        if ($primaryImage->exists()) {
            return $primaryImage;
        } */

        return  $this->hasOne(ProductGraphics::class, 'variant_id', 'variant_value_id')
                    ->where('graphic_type', 'image')
                    ->where('status', 1)
                    ->orderByDesc('is_variant_icon')
                    ->orderByDesc('id');
    }
    
    
    // public function variantValue()
    // {
    //     return $this->belongsTo(VariantValue::class, 'variant_value_id');
    // }

    // public function productVariant()
    // {
    //     return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    // }
}

<?php 
namespace App\Models; 
use Eloquent;
use App\Models\VariantValue;
/**
 * Review Model
 */
class Variant extends Eloquent {

	
/**
 * The database table used by the model.
 *
 * @var string
 */
	protected $table = 'variants';
	
	protected $fillable = ['name'];

	public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function variant_values(){
        return $this->hasMany(VariantValue::class, 'variant_id');
    }
}

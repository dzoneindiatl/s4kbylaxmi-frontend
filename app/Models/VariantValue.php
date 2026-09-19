<?php 
namespace App\Models; 
use Illuminate\Database\Eloquent\Model;

class VariantValue extends Model {

	protected $table = 'variant_values';
	protected $fillable = ['variant_id', 'name'];

	public function productVariantValues()
    {
        return $this->hasMany(ProductVariantValue::class, 'variant_value_id');
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class, 'variant_id');
    }
}

<?php 
namespace App\Models; 
use Eloquent;

class ProductAttributeValue extends Eloquent {
	protected $table = 'product_attribute_values';

    protected $fillable = [
        'product_id',
        'status',
        'attribute_id',
    ];
	
	public function attributeValue() {
        return $this->belongsTo(AttributeValue::class);
    }
}

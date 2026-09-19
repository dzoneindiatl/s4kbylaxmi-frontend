<?php 
namespace App\Models; 
use Eloquent;
use App\Models\AttributeValue;
class Attribute extends Eloquent {
	protected $table = 'attributes';
	
	
	 public function attribute_values(){
        return $this->hasMany(AttributeValue::class, 'attribute_id');
    }
}

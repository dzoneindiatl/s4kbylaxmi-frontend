<?php 
namespace App\Models; 
use Eloquent;
/**
 * Review Model
 */
class AttributeValue extends Eloquent {
    protected $table = 'attribute_values';
    protected $fillable=['attribute_id','name','is_deleted'];
}

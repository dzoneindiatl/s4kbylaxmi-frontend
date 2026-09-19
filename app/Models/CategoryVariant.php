<?php 
namespace App\Models; 
use Eloquent;
/**
 * Review Model
 */
class CategoryVariant extends Eloquent {

	
/**
 * The database table used by the model.
 *
 * @var string
 */
	protected $table = 'category_variants';


	public function variant()
	{
		return $this->belongsTo(Variant::class);
	}
	
}// end EmailAction class

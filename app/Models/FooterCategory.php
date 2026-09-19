<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterCategory extends Model
{
    use HasFactory;

    public function subcategories()
    {
        return $this->hasMany(FooterSubcategory::class)
                    ->where('is_deleted', 0)->where('is_active', 1)->orderBy('order_number','asc');
    }
}

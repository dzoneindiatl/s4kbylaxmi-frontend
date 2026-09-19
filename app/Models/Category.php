<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use File;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id')
            ->where('is_active', 1)
            
            ->where('is_deleted', 0);
    }

    public function childrenmenu()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id')
            ->where('is_active', 1)
            ->where('show_on_menu', '=', 1)
            ->where('is_deleted', 0);
    }

    public function parentcategory()
    {
        return $this->hasOne(Category::class, 'id', 'parent_id');
    }
    public function superparentcategory()
    {
        return $this->hasOne(Category::class, 'id', 'parent_id');
    }

    public function childCategoryProducts()
    {
        return $this->hasMany(Product::class, 'main_child_category_id', 'id');
    }
    // newly added

    public function ancestors()
    {
        $ancestors = collect([]);
        $parent = $this->parent;

        while ($parent) {
            $ancestors->prepend($parent); // prepend to maintain the order from root to child
            $parent = $parent->parent;
        }

        return $ancestors;
    }

    // newly added


    function getImageAttribute($value = "")
    {
        if ($value != "" && File::exists(Config('constant.CATEGORY_IMAGE_ROOT_PATH') . $value)) {
            return  Config('constant.CATEGORY_IMAGE_URL') . $value;
        }
    }

    public function getActiveCategories()
    {
        return self::whereNull('parent_id')
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->select('id', 'parent_id', 'name', 'slug', 'description', 'image', 'thumbnail_image', 'video', 'category_order')
            ->orderBy('category_order', 'ASC')
            ->with('subcategories') // Eager loading subcategories
            ->limit(5)
            ->get();
    }

    public function getAllCategories()
    {
        return self::whereNull('parent_id')
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->where('show_on_menu', '=', 1)
            ->where('category_type_id', '=', 2)
            ->select('id', 'parent_id', 'name', 'slug', 'style_type', 'description', 'image', 'thumbnail_image', 'video', 'category_order', 'category_type_id')
            ->orderBy('category_order', 'ASC')
            ->with('subcategories') // Eager loading subcategories
            ->limit(5)
            ->get();
    }

    public function getAllMenuCategories()
    {
        return self::whereNull('parent_id')
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->where('show_on_menu', '=', 1)
            ->where('category_type_id', '=', 2)
            ->select('id', 'parent_id', 'name', 'slug', 'style_type', 'description', 'image', 'thumbnail_image as thumbnail', 'video', 'category_order', 'category_type_id')
            ->orderBy('category_order', 'ASC')
            // ->with('subcategories') // Eager loading subcategories
            ->with(['subcategories' => function ($query) {
                $query->orderBy('category_order', 'ASC');
            }])
            ->limit(5)
            ->get();
    }

    public function getAllHomeCategories()
    {
        return self::whereNull('parent_id')
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->where('show_on_menu', '=', 1)
            ->where('category_type_id', '=', 2)
            ->select('id', 'parent_id', 'name', 'slug', 'style_type', 'description', 'image', 'thumbnail_image', 'video', 'category_order', 'category_type_id')
            ->orderBy('category_order', 'ASC')
            ->with('subcategories') // Eager loading subcategories
            ->limit(5)
            ->get();
    }

    public function getAllMenuCollections()
    {
        return self::whereNull('parent_id')
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->where('show_on_menu', '=', 1)
            ->where('category_type_id', '=', 1)
            ->select('id', 'parent_id', 'name', 'slug', 'style_type', 'description', 'image', 'thumbnail_image', 'video', 'category_order', 'category_type_id')
            ->orderBy('category_order', 'ASC')
            ->with('subcategories') // Eager loading subcategories
            ->limit(5)
            ->get();
    }

    public function getAllHomeCollections()
    {
        return self::whereNull('parent_id')
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->where('show_on_menu', '=', 1)
            ->where('category_type_id', '=', 1)
            ->select('id', 'parent_id', 'name', 'slug', 'style_type', 'description', 'image', 'thumbnail_image', 'video', 'category_order', 'category_type_id')
            ->orderBy('category_order', 'ASC')
            ->with('subcategories') // Eager loading subcategories
            ->limit(5)
            ->get();
    }


    // public function subcategories()
    // {
    //     return $this->hasMany(Category::class, 'parent_id');
    // }

    // App\Models\Category.php

    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_id')
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->where('show_on_menu', '=', 1)
            ->orderBy('category_order', 'ASC')
            ->with('subcategories'); 
    }

    public function subCategory()
    {
        return $this->hasMany(Category::class, 'parent_id'); 
    }

    function getThumbnailImageAttribute($value = "")
    {
        if ($value != "" && File::exists(Config('constant.CATEGORY_IMAGE_ROOT_PATH') . $value)) {
            return  Config('constant.CATEGORY_IMAGE_URL') . $value;
        }
    }

    function getVideoAttribute($value = "")
    {
        if ($value != "" && File::exists(Config('constant.CATEGORY_VIDEO_ROOT_PATH') . $value)) {
            return  Config('constant.CATEGORY_VIDEO_URL') . $value;
        }
    }

    public function category_with_product()
    {
        return $this->hasMany(Product::class, 'main_category_id');
    }

    protected $casts = [
        'category_id' => 'array',
    ];
}
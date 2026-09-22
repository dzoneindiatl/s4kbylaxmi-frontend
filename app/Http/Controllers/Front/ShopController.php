<?php

namespace App\Http\Controllers\Front;

use App\Models\ProductDescription;
use Exception, DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductVariantCombination;
use App\Models\ProductGraphics;
use App\Models\ProductSpecification;
use App\Models\ProductVariant;
use App\Models\ProductVariantValue;
use App\Models\SizeChartAssign;
use App\Models\SizeChartDetailValue;
use App\Models\SizeChartDetail;
use App\Models\SizeChartTebular;
use Illuminate\Support\Facades\Crypt;
class ShopController extends Controller
{
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null, $childCategorySlug = null)
    {
        $DB = Product::with(['ProductVariantCombination', 'productVariants.variantValues.variant_value'])->where('is_deleted', 0)->where('is_active', 1);
        $categoriesData = Category::whereNull('parent_id')->where('is_deleted', 0)->where('is_active', 1)->get();

        // Category filters
        if (!empty($categorySlug)) {
            $categoryId = Category::where('slug', $categorySlug)->value('id');
            $DB->where('category_id', $categoryId);
            $categoriesData = Category::where('parent_id', $categoryId)->where('is_deleted', 0)->where('is_active', 1)->get();
        }

        if (!empty($categorySlug) && !empty($subCategorySlug)) {
            $categoryId = Category::where('slug', $categorySlug)->value('id');
            $subCategoryId = Category::where('parent_id', $categoryId)->where('slug', $subCategorySlug)->value('id');
            $DB->where('category_id', $categoryId)->where('sub_category_id', $subCategoryId);
            $categoriesData = Category::where('parent_id', $subCategoryId)->where('is_deleted', 0)->where('is_active', 1)->get();
        }

        if (!empty($categorySlug) && !empty($subCategorySlug) && !empty($childCategorySlug)) {
            $categoryId = Category::where('slug', $categorySlug)->value('id');
            $subCategoryId = Category::where('parent_id', $categoryId)->where('slug', $subCategorySlug)->value('id');
            $childCategoryId = Category::where('parent_id', $subCategoryId)->where('slug', $childCategorySlug)->value('id');
            $DB->where('category_id', $categoryId)
                ->where('sub_category_id', $subCategoryId)
                ->where('child_category_id', $childCategoryId);
        }

        // Price filtering
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $DB->whereHas('variants', function ($q) use ($request) {
                $q->whereBetween('selling_price', [$request->min_price, $request->max_price]);
            });
        }

        // Sorting
        switch ($request->sortBy) {
            case 'a_z':
                $DB->orderBy('name', 'asc');
                break;
            case 'z_a':
                $DB->orderBy('name', 'desc');
                break;
            case 'low_high':
                $DB->with(['variants' => function ($q) {
                    $q->orderBy('selling_price', 'asc');
                }]);
                break;
            case 'high_low':
                $DB->with(['variants' => function ($q) {
                    $q->orderBy('selling_price', 'desc');
                }]);
                break;
            default:
                $DB->orderBy('created_at', 'desc');
                break;
        }

        // Pagination
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', Config("Reading.records_per_page"));
        $totalResults = $DB->count();
        $results = $DB->offset($offset)->limit($limit)->get();

        // Post-processing: add images, wishlist/cart status
        foreach ($results as $product) {
            $product->productImages = ProductGraphics::where('product_id', $product->id)
                ->limit(2)
                ->pluck('graphic')
                ->map(function ($img) {
                    return !empty($img) ? Config('constant.PRODUCT_IMAGE_URL') . $img : Config('constant.IMAGE_URL') . "noimage.png";
                })
                ->toArray();

            $product->isProductAddedIntoCart = isProductAddedInCart($product->id) ? 1 : 0;
            $product->isProductAddedIntoWishlist = isProductAddedInWishlist($product->id) ? 1 : 0;
        }

        // Return view
        if ($request->ajax()) {
            return view("front.modules.shop.load_more_data", compact('results', 'totalResults'));
        } else {
            return view("front.modules.shop.index", compact('results', 'categoriesData', 'totalResults', 'categorySlug', 'subCategorySlug', 'childCategorySlug'));
        }
    }

    public function productDetail(Request $request, $productSlug)
    {
        $product = Product::where('slug',$productSlug)->first();
             
        $productvariants = ProductVariant::with(relations: [
            'variant:id,name,type',
            'variantValues.variant_value:id,name,color_code',
            'variantValues.first_image' // <-- add this
        ])
            ->select('id', 'variant_id', 'product_id')
            ->where('product_id', $product->id)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'variant_id' => $item->variant_id,
                    'product_id' => $item->product_id,
                    'variant_name' => $item->variant->name ?? null,
                    'variant_type' => $item->variant->type ?? null,
                    'variant_values' => $item->variantValues->map(function ($v) {
                        return [
                            'id' => $v->id,
                            'product_variant_id' => $v->product_variant_id,
                            'is_main' => $v->is_main,
                            'variant_value_id' => $v->variant_value_id,
                            'name' => $v->variant_value->name ?? null,
                            'color_code' => $v->variant_value->color_code ?? null,
                            'image' => $v->first_image?->graphic ?? null,
                        ];
                    })->toArray()
                ];
            })->toArray();
            
            $related_product_ids = Product::where('id', $product->id)->value('related_products');
            $related_ids_array = array_filter(explode(',', $related_product_ids));
            $related_products = Product::leftJoin('product_graphics', 'products.id', '=', 'product_graphics.product_id')
                ->whereIn('products.id', $related_ids_array)
                ->select(
                    'products.id',
                    'products.selling_price',
                    'products.discount_type',
                    'products.discount',
                    'products.buying_price',
                    'products.name',
                    'product_graphics.graphic as image'
                )
                ->groupBy('products.id')
                ->get();
                
            $productVarientCom = ProductVariantCombination::where('product_id', $product->id)->get();
            $productSeller = Product::with(['productVariants.variantValues.first_image'])->where(['best_seller' => true,'draf' => false])->get();

            
            $releatedProduct = Product::with(['productVariants.variantValues.first_image'])->whereIn('id', [$product->related_products])->get();

        return view('front.modules.shop.product-detail', compact('product', 'productvariants', 'related_products', 'productSeller', 'releatedProduct'));
    }
    
    public function productSortFilter(Request $request){

        if ($request->ajax()) {
                $DB = Product::select('id', 'name', 'slug', 'buying_price', 'selling_price', 'category_id', 'in_stock', 'is_featured', 'is_active', 'draf', 'qty');
                $DB->where('draf', false);
            
                if ($request->sort_by == 'what_new') {
                    $DB->orderBy('products.id', 'DESC');
                } elseif ($request->sort_by == 'high_to_low') {
                    $DB->orderBy('products.selling_price', 'DESC');
                } elseif ($request->sort_by == 'low_to_high') {
                    $DB->orderBy('products.selling_price', 'ASC');
                }
            
                $productList = $DB->with(['frontProductImage', 'firstProductImage', 'productVariants.variantValues.variant_value'])
                    ->where('is_deleted', 0)
                    ->where('draf', false)
                    ->get();
            
                $html = '';
            
                foreach ($productList as $product) {
                    // echo "<pre@@@>"; print_r($product);
                    // Prepare discount
                    $discountText = '';
                    if ($product->discount_type == 'flat') {
                        $discountText = 'Flat Rs ' . (floor($product->discount) ?? 0) . ' Off';
                    } elseif ($product->discount_type == 'percentage') {
                        $discountText = (floor($product->discount) ?? 0) . '% Off';
                    }
            
                    $fullPriceHtml = '';
                    if ($discountText) {
                        $fullPriceHtml = '<li class="full-price">' . floor($product->buying_price) . '</li>';
                    }
            
                    $discountHtml = '';
                    if ($discountText) {
                        $discountHtml = '<span class="Discount">' . $discountText . '</span>';
                    }
            
                    $imageSrc = $product->frontProductImage ? $product->frontProductImage->graphic :  $product->firstProductImage->graphic;
            
                    $html .= '<div class="col-md-3">
                        <div class="product-card">
                            <a href="' . url('/product-detail/' . $product->slug) . '">
                                <div>
                                    <figure class="item-single">
                                        <img src="' . $imageSrc . '" class="default-image">
                                        <img src="' . $imageSrc . '" class="hover-image">
                                        <span class="icon-top">
                                            <!-- heart svg -->
                                        </span>';
            
                    // Variant color section
                    if ($product->productVariants) {
                        $html .= '<div class="color-choose">';
                        foreach ($product->productVariants as $productVariantsValue) {
                            foreach ($productVariantsValue->variantValues as $variantValue) {
                                if ($variantValue->variant_value->variant_id == 1) {
                                    $colorName = strtolower($variantValue->variant_value->name);
                                    $html .= '<div>
                                        <input data-image="' . $colorName . '" type="radio" id="' . $colorName . '" name="color_' . $product->id . '" value="' . $colorName . '" checked />
                                        <label for="' . $colorName . '"><span></span></label>
                                    </div>';
                                }
                            }
                        }
                        $html .= '</div>';
                    }
            
                    $html .= '</figure>
                                </div>
                            </a>
                            <div class="bottom-content">
                                <p><a href="' . url('/product-detail/' . $product->slug) . '">' . $product->name . '</a></p>
                                <div class="price-btn-sec d-flex">
                                    <div class="product-color">
                                        <ul>
                                            <li class="price">' . $product->selling_price . '</li>
                                            ' . $fullPriceHtml . '
                                        </ul>
                                        ' . $discountHtml . '
                                    </div>
                                    <div class="add-cart-btn ms-4">
                                        <button type="submit" name="add" class="boost-pfs-quickview-cart-btn">Add To Cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            
                return response()->json(['html' => $html]);
            }

    }
    
    
   public function getRecentlyViewed(Request $request)
    {
        $ids = $request->input('ids', []);
        $recentlyProducts = Product::with(["product_main_images"])
            ->whereIn('id', $ids)
            ->orderByRaw("FIELD(id, " . implode(',', $ids) . ")")
            ->get();
    
        $html = '<div class="col-md-12 mt-5 recent-product-sec">
            <h3>Recently Viewed Products</h3>
            <section class="women-seller custom-seller-slider women-seller-slider" id="recently_view">
            <div class="owl-carousel owl-theme" id="recently-view-product-slider">';
    
        foreach ($recentlyProducts as $product) {
            $graphics = $product->product_main_images->where('status', 1);
            $frontImage = $graphics->firstWhere('is_front', 1);
            $backImage = $graphics->firstWhere('is_back', 1);
            $fallbackImages = $graphics->pluck('graphic')->take(2);
            
            $firstImage = $frontImage? $frontImage->graphic: ($fallbackImages->get(0) ?? null);
            $secondImage = $backImage? $backImage->graphic: ($fallbackImages->get(1) ?? $firstImage);
            
            $defaultImage = asset('uploads/products/' . $firstImage);
            $hoverImage = asset('uploads/products/' . $secondImage);
                                        
            
            $productUrl = route('front-product.detail',['product' => 'product','title' =>productSlug($product->name).'.html', 'sku' => productSlug($product->sku)]);
            $productName = htmlspecialchars($product->name);
            $sellingPrice = floor($product->selling_price);
            $buyingPrice = floor($product->buying_price);
            $discount = floor($product->discount) ?? 0;
            $discountText = '';
    
            
            $discountText = \App\Helpers\Attributes::productDiscountMsg($product);
    
            $html .= '<a href="' . $productUrl . '">
                <div class="item">
                    <div class="product-card">
                        <figure>
                            <img src="' . $defaultImage . '" alt="' . $productName . '" class="default-image">
                            <img src="' . $hoverImage . '" alt="' . $productName . ' Hover" class="hover-image">
                            <span class="icon-top">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="icon-icon-P1l">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06
                                        a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78
                                        1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                </svg>
                            </span>
                        </figure>';
             // Variant color section
            if ($product->productVariants) {
                $html .= '<div class="color-choose">';
                foreach ($product->productVariants as $productVariantsValue) {
                    foreach ($productVariantsValue->variantValues as $variantValue) {
                        if ($variantValue->variant_value->variant_id == 1) {
                            $colorName = strtolower($variantValue->variant_value->name);
                            $html .= '<div>
                                <input data-image="' . $colorName . '" type="radio" id="' . $colorName . '" name="color_' . $product->id . '" value="' . $colorName . '" checked />
                                <label for="' . $colorName . '"><span></span></label>
                            </div>';
                        }
                    }
                }
            $html .= '</div>';
            }
                    
            $html .= '</div>
                <div class="bottom-content">
                    <p>' . $productName . '</p>
                    <div class="price-btn-sec d-flex">
                        <div class="product-color">
                            <ul>
                                <li class="price">₹' . $sellingPrice . '</li>';
                                if ($product->discount_type == 'flat' || $product->discount_type == 'percentage') {
                                    $html .= '<li class="full-price">₹' . $buyingPrice . '</li>';
                                }
                                $html .= '</ul>' . $discountText . '
                            </div>
                            <div class="add-cart-btn ms-4">
                                <button type="submit" name="add" class="boost-pfs-quickview-cart-btn">Add To Cart</button>
                            </div>
                        </div>
                    </div>
                </div>
            </a>';
        }
    
        // ✅ Append closing tags, not overwrite
        $html .= '</div>
            </section>
        </div>';
    
        return ['success' => true, 'data' => $html];
    }

}




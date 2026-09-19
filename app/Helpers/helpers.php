<?php

use App\Models\Coupon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\User;
use App\Models\Category;
use App\Models\Pincodes;
use App\Models\Product;

use App\Models\Wishlist;
use App\Models\ProductVariantValue;
use App\Models\ProductGraphics;
use App\Models\FooterCategory;
use App\Models\ProductVariantCombination;
use App\Models\ProductVariantCombinationImage;

if (!function_exists('getUSerById')) {
    function getUserById($userId = 0)
    {
		$user = '';
        $user = User::where('id', $userId)->first();
        if(!empty($user)){
            $user = $user;
        }
        return $user;
    }
}

if (!function_exists('getProductDetail')) {
    function getProductDetail($productId = 0)
    {
        $product      = Product::where('is_active', 1)->where('is_deleted', 0)->where('id', $productId)->first();
        return $product ;
    }
}

if (!function_exists('getMinMaxOrderQty')) {
    function getMinMaxOrderQty($productId = 0)
    {
        $getMinMaxOrderQtyArr      = Product::where('is_active', 1)->where('is_deleted', 0)->where('id', $productId)->first();
        if(!empty($getMinMaxOrderQtyArr)){
            $getMinMaxOrderQtyArr['minSellQty'] = $getMinMaxOrderQtyArr->min_selling_units;
            $getMinMaxOrderQtyArr['maxSellQty'] = $getMinMaxOrderQtyArr->max_selling_units;
        }
        return $getMinMaxOrderQtyArr;
    }
}

if (!function_exists('activeVarientByProductId')) {
    function activeVarientByProductId($productId = 0)
    {
        $activeVarientIdArr      = ProductVariantValue::where('is_main', 1)->where('product_id', $productId)->first();
        $activeVarientID = '';
        if(!empty($activeVarientIdArr)){
            $activeVarientID = $activeVarientIdArr->variant_value_id;
        }
        return $activeVarientID;
       
    }
}

if (!function_exists('getPriceByActiveVarientId')) {
    function getPriceByActiveVarientId($productId = 0,$activeVarientID = 0)
    {   
        $activeVarientID = (array) $activeVarientID;

        $activeVarientIdArr = ProductVariantCombination::where('product_id', $productId)
        ->where(function ($query) use ($activeVarientID) {
            foreach ($activeVarientID as $id) {
                $query->orWhereJsonContains('combination_id', $id);
            }
        })
        ->first();
        $getActiveVarientPriceData = array();
        if(!empty($activeVarientIdArr)){
            $getActiveVarientPriceData['buying_price'] = $activeVarientIdArr->price;
            $getActiveVarientPriceData['selling_price'] = $activeVarientIdArr->selling_price;
        }
        return $getActiveVarientPriceData;
       
    }
}

if (!function_exists('getActiveFrontImg')) {
    function getActiveFrontImg($productId = 0,$varient_id=0)
    {
        $getActiveFrontImgArr      = ProductGraphics::where('status', 1)->where('is_front', 1)->where('product_id', $productId)->where('variant_id', $varient_id)->first();
        $getActiveFrontImg = '';
        if(!empty($getActiveFrontImgArr)){
            $getActiveFrontImg = $getActiveFrontImgArr->graphic;
        }
        return $getActiveFrontImg;
       
    }
}

if (!function_exists('getActiveBackImg')) {
    function getActiveBackImg($productId = 0,$varient_id=0)
    {
        $getActiveBackImgArr      = ProductGraphics::where('status', 1)->where('is_back', 1)->where('product_id', $productId)->where('variant_id', $varient_id)->first();
        $getActiveBackImg = '';
        if(!empty($getActiveBackImgArr)){
            $getActiveBackImg = $getActiveBackImgArr->graphic;
        }
        return $getActiveBackImg;
       
    }
}

if (!function_exists('getActiveVarientImg')) {
    function getActiveVarientImg($productId = 0,$varient_id=0)
    {
        $getActiveVarientImgArr      = ProductGraphics::where('status', 1)->where('product_id', $productId)->where('variant_id', $varient_id)->get();
        $getActiveBackImg = array();
        if(!empty($getActiveVarientImgArr)){
            foreach($getActiveVarientImgArr as $img){
                $getActiveBackImg[] = $img->graphic;
            }
        }
        return $getActiveBackImg;
       
    }
}

if (!function_exists('getSmartCategoryUrl')) {
    function getSmartCategoryUrl($type, $category, $subcategory = null, $child = null)
    {
        $parentSlug = $category->slug;
        $childSlug = null;
        $subChildSlug = null;

        if ($type === 'category') {
            $firstSub = $category->subcategories->first();
            if ($firstSub) {
                $childSlug = $firstSub->slug;
                $firstChild = $firstSub->subcategories->first();
                if ($firstChild) {
                    $subChildSlug = $firstChild->slug;
                }
            }
        } elseif ($type === 'subcategory' && $subcategory) {
            $childSlug = $subcategory->slug;
            $firstChild = $subcategory->subcategories->first();
            if ($firstChild) {
                $subChildSlug = $firstChild->slug;
            }
        } elseif ($type === 'child' && $subcategory && $child) {
            $childSlug = $subcategory->slug;
            $subChildSlug = $child->slug;
        }

        $params = ['parent' => $parentSlug];
        if ($childSlug) $params['child'] = $childSlug;
        if ($subChildSlug) $params['subchild'] = $subChildSlug;

        return route('category.show', $params);
    }
}

if (!function_exists('getYoutubeVideoId')) {
    function getYoutubeVideoId($url)
    {
        preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $url, $matches);
        return $matches[1] ?? '';
    }
}

if (!function_exists('getVimeoVideoId')) {
    function getVimeoVideoId($url)
    {
        preg_match('/vimeo\.com\/(\d+)/', $url, $matches);
        return $matches[1] ?? '';
    }
}


if (!function_exists('productSlug')) {
    function productSlug($slug)
    {
        return Str::slug($slug);
    }
}

if (!function_exists('productWishlist')) {
    function productWishlist($product_id)
    {
        $user_id = Auth::guard('customer')->id();
        return $user_id
            ? Wishlist::where('user_id', $user_id)->where('product_id', $product_id)->exists()
            : false;
    }
}

if (!function_exists('generateOrderNumber')) {
    function generateOrderNumber()
    {
        return DB::transaction(function () {
            $lastOrder = Order::latest('id')->first();
            $sequence = $lastOrder ? ((int) $lastOrder->order_number + 1) : 1001;
            $orderNumber = "{$sequence}";

            return $orderNumber;
        });
    }
}

if (!function_exists('prx')) {
    function prx($arr, $exit = true)
    {
        echo '<pre>';
        print_r($arr);
        if ($exit) exit;
    }
}



if (!function_exists('getActiveFrontImg')) {
    function getActiveFrontImg($productId = 0,$varient_id=0)
    {
        $getActiveFrontImgArr      = ProductGraphics::where('status', 1)->where('is_front', 1)->where('product_id', $productId)->where('variant_id', $varient_id)->first();
        $getActiveFrontImg = '';
        if(!empty($getActiveFrontImgArr)){
            $getActiveFrontImg = $getActiveFrontImgArr->graphic;
        }
        return $getActiveFrontImg;
       
    }
}


if (!function_exists('getProductImages')) {
    function getProductImages($productId = 0, $combo = null)
    {
        if (empty($combo) || $productId == 0) {
            return null;
        }
        $valueIds = [];

        foreach ($combo as $variantName => $variantValue) {
            $variantId = DB::table('variants')
                ->where('name', $variantName)
                ->value('id');

            if ($variantId) {
                $valueId = DB::table('variant_values')
                    ->where('variant_id', $variantId)
                    ->where('name', $variantValue)
                    ->value('id');

                if ($valueId) {
                    $valueIds[] = $valueId;
                }
            }
        }

        // Find image matching any of the variant values
        return DB::table('product_graphics')
            ->where('product_id', $productId)
            ->whereIn('variant_id', $valueIds)
            ->orderBy('id', 'Asc')
            ->first();
    }
}

if (!function_exists('getOrderStatuss')) {
    function getOrderStatuss($id = 0, $slug = '')
    {
        $status = Cache::remember('order_statusess', 60 * 60, function () {
            return OrderStatus::all()->keyBy('id')->toArray();
        });
        if ($id > 0 && isset($status[$id])) {
            $status =  $status[$id];
        } else if (!empty($slug)) {
            $status = collect($status)->firstWhere('slug', $slug);
        }
        return $status;
    }
}


if (!function_exists('getProductCoupon')) {
    function getProductCoupon()
    {
        return Coupon::where('show_on_detail', 1)->where('is_active', 1)->first()?->toArray();
    }
}

if (!function_exists('orderCancellationRequest')) {
    function orderCancellationRequest($orderId =  0)
    {
        return DB::table('order_cancellations')->where('order_id', $orderId)->get()->keyBy('order_item_id')?->toArray();
    }
}

if (!function_exists('orderRefundRequest')) {
    function orderRefundRequest($orderId =  0)
    {
        return DB::table('refund_requests')->where('order_id', $orderId)->get()->keyBy('order_item_id')?->toArray();
    }
}

if (!function_exists('refCodeExist')) {
    function refCodeExist($code = '', $setCookie = false)
    {
        $isExist = User::where('user_referral_code', $code)->where('is_active', 1)->select('id', 'user_referral_code')->first();
        if (!empty($isExist)) {
            // 7 days = 60 minutes * 24 hours * 7 days
            $minutes = 60 * 24 * 7;
            cookie()->queue(cookie('referral_code', $code, $minutes));
        }
        return $isExist;
    }
}

if (!function_exists('getProductVariantSku')) {
    function getProductVariantSku($pvcId =  0)
    {
        return DB::table('product_variant_combinations')->where('id', $pvcId)->first();
    }
}


if (!function_exists('getShippingCharge')) {
    function getShippingCharge($pincode, $weight, $cartAmount)
    {

        $pincode = Pincodes::where('pincode', $pincode)->with('state')->first();

        $charge = 0;

        if (!empty($pincode)) {
            // delivery 3 is delivery with extra charge
            $charge = ($pincode->delivery == 3 && $pincode->extra_delivery_charge > 0) ? $pincode->extra_delivery_charge : 0;

            $isFreeShipping = $pincode?->state?->is_free_shipping ?? 0;
            $minCartAmount = $pincode?->state?->free_shipping_min_cart_amount ?? 0;

            if ($isFreeShipping) {
                $charge = 0;
            } else if ($isFreeShipping == 0 && $minCartAmount > 0 && $cartAmount >= $minCartAmount) {
                $charge = 0;
            } else {
                $weightRanges = !empty($pincode->state->weight_ranges) ? json_decode($pincode->state->weight_ranges, true) : [];
                if (!empty($weightRanges)) {
                    // Sort by weight_from to ensure order
                    usort($weightRanges, function ($a, $b) {
                        return $a['weight_from'] <=> $b['weight_from'];
                    });

                    $found = false;

                    foreach ($weightRanges as $range) {
                        if ($weight >= $range['weight_from'] && $weight <= $range['weight_to']) {
                            $charge += $range['delivery_charge'];
                            $found = true;
                            break;
                        }
                    }

                    // If not found, pick the next higher charge
                    if (!$found) {
                        foreach ($weightRanges as $range) {
                            if ($weight < $range['weight_from']) {
                                $charge += $range['delivery_charge'];
                                $found = true;
                                break;
                            }
                        }

                        // If still not found (i.e., weight is higher than all ranges)
                        if (!$found && !empty($weightRanges)) {
                            $lastRange = end($weightRanges);
                            $charge += $lastRange['delivery_charge'];
                        }
                    }
                }
            }
        }

        return $charge;
    }
}

if (!function_exists('updateDeletedVariantImages')) {
    function updateDeletedVariantImages($productId = 0, $variantImageIds = [])
    {
        if ($productId == 0 || empty($variantImageIds)) {
            return false;
        }
        $existingImageIds = DB::table('product_graphics')
            ->where('product_id', $productId)
            ->whereNotIn('variant_id', $variantImageIds)
            ->update(['status' => 0,'is_front' => 0,'is_back' => 0,'is_variant_icon' => 0]);
        return true;
    }
}

if (!function_exists('getVariantProctCount')) {
    function getVariantProductCount(int $variantId, int $variantValueId, string $slug = ''): int
    {
        $category = Category::where('slug', $slug)->first();
        $data = DB::table('product_variant_values')
        ->join('product_variants', 'product_variants.id', '=', 'product_variant_values.product_variant_id')
        ->join('products', 'products.id', '=', 'product_variant_values.product_id')
        ->where('product_variant_values.variant_value_id', $variantValueId)
        ->where('product_variants.variant_id', $variantId)
        ->where('products.is_active', 1)
        ->where('products.is_deleted', 0)
        ->where(function ($query) use($category) {
            $query->where('products.main_category_id', $category->id)
                ->orWhere('products.main_sub_category_id', $category->id)
                ->orWhere('products.main_child_category_id', $category->id);
        })
        ->count();

        return $data;

    }

}

if (!function_exists('getAttributeProductCount')) {
    function getAttributeProductCount(int $id, int $valueId, string $slug = ''): int
    {
        $category = Category::where('slug', $slug)->first();
        $data = DB::table('product_attributes')
        ->join('products', 'products.id', '=', 'product_attributes.product_id')
        ->where('product_attributes.attribute_value_id', $valueId)
        ->where('product_attributes.attribute_id', $id)
        ->where('products.is_active', 1)
        ->where('products.is_deleted', 0)
        ->where(function ($query) use($category) {
            $query->where('products.main_category_id', $category->id)
                ->orWhere('products.main_sub_category_id', $category->id)
                ->orWhere('products.main_child_category_id', $category->id);
        })
        ->count();

        return $data;

    }

}

if (!function_exists('getPriceRangeProductCount')) {
    function getPriceRangeProductCount(int $min, int $max, string $slug = ''): int
    {
        if (empty($slug)) {
            return 0;
        }

        if ($max==0) {
            $max = 50000;
        }

        $category = Category::where('slug', $slug)->first();

        return Product::whereBetween('selling_price', [$min, $max])
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->where(function ($query) use ($category) {
                $query->where('main_category_id', $category->id)
                    ->orWhere('main_sub_category_id', $category->id)
                    ->orWhere('main_child_category_id', $category->id);
            })
            ->count();
    }

}

if (!function_exists('footerCategoryContent')) {
    function footerCategoryContent()
    {
        // Footer section 
        // The First 
        $firstCategory = FooterCategory::with('subcategories')->where('id', 1)->first();
        // Second 
        $secondCategory = FooterCategory::with('subcategories')->where('id', 2)->first();
        // Third 
        $thirdCategory = FooterCategory::with('subcategories')->where('id', 3)->first();
        // Fourth 
        $fourthCategory = FooterCategory::with('subcategories')->where('id', 4)->first();
        // fifth 
        $fifthCategory = FooterCategory::with('subcategories')->where('id', 5)->first();

        $ActiveCoupon =  Coupon::where('is_active', 1)->first();
        return [
            'firstCategory' => $firstCategory,
            'secondCategory' => $secondCategory,
            'thirdCategory' => $thirdCategory,
            'fourthCategory' => $fourthCategory,
            'fifthCategory' => $fifthCategory,
            'ActiveCoupon' => $ActiveCoupon
        ];
    }
}

if (!function_exists('getCartData')) {
    function getCartData(){
        if (auth()->check()) {
            $cartData = Cart::where('user_id', auth()->guard('customer')->user()->id)->select('product_id', 'quantity')->get()->toArray();
        } else {
           // $cartData = session()->get('cartData', []);
           $cartData = localStorage.getItem('cartItems');
        }
        if (!empty($cartData)) {
            foreach ($cartData as &$cartVal) {
                $productDetails = ProductVariantCombination::where('product_variant_combinations.id', $cartVal['product_id'] ?? 0)->leftJoin('products', 'products.id', 'product_variant_combinations.product_id')->select('product_variant_combinations.*', 'products.name', DB::raw('(SELECT name from variant_values WHERE id = product_variant_combinations.variant1_value_id ) as variant_value1_name'), DB::raw('(SELECT name from variant_values WHERE id = product_variant_combinations.variant2_value_id ) as variant_value2_name'))->first();
                
                $cartVal['product_name'] = $productDetails->name ?? '';
                $cartVal['variant_value1_name'] = $productDetails->variant_value1_name ?? '';
                $cartVal['variant_value2_name'] = $productDetails->variant_value2_name ?? '';
                $cartVal['product_price'] = ($productDetails->selling_price ?? 0) * ($cartVal['quantity'] ?? 0);
                $cartVal['buying_price'] = ($productDetails->buying_price ?? 0) * ($cartVal['quantity'] ?? 0);
                $productImage = ProductVariantCombinationImage::where('product_variant_combination_images.product_variant_combination_id', $productDetails->id)->leftJoin('product_images', 'product_images.id', 'product_variant_combination_images.product_image_id')->value('product_images.image');
                $cartVal['product_image'] = (!empty($productImage)) ? Config('constant.PRODUCT_IMAGE_URL') . $productImage : Config('constant.IMAGE_URL') . "noimage.png";
            }
        }
    }
}

if (!function_exists('getUserOrderStatusHistory')) {
    function getUserOrderStatusHistory($userId =  0,$orderId =  0,$orderItemId =  0)
    {
        return DB::table('order_status_history')->select('order_status','remark','updated_at')->where('user_id', $userId)->where('order_id', $orderId)->where('order_item_id', $orderItemId)->get();
    }
}
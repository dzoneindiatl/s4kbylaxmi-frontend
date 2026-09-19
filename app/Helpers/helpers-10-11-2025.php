<?php

use App\Models\Coupon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\User;
use App\Models\Pincodes;

use App\Models\Wishlist;

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

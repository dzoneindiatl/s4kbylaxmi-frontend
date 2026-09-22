<?php

namespace App\Http\Controllers\Front;

use Session;
use Exception;
use Carbon\Carbon;
use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Pincodes;
use App\Models\Wishlist;
use App\Models\CouponUse;
use App\Models\CategoryTax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductVariantCombinationImage;
use App\Models\{ProductVariantCombination, VariantValue, Coupon, ProductGraphics};

class CartController extends Controller
{

    public function index(Request $request)
    {
        try {
            if (!empty($request->checkoutFrom)) { 
                if (auth()->check()) {
                    $cartData = Cart::where('user_id', auth()->user()->id)->select('product_id', 'quantity')->get()->toArray();
                } else {

                    $cartData = session()->get('cartData', []);
                }

                moveCartSessionDataToCheckoutSessionData($cartData, $request->checkoutFrom);

                return redirect()->route('front-product.checkoutBag');
            }

            $cartData = getCartData();
            if (count($cartData) == 0) {
                return redirect()->route('front-home.index')->with('error', 'Cart is empty');
            }
            
            return view('front.modules.cart.index', compact('cartData'));
        } catch (Exception $e) {
            Log::error($e);
            @dd($e);
            return redirect()->back()->with(['error' => 'Something is wrong', 'error_msg' => $e->getMessage()]);
        }
    }


    public function addToCart(Request $request)
    {
        try {

            $userId = Auth::guard('customer')->id();

            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not logged in'
                ], 401);
            }

            $productId = $request->input('product_id');
            $quantity = (int) $request->input('quantity', 1);
            $selectedVariants = $request->input('selected_variants', []);
            $type = $request->input('addType');

            info("----------productId--------", [$productId]);
            info("------quantity------", [$quantity]);
            info("-------selected variants-------", [$selectedVariants]);
            info("-----type-----", [$type]);

            if (!$productId || $quantity < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid data'
                ], 400);
            }

            /*
            |--------------------------------------------------------------------------
            | VARIANT PRODUCT
            |--------------------------------------------------------------------------
            */
            $cartId = $request->input('cart_id');
            if ($type === 'remove') {

                $cart = Cart::where('id', $cartId)
                    ->where('user_id', $userId)
                    ->first();

                info("---------remove cart----------", [
                    'cart_id' => $cartId,
                    'user_id' => $userId,
                    'cart' => $cart
                ]);

                if (!$cart) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cart item not found'
                    ], 404);
                }

                $cart->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Product removed from cart'
                ]);
            }
            if (!empty($selectedVariants) && is_array($selectedVariants)) {

                $variantValueNames = array_values($selectedVariants);

                $nameToId = VariantValue::whereIn('name', $variantValueNames)
                    ->pluck('id', 'name');

                info("-----nameToId-----", [$nameToId]);

                $variantValueIds = collect($variantValueNames)
                    ->map(function ($name) use ($nameToId) {
                        return (int) ($nameToId[$name] ?? 0);
                    })
                    ->filter()
                    ->values()
                    ->toArray();

                info("-----variantValueIds-------", [$variantValueIds]);

                if (empty($variantValueIds)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid variant selected'
                    ], 400);
                }

                $jsonCombo = json_encode($variantValueIds);

                $combination = ProductVariantCombination::where('product_id', $productId)
                    ->where('combination_id', $jsonCombo)
                    ->first();

                info("---------combination--------", [$combination]);

                if (!$combination) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Combination not found'
                    ], 404);
                }

                $alreadyExists = Cart::where('user_id', $userId)
                    ->where('product_id', $productId)
                    ->where(
                        'product_variant_combination_id',
                        $combination->id
                    )
                    ->first();

                info("---------already exists----------", [$alreadyExists]);

                /*
                |--------------------------------------------------------------------------
                | REMOVE
                |--------------------------------------------------------------------------
                */

                if ($type === 'remove') {

                    if ($alreadyExists) {
                        $alreadyExists->delete();
                    }

                    return response()->json([
                        'success' => true,
                        'message' => 'Product removed from cart'
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | ADD / UPDATE
                |--------------------------------------------------------------------------
                */

                if (!$alreadyExists) {

                    Cart::create([
                        'user_id' => $userId,
                        'product_id' => $productId,
                        'product_variant_combination_id' => $combination->id,
                        'quantity' => $quantity,
                    ]);

                } else {

                    $alreadyExists->quantity = $quantity;
                    $alreadyExists->save();
                }
            }

            /*
            |--------------------------------------------------------------------------
            | NORMAL PRODUCT - NO VARIANT
            |--------------------------------------------------------------------------
            */

            else {

                $alreadyExists = Cart::where('user_id', $userId)
                    ->where('product_id', $productId)
                    ->whereNull('product_variant_combination_id')
                    ->first();

                info("---------normal product already exists----------", [$alreadyExists]);

                /*
                |--------------------------------------------------------------------------
                | REMOVE
                |--------------------------------------------------------------------------
                */

                if ($type === 'remove') {

                    if ($alreadyExists) {
                        $alreadyExists->delete();
                    }

                    return response()->json([
                        'success' => true,
                        'message' => 'Product removed from cart'
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | ADD / UPDATE
                |--------------------------------------------------------------------------
                */

                if (!$alreadyExists) {

                    Cart::create([
                        'user_id' => $userId,
                        'product_id' => $productId,
                        'product_variant_combination_id' => null,
                        'quantity' => $quantity,
                    ]);

                } else {

                    $alreadyExists->quantity = $quantity;
                    $alreadyExists->save();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully'
            ]);

        } catch (\Exception $e) {

            Log::error('Add to cart error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error_msg' => $e->getMessage()
            ], 500);
        }
    }

    public function getCartItems(Request $request)
    {
        try {
            $userId = Auth::guard('customer')->id();
            $carts = Cart::where('user_id', $userId)->get();
            $cartItems = [];
            foreach ($carts as $cart) {
                $product = $cart->product;
                //$productVariantCombinationId = $cart->product_variant_combination_id ? $cart->productVariantCombination->product : null;

                $categoryTaxes = CategoryTax::where('category_taxes.category_id', $product->main_category_id ?? 0)
                    ->leftJoin('taxes', 'taxes.id', '=', 'category_taxes.tax_id')
                    ->select(
                        'category_taxes.id',
                        'category_taxes.category_id',
                        'taxes.tax_type',
                        'taxes.tax_option',
                        'taxes.tax_from',
                        'taxes.tax_to',
                        'taxes.tax_rate'
                    )
                    ->get();
                $combination = $cart?->productVariantCombination;
                $combinationIds = []; 
                $selectedVariants = [];
                $image = "";
                if(!empty($combination->combination_id)) {
                    $combinationIds = json_decode($combination->combination_id, true);
                    $img = ProductGraphics::whereIn('variant_id', $combinationIds)->where('product_id', $product?->id)->where('graphic_type', 'image')->where('status', 1)->first();
                    if($img) {
                        $image = $img->graphic;
                    }

                    $variantValue = VariantValue::whereIn('id', $combinationIds)
                    ->get()
                    ->sortBy(function ($item) use ($combinationIds) {
                        return array_search($item->id, $combinationIds);
                    })
                    ->values();
                    foreach ($variantValue as $value) {
                        $variantName = $value->variant->name ?? null;
                        if ($variantName) {
                            $selectedVariants[lcfirst($variantName)] = $value->name;
                        }
                    }
                }
                
                if(empty($image)) {
                    $variantImage = $combination->variant_images->where('product_id', $product->id)->first();                
                    if ($variantImage) {
                        $image = $variantImage?->graphic;
                    } else {
                        $productImages = $product->product_main_images->first();
                        $image = $productImages?->graphic;
                    }
                }                
                
                $tax_option = "inclusive";
                $tax_type = 'flat';
                $tax_price_total = 0;
                $tax_id = "";
                $tax_rate = 0;
                $finalTax = 0;
                if (count($categoryTaxes) > 0) {
                    foreach ($categoryTaxes as $taxKey => $tax) {
                        $tax_type = $tax->tax_type;
                        $taxprice = 0;
                        $tax_price = 0;
                        if ($tax->tax_type == "flat") {
                            $tax_id = $tax->id;
                            $tax_rate = $tax->tax_rate;
                            $tax_option = $tax->tax_option;
                            if ($tax_option == "inclusive") {
                                $taxprice = 1 + ($tax_rate / 100);
                                $tax_price = ($tax->tax_rate > 0 || strToLower($tax->tax_rate) != "no tax") ? ($combination->selling_price / $taxprice) : 0;
                                $tax_price = $combination->selling_price - $tax_price;
                            } else {
                                $tax_price = ($tax->tax_rate > 0 || strToLower($tax->tax_rate) != "no tax") ? (($combination->selling_price * $tax->tax_rate) / 100) : 0;
                            }
                        } else if ($tax->tax_type == "floating") {
                            if (((int)$combination->selling_price >= (int)$tax->tax_from) && ((int)$combination->selling_price <= (int)$tax->tax_to)) {
                                $tax_id = $tax->id;
                                $tax_rate = $tax->tax_rate;
                                $tax_option = $tax->tax_option;
                                $rate = ($tax->tax_rate > 0 || strToLower($tax->tax_rate) != "no tax") ? $tax->tax_rate : 0;
                                if ($tax_option == "inclusive") {
                                    $taxprice = 1 + ($rate / 100);
                                    $tax_price = ($rate > 0) ? ($combination->selling_price / $taxprice) : 0;
                                    $tax_price = $combination->selling_price - $tax_price;
                                    break; // exit loop once match is found
                                } else {
                                    $tax_price = ($rate > 0) ? (($combination->selling_price * $rate) / 100) : 0;
                                    break; // exit loop once match is found
                                }
                            }
                        }
                    }
                }
                $finalTax = $tax_price * $cart->quantity;
                $cartItems[] = [
                    'randomId'          => 'prod_' . time() . '_' . rand(1000, 9999),
                    'productId'         => $product->id,
                    'name'              => $product->name,
                    'sku'               => $product->sku,
                    'quantity'          => $cart->quantity,
                    'price'             => $combination->price,
                    'sellingPrice'      => $combination->selling_price,
                    'discountType'      => $combination->discount_type,
                    'discountAmount'    => $combination->discount,
                    'image'             => Config('constant.PRODUCT_IMAGE_PATH') . $image,
                    'image2'             => $image,
                    'selectedVariants'  => $selectedVariants,
                    'tax_option'        => $tax_option,
                    'tax_id'            => $tax_id,
                    'tax_rate'          => $tax_rate,
                    'tax_price'         => $finalTax,
                    'tax_type'          => $tax_type,
                    'rawTaxArr'         => e(json_encode($categoryTaxes)),
                ];
            }
            return response()->json([
                'success' => true,
                'cartItems' => $cartItems

            ]);
        } catch (\Exception $e) {
            Log::error('Add to cart error: ' . $e->getMessage());
            return response()->json([
                'success'   => false,
                'message'   => 'An error occurred',
                'error_msg' => $e->getMessage()
            ], 500);
        }
    }

    public function addToWishlist(Request $request)
    {
        try {

            $productId = $request->input('product_id');
            if (!empty($request->action) && $request->action == 'move') {
                Cart::where('user_id', auth()->guard('customer')->user()->id)->where('product_id', $productId)->delete();
            }

            $isProductAddedInWishlistAlready = Wishlist::where('user_id', auth()->guard('customer')->user()->id)->where('product_id', $productId)->first();
            if (empty($isProductAddedInWishlistAlready)) {
                $obj   = new Wishlist;
                $obj->user_id = auth()->guard('customer')->user()->id;
                $obj->product_id = $productId;
                $obj->save();
            }


            if ($request->ajax()) {
                $count = Wishlist::where('user_id', auth()->guard('customer')->user()->id)->count();
                return response()->json(['success' => true, 'data' => ['count' => $count]]);
            } else {

                return Redirect()->back()->with(['success' => 'Product has been added to wishlist successfully']);
            }
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->back()->with(['error' => 'Something is wrong', 'error_msg' => $e->getMessage()]);
        }
    }

    public function removeFromWishlist(Request $request)
    {
        try {
            $productId = $request->input('product_id');

            Wishlist::where('user_id', auth()->guard('customer')->user()->id)->where('product_id', $productId)->delete();

            if ($request->ajax()) {
                $count = Wishlist::where('user_id', auth()->guard('customer')->user()->id)->count();
                return response()->json(['success' => true, 'data' => ['count' => $count]]);
            } else {
                return redirect()->route('front-user.wishlist')->with('success', 'Product removed from wishlist successfully');
            }
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->back()->with(['error' => 'Something is wrong', 'error_msg' => $e->getMessage()]);
        }
    }

    public function checkDelevery(Request $request)
    {
        // try {
        $pincode = $request->pincode;
        $checkpincode = Pincodes::where('pincode', $pincode)->first();
        if ($checkpincode->delivery == 1) {
            $message = "<span style='color:green; font-weight:600;'> Great news! Delivery is available to your area.</span>";
        } elseif ($checkpincode->delivery == 2) {
            $message = "<span style='color:red; font-weight:600;'> Sorry, we currently do not deliver to this location.</span>";
        } elseif ($checkpincode->delivery == 3) {
            $extracharge = $checkpincode->extra_charge ? ' of ₹' . $checkpincode->extra_charge : '';
            $message = "<span style='color:orange; font-weight:600;'> Delivery available with an extra charge{$extracharge}.</span>";
        } else {
            $message = "<span style='color:#555;'> Delivery not available for this pincode.</span>";
        }
        return response()->json([
            'success' => true,
            'data' => [
                'extra_charge' => $checkpincode->extra_charge,
                'message' => $message,
            ]
        ]);

        // } catch (Exception $e) {
        //     Log::error($e);
        //     return redirect()->back()->with(['error' => 'Something is wrong', 'error_msg' => $e->getMessage()]);
        // }
    }

    public function applyCoupon(Request $request)
    {
        $userId =  Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null;

        $request->validate([
            'coupon_code' => 'required|string',
            'cart_total'  => 'required|numeric',
            'cart_items'  => 'required|array',
        ]);

        $coupon = Coupon::where('coupon_code', $request->coupon_code)
            ->where('is_active', 1)
            ->where(function ($query) {
                $query->where('end_date', '>=', Carbon::now())
                    ->orwhereNull('end_date');
            })
            ->where(function ($query) use ($userId) {
                $query->where('start_date', '<=', Carbon::now())
                    ->orwhereNull('start_date');
            })->with('coupon_user')
            ->first();
        if (!$coupon) {
            return response()->json(['status' => false, 'message' => 'Invalid or inactive coupon.']);
        }
        $checkCategory = $coupon->category_id;
        $checkSubCat   = !empty($coupon->sub_categories) ? array_filter(json_decode($coupon->sub_categories, true)) : null;
        if ($checkCategory || $checkSubCat) {
            $productIds = collect($request->cart_items)
                ->pluck('productId')
                ->unique()
                ->values()
                ->toArray();

            $products = Product::whereIn('id', $productIds)
                // ->where('is_public', 1)
                ->where('draf', 0)
                ->get(['id', 'main_category_id', 'main_sub_category_id']);

            if ($products->isEmpty()) {
                return response()->json(['status' => false, 'message' => 'Cart contains no valid public products.']);
            }

            $productCategoryIds    = $products->pluck('main_category_id')->unique()->toArray();
            $productSubCategoryIds = $products->pluck('main_sub_category_id')->unique()->toArray();

            // Category check
            if ($checkCategory && !in_array($coupon->category_id, $productCategoryIds)) {
                return response()->json(['status' => false, 'message' => 'Coupon is not valid for the selected product category.']);
            }

            // Subcategory check           
            if ($checkSubCat) {
                $allowedSubCats = is_array($coupon->sub_categories)
                    ? $coupon->sub_categories
                    : array_filter(json_decode($coupon->sub_categories, true));

                $intersect = array_intersect($allowedSubCats, $productSubCategoryIds);

                if (empty($intersect)) {
                    return response()->json(['status' => false, 'message' => 'Coupon is not valid for the selected subcategories.']);
                }
            }
        }

        if ($coupon->user_type && $coupon->user_type !== 'all' && $userId) {
            if (!$userId) {
                return response()->json(['status' => false, 'message' => 'Login required to apply this coupon.']);
            }

            $userOrderCount = Order::where('user_id', $userId)->count();

            if ($coupon->user_type === 'new' && $userOrderCount > 0) {
                return response()->json(['status' => false, 'message' => 'This coupon is only valid for new users.']);
            }

            if ($coupon->user_type === 'existing' && $userOrderCount === 0) {
                return response()->json(['status' => false, 'message' => 'This coupon is only valid for existing users.']);
            }
        }
        if ($userId && $coupon->per_user_avalibity > 0) {
            $perUserUsed = CouponUse::where('user_id', $userId)->where('coupon_id', $coupon->id)->count();
            if ($coupon->per_user_avalibity <= $perUserUsed) {
                return response()->json(['status' => false, 'message' => 'Coupon limit reached.']);
            }
        }
        // Date validity check
        $today = now()->toDateString();
        $formattedDate = Carbon::parse($coupon->start_date)->format('Y-m-d');
        $formattedEnd = Carbon::parse($coupon->end_date)->format('Y-m-d');
        if ($coupon->start_date && $formattedDate > $today) {
            return response()->json(['status' => false, 'message' => 'Coupon not started yet.']);
        }

        if ($coupon->end_date && $formattedEnd < $today) {
            return response()->json(['status' => false, 'message' => 'Coupon expired.']);
        }

        // Limit check
        if (!$coupon->is_unlimited && $coupon->available_coupons <= 0) {
            return response()->json(['status' => false, 'message' => 'Coupon limit reached.']);
        }

        // Private user check
        if ($coupon->coupon_type === 'private') {
            if (!$userId) {
                return response()->json(['status' => false, 'message' => 'Login required to apply this coupon.']);
            }
            $couponUsers = $coupon?->coupon_user?->pluck('user_id')?->toArray() ?? [];
            if (!in_array($userId, $couponUsers)) {
                return response()->json(['status' => false, 'message' => 'You are not allowed to use this private coupon.']);
            }
            /* if (!$request->user_id || !$coupon->customers->contains($request->user_id)) {
                return response()->json(['status' => false, 'message' => 'You are not allowed to use this coupon.']);
            } */
        }


        // Min cart value check
        if ($coupon->min_cart_value && $request->cart_total < $coupon->min_cart_value) {
            return response()->json(['status' => false, 'message' => 'Cart total is below the minimum required for this coupon.']);
        }

        // Discount calculation
        $discount = 0;
        $discount = $coupon->discount_type === 'flat'
            ? $coupon->discount_value
            : ($request->cart_total * $coupon->discount_value) / 100;

        // Apply min and max caps (if set)
        if ($coupon->max_discount) {
            $discount = min($discount, $coupon->max_discount);
        }
        if ($coupon->min_discount) {
            $discount = max($discount, $coupon->min_discount);
        }




        return response()->json([
            'status'   => true,
            'message'  => 'Coupon applied successfully.',
            'discount' => round($discount, 2),
            'coupon'   => $coupon->only('id', 'coupon_code', 'discount_type', 'discount_value'),
        ]);
    }


    public function getCoupon(Request $request)
    {
        $cartItems = $request->cart_items;

        $userId =  Auth::guard('customer')->check() ? Auth::guard('customer')->id() : '';
        if (!$cartItems || !is_array($cartItems)) {
            return response()->json(['status' => false, 'message' => 'Cart information required.']);
        }

        // Step 1: Gather Product Info
        $productIds = collect($cartItems)->pluck('productId')->unique()->toArray();
        $products = Product::whereIn('id', $productIds)->get();

        $categoryIds = $products->pluck('main_category_id')->unique()->toArray();
        $subCategoryIds = $products->pluck('main_sub_category_id')->unique()->toArray();
        $today = now()->toDateString();
        // Step 2: Fetch Active Coupons with Valid Date Range
        $coupons = Coupon::where('is_active', 1)
            ->where(function ($q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })
            ->where(function ($q) use ($userId) {
                if ($userId) {
                    $q->whereDoesntHave('customers')
                        ->orWhereHas('customers', function ($subQ) use ($userId) {
                            $subQ->where('users.id', $userId);
                        });
                } else {
                    $q->where('coupon_type', 'public');
                }
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })
            ->get()
            ->filter(function ($coupon) use ($categoryIds, $subCategoryIds) {
                if ($coupon->category_id && !in_array($coupon->category_id, $categoryIds)) {
                    return false;
                }

                if (!empty($coupon->sub_categories)) {
                    $allowed = is_array($coupon->sub_categories)
                        ? $coupon->sub_categories
                        : json_decode($coupon->sub_categories, true);
                    if (!array_intersect($allowed, $subCategoryIds)) {
                        return false;
                    }
                }

                // Add more user-based filtering if needed (user_id, user_type, etc.)

                return true;
            })->values();

        if ($coupons->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'No applicable coupons found.']);
        }

        $cartTotal = collect($cartItems)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        // Step 4: Transform coupons
        $transformedCoupons = $coupons->map(function ($coupon) use ($cartTotal) {
            $isApplicable = true;
            $reason = '';

            if ($coupon->min_order_amount && $cartTotal < $coupon->min_order_amount) {
                $isApplicable = false;
                $reason = 'Minimum order amount ₹' . $coupon->min_order_amount . ' not met.';
            }

            $discountAmount = 0;
            if ($coupon->discount_type === 'flat') {
                $discountAmount = $coupon->discount_value;
            } elseif ($coupon->discount_type === 'percentage') {
                $discountAmount = ($cartTotal * $coupon->discount_value) / 100;
                if ($coupon->max_discount) {
                    $discountAmount = min($discountAmount, $coupon->max_discount);
                }
            }

            $description = '';

            if (!$coupon->min_discount && !$coupon->max_discount) {
                if ($coupon->discount_type === 'flat') {
                    $description = 'Get Rs. ' . $coupon->discount_value . ' off';
                } elseif ($coupon->discount_type === 'percentage') {
                    $description = 'Get' . $coupon->discount_value . '% off';
                }
            }


            if ($coupon->min_discount && $coupon->max_discount) {
                /* $description .= 'Between Rs.' . $coupon->max_discount . ' and Rs. ' . $coupon->min_discount; */
                $description .= 'Get Rs.' . $coupon->min_discount . ' to Rs. ' . $coupon->max_discount . ' off ';
            } elseif ($coupon->max_discount) {
                $description .= ' Up to Rs.' . $coupon->max_discount;
            } elseif ($coupon->min_discount) {
                $description .= 'Minimum Rs.' . $coupon->min_discount;
            }



            if ($coupon->min_cart_value) {
                $description .= ' on minimum purchase of Rs. ' . $coupon->min_cart_value;
                if ($cartTotal < $coupon->min_cart_value) {
                    $isApplicable = false;
                    $difference = $coupon->min_cart_value - $cartTotal;
                    $reason = 'Shop for Rs. ' . $difference . ' more to apply';
                }
            }

            if ($coupon->user_type === 'new') {
                $description .= '(Only for new users)';
            }

            if ($coupon->end_date) {
                $description .= '</br> Expires on: ' . \Carbon\Carbon::parse($coupon->end_date)->format('jS F Y');
            }
            $per_user_avalibity = '';
            if ($coupon->per_user_avalibity) {
                $per_user_avalibitys = $coupon->per_user_avalibity;
                $per_user_avalibity .= $per_user_avalibitys;
            }


            return [
                'code'            => $coupon->coupon_code,
                'name'            => $coupon->name,
                'description'            => $coupon->description,
                'discount_type'   => $coupon->discount_type,
                'discount_value'  => $coupon->discount_value,
                'discount_amount' => round($discountAmount),
                'description'     => $description ?? 'Discount available',
                'is_applicable'   => $isApplicable,
                'reason'          => $reason,
                'per_user_avalibity' => $per_user_avalibity,
            ];
        })->values();

        return response()->json([
            'status'  => true,
            'message' => 'Valid coupons fetched.',
            'coupons' => $transformedCoupons
        ]);
    }
}

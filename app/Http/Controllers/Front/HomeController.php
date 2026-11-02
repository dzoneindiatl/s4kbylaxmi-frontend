<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\SizeChartManager;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category; 
use App\Models\PriceDrop; 
use App\Models\Variant; 
use App\Models\VariantValue; 
use App\Models\ProductGraphics; 
use App\Models\Attribute; 
Use App\Models\CategoryAttribute; 
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;
use App\Models\RecentlyViewed; 
use App\Models\ProductDetailManager;
use App\Models\Setting; 
use App\Models\Coupon; 
use App\Models\ProductVariant; 
use App\Models\ProductVariantCombination;
use App\Models\CategoryTax;  
use App\Models\Country;
use App\Models\State;
use App\Models\City;
class HomeController extends Controller
{
    public function index(){
        return view('front.home.index'); 
    }

    public function getStates(Request $request)
    {
        $validated = $request->validate([
            'countryId' => ['required', 'integer', 'exists:countries,id'],
        ]);

        return response()->json(State::where('country_id', $validated['countryId'])
            ->where('is_active', 1)
            ->orderBy('name')
            ->get(['id', 'name']));
    }

    public function getCities(Request $request)
    {
        $validated = $request->validate([
            'countryId' => ['required', 'integer', 'exists:countries,id'],
            'stateId' => ['required', 'integer', 'exists:states,id'],
        ]);

        $state = State::where('id', $validated['stateId'])
            ->where('country_id', $validated['countryId'])
            ->where('is_active', 1)
            ->first();
        if (!$state) {
            return response()->json([
                'message' => 'The selected state does not belong to this country.',
            ], 422);
        }

        return response()->json(City::where('country_id', $validated['countryId'])
            ->where('state_id', $state->id)
            ->where('is_active', 1)
            ->orderBy('name')
            ->get(['id', 'name']));
    }

    public function productListing(Request $request, $path)
    {   
        $segments = array_values(array_filter(explode('/', $path)));
        $category = null;
        $subCategory = null;
        $subChildCategory = null;
        
        if(count($segments) === 1){
            $category = Category::select('id','parent_id','slug','name')->whereNull('parent_id')->where('slug', $segments[0])->firstOrFail();
        }
        elseif (count($segments) === 2) {
            $category = Category::select('id','parent_id','slug','name')->whereNull('parent_id')->where('slug', $segments[0])->firstOrFail();
            $subCategory = Category::select('id','parent_id','slug','name')->where('parent_id', $category->id)->where('slug', $segments[1])->firstOrFail();

        } 
        elseif (count($segments) === 3) {
            $category = Category::select('id','parent_id','slug','name')->whereNull('parent_id')->where('slug', $segments[0])->firstOrFail();
            $subCategory = Category::select('id','parent_id','slug','name')->where('parent_id', $category->id)->where('slug', $segments[1])->firstOrFail();
            $subChildCategory = Category::select('id','parent_id','slug','name')->where('parent_id', $subCategory->id)->where('slug', $segments[2])->firstOrFail();

        } else {
            abort(404);
        }
        $isWishlisteddata = [];
        $parent = '';
        $grandParent = '';
        $today = now()->toDateString();
        $priceDrop = PriceDrop::whereDate('start_date', '<=', $today)->whereDate('end_date', '>=', $today)->where('is_deleted',0)->latest()->first();
        
        $DB = Product::select('id','name','slug','buying_price','selling_price','discount','discount_type','sku')->where('is_deleted', 0)
            ->where('is_active', "1")->orderBy('id','DESC'); 

        $hasCategoryFilter = $request->filled('category_id') || $request->filled('sub_category_id') ||  $request->filled('sub_child_category_id');
        
        if (!$hasCategoryFilter) {
            if (count($segments) === 1) {
                $DB->where('main_category_id', $category->id)->orWhere('main_collection_id',$category->id);
                $categoriesData = Category::where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->where('parent_id', $category->id)
                    ->get();
            }
            elseif (count($segments) === 2) {

                $DB->where(function ($q) use ($subCategory) {
                    $q->where('main_sub_category_id', $subCategory->id)
                        ->orWhere(function ($query) use ($subCategory) {
                            $query->whereNotNull('sub_category_id')
                                ->whereRaw('JSON_VALID(sub_category_id)')
                                ->whereJsonContains('sub_category_id', (string) $subCategory->id);
                        });
                });

                $categoriesData = Category::where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->where('parent_id', $subCategory->id)
                    ->get();
            }
            elseif (count($segments) === 3) {
                $DB->where(function ($q) use ($subChildCategory) {
                    $q->where('main_child_category_id', $subChildCategory->id)
                        ->orWhere(function ($query) use ($subChildCategory) {
                            $query->whereNotNull('child_category_id')
                                ->whereRaw('JSON_VALID(child_category_id)')
                                ->whereJsonContains('child_category_id', (string) $subChildCategory->id);
                        });
                });
                 $categoriesData = collect();
            }
        } else {
            $categoriesData = collect();
        } 
        $categoryIds = (array) $request->input('category_id', []);
        if (!empty($categoryIds)) {
            $DB->whereIn('main_category_id', $categoryIds);
        }

        $subCategoryIds = (array) $request->input('sub_category_id', []);
        if (!empty($subCategoryIds)) {
            $DB->where(function ($q) use ($subCategoryIds) {
                $q->whereIn('main_sub_category_id',$subCategoryIds);
                foreach ($subCategoryIds as $id) {
                    $q->orWhereJsonContains('sub_category_id',(string) $id);
                }
            });
        }

        $subChildCategoryIds = (array) $request->input('sub_child_category_id',[]);
        if (!empty($subChildCategoryIds)) {
            $DB->where(function ($q) use ($subChildCategoryIds) {
                $q->whereIn('main_child_category_id',$subChildCategoryIds);
                foreach ($subChildCategoryIds as $id) {
                    $q->orWhereJsonContains('child_category_id',(string) $id);
                }
            });
        }

        $selectedVariantValuesColor = (array) $request->input('variantValuesColor',[]);
        if (!empty($selectedVariantValuesColor)) {
            $DB->whereHas('getProductVariantValue',function ($query) use ($selectedVariantValuesColor) {
                    $query->whereIn('variant_value_id',$selectedVariantValuesColor);
                }
            );
        }

        if ($request->filled('price_range')) {
            $range = explode('-',$request->input('price_range'));
            if (count($range) === 2) {
                $min = (int) $range[0];
                $max = (int) $range[1];
                if ($min <= $max) {
                    $DB->whereBetween('selling_price',[$min, $max]);
                }
            }
        }

        switch ($request->input('sortBy')) {
            case 'new_arrivals':
                $DB->where('is_new_arrivals', 1);
                break;

            case 'best_seller':
                $DB->where('best_seller', 1);
                break;

            case 'featured':
                $DB->where('is_featured', 1);
                break;

            case 'trending':
                $DB->where('trending', 1);
                break;
            case 'low_high':
                $DB->orderBy('selling_price','asc');
                break;

            case 'high_low':
                $DB->orderBy('selling_price','desc');
                break;

            default:
                $DB->orderBy('product_order','asc');
                break;
        }

        $offset = (int) $request->input('offset', 0);
        $limit = (int) $request->input('limit',config('Reading.records_per_page'));
        $totalResults = (clone $DB)->count();
        $results = $DB->offset($offset)->limit($limit)->get();
        $hasMore = ($offset + $results->count()) < $totalResults;       
        if ($category->parent_id !== null) {
            $parent = Category::find($category->parent_id);
            $grandParent = $parent ? Category::find($parent->parent_id): null;
            $catAttributeIds = CategoryAttribute::where( 'category_id', $category->parent_id);
            if ($grandParent) {
                $catAttributeIds->orWhere('category_id',$grandParent->id);
            }
            $catAttributeIds = $catAttributeIds->pluck('attribute_id');
        } else {
            $catAttributeIds = CategoryAttribute::where('category_id',$category->id)->pluck('attribute_id');
        }

        $attributes = Attribute::whereIn('id',$catAttributeIds)->where('is_active', 1)->where('is_deleted', 0)->get();
        $AllMainCategory = Category::select('id','name','slug','parent_id')->whereNull('parent_id')->where('is_active', 1)->where('is_deleted', 0)->get();
        $allSubCategory = Category::select('id','name','slug','parent_id')->where('is_active', 1)->where('is_deleted', 0)->whereIn('parent_id',$AllMainCategory->pluck('id'))->get();
        $allChildCategory = Category::select('id','name','slug','parent_id')->where('is_active',1)->where('is_deleted',0)->whereIn('parent_id',$allSubCategory->pluck('id'))->get(); 
    
        $variants = Variant::where('is_active', 1)
            ->where('is_deleted', 0)
            ->get();
        $variantColor = VariantValue::whereIn('variant_id',$variants->pluck('id'))->get();
        $results->each(function ($product) {

            $product->primary_variant_value = null;

            $colorVariants = [];
            $sizeVariants = [];

            foreach ($product->productVariants as $productVariant) {
                $variantName = strtolower($productVariant->variant->name ?? '');
                foreach ($productVariant->variantValues as $variantValue) {
                    if ($variantName === 'color') {
                        $colorVariants[] = [
                            'name' => $variantValue->variant_value->name ?? null,
                            'color_code' => $variantValue->variant_value->color_code ?? null,
                        ];
                    }
                    if ($variantName === 'size') {
                        $sizeVariants[] = $variantValue->variant_value->name ?? null;
                    }
                    if ((int) $variantValue->is_main === 1) {
                        $product->primary_variant_value = $variantValue;
                    }
                }
            }

            $product->color_variants = $colorVariants;
            $product->size_variants = $sizeVariants;
        });
        $user = Auth::guard('customer')->user();
        if ($user) {
            $isWishlisteddata =Wishlist::where('user_id',$user->id)->pluck('product_id')->toArray();
        }
        if ($request->ajax()) {
            return response()->json([
                'html' => view('front.home.product_list',compact('results','isWishlisteddata','totalResults','category','grandParent','parent','categoriesData','variants','attributes','limit','allSubCategory','AllMainCategory','variantColor','path','priceDrop'))->render(),
                'totalResults' => $totalResults,
                'hasMore' => $hasMore,
                'nextOffset' => $offset + $results->count(),
            ]);
        } 
        return view('front.home.product_list',compact('results','categoriesData','totalResults','variants','attributes','limit','category','isWishlisteddata','parent','grandParent','AllMainCategory','allSubCategory','variantColor','hasMore','allChildCategory','path','priceDrop'));
    }

    public function productDetail(Request $request, $product, $title, $sku){
        
        $isWishlisted = null;
        $isWishlisteddata = [];
        $productcat = '';
        $productSubCat = '';
        $productChildCat = '';
        $user = Auth::guard('customer')->user();
        $product = Product::where('sku', $sku)->first();
        $resolvedBySlug = false;
        if (!$product) {
            $productSlug = preg_replace('/\.html$/i', '', (string) $title);
            $product = Product::where('slug', $productSlug)->first();
            $resolvedBySlug = (bool) $product;
        }
        if ($product && ((int) $product->is_active !== 1 || (int) ($product->is_deleted ?? 0) === 1)) {
            $product = null;
        }
        abort_if(!$product, 404);
        if ($resolvedBySlug && $product->sku) {
            return redirect()->route('front-product.detail', [
                'product' => 'product',
                'title' => $product->slug . '.html',
                'sku' => $product->sku,
            ]);
        }
        if ($user) {
            RecentlyViewed::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'product_id' => $product->id
                ],
                [
                    'updated_at' => now()
                ]
            );
        } else {
            $recent = session()->get('recently_viewed', []);
            if (($key = array_search($product->id, $recent)) !== false) {
                unset($recent[$key]);
            }
            array_unshift($recent, $product->id);
            $recent = array_slice($recent, 0, 50);
            session(['recently_viewed' => $recent]);
        }
        $today = now()->toDateString();
        $priceDrop = PriceDrop::whereDate('start_date', '<=', $today)->whereDate('end_date', '>=', $today)->where('is_deleted',0)->latest()->first();

        $productcat = Category::whereIn('id', array_filter([
            $product->main_category_id,
            $product->main_collection_id,
        ]))->first();
        $sizeChartData = $productcat?->size_chart_id
            ? SizeChartManager::with(['sizes', 'sections.measurements.values'])
                ->find($productcat->size_chart_id)
            : null;
        $productDetailIds = array_filter(explode(',', (string) ($productcat->product_detail_manager ?? '')));
        $productDetailManager = $productDetailIds
            ? ProductDetailManager::whereIn('id', $productDetailIds)
                ->select('id', 'section_name', 'content', 'order')
                ->orderBy('order', 'asc')
                ->get()
            : collect();
        $productSubCat = Category::where('id', $product->main_sub_category_id)->first(); 
        
        $productChildCat = Category::where('id', $product->main_child_category_id)->first();
        
        $productvariants = ProductVariant::with([
            'variant:id,name,type',
            'variantValues.variant_value:id,name,color_code',
            'variantValues.first_image' => function ($q) use ($product) {
                $q->where('product_id', $product->id);
            }
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
        // @dd($productvariants);
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
                'products.sku',
                'products.short_description',
                'product_graphics.graphic as image'
            )
            ->groupBy('products.id')
            ->get();

        $productVarientCom = ProductVariantCombination::where('product_id', $product->id)->get();
        $bestproduct = Product::where('best_seller', 1)->where('is_active', "1")->where("is_deleted",  0)->where(['best_seller' => true, 'draf' => false])->orderBy('id', 'desc')->get();
        $releatedProduct = Product::with(['productVariants.variantValues.first_image'])->where('is_active', "1")->whereIn('id', [$product->related_products])->get();
        
        $returnexchangeProduct = Setting::where('id', 28)->first();
        $contactDetails = Setting::whereIn('key', ['Contact.contact_email', 'Contact.contact_number', 'Contact.whatsapp_number'])->get();
        $productCategoryId = Product::where('id', $product->id ?? 0)->pluck('main_category_id')->first();
        $categoryTaxes = CategoryTax::where('category_taxes.category_id', $productCategoryId ?? 0)
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
            ->get()->toArray();

        $recentlyViewedProducts = collect();

        if ($user) {

            $recentlyViewedProducts = Product::with(['productVariants.variantValues.first_image'])->where('is_active', 1)->whereIn(
                'id',
                RecentlyViewed::where('user_id', $user->id)
                    ->orderBy('updated_at', 'desc')
                    ->pluck('product_id')
            )->where('id', '!=', $product->id)->get();

        } else {

            $recentIds = session()->get('recently_viewed', []);

            $recentlyViewedProducts = Product::with(['productVariants.variantValues.first_image'])->where('is_active', 1)->whereIn('id', $recentIds)->where('id', '!=', $product->id)->get();
        } 

        if ($user) {
            $isWishlisted = Wishlist::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->exists();

            $isWishlisteddata = Wishlist::where('user_id', $user->id)
                ->pluck('product_id')
                ->toArray();
        }
     
        $best_seller_products = Product::where('best_seller', 1)->where('is_active', "1")->orderBy('id', 'desc')->get();
        $couponOnDetail = Coupon::where('show_on_detail',1)->where('is_active',1)->first();         
        $facebook = Setting::select('id','value')->where('key','Social.facebook')->first();
        $instagram = Setting::select('id','value')->where('key','Social.instagram')->first(); 
        $pinterst = Setting::select('id','value')->where('key','Social.pinterest')->first(); 
        $youtube = Setting::select('id','value')->where('key','Social.youtube')->first(); 
        $twitter = Setting::select('id','value')->where('key','Social.twitter')->first();        
            
        // return $product; 
        // 'reviews', 'productreview',

        return view('front.home.product_detail', compact('product','productChildCat', 'productcat', 'productSubCat', 'productvariants', 'related_products', 'bestproduct', 'releatedProduct', 'returnexchangeProduct', 'contactDetails', 'productVarientCom', 'isWishlisted', 'isWishlisteddata', 'categoryTaxes',  'recentlyViewedProducts','facebook','instagram','pinterst','youtube','twitter','productDetailManager', 'best_seller_products','priceDrop','couponOnDetail','sizeChartData'));

    }

    public function viewBag(){
        return view('front.home.cart'); 
    }

    public function variantCombinationPrices(Request $request)
    {
        try {
            $data = $request->json()->all();
            $productId = $data['product_id'] ?? null;
            $skuIds    = $data['vsku'] ?? null;
            $variantId = $data['variant_id'] ?? null;

            if (!$productId || !is_array($skuIds) || count($skuIds) === 0) {
                return response()->json(['error' => 'Missing required data'], 422);
            }

            $skuIds = array_values(array_unique(array_filter(
                array_map('intval', $skuIds),
                fn ($id) => $id > 0
            )));
            if (count($skuIds) === 0) {
                return response()->json(['error' => 'Missing required variant data'], 422);
            }

            
            $today = now()->toDateString();
            $priceDrop = PriceDrop::whereDate('start_date', '<=', $today)->whereDate('end_date', '>=', $today)->where('is_deleted',0)->latest()->first();

            
            $combinationsQuery = ProductVariantCombination::where('product_id', $productId);
            foreach ($skuIds as $skuId) {
                $combinationsQuery->whereJsonContains('combination_id', $skuId);
            }

            $requestedIds = $skuIds;
            sort($requestedIds, SORT_NUMERIC);
            $combination = $combinationsQuery
                ->get(['combination_id', 'price', 'selling_price', 'sku', 'discount', 'discount_type', 'qty'])
                ->first(function ($candidate) use ($requestedIds) {
                    $combinationIds = is_array($candidate->combination_id)
                        ? $candidate->combination_id
                        : json_decode($candidate->combination_id, true);

                    if (!is_array($combinationIds)) {
                        return false;
                    }

                    $combinationIds = array_map('intval', $combinationIds);
                    sort($combinationIds, SORT_NUMERIC);

                    return $combinationIds === $requestedIds;
                });

            if (!$combination) {
                return response()->json(['error' => 'Combination not found'], 404);
            }

            $mainPrice = $combination->selling_price;
            if(isset($priceDrop) && !empty($priceDrop)){
                $priceDropProductIds = $priceDrop->product_id;
                $discountAmount = $priceDrop->amount;
                if($priceDrop->gain_type == "drop"){
                    if($priceDropProductIds == "all"){
                        if($priceDrop->drop_type == "percentage"){
                            $mainPrice =$combination->selling_price - (($discountAmount/100) * $combination->selling_price) ; 
                        }
                        if($priceDrop->drop_type == "flat"){
                            $mainPrice = $combination->selling_price - $discountAmount ; 
                        }
                    }
                    else{
                        $productIdArray = array_map('trim', explode(",", $priceDropProductIds));
                        if(in_array((string) $productId, $productIdArray, true)){
                            if ($priceDrop->drop_type == "percentage") {
                                $mainPrice = $combination->selling_price - (($priceDrop->amount / 100) * $combination->selling_price);
                            } 
                            if ($priceDrop->drop_type == "flat") {
                                $mainPrice = $combination->selling_price - $priceDrop->amount;
                            }
                        }
                    }
                }
                if($priceDrop->gain_type == "gain"){
                    if($priceDropProductIds == "all"){
                        if($priceDrop->drop_type == "percentage"){
                            $mainPrice = $combination->selling_price + (($discountAmount/100) * $combination->selling_price) ; 
                        }
                        if($priceDrop->drop_type == "flat"){
                            $mainPrice = $combination->selling_price + $discountAmount ; 
                        }
                    }
                    else{
                        $productIdArray = array_map('trim', explode(",", $priceDropProductIds));
                        if(in_array((string) $productId, $productIdArray, true)){
                            if ($priceDrop->drop_type == "percentage") {
                                $mainPrice = $combination->selling_price + (($priceDrop->amount / 100) * $combination->selling_price);
                            } 
                            if ($priceDrop->drop_type == "flat") {
                                $mainPrice = $combination->selling_price + $priceDrop->amount;
                            }
                        }
                    }
                }    
            }
            $combination->selling_price = $mainPrice;

            $variantValueId = $data['variant_value_id'] ?? null;
            $images = ProductGraphics::where('product_id', $productId)
            ->where('variant_id', $variantValueId ?: $variantId)
            ->where('status', 1)
            ->where('graphic_type', 'image')
            ->orderBy('is_front', 'desc')   // front first
            ->orderBy('is_back', 'desc')    // back second
            ->orderBy('updated_at', 'asc')  // optional: stable order for rest
            ->get(['graphic', 'graphic_type', 'is_front', 'is_back']);

            return response()->json([
                'combination'   => $combination,
                'images'        => $images->map(fn($img) => [
                    'graphic'      => asset('uploads/products/' . $img->graphic),
                    'graphic_type' => $img->graphic_type,
                    'is_front'     => $img->is_front,
                    'is_back'      => $img->is_back,
                ]),
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }

    public function variantStockCheck(Request $request)
    {
        $variantValueIds = $request->input('vsku');
        if (!is_array($variantValueIds) || count($variantValueIds) === 0) {
            $variantValueIds = [$request->input('variant_value_id')];
        }

        $variantValueIds = array_values(array_unique(array_filter(
            array_map('intval', $variantValueIds),
            fn ($id) => $id > 0
        )));

        if (!$request->input('product_id') || count($variantValueIds) === 0) {
            return response()->json(['error' => 'Missing product or variant data'], 422);
        }

        $combinationsQuery = ProductVariantCombination::where('product_id', $request->input('product_id'));
        foreach ($variantValueIds as $variantValueId) {
            $combinationsQuery->whereJsonContains('combination_id', $variantValueId);
        }

        $requestedIds = $variantValueIds;
        sort($requestedIds, SORT_NUMERIC);
        $combination = $combinationsQuery->get(['combination_id', 'qty', 'is_out_of_stock'])
            ->first(function ($candidate) use ($requestedIds) {
                $combinationIds = is_array($candidate->combination_id)
                    ? $candidate->combination_id
                    : json_decode($candidate->combination_id, true);

                if (!is_array($combinationIds)) {
                    return false;
                }

                $combinationIds = array_map('intval', $combinationIds);
                sort($combinationIds, SORT_NUMERIC);

                return $combinationIds === $requestedIds;
            });

        return response()->json([
            'out_of_stock' => !$combination || (int) $combination->qty <= 0 || (int) $combination->is_out_of_stock === 1,
            'qty' => $combination ? (int) $combination->qty : 0,
        ]);
    }
}

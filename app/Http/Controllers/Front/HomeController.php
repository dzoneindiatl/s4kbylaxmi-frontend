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
use App\Models\AttributeValue;
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
use Illuminate\Support\Facades\DB;

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
        
        $DB = Product::select('id','name','slug','buying_price','selling_price','discount','discount_type','sku','product_type','qty','min_selling_units','max_selling_units')->where('is_deleted', 0)
            ->where('is_active', "1");

        $categorySelectionProvided = $request->has('category_id')
            || $request->has('sub_category_id')
            || $request->has('sub_child_category_id');
        $selectedCategoryId = $categorySelectionProvided
            ? (int) $request->input('category_id', 0)
            : (int) $category->id;
        $selectedSubCategoryId = $categorySelectionProvided
            ? (int) $request->input('sub_category_id', 0)
            : (int) ($subCategory->id ?? 0);
        $selectedChildCategoryId = $categorySelectionProvided
            ? (int) $request->input('sub_child_category_id', 0)
            : (int) ($subChildCategory->id ?? 0);

        $filterCategories = Category::whereNull('parent_id')
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->orderBy('category_order')
            ->get(['id', 'parent_id', 'name', 'slug']);
        $allSubCategory = Category::whereIn('parent_id', $filterCategories->pluck('id'))
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->orderBy('category_order')
            ->get(['id', 'parent_id', 'name', 'slug']);
        $allChildCategory = Category::whereIn('parent_id', $allSubCategory->pluck('id'))
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->orderBy('category_order')
            ->get(['id', 'parent_id', 'name', 'slug']);

        if ($selectedSubCategoryId
            && !$allSubCategory->contains(function ($item) use ($selectedSubCategoryId, $selectedCategoryId) {
                return (int) $item->id === $selectedSubCategoryId
                    && (int) $item->parent_id === $selectedCategoryId;
            })) {
            $selectedSubCategoryId = 0;
        }
        if ($selectedChildCategoryId
            && !$allChildCategory->contains(function ($item) use ($selectedChildCategoryId, $selectedSubCategoryId) {
                return (int) $item->id === $selectedChildCategoryId
                    && (int) $item->parent_id === $selectedSubCategoryId;
            })) {
            $selectedChildCategoryId = 0;
        }
        if (!$selectedCategoryId || !$filterCategories->contains('id', $selectedCategoryId)) {
            $selectedCategoryId = 0;
            $selectedSubCategoryId = 0;
            $selectedChildCategoryId = 0;
        }

        $categoriesData = $selectedSubCategoryId
            ? $allChildCategory->where('parent_id', $selectedSubCategoryId)->values()
            : $allSubCategory->where('parent_id', $selectedCategoryId)->values();

        if ($selectedCategoryId) {
            $DB->where(function ($query) use ($selectedCategoryId) {
                $query->where('main_category_id', $selectedCategoryId)
                    ->orWhere('main_collection_id', $selectedCategoryId);
            });
        }
        if ($selectedSubCategoryId) {
            $DB->where(function ($query) use ($selectedSubCategoryId) {
                $query->where('main_sub_category_id', $selectedSubCategoryId)
                    ->orWhere(function ($jsonQuery) use ($selectedSubCategoryId) {
                        $jsonQuery->whereNotNull('sub_category_id')
                            ->whereRaw('JSON_VALID(sub_category_id)')
                        ->where(function ($valueQuery) use ($selectedSubCategoryId) {
                            $valueQuery->whereJsonContains('sub_category_id', $selectedSubCategoryId)
                                ->orWhereJsonContains('sub_category_id', (string) $selectedSubCategoryId);
                        });
                    });
            });
        }
        if ($selectedChildCategoryId) {
            $DB->where(function ($query) use ($selectedChildCategoryId) {
                $query->where('main_child_category_id', $selectedChildCategoryId)
                    ->orWhere(function ($jsonQuery) use ($selectedChildCategoryId) {
                        $jsonQuery->whereNotNull('child_category_id')
                            ->whereRaw('JSON_VALID(child_category_id)')
                        ->where(function ($valueQuery) use ($selectedChildCategoryId) {
                            $valueQuery->whereJsonContains('child_category_id', $selectedChildCategoryId)
                                ->orWhereJsonContains('child_category_id', (string) $selectedChildCategoryId);
                        });
                    });
            });
        }

        $priceFloor = 0;
        $priceCeiling = max(0, (int) ceil((float) Product::where('is_deleted', 0)
            ->where('is_active', '1')
            ->max('selling_price')));
        $selectedMinPrice = max($priceFloor, min($priceCeiling, (int) $request->input('price_min', $priceFloor)));
        $selectedMaxPrice = max($priceFloor, min($priceCeiling, (int) $request->input('price_max', $priceCeiling)));
        if ($selectedMinPrice > $selectedMaxPrice) {
            $selectedMinPrice = $selectedMaxPrice;
        }

        $requestedColorIds = array_values(array_filter(array_map('intval', (array) $request->input('variantValuesColor', [])), function ($id) {
            return $id > 0;
        }));
        $requestedSizeIds = array_values(array_filter(array_map('intval', (array) $request->input('variantValuesSize', [])), function ($id) {
            return $id > 0;
        }));
        $colorVariantIds = Variant::where('is_active', 1)
            ->where('is_deleted', 0)
            ->whereIn(DB::raw('LOWER(name)'), ['color', 'colour'])
            ->pluck('id');
        $sizeVariantIds = Variant::where('is_active', 1)
            ->where('is_deleted', 0)
            ->whereRaw('LOWER(name) = ?', ['size'])
            ->pluck('id');
        $usedVariantValueIds = DB::table('product_variant_values')
            ->join('products', 'products.id', '=', 'product_variant_values.product_id')
            ->where('products.is_deleted', 0)
            ->where('products.is_active', '1')
            ->distinct()
            ->pluck('product_variant_values.variant_value_id')
            ->map(function ($id) {
                return (int) $id;
            });
        $variantColor = VariantValue::whereIn('variant_id', $colorVariantIds)
            ->whereIn('id', $usedVariantValueIds)
            ->orderBy('name')
            ->get();
        $variantSize = VariantValue::whereIn('variant_id', $sizeVariantIds)
            ->whereIn('id', $usedVariantValueIds)
            ->orderBy('name')
            ->get();
        $selectedColorIds = array_values(array_intersect(
            $requestedColorIds,
            $variantColor->pluck('id')->map(function ($id) {
                return (int) $id;
            })->all()
        ));
        $selectedSizeIds = array_values(array_intersect(
            $requestedSizeIds,
            $variantSize->pluck('id')->map(function ($id) {
                return (int) $id;
            })->all()
        ));
        if ($selectedColorIds) {
            $DB->whereHas('getProductVariantValue', function ($query) use ($selectedColorIds) {
                $query->whereIn('variant_value_id', $selectedColorIds);
            });
        }
        if ($selectedSizeIds) {
            $DB->whereHas('getProductVariantValue', function ($query) use ($selectedSizeIds) {
                $query->whereIn('variant_value_id', $selectedSizeIds);
            });
        }

        $requestedAttributeValueIds = array_values(array_filter(array_map('intval', (array) $request->input('attribute_value_id', [])), function ($id) {
            return $id > 0;
        }));
        $usedAttributeValueIds = DB::table('product_attributes')
            ->join('products', 'products.id', '=', 'product_attributes.product_id')
            ->where('products.is_deleted', 0)
            ->where('products.is_active', '1')
            ->distinct()
            ->pluck('product_attributes.attribute_value_id');
        $usedAttributeValueIds = AttributeValue::whereIn('id', $usedAttributeValueIds)
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->pluck('id')
            ->map(function ($id) {
                return (int) $id;
            });
        $selectedAttributeValueIds = array_values(array_intersect(
            $requestedAttributeValueIds,
            $usedAttributeValueIds->all()
        ));
        $selectedAttributeValues = AttributeValue::whereIn('id', $selectedAttributeValueIds)
            ->get(['id', 'attribute_id'])
            ->groupBy('attribute_id');
        foreach ($selectedAttributeValues as $attributeValues) {
            $attributeValueIds = $attributeValues->pluck('id')->all();
            $DB->whereHas('productAttributes', function ($query) use ($attributeValueIds) {
                $query->whereIn('attribute_value_id', $attributeValueIds);
            });
        }

        if ($request->has('price_min') || $request->has('price_max')) {
            $DB->whereBetween('selling_price', [$selectedMinPrice, $selectedMaxPrice]);
        } elseif ($request->filled('price_range')) {
            $range = explode('-', $request->input('price_range'));
            if (count($range) === 2 && is_numeric($range[0]) && is_numeric($range[1]) && (int) $range[0] <= (int) $range[1]) {
                $DB->whereBetween('selling_price', [(int) $range[0], (int) $range[1]]);
            }
        }

        switch ($request->input('sortBy')) {
            case 'new_arrivals':
                $DB->where('is_new_arrivals', 1);
                $DB->orderByDesc('created_at');
                break;

            case 'newest':
                $DB->orderByDesc('created_at');
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
        $DB->orderByDesc('id');

        $offset = (int) $request->input('offset', 0);
        $limit = (int) $request->input('limit',config('Reading.records_per_page'));
        $totalResults = (clone $DB)->count();
        $results = $DB->offset($offset)->limit($limit)->get();
        $variantProductIds = $results
            ->filter(function ($product) {
                return (int) $product->product_type !== 1;
            })
            ->pluck('id');
        $availableVariantStocks = $variantProductIds->isNotEmpty()
            ? ProductVariantCombination::whereIn('product_id', $variantProductIds)
                ->where('qty', '>', 0)
                ->where(function ($query) {
                    $query->whereNull('is_out_of_stock')
                        ->orWhere('is_out_of_stock', '!=', 1);
                })
                ->get(['product_id', 'qty'])
                ->groupBy('product_id')
            : collect();
        $results->each(function ($product) use ($availableVariantStocks) {
            $minimum = max((int) ($product->min_selling_units ?? 1), 1);
            $configuredMaximum = max((int) ($product->max_selling_units ?? 0), 0);
            $canSellMinimum = $configuredMaximum === 0 || $configuredMaximum >= $minimum;

            if ((int) $product->product_type === 1) {
                $product->isOutOfStock = !$canSellMinimum || (int) $product->qty < $minimum;
                return;
            }

            $product->isOutOfStock = !$canSellMinimum
                || !$availableVariantStocks->get($product->id, collect())->contains(function ($combination) use ($minimum) {
                    return (int) $combination->qty >= $minimum;
                });
        });
        $hasMore = ($offset + $results->count()) < $totalResults;       
        $parent = $subCategory ?: '';
        $grandParent = $category;
        $attributes = Attribute::with(['attribute_values' => function ($query) use ($usedAttributeValueIds) {
            $query->whereIn('id', $usedAttributeValueIds)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->orderBy('name');
        }])
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->orderBy('name')
            ->get();
        $variants = Variant::where('is_active', 1)
            ->where('is_deleted', 0)
            ->get();
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
                'html' => view('front.home.product_list',compact('results','isWishlisteddata','totalResults','category','grandParent','parent','categoriesData','variants','attributes','limit','filterCategories','allSubCategory','allChildCategory','variantColor','variantSize','path','priceDrop','priceFloor','priceCeiling','selectedMinPrice','selectedMaxPrice','selectedCategoryId','selectedSubCategoryId','selectedChildCategoryId','selectedColorIds','selectedSizeIds','selectedAttributeValueIds'))->render(),
                'totalResults' => $totalResults,
                'hasMore' => $hasMore,
                'nextOffset' => $offset + $results->count(),
            ]);
        } 
        return view('front.home.product_list',compact('results','categoriesData','totalResults','variants','attributes','limit','category','isWishlisteddata','parent','grandParent','filterCategories','allSubCategory','allChildCategory','variantColor','variantSize','hasMore','path','priceDrop','priceFloor','priceCeiling','selectedMinPrice','selectedMaxPrice','selectedCategoryId','selectedSubCategoryId','selectedChildCategoryId','selectedColorIds','selectedSizeIds','selectedAttributeValueIds'));
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
        $relatedProductIds = json_decode((string) $product->related_products, true);
        if (!is_array($relatedProductIds)) {
            $relatedProductIds = explode(',', (string) $product->related_products);
        }
        $relatedProductIds = collect($relatedProductIds)
            ->map(fn ($id) => is_numeric($id) ? (int) $id : null)
            ->filter(fn ($id) => $id && $id !== (int) $product->id)
            ->unique()
            ->values();
        $relatedProductsById = Product::with(['product_main_images' => function ($query) {
            $query->where('status', 1)
                ->where('graphic_type', 'image')
                ->orderByDesc('is_front')
                ->orderBy('id');
        }])
            ->whereIn('id', $relatedProductIds)
            ->where('is_active', '1')
            ->where('is_deleted', 0)
            ->get(['id', 'name', 'slug', 'sku', 'selling_price'])
            ->keyBy('id');
        $relatedProducts = $relatedProductIds
            ->map(fn ($id) => $relatedProductsById->get($id))
            ->filter()
            ->values();

        $productVarientCom = ProductVariantCombination::where('product_id', $product->id)->get();
        $bestproduct = Product::where('best_seller', 1)->where('is_active', "1")->where("is_deleted",  0)->where(['best_seller' => true, 'draf' => false])->orderBy('id', 'desc')->get();
        
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

        return view('front.home.product_detail', compact('product','productChildCat', 'productcat', 'productSubCat', 'productvariants', 'relatedProducts', 'bestproduct', 'returnexchangeProduct', 'contactDetails', 'productVarientCom', 'isWishlisted', 'isWishlisteddata', 'categoryTaxes',  'recentlyViewedProducts','facebook','instagram','pinterst','youtube','twitter','productDetailManager', 'best_seller_products','priceDrop','couponOnDetail','sizeChartData'));

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
                ->get(['combination_id', 'price', 'selling_price', 'sku', 'discount', 'discount_type', 'qty', 'is_out_of_stock'])
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

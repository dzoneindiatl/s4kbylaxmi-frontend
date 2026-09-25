<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,100..700,0,0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <title>S4kByLaxmi</title>
</head>
<body>
    @include('front.includes.header')
        <div id="flash-msg" class="alert alert-info d-none"></div>
        <section class="product-detail pt-30">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ url('/') }}">Home</a>
                <span>/</span>
                @if($productcat->category_type_id == 1)
                    <a href="{{ route('category.show',['path'=>$productcat->slug]) }}">{{ $productcat->name }}</a>
                @else    
                    <a href="{{ route('category.show',['path'=>$productcat->slug]) }}">{{ $productcat->name }}</a> 
                    @if(!empty($productSubCat))  
                        <span>/</span><a href="{{ route('category.show',['path'=>$productcat->slug.'/'.$productSubCat->slug]) }}">{{ $productSubCat->name }}</a>
                    @endif
                    @if(!empty($productChildCat))        
                        <span>/</span> <a href="{{ route('category.show',['path'=>$productcat->slug.'/'.$productSubCat->slug.'/'.$productChildCat->slug]) }}">{{ $productChildCat->name }}</a> 
                    @endif 
                @endif      
                <span>/</span>
                <strong>{{ $product->name }}</strong>
            </div>


            <div class="product-wrapper">
                <div class="product-gallery">
                    <button class="zoom-btn">
                        <span class="material-symbols-outlined">
                            zoom_in
                        </span>
                    </button>
                    <!-- LEFT -->
                     @php 
                        $discount = ""; 
                        if(isset($product->discount) && !empty($discount)){
                            if(isset($product->discount_type) && !empty($discount_type)){
                                if($product->discount_type == 'percentage'){
                                    $discount = $product->discount."% OFF" ; 
                                }
                                if($product->discount_type == 'flat'){
                                    $discount = "Rs. ".$product->discount. "OFF";  
                                }
                            }
                        }
                    @endphp 
                    @if(!empty($discount))
                    <div class="image-top-badges">
                        <span class="offer-badge">{{ $discount }}</span>                        
                        {{-- <span class="best-badge">★ Best Seller</span> --}}
                    </div>
                    @endif 
                    <div class="gallery-wrapper">
                        @php
                            $primaryVariantId = \App\Models\ProductVariantValue::where('product_id', $product->id)->where('is_main', 1)->value('variant_value_id');
                            $allProductImages = $product->product_main_images;
                              $sortedProductImages = $allProductImages
                                ->filter(function ($img) use ($primaryVariantId) {
                                    return (string) $img->variant_id === (string) $primaryVariantId;
                                })
                                ->sortByDesc(function ($img) {
                                    return (int) $img->is_front;
                                })
                                ->values();
                        @endphp
                        <div class="gallery-thumb">
                            @foreach($sortedProductImages as $index => $img)
                                <div class="thumb @if($index == 0)is-nav-selected @endif">
                                    <img src="{{ asset('uploads/products/'.$img->graphic) }}">
                                </div>
                            @endforeach
                        </div>

                        <div class="gallery-main">
                            @foreach($sortedProductImages as $img)
                                <div class="gallery-cell">
                                    <a data-fancybox="product-gallery"
                                    href="{{ asset('uploads/products/'.$img->graphic) }}">
                                        <img src="{{ asset('uploads/products/'.$img->graphic) }}">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @php
                    if ($product->product_type == 1) {
                        $firstImage = getActiveFrontImg($product->id, $product->id);
                        $secondImage = getActiveBackImg($product->id, $product->id);
                    } else {
                        $activeVarientId = activeVarientByProductId($product->id);
                        $firstImage = getActiveFrontImg($product->id, $activeVarientId);
                        $secondImage = getActiveBackImg($product->id, $activeVarientId);
                    }
                                    
                    if ($product->product_type == 2) {
                        $priceData = getPriceByActiveVarientId($product->id, $activeVarientId);
                        $buying_price = $priceData['buying_price'];
                        $selling_price = $priceData['selling_price'];
                        $discount_product = $buying_price - $selling_price;
                        $productImages = getActiveVarientImg($product->id, $activeVarientId);
                    } else {
                        $buying_price = $product->buying_price;
                        $selling_price = $product->selling_price;
                        $discount_product = $buying_price - $selling_price;
                        $productImages = getActiveVarientImg($product->id, $product->id);
                    }
                                    
                    $discountText = '';
                                    
                    if (!empty($product->discount) && !empty($product->discount_type)) {
                        if ($product->discount_type == 'percentage') {
                            $discountText = $product->discount . '% Off';
                        }
                        if ($product->discount_type == 'flat') {
                            $discountText = '₹' . $product->discount . 'Off';
                        }
                    }
                                    
                @endphp
                <div class="product-details">
                    <h1>{{ $product->name }}</h1>
                    <p class="subtitle">Soft rayon fabric | Elegant everyday wear</p>

                    {{-- <div class="rating">
                        ★★★★★
                        <strong>
                            4.7
                        </strong>
                        <span>
                            (568 Reviews)
                        </span>
                    </div> --}}
                    @php 
                        if(isset($priceDrop) && !empty($priceDrop)){
                            $productId = $priceDrop->product_id;
                            $discountAmount = $priceDrop->amount;
                            if($priceDrop->gain_type == "drop"){
                                if($productId == "all"){
                                    if($priceDrop->drop_type == "percentage"){
                                        $mainPrice =$selling_price - (($discountAmount/100) * $selling_price) ; 
                                    }
                                    if($priceDrop->drop_type == "flat"){
                                        $mainPrice = $selling_price - $discountAmount ; 
                                    }
                                }
                                else{
                                    $productIdArray = explode(",",$productId); 
                                    if(in_array($product->id,$productIdArray)){
                                        if ($priceDrop->drop_type == "percentage") {
                                            $mainPrice = $selling_price - (($priceDrop->amount / 100) * $selling_price);
                                        } elseif ($priceDrop->drop_type == "flat") {
                                            $mainPrice = $selling_price - $priceDrop->amount;
                                        }
                                    }
                                }
                            }
                            if($priceDrop->gain_type == "gain"){
                                if($productId == "all"){
                                    if($priceDrop->drop_type == "percentage"){
                                        $mainPrice = $selling_price + (($discountAmount/100) * $selling_price) ; 
                                    }
                                    if($priceDrop->drop_type == "flat"){
                                        $mainPrice = $selling_price + $discountAmount ; 
                                    }
                                }
                                else{
                                    $productIdArray = explode(",",$productId); 
                                    if(in_array($product->id,$productIdArray)){
                                        if ($priceDrop->drop_type == "percentage") {
                                            $mainPrice = $selling_price + (($priceDrop->amount / 100) * $selling_price);
                                        } elseif ($priceDrop->drop_type == "flat") {
                                            $mainPrice = $selling_price + $priceDrop->amount;
                                        }
                                    }
                                }
                            }    
                        }
                    @endphp
                    <div class="price-box">
                        <h2 id="productPrice"> ₹ @if(isset($priceDrop) && !empty($priceDrop) && $mainPrice > 0) {{ $mainPrice }} @else {{ floor($selling_price) }} @endif</h2>

                        <del id="pdpStrikedMrp">₹ {{ $buying_price }}</del>
                        <span class="save" id="discountShow">
                           ₹ {{ $product->buying_price - $product->selling_price}}
                        </span>
                    </div>

                    {{-- <div class="comfort">
                        <span class="material-symbols-outlined">favorite</span> Loved for comfort,fit & premium look
                    </div> --}}

                    <hr class="divider">
                    <div class="product-attributes">
                        @if (!empty($productvariants))
                            @foreach ($productvariants as $variant)
                                @php
                                    $variantValues = $variant['variant_values'] ?? [];
                                    $hasMain = collect($variantValues)->where('is_main', 1)->isNotEmpty();
                                    $displayType = (int) ($variant['variant_type'] ?? 0);
                                @endphp

                                @if (count($variantValues))
                                    <div class="product-option">
                                        <div class="option-title">
                                            <h5>{{ $variant['variant_name'] }}</h5>
                                        </div>

                                        {{-- TYPE 1 : ONLY ROUND --}}
                                        @if ($displayType === 1)
                                            <div class="colors variant-round">
                                                @foreach ($variantValues as $k => $variantValue)
                                                    @php
                                                        $isActive = ($hasMain && (int) $variantValue['is_main'] === 1) || (!$hasMain && $k === 0);
                                                        $colorCode = $variantValue['color_code'] ?? '#ddd';
                                                    @endphp

                                                    <label>
                                                        <input
                                                            type="radio"
                                                            name="variant_{{ $variant['variant_id'] ?? $variant['variant_name'] }}"
                                                            value="{{ $variantValue['variant_value_id'] }}"
                                                            class="attribute-input"
                                                            {{ $isActive ? 'checked' : '' }}>

                                                        <span
                                                            class="s-variant color-variant {{ $isActive ? 'active' : '' }}"
                                                            style="background: {{ $colorCode }};"
                                                            data-product-id="{{ $variant['product_id'] ?? $product->id }}"
                                                            data-id="{{ $variantValue['id'] }}"
                                                            data-variant-id="{{ $variant['variant_id'] ?? '' }}"
                                                            data-type="{{ $variant['variant_name'] }}"
                                                            data-value="{{ $variantValue['name'] }}"
                                                            data-vid="{{ $variantValue['variant_value_id'] }}"
                                                            data-value-id="{{ $variantValue['variant_value_id'] }}"
                                                            title="{{ $variantValue['name'] }}"
                                                            onclick="selectVariant(this);checkVariantStock(this);">
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @elseif ($displayType === 2)
                                            <div class="sizes variant-box">
                                                @foreach ($variantValues as $k => $variantValue)
                                                    @php
                                                        $isActive = ($hasMain && (int) $variantValue['is_main'] === 1) || (!$hasMain && $k === 0);
                                                    @endphp

                                                    <label>
                                                        <input
                                                            type="radio"
                                                            name="variant_{{ $variant['variant_id'] ?? $variant['variant_name'] }}"
                                                            value="{{ $variantValue['variant_value_id'] }}"
                                                            class="attribute-input"
                                                            {{ $isActive ? 'checked' : '' }}>

                                                        <span
                                                            class="s-variant {{ $isActive ? 'active' : '' }}"
                                                            data-product-id="{{ $variant['product_id'] ?? $product->id }}"
                                                            data-id="{{ $variantValue['id'] }}"
                                                            data-variant-id="{{ $variant['variant_id'] ?? '' }}"
                                                            data-type="{{ $variant['variant_name'] }}"
                                                            data-value="{{ $variantValue['name'] }}"
                                                            data-vid="{{ $variantValue['variant_value_id'] }}"
                                                            data-value-id="{{ $variantValue['variant_value_id'] }}"
                                                            onclick="selectVariant(this);checkVariantStock(this);">
                                                            {{ $variantValue['name'] }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>

                                        {{-- TYPE 3 : ROUND WITH IMAGE --}}
                                        @elseif ($displayType === 3)
                                            <div class="colors variant-round variant-with-image">
                                                @foreach ($variantValues as $k => $variantValue)
                                                    @php
                                                        $isActive = ($hasMain && (int) $variantValue['is_main'] === 1) || (!$hasMain && $k === 0);
                                                        $image = $variantValue['image'] ?? null;
                                                    @endphp

                                                    <label>
                                                        <input
                                                            type="radio"
                                                            name="variant_{{ $variant['variant_id'] ?? $variant['variant_name'] }}"
                                                            value="{{ $variantValue['variant_value_id'] }}"
                                                            class="attribute-input"
                                                            {{ $isActive ? 'checked' : '' }}>

                                                        <span
                                                            class="s-variant {{ $isActive ? 'active' : '' }}"
                                                            data-product-id="{{ $variant['product_id'] ?? $product->id }}"
                                                            data-id="{{ $variantValue['id'] }}"
                                                            data-variant-id="{{ $variant['variant_id'] ?? '' }}"
                                                            data-type="{{ $variant['variant_name'] }}"
                                                            data-value="{{ $variantValue['name'] }}"
                                                            data-vid="{{ $variantValue['variant_value_id'] }}"
                                                            data-value-id="{{ $variantValue['variant_value_id'] }}"
                                                            onclick="selectVariant(this);checkVariantStock(this);">

                                                            @if ($image)
                                                                <img src="{{ asset('uploads/products/' . $image) }}" alt="{{ $variantValue['name'] }}">
                                                            @else
                                                                {{ $variantValue['name'] }}
                                                            @endif
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>

                                        {{-- TYPE 4 : ROUND WITH COLOR --}}
                                        @elseif ($displayType === 4)
                                            <div class="colors variant-round">
                                                @foreach ($variantValues as $k => $variantValue)
                                                    @php
                                                        $isActive = ($hasMain && (int) $variantValue['is_main'] === 1) || (!$hasMain && $k === 0);
                                                        $colorCode = $variantValue['color_code'] ?? '#ddd';
                                                    @endphp

                                                    <label>
                                                        <input
                                                            type="radio"
                                                            name="variant_{{ $variant['variant_id'] ?? $variant['variant_name'] }}"
                                                            value="{{ $variantValue['variant_value_id'] }}"
                                                            class="attribute-input"
                                                            {{ $isActive ? 'checked' : '' }}>

                                                        <span
                                                            class="s-variant color-variant {{ $isActive ? 'active' : '' }}"
                                                            style="background: {{ $colorCode }};"
                                                            data-product-id="{{ $variant['product_id'] ?? $product->id }}"
                                                            data-id="{{ $variantValue['id'] }}"
                                                            data-variant-id="{{ $variant['variant_id'] ?? '' }}"
                                                            data-type="{{ $variant['variant_name'] }}"
                                                            data-value="{{ $variantValue['name'] }}"
                                                            data-vid="{{ $variantValue['variant_value_id'] }}"
                                                            data-value-id="{{ $variantValue['variant_value_id'] }}"
                                                            title="{{ $variantValue['name'] }}"
                                                            onclick="selectVariant(this);checkVariantStock(this);">
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>

                                        {{-- TYPE 5 : BOX WITH COLOR --}}
                                        @elseif ($displayType === 5)
                                            <div class="sizes variant-box-color">
                                                @foreach ($variantValues as $k => $variantValue)
                                                    @php
                                                        $isActive = ($hasMain && (int) $variantValue['is_main'] === 1) || (!$hasMain && $k === 0);
                                                        $colorCode = $variantValue['color_code'] ?? '#ddd';
                                                    @endphp

                                                    <label>
                                                        <input
                                                            type="radio"
                                                            name="variant_{{ $variant['variant_id'] ?? $variant['variant_name'] }}"
                                                            value="{{ $variantValue['variant_value_id'] }}"
                                                            class="attribute-input"
                                                            {{ $isActive ? 'checked' : '' }}>

                                                        <span
                                                            class="s-variant {{ $isActive ? 'active' : '' }}"
                                                            style="background: {{ $colorCode }};"
                                                            data-product-id="{{ $variant['product_id'] ?? $product->id }}"
                                                            data-id="{{ $variantValue['id'] }}"
                                                            data-variant-id="{{ $variant['variant_id'] ?? '' }}"
                                                            data-type="{{ $variant['variant_name'] }}"
                                                            data-value="{{ $variantValue['name'] }}"
                                                            data-vid="{{ $variantValue['variant_value_id'] }}"
                                                            data-value-id="{{ $variantValue['variant_value_id'] }}"
                                                            onclick="selectVariant(this);checkVariantStock(this);">
                                                            {{ $variantValue['name'] }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>

                                        {{-- TYPE 6 : BOX WITH IMAGE --}}
                                        @elseif ($displayType === 6)
                                            <div class="sizes variant-box-image">
                                                @foreach ($variantValues as $k => $variantValue)
                                                    @php
                                                        $isActive = ($hasMain && (int) $variantValue['is_main'] === 1) || (!$hasMain && $k === 0);
                                                        $image = $variantValue['image'] ?? null;
                                                    @endphp

                                                    <label>
                                                        <input
                                                            type="radio"
                                                            name="variant_{{ $variant['variant_id'] ?? $variant['variant_name'] }}"
                                                            value="{{ $variantValue['variant_value_id'] }}"
                                                            class="attribute-input"
                                                            {{ $isActive ? 'checked' : '' }}>

                                                        <span
                                                            class="s-variant {{ $isActive ? 'active' : '' }}"
                                                            data-product-id="{{ $variant['product_id'] ?? $product->id }}"
                                                            data-id="{{ $variantValue['id'] }}"
                                                            data-variant-id="{{ $variant['variant_id'] ?? '' }}"
                                                            data-type="{{ $variant['variant_name'] }}"
                                                            data-value="{{ $variantValue['name'] }}"
                                                            data-vid="{{ $variantValue['variant_value_id'] }}"
                                                            data-value-id="{{ $variantValue['variant_value_id'] }}"
                                                            onclick="selectVariant(this);checkVariantStock(this);">

                                                            @if ($image)
                                                                <img src="{{ asset('uploads/products/' . $image) }}" alt="{{ $variantValue['name'] }}">
                                                            @else
                                                                {{ $variantValue['name'] }}
                                                            @endif
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>

                                        {{-- TYPE 7 : ONLY RECTANGLE --}}
                                        @elseif ($displayType === 7)
                                            <div class="sizes variant-rectangle">
                                                @foreach ($variantValues as $k => $variantValue)
                                                    @php
                                                        $isActive = ($hasMain && (int) $variantValue['is_main'] === 1) || (!$hasMain && $k === 0);
                                                    @endphp

                                                    <label>
                                                        <input
                                                            type="radio"
                                                            name="variant_{{ $variant['variant_id'] ?? $variant['variant_name'] }}"
                                                            value="{{ $variantValue['variant_value_id'] }}"
                                                            class="attribute-input"
                                                            {{ $isActive ? 'checked' : '' }}>

                                                        <span
                                                            class="s-variant {{ $isActive ? 'active' : '' }}"
                                                            data-product-id="{{ $variant['product_id'] ?? $product->id }}"
                                                            data-id="{{ $variantValue['id'] }}"
                                                            data-variant-id="{{ $variant['variant_id'] ?? '' }}"
                                                            data-type="{{ $variant['variant_name'] }}"
                                                            data-value="{{ $variantValue['name'] }}"
                                                            data-vid="{{ $variantValue['variant_value_id'] }}"
                                                            data-value-id="{{ $variantValue['variant_value_id'] }}"
                                                            onclick="selectVariant(this);checkVariantStock(this);">
                                                            {{ $variantValue['name'] }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>

                                        {{-- TYPE 8 : RECTANGLE WITH IMAGE --}}
                                        @elseif ($displayType === 8)
                                            <div class="sizes variant-rectangle-image">
                                                @foreach ($variantValues as $k => $variantValue)
                                                    @php
                                                        $isActive = ($hasMain && (int) $variantValue['is_main'] === 1) || (!$hasMain && $k === 0);
                                                        $image = $variantValue['image'] ?? null;
                                                    @endphp

                                                    <label>
                                                        <input
                                                            type="radio"
                                                            name="variant_{{ $variant['variant_id'] ?? $variant['variant_name'] }}"
                                                            value="{{ $variantValue['variant_value_id'] }}"
                                                            class="attribute-input"
                                                            {{ $isActive ? 'checked' : '' }}>

                                                        <span
                                                            class="s-variant {{ $isActive ? 'active' : '' }}"
                                                            data-product-id="{{ $variant['product_id'] ?? $product->id }}"
                                                            data-id="{{ $variantValue['id'] }}"
                                                            data-variant-id="{{ $variant['variant_id'] ?? '' }}"
                                                            data-type="{{ $variant['variant_name'] }}"
                                                            data-value="{{ $variantValue['name'] }}"
                                                            data-vid="{{ $variantValue['variant_value_id'] }}"
                                                            data-value-id="{{ $variantValue['variant_value_id'] }}"
                                                            onclick="selectVariant(this);checkVariantStock(this);">

                                                            @if ($image)
                                                                <img src="{{ asset('uploads/products/' . $image) }}" alt="{{ $variantValue['name'] }}">
                                                            @else
                                                                {{ $variantValue['name'] }}
                                                            @endif
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>

                                        {{-- TYPE 9 : RECTANGLE WITH COLOR --}}
                                        @elseif ($displayType === 9)
                                            <div class="sizes variant-rectangle-color">
                                                @foreach ($variantValues as $k => $variantValue)
                                                    @php
                                                        $isActive = ($hasMain && (int) $variantValue['is_main'] === 1) || (!$hasMain && $k === 0);
                                                        $colorCode = $variantValue['color_code'] ?? '#ddd';
                                                    @endphp

                                                    <label>
                                                        <input
                                                            type="radio"
                                                            name="variant_{{ $variant['variant_id'] ?? $variant['variant_name'] }}"
                                                            value="{{ $variantValue['variant_value_id'] }}"
                                                            class="attribute-input"
                                                            {{ $isActive ? 'checked' : '' }}>

                                                        <span
                                                            class="s-variant {{ $isActive ? 'active' : '' }}"
                                                            style="background: {{ $colorCode }};"
                                                            data-product-id="{{ $variant['product_id'] ?? $product->id }}"
                                                            data-id="{{ $variantValue['id'] }}"
                                                            data-variant-id="{{ $variant['variant_id'] ?? '' }}"
                                                            data-type="{{ $variant['variant_name'] }}"
                                                            data-value="{{ $variantValue['name'] }}"
                                                            data-vid="{{ $variantValue['variant_value_id'] }}"
                                                            data-value-id="{{ $variantValue['variant_value_id'] }}"
                                                            onclick="selectVariant(this);checkVariantStock(this);">
                                                            {{ $variantValue['name'] }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                    
                    <div class="our-measurement">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#centimeterPreviewModal{{ $sizeChartData->id }}" title="Preview">Measurements</button>
                    </div>

                    <div class="product-option quantity-option">
                        <h5 class="mb-10">
                            Quantity
                        </h5>

                        <div class="quantity">
                            <button class="minus qty-btn">
                                -
                            </button>
                            <input type="text" value="1" id="quantity" class="qty" maxlength="{{ $product->qty }}" max="{{ $product->qty }}">
                            <button class="plus qty-btn">
                                +
                            </button>
                        </div>
                    </div>
                    <div class="delivery-box mb-30">
                        <h5>
                            Check Delivery Availability
                        </h5>
                        <div class="delivery-form">
                            <input type="text" placeholder="Enter Pincode">
                            <button>
                                Check
                            </button>
                        </div>
                    </div>
                                
                    <div class="d-flex gap-10">
                        <button class="product_buynow_btn">
                            <div>
                                <span class="material-symbols-outlined">
                                    electric_bolt
                                </span> Buy Now
                            </div>
                            <p>Get it delivered soon</p>
                        </button>
                        <button class="product_addtocart_btn addToCartBtn addToCartBtnText" data-id="{{ $product->id }}"
                                                            data-name="{{ $product->name }}"
                                                            data-producttype="{{ $product->product_type }}"
                                                            data-sku="{{ $product->sku }}"
                                                            data-price="{{ $buying_price }}"
                                                            data-salePrice="{{ $selling_price }}"
                                                            data-discountType="{{ $product->discount_type }}"
                                                            data-discount="{{ $discount_product }}"
                                                            data-tax-arr="{{ e(json_encode($categoryTaxes)) }}">
                            <div><span class="material-symbols-outlined">local_mall</span>Add To Cart</div>
                            <p>Save for later</p>
                        </button>
                    </div>
                </div>
            </div>


            <!-- FEATURES -->
            <div class="service-strip">
                <div class="service">
                    <span class="material-symbols-outlined">
                        local_shipping
                    </span>
                    <div>
                        <h6>
                            Free Shipping
                        </h6>
                        <p>
                            On orders above ₹599
                        </p>
                    </div>
                </div>

                <div class="service">
                    <span class="material-symbols-outlined">
                        sync
                    </span>
                    <div>
                        <h6>
                            Easy Returns
                        </h6>
                        <p>
                            Hassle-free returns
                        </p>
                    </div>
                </div>

                <div class="service">
                    <span class="material-symbols-outlined">
                        verified_user
                    </span>
                    <div>
                        <h6>
                            Secure Checkout
                        </h6>
                        <p>
                            100% secure payments
                        </p>
                    </div>
                </div>

                <div class="service">
                    <span class="material-symbols-outlined">
                        payments
                    </span>
                    <div>
                        <h6>
                            Cash On Delivery
                        </h6>
                        <p>
                            Available across India
                        </p>
                    </div>
                </div>
            </div>

            <!-- PRODUCT INFO SECTION -->
            <div class="product-bottom">
                <!-- LEFT -->
                <div class="product-left-info">
                    <!-- WHY LOVE IT -->
                    <div class="info-card">
                        <h3>Why You'll Love It</h3>
                        <ul class="love-list">
                            <li>Breathable soft rayon fabric</li>
                            <li>Skin-friendly & lightweight</li>
                            <li>Elegant floral print</li>
                            <li>Flattering fit for all body types</li>
                            <li>Perfect for daily wear & festive casual</li>
                        </ul>
                    </div>

                    <!-- SIZE CHART -->
                    <div class="info-card size-chart-card">
                        <h3>Size Chart</h3>
                        <table class="size-chart">
                            <thead>
                                <tr>
                                    <th>Size</th>
                                    <th>S</th>
                                    <th>M</th>
                                    <th>L</th>
                                    <th>XL</th>
                                    <th>XXL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Bust</td>
                                    <td>36</td>
                                    <td>38</td>
                                    <td>40</td>
                                    <td>42</td>
                                    <td>44</td>
                                </tr>

                                <tr>
                                    <td>Length</td>
                                    <td>42</td>
                                    <td>42.5</td>
                                    <td>43</td>
                                    <td>43.5</td>
                                    <td>44</td>
                                </tr>

                            </tbody>

                        </table>

                        <small>
                            All sizes are in inches. Fit may vary slightly by style.
                        </small>

                    </div>

                    <!-- REVIEW -->
                    <div class="review-card">
                        <div class="review-user">
                            <img src="images/category_image_04.png" alt="">
                            <div>
                                <h5>Priya S.</h5>
                                <div class="stars">
                                    ★★★★★
                                </div>
                            </div>
                        </div>
                        <p>
                            "Super soft fabric and perfect fit! The print is elegant and feels premium. Loved it."
                        </p>
                    </div>
                </div>

                <!-- RIGHT -->
                <div class="product-right-info">
                    <!-- SPECIFICATION -->
                    <div class="info-card">
                        <h3>Product Details</h3>
                        <table class="spec-table">
                            <tr>
                                <td>Fabric</td>
                                <td>Soft Rayon</td>
                            </tr>
                            <tr>
                                <td>Neckline</td>
                                <td>Round Neck</td>
                            </tr>
                            <tr>
                                <td>Sleeve Type</td>
                                <td>3/4th Sleeves</td>
                            </tr>
                            <tr>
                                <td>Occasion</td>
                                <td>Daily Wear, Casual, Festive</td>
                            </tr>
                            <tr>
                                <td>Wash Care</td>
                                <td>Hand Wash / Gentle Machine Wash</td>
                            </tr>
                        </table>
                    </div>

                    <!-- OFFER -->
                    <div class="offer-card">
                        <div>
                            <span>Special Offer</span>
                            <h3>
                                Buy 2 &
                                Get Extra 10% Off
                            </h3>
                        </div>

                        <a href="#">
                            Shop More
                        </a>
                    </div>
                </div>
            </div>



            <!-- MOBILE STICKY -->
            <div class="mobile-cart">
                <button class="buy-now">
                    Buy Now
                </button>
                <button class="add-cart">
                    Add To Cart
                </button>
            </div>
        </div>
    </section>

    <!-- Best Seller -->
    <section class="trending-collection like-list mt-50">
        <div class="container">
            <div class="heading">
                <h2 class="section-title">You May Also Like</h2>
            </div>
            <div class="trending-collection-section you-may-like">
                <div class="collection_section">
                    <!-- One Item -->
                    <div class="product_item">
                        <div class="product_image">
                            <img src="images/collection_image_01.jpg" alt="">

                            <div class="product_hover">
                                <svg class="svg_shape" viewBox="0 0 368 94" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0,94 L0,82
                                            C12,82 18,78 24,70
                                            C32,58 45,48 68,48

                                            C92,48 106,28 126,18
                                            C144,9 162,12 184,18

                                            C206,12 224,9 242,18
                                            C262,28 276,48 300,48

                                            C323,48 336,58 344,70
                                            C350,78 356,82 368,82

                                            L368,94
                                            Z" fill="#f8f3ee" />
                                </svg>
                                <div class="product_colors">
                                    <span style="background:#7A2E43"></span>
                                    <span style="background:#87CEEB"></span>
                                    <span style="background:#E8D5B5"></span>
                                    <span style="background:#7A8F4D"></span>
                                </div>

                                <div class="product_sizes">
                                    <span>S</span>
                                    <span>M</span>
                                    <span>L</span>
                                    <span>XL</span>
                                </div>

                                <a href="#" class="btn_cart">
                                    Add To Cart
                                </a>
                            </div>

                            <!-- new tag -->
                            <span class="product_tag">New</span>

                            <!-- whishlist tag -->
                            <div class="product_tag_wishlist">
                                <span class="material-symbols-outlined">
                                    favorite
                                </span>
                            </div>
                        </div>
                        <div class="product_data">
                            <div class="product_title">
                                <a href=""> Mizoya Green Cotton A Line Dress</a>
                            </div>
                            <div class="product_price"><span class="price_orignal">Rs. 3250</span> <span
                                    class="price_old">4350</span></div>
                        </div>
                    </div>
                    <!-- One Item -->
                    <div class="product_item">
                        <div class="product_image">
                            <img src="images/collection_image_02.jpg" alt="">

                            <div class="product_hover">
                                <svg class="svg_shape" viewBox="0 0 368 94" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0,94 L0,82
                                            C12,82 18,78 24,70
                                            C32,58 45,48 68,48

                                            C92,48 106,28 126,18
                                            C144,9 162,12 184,18

                                            C206,12 224,9 242,18
                                            C262,28 276,48 300,48

                                            C323,48 336,58 344,70
                                            C350,78 356,82 368,82

                                            L368,94
                                            Z" fill="#f8f3ee" />
                                </svg>
                                <div class="product_colors">
                                    <span style="background:#7A2E43"></span>
                                    <span style="background:#87CEEB"></span>
                                    <span style="background:#E8D5B5"></span>
                                    <span style="background:#7A8F4D"></span>
                                </div>

                                <div class="product_sizes">
                                    <span>S</span>
                                    <span>M</span>
                                    <span>L</span>
                                    <span>XL</span>
                                </div>

                                <a href="#" class="btn_cart">
                                    Add To Cart
                                </a>
                            </div>

                            <!-- new tag -->
                            <span class="product_tag">New</span>

                            <!-- whishlist tag -->
                            <div class="product_tag_wishlist">
                                <span class="material-symbols-outlined">
                                    favorite
                                </span>
                            </div>
                        </div>
                        <div class="product_data">
                            <div class="product_title">
                                <a href=""> Mizoya Green Cotton A Line Dress</a>
                            </div>
                            <div class="product_price"><span class="price_orignal">Rs. 3250</span> <span
                                    class="price_old">4350</span></div>
                        </div>
                    </div>
                    <!-- One Item -->
                    <div class="product_item">
                        <div class="product_image">
                            <img src="images/collection_image_03.jpg" alt="">

                            <div class="product_hover">
                                <svg class="svg_shape" viewBox="0 0 368 94" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0,94 L0,82
                                            C12,82 18,78 24,70
                                            C32,58 45,48 68,48

                                            C92,48 106,28 126,18
                                            C144,9 162,12 184,18

                                            C206,12 224,9 242,18
                                            C262,28 276,48 300,48

                                            C323,48 336,58 344,70
                                            C350,78 356,82 368,82

                                            L368,94
                                            Z" fill="#f8f3ee" />
                                </svg>
                                <div class="product_colors">
                                    <span style="background:#7A2E43"></span>
                                    <span style="background:#87CEEB"></span>
                                    <span style="background:#E8D5B5"></span>
                                    <span style="background:#7A8F4D"></span>
                                </div>

                                <div class="product_sizes">
                                    <span>S</span>
                                    <span>M</span>
                                    <span>L</span>
                                    <span>XL</span>
                                </div>

                                <a href="#" class="btn_cart">
                                    Add To Cart
                                </a>
                            </div>

                            <!-- new tag -->
                            <span class="product_tag">New</span>

                            <!-- whishlist tag -->
                            <div class="product_tag_wishlist">
                                <span class="material-symbols-outlined">
                                    favorite
                                </span>
                            </div>
                        </div>
                        <div class="product_data">
                            <div class="product_title">
                                <a href=""> Mizoya Green Cotton A Line Dress</a>
                            </div>
                            <div class="product_price"><span class="price_orignal">Rs. 3250</span> <span
                                    class="price_old">4350</span></div>
                        </div>
                    </div>
                    <!-- One Item -->
                    <div class="product_item">
                        <div class="product_image">
                            <img src="images/collection_image_04.jpg" alt="">

                            <div class="product_hover">
                                <svg class="svg_shape" viewBox="0 0 368 94" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0,94 L0,82
                                            C12,82 18,78 24,70
                                            C32,58 45,48 68,48

                                            C92,48 106,28 126,18
                                            C144,9 162,12 184,18

                                            C206,12 224,9 242,18
                                            C262,28 276,48 300,48

                                            C323,48 336,58 344,70
                                            C350,78 356,82 368,82

                                            L368,94
                                            Z" fill="#f8f3ee" />
                                </svg>
                                <div class="product_colors">
                                    <span style="background:#7A2E43"></span>
                                    <span style="background:#87CEEB"></span>
                                    <span style="background:#E8D5B5"></span>
                                    <span style="background:#7A8F4D"></span>
                                </div>

                                <div class="product_sizes">
                                    <span>S</span>
                                    <span>M</span>
                                    <span>L</span>
                                    <span>XL</span>
                                </div>

                                <a href="#" class="btn_cart">
                                    Add To Cart
                                </a>
                            </div>

                            <!-- new tag -->
                            <span class="product_tag">New</span>

                            <!-- whishlist tag -->
                            <div class="product_tag_wishlist">
                                <span class="material-symbols-outlined">
                                    favorite
                                </span>
                            </div>
                        </div>
                        <div class="product_data">
                            <div class="product_title">
                                <a href=""> Mizoya Green Cotton A Line Dress</a>
                            </div>
                            <div class="product_price"><span class="price_orignal">Rs. 3250</span> <span
                                    class="price_old">4350</span></div>
                        </div>
                    </div>
                    <!-- One Item -->
                    <div class="product_item">
                        <div class="product_image">
                            <img src="images/collection_image_03.jpg" alt="">

                            <div class="product_hover">
                                <svg class="svg_shape" viewBox="0 0 368 94" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0,94 L0,82
                                            C12,82 18,78 24,70
                                            C32,58 45,48 68,48

                                            C92,48 106,28 126,18
                                            C144,9 162,12 184,18

                                            C206,12 224,9 242,18
                                            C262,28 276,48 300,48

                                            C323,48 336,58 344,70
                                            C350,78 356,82 368,82

                                            L368,94
                                            Z" fill="#f8f3ee" />
                                </svg>
                                <div class="product_colors">
                                    <span style="background:#7A2E43"></span>
                                    <span style="background:#87CEEB"></span>
                                    <span style="background:#E8D5B5"></span>
                                    <span style="background:#7A8F4D"></span>
                                </div>

                                <div class="product_sizes">
                                    <span>S</span>
                                    <span>M</span>
                                    <span>L</span>
                                    <span>XL</span>
                                </div>

                                <a href="#" class="btn_cart">
                                    Add To Cart
                                </a>
                            </div>

                            <!-- new tag -->
                            <span class="product_tag">New</span>

                            <!-- whishlist tag -->
                            <div class="product_tag_wishlist">
                                <span class="material-symbols-outlined">
                                    favorite
                                </span>
                            </div>
                        </div>
                        <div class="product_data">
                            <div class="product_title">
                                <a href=""> Mizoya Green Cotton A Line Dress</a>
                            </div>
                            <div class="product_price"><span class="price_orignal">Rs. 3250</span> <span
                                    class="price_old">4350</span></div>
                        </div>
                    </div>
                    <!-- One Item -->
                    <div class="product_item">
                        <div class="product_image">
                            <img src="images/collection_image_03.jpg" alt="">

                            <div class="product_hover">
                                <svg class="svg_shape" viewBox="0 0 368 94" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0,94 L0,82
                                            C12,82 18,78 24,70
                                            C32,58 45,48 68,48

                                            C92,48 106,28 126,18
                                            C144,9 162,12 184,18

                                            C206,12 224,9 242,18
                                            C262,28 276,48 300,48

                                            C323,48 336,58 344,70
                                            C350,78 356,82 368,82

                                            L368,94
                                            Z" fill="#f8f3ee" />
                                </svg>
                                <div class="product_colors">
                                    <span style="background:#7A2E43"></span>
                                    <span style="background:#87CEEB"></span>
                                    <span style="background:#E8D5B5"></span>
                                    <span style="background:#7A8F4D"></span>
                                </div>

                                <div class="product_sizes">
                                    <span>S</span>
                                    <span>M</span>
                                    <span>L</span>
                                    <span>XL</span>
                                </div>

                                <a href="#" class="btn_cart">
                                    Add To Cart
                                </a>
                            </div>

                            <!-- new tag -->
                            <span class="product_tag">New</span>

                            <!-- whishlist tag -->
                            <div class="product_tag_wishlist">
                                <span class="material-symbols-outlined">
                                    favorite
                                </span>
                            </div>
                        </div>
                        <div class="product_data">
                            <div class="product_title">
                                <a href=""> Mizoya Green Cotton A Line Dress</a>
                            </div>
                            <div class="product_price"><span class="price_orignal">Rs. 3250</span> <span
                                    class="price_old">4350</span></div>
                        </div>
                    </div>
                    <!-- One Item -->
                    <div class="product_item">
                        <div class="product_image">
                            <img src="images/collection_image_04.jpg" alt="">

                            <div class="product_hover">
                                <svg class="svg_shape" viewBox="0 0 368 94" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0,94 L0,82
                                            C12,82 18,78 24,70
                                            C32,58 45,48 68,48

                                            C92,48 106,28 126,18
                                            C144,9 162,12 184,18

                                            C206,12 224,9 242,18
                                            C262,28 276,48 300,48

                                            C323,48 336,58 344,70
                                            C350,78 356,82 368,82

                                            L368,94
                                            Z" fill="#f8f3ee" />
                                </svg>
                                <div class="product_colors">
                                    <span style="background:#7A2E43"></span>
                                    <span style="background:#87CEEB"></span>
                                    <span style="background:#E8D5B5"></span>
                                    <span style="background:#7A8F4D"></span>
                                </div>

                                <div class="product_sizes">
                                    <span>S</span>
                                    <span>M</span>
                                    <span>L</span>
                                    <span>XL</span>
                                </div>

                                <a href="#" class="btn_cart">
                                    Add To Cart
                                </a>
                            </div>

                            <!-- new tag -->
                            <span class="product_tag">New</span>

                            <!-- whishlist tag -->
                            <div class="product_tag_wishlist">
                                <span class="material-symbols-outlined">
                                    favorite
                                </span>
                            </div>
                        </div>
                        <div class="product_data">
                            <div class="product_title">
                                <a href=""> Mizoya Green Cotton A Line Dress</a>
                            </div>
                            <div class="product_price"><span class="price_orignal">Rs. 3250</span> <span
                                    class="price_old">4350</span></div>
                        </div>
                    </div>
                    <!-- One Item -->
                    <div class="product_item">
                        <div class="product_image">
                            <img src="images/collection_image_03.jpg" alt="">

                            <div class="product_hover">
                                <svg class="svg_shape" viewBox="0 0 368 94" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0,94 L0,82
                                            C12,82 18,78 24,70
                                            C32,58 45,48 68,48

                                            C92,48 106,28 126,18
                                            C144,9 162,12 184,18

                                            C206,12 224,9 242,18
                                            C262,28 276,48 300,48

                                            C323,48 336,58 344,70
                                            C350,78 356,82 368,82

                                            L368,94
                                            Z" fill="#f8f3ee" />
                                </svg>
                                <div class="product_colors">
                                    <span style="background:#7A2E43"></span>
                                    <span style="background:#87CEEB"></span>
                                    <span style="background:#E8D5B5"></span>
                                    <span style="background:#7A8F4D"></span>
                                </div>

                                <div class="product_sizes">
                                    <span>S</span>
                                    <span>M</span>
                                    <span>L</span>
                                    <span>XL</span>
                                </div>

                                <a href="#" class="btn_cart">
                                    Add To Cart
                                </a>
                            </div>

                            <!-- new tag -->
                            <span class="product_tag">New</span>

                            <!-- whishlist tag -->
                            <div class="product_tag_wishlist">
                                <span class="material-symbols-outlined">
                                    favorite
                                </span>
                            </div>
                        </div>
                        <div class="product_data">
                            <div class="product_title">
                                <a href=""> Mizoya Green Cotton A Line Dress</a>
                            </div>
                            <div class="product_price"><span class="price_orignal">Rs. 3250</span> <span
                                    class="price_old">4350</span></div>
                        </div>
                    </div>
                </div>
                <div class="slider-nav">
                    <button class="prev-btn">
                        <span class="material-symbols-outlined">arrow_back</span>
                    </button>

                    <button class="next-btn">
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </button>
                </div>

            </div>
        </div>
    </section>
    <style>
        .textcentercss{
            margin-left:12em; 
        }
    </style>
    <div class="modal fade"
         id="centimeterPreviewModal{{ $sizeChartData->id }}"
         tabindex="-1"
         aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">{{ $sizeChartData->title }}</h5>
                    <h4 class="textcentercss">{{ ucwords(str_replace('_',' ',$sizeChartData->chart_format)) }}</h4>
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <ul class="nav nav-tabs mb-3"
                        id="sizeChartTabs{{ $sizeChartData->id }}"
                        role="tablist">

                        <li class="nav-item" role="presentation">
                            <button class="nav-link active"
                                    data-bs-toggle="tab"
                                    data-bs-target="#inchTab{{ $sizeChartData->id }}"
                                    type="button">
                                Inch
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link"
                                    data-bs-toggle="tab"
                                    data-bs-target="#cmTab{{ $sizeChartData->id }}"
                                    type="button">
                                CM
                            </button>
                        </li>

                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active"
                             id="inchTab{{ $sizeChartData->id }}">

                            @include('front.modals.chart_preview', [
                                'result' => $sizeChartData,
                                'unit' => 'inch'
                            ])

                        </div>
                        <div class="tab-pane fade"
                             id="cmTab{{ $sizeChartData->id }}">

                            @include('front.modals.chart_preview', [
                                'result' => $sizeChartData,
                                'unit' => 'cm'
                            ])

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>



    <!-- jQuery (required) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flickity@2/dist/flickity.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flickity@2/dist/flickity.pkgd.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6/dist/fancybox/fancybox.css" />
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6/dist/fancybox/fancybox.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="{{ asset('assets/js/product-page.js') }}"></script>
    <script src="{{ asset('assets/js/cart.js') }}"></script>
    @include('front.includes.script')
    @php
    $minSellingQty = 1;
    $maxSellingQty = 10;
    @endphp 
    <script>
        var minSellingQty = '{{ $minSellingQty }}';
        var maxSellingQty = '{{ $maxSellingQty }}';
        var getVarient = "{{ route('variant.combination.prices') }}";
        $(document).off('click.qty', '.qty-btn');

        $(document).on('click.qty', '.qty-btn', function (e) {
            e.preventDefault();

            const input = $(this).siblings('.qty');
            let qty = parseInt(input.val(), 10) || 1;
            const maxQty = parseInt(input.attr('max'), 10) || 1;

            if ($(this).hasClass('plus')) {
                if (qty < maxQty) {
                    input.val(qty + 1).trigger('change');
                }
            }

            if ($(this).hasClass('minus')) {
                if (qty > 1) {
                    input.val(qty - 1).trigger('change');
                }
            }
        });

        $(document).off('input.qty', '.qty');

        $(document).on('input.qty', '.qty', function () {
            let qty = parseInt($(this).val(), 10) || 1;
            const maxQty = parseInt($(this).attr('max'), 10) || 1;

            if (qty < 1) {
                qty = 1;
            }
            if (qty > maxQty) {
                qty = maxQty;
            }
            $(this).val(qty);
        });
        function checkItemInCart() {
            const productId = $('#product_id').val();
            const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
            let selected = {};

            $('.s-variant.active[data-type]').each(function() {
                const type = ($(this).data('type') || '').toString().toLowerCase();
                const value = $(this).data('value');

                if (type && value !== undefined) {
                    selected[type] = value;
                }
            });

            const exists = cartItems.some(function(item) {
                if (String(item.productId) !== String(productId)) return false;

                const variant = item.selectedVariants || {};
                const keys1 = Object.keys(selected);
                const keys2 = Object.keys(variant);

                if (keys1.length !== keys2.length) return false;

                return keys1.every(function(key) {
                    return String(variant[key]) === String(selected[key]);
                });
            });

            $('.addToCartText').html(exists ? 'Go To Cart' : 'Add To Cart');
        }
        
        function selectVariant(el) {
            const productId = el.dataset.productId;
            const variantId = el.dataset.variantId;
            const variantValueId = el.dataset.vid;
            const value = el.dataset.value;
            const valueId = el.dataset.id;
            const type = (el.dataset.type || '').toLowerCase();

            console.log('Selected variant:', {
                productId,
                variantId,
                variantValueId,
                valueId,
                type,
                value
            });

            $('.s-variant[data-variant-id="' + variantId + '"]').removeClass('active');
            $(el).addClass('active');

            $('input.attribute-input[name="variant_' + variantId + '"]').prop('checked', false);
            $('input.attribute-input[name="variant_' + variantId + '"][value="' + variantValueId + '"]').prop('checked', true);

            const selectedSpan = document.getElementById('selected-value-' + valueId);

            if (selectedSpan) {
                selectedSpan.textContent = value;
            }

            const selectedVariantIds = $('.s-variant.active').map(function() {
                return $(this).data('vid');
            }).get();

            console.log('Selected Variant IDs:', selectedVariantIds);

            checkItemInCart();

            $.ajax({
                url: getVarient,
                type: 'POST',
                data: JSON.stringify({
                    product_id: productId,
                    vsku: selectedVariantIds,
                    variant_id: variantId,
                    _token: window.csrfToken
                }),
                contentType: 'application/json',
                success: function(response) {
                    const combination = response?.combination;
                    if (!combination) return;

                    const {
                        selling_price,
                        price,
                        sku,
                        discount,
                        discount_type,
                        qty
                    } = combination;

                    const buyingPrice = Number(price) || 0;
                    const sellingPrice = Number(selling_price) || 0;

                    $('#productSku').html(sku || '');
                    $('#productPrice').text(`₹${Math.floor(sellingPrice)}`);
                    $('#pdpStrikedMrp').text(`₹${Math.floor(buyingPrice)}`);

                    const discountProduct = buyingPrice - sellingPrice;

                    $('#discountShow').text(
                        `₹ ${Math.max(Math.floor(discountProduct), 0)} OFF`
                    );

                    const $addToCartBtn = $('.addToCartBtn');

                    $addToCartBtn.attr('data-price', buyingPrice);
                    $addToCartBtn.attr('data-saleprice', sellingPrice);
                    $addToCartBtn.attr('data-discounttype', discount_type);
                    $addToCartBtn.attr('data-discount', discount);

                    const stockQty = parseInt(qty || 0);
                    const minimumQty = parseInt(minSellingQty || 1);

                    if (stockQty <= 0) {
                        $('.varient_less_than_min_qty_notice').html('Out Of Stock');
                        $('.add-to-cart').addClass('d-none disabled-action');
                    } else if (minimumQty > stockQty) {
                        $('.varient_less_than_min_qty_notice')
                            .html('<b>Only ' + stockQty + ' items are left</b>');
                        $('.add-to-cart').removeClass('d-none disabled-action');
                    } else {
                        $('.varient_less_than_min_qty_notice').html('');
                        $('.add-to-cart').removeClass('d-none');
                    }
                },
                error: function(xhr) {
                    console.error('Variant fetch error:', xhr.responseText);
                }
            });

            if (type === 'color') {
                updateVariantImages(variantValueId);
            }
        }
        
        function checkVariantStock(el) {
            const productId = el.dataset.productId;
            const variantId = el.dataset.variantId;
            const variantValueId = el.dataset.vid;

            console.log('Checking variant stock:', {
                productId,
                variantId,
                variantValueId
            });

            $.ajax({
                url: "{{ route('variant.stock.check') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: productId,
                    variant_id: variantId,
                    variant_value_id: variantValueId
                },
                success: function(res) {
                    const productOut = $('#variantOutOfStock').data('product-out');

                    if (productOut == 1) {
                        $('#variantOutOfStock').removeClass('d-none');
                        $('.add-to-cart').addClass('disabled-action');
                        return;
                    }

                    $('.s-variant').removeClass('out-of-stock');

                    if (res.out_of_stock) {
                        $(el).addClass('out-of-stock');
                        $('#variantOutOfStock').removeClass('d-none');
                        $('.add-to-cart').addClass('disabled-action');
                    } else {
                        $('#variantOutOfStock').addClass('d-none');
                        $('.add-to-cart').removeClass('disabled-action');
                    }
                },
                error: function(xhr) {
                    console.error('Variant stock check error:', xhr.responseText);
                }
            });
        }
        
        function updateVariantImages(colorVariantId) {
            console.log('Active Color ID:', colorVariantId);
            const allImages = @json($product->product_main_images);
            const $mainGallery = $('.gallery-main');
            const $thumbGallery = $('.gallery-thumb');
            const mainFlkty = $mainGallery.data('flickity');
            if (mainFlkty) {
                $mainGallery.flickity('destroy');
            }

            const variantImages = allImages
                .filter(function(image) {
                    return String(image.variant_id) === String(colorVariantId);
                })
                .sort(function(a, b) {
                    return Number(b.is_front) - Number(a.is_front);
                });

            console.log('Color Variant Images:', variantImages);
            console.log('Total Images:', variantImages.length);

            if (!variantImages.length) {
                console.log('No images found for color:', colorVariantId);
                return;
            }

            let mainHtml = '';
            let thumbHtml = '';

            variantImages.forEach(function(image, index) {
                const imageUrl = "{{ asset('uploads/products') }}/" + image.graphic;

                mainHtml += `
                    <div class="gallery-cell">
                        <a data-fancybox="product-gallery" href="${imageUrl}">
                            <img src="${imageUrl}" alt="">
                        </a>
                    </div>
                `;

                thumbHtml += `
                    <div class="thumb ${index === 0 ? 'is-nav-selected' : ''}">
                        <img src="${imageUrl}" alt="">
                    </div>
                `;
            });

            $mainGallery.html(mainHtml);
            $thumbGallery.html(thumbHtml);

            $mainGallery.flickity({
                cellAlign: 'left',
                contain: true,
                pageDots: false,
                prevNextButtons: false,
                draggable: true,
                adaptiveHeight: true
            });

            $('.thumb').off('click').on('click', function() {
                const index = $(this).index();
                $mainGallery.flickity('select', index);
            });

            $mainGallery.off('change.flickity').on('change.flickity', function(event, index) {
                $('.thumb').removeClass('is-nav-selected');
                $('.thumb').eq(index).addClass('is-nav-selected');
            });

            Fancybox.unbind('[data-fancybox="product-gallery"]');

            Fancybox.bind('[data-fancybox="product-gallery"]', {
                Toolbar: {
                    display: {
                        left: [],
                        middle: [],
                        right: [
                            "zoomIn",
                            "zoomOut",
                            "toggle1to1",
                            "rotateCCW",
                            "rotateCW",
                            "flipX",
                            "flipY",
                            "close"
                        ]
                    }
                },
                Thumbs: {
                    autoStart: true
                },
                Images: {
                    zoom: true
                }
            });
        }
       

            
    </script>


</body>
</html>


@extends('front.layout.app')
@section('content')

    <div id="flash-msg" class="alert alert-info d-none"></div>

    @php      
                $activeVarientId = null;
        if ($product->product_type == 1) {
            $firstImage = getActiveFrontImg($product->id, $product->id);
            $secondImage = getActiveBackImg($product->id, $product->id);
            $buying_price = $product->buying_price;
            $selling_price = $product->selling_price;
            $productImages = getActiveVarientImg($product->id, $product->id);
        } else {
            $activeVarientId = activeVarientByProductId($product->id);
            $firstImage = getActiveFrontImg($product->id, $activeVarientId);
            $secondImage = getActiveBackImg($product->id, $activeVarientId);
            $priceData = getPriceByActiveVarientId($product->id, $activeVarientId);
            $buying_price = $priceData['buying_price'] ?? 0;
            $selling_price = $priceData['selling_price'] ?? 0;
            $productImages = getActiveVarientImg($product->id, $activeVarientId);
        }

        $minSellingQty = max((int) ($product->min_selling_units ?? 1), 1);
        $maxSellingQty = max((int) ($product->max_selling_units ?? 0), 0);
        $discount_product = $buying_price - $selling_price;
        $discountText = '';

        if (!empty($product->discount) && !empty($product->discount_type)) {
            if ($product->discount_type == 'percentage') {
                $discountText = $product->discount . '% Off';
            } elseif ($product->discount_type == 'flat') {
                $discountText = '₹' . $product->discount . ' Off';
            }
        }

        $mainPrice = null;

        if (isset($priceDrop) && !empty($priceDrop)) {
            $productId = $priceDrop->product_id;
            $discountAmount = $priceDrop->amount;
            $isApplicable = ($productId == 'all');
            if (!$isApplicable) {
                $productIdArray = explode(',', $productId);
                $isApplicable = in_array($product->id, $productIdArray);
            }
            if ($isApplicable) {
                if ($priceDrop->gain_type == 'drop') {
                    if ($priceDrop->drop_type == 'percentage') {
                        $mainPrice = $selling_price - (($discountAmount / 100) * $selling_price);
                    } elseif ($priceDrop->drop_type == 'flat') {
                        $mainPrice = $selling_price - $discountAmount;
                    }
                } elseif ($priceDrop->gain_type == 'gain') {
                    if ($priceDrop->drop_type == 'percentage') {
                        $mainPrice = $selling_price + (($discountAmount / 100) * $selling_price);
                    } elseif ($priceDrop->drop_type == 'flat') {
                        $mainPrice = $selling_price + $discountAmount;
                    }
                }
            }
        }

        $defaultVariantImageId = collect($productvariants ?? [])
            ->map(function ($variant) {
                $variantValues = collect($variant['variant_values'] ?? []);
                $mainValue = $variantValues->first(function ($value) {
                    return (int) ($value['is_main'] ?? 0) === 1;
                });

                return $mainValue['variant_value_id']
                    ?? $variantValues->first()['variant_value_id']
                    ?? null;
            })
            ->filter()
            ->first();
        $primaryVariantId = $product->product_type == 1
            ? $product->id
            : ($activeVarientId ?: $defaultVariantImageId);
        $allProductImages = $product->product_main_images;
        $sortedProductImages = $allProductImages
            ->filter(function ($img) use ($primaryVariantId) {
                return (string) $img->variant_id === (string) $primaryVariantId
                    && (int) ($img->status ?? 1) === 1
                    && ($img->graphic_type ?? 'image') === 'image';
            })
            ->sortByDesc(function ($img) {
                return (int) $img->is_front;
            })
            ->values();
        $hasSizeVariant = collect($productvariants ?? [])->contains(function ($variant) {
            return str_contains(strtolower($variant['variant_name'] ?? ''), 'size');
        });
    @endphp



    <main class="product-detail-page">
        <div class="detail-breadcrumb">
            <span>
                <a href="{{ url('/') }}">Home</a>
                /
                @if(isset($productcat) && $productcat)
                    <a href="{{ route('category.show', ['path' => $productcat->slug]) }}">{{ $productcat->name }}</a>
                    @if($productcat->category_type_id != 1)
                        @if(!empty($productSubCat))
                            / <a
                                href="{{ route('category.show', ['path' => $productcat->slug . '/' . $productSubCat->slug]) }}">{{ $productSubCat->name }}</a>
                        @endif
                        @if(!empty($productChildCat) && !empty($productSubCat))
                            / <a
                                href="{{ route('category.show', ['path' => $productcat->slug . '/' . $productSubCat->slug . '/' . $productChildCat->slug]) }}">{{ $productChildCat->name }}</a>
                        @endif
                    @endif
                @endif
            </span>
            <span>PRODUCT DETAILS</span>
        </div>

        <section class="detail-top" aria-label="Product information">
            <div class="detail-gallery">
                <div class="detail-main-image">
                    @if($sortedProductImages->count())
                        <img id="detailMainImage" class="default-image"
                            src="{{ asset('uploads/products/' . $sortedProductImages->first()->graphic) }}"
                            alt="{{ $product->name }}">
                    @elseif(!empty($firstImage))
                        <img id="detailMainImage" class="default-image" src="{{ asset('uploads/products/' . $firstImage) }}"
                            alt="{{ $product->name }}">
                    @else
                        <img id="detailMainImage" class="default-image" src="{{ asset('assets/images/no-image.png') }}"
                            alt="{{ $product->name }}">
                    @endif
                    <button class="gallery-arrow gallery-prev" type="button" aria-label="Previous product image">
                        <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                            <path d="M19 12H5m6-6-6 6 6 6"></path>
                        </svg>
                    </button>
                    <button class="gallery-arrow gallery-next" type="button" aria-label="Next product image">
                        <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                            <path d="M5 12h14m-6-6 6 6-6 6"></path>
                        </svg>
                    </button>
                </div>

                <div class="detail-thumbnails" aria-label="Product images">
                    @forelse($sortedProductImages as $index => $img)
                        <button class="detail-thumbnail {{ $index === 0 ? 'is-active' : '' }}" type="button"
                            data-image="{{ asset('uploads/products/' . $img->graphic) }}"
                            aria-label="View product image {{ $index + 1 }}">
                            <img src="{{ asset('uploads/products/' . $img->graphic) }}"
                                alt="{{ $product->name }} image {{ $index + 1 }}">
                        </button>
                    @empty
                        @if(!empty($firstImage))
                            <button class="detail-thumbnail is-active" type="button"
                                data-image="{{ asset('uploads/products/' . $firstImage) }}">
                                <img src="{{ asset('uploads/products/' . $firstImage) }}" alt="{{ $product->name }}">
                            </button>
                        @endif
                        @if(!empty($secondImage))
                            <button class="detail-thumbnail" type="button"
                                data-image="{{ asset('uploads/products/' . $secondImage) }}">
                                <img src="{{ asset('uploads/products/' . $secondImage) }}" alt="{{ $product->name }} back view">
                            </button>
                        @endif
                    @endforelse
                </div>

                <div class="gallery-caption">
                    <span>{{ $productcat->name ?? 'COLLECTION' }}</span>
                    <span>1/{{ $sortedProductImages->count() ?: (!empty($firstImage) ? 1 : 0) }}</span>
                </div>
            </div>

            <div class="detail-purchase">
                <p class="detail-kicker">{{ $productcat->name ?? 'COLLECTION' }}</p>
                <h1>{{ $product->name }}</h1>

                <div class="detail-price">
                    <strong id="productPrice">
                        ₹{{ isset($mainPrice) && $mainPrice !== null ? floor($mainPrice) : floor($selling_price) }}
                    </strong>
                    <del id="pdpStrikedMrp">₹{{ floor($buying_price) }}</del>
                    <span id="discountShow">
                        @if($discount_product > 0)
                            ₹{{ floor($discount_product) }} OFF
                        @elseif(!empty($discountText))
                            {{ $discountText }}
                        @endif
                    </span>
                </div>

                <div class="detail-rule"></div>
                <div class="product-attributes">
                    @if (!empty($productvariants))
                        @foreach ($productvariants as $variant)
                            @php
                                $variantValues = $variant['variant_values'] ?? [];
                                $hasMain = collect($variantValues)->where('is_main', 1)->isNotEmpty();
                                $displayType = (int) ($variant['variant_type'] ?? 0);
                                $variantName = strtolower($variant['variant_name'] ?? '');
                                $isColorVariant = str_contains($variantName, 'color')
                                    || str_contains($variantName, 'colour');
                            @endphp

                            @if (count($variantValues))
                                <div class="product-option variant-option">
                                    <div class="{{ $isColorVariant ? 'color-heading' : 'size-heading' }}">
                                        <span>{{ $variant['variant_name'] }}</span>
                                        @if ($sizeChartData && str_contains($variantName, 'size'))
                                            <button type="button" class="size-chart-link" data-bs-toggle="modal"
                                                data-bs-target="#centimeterPreviewModal{{ $sizeChartData->id }}">
                                                Size chart
                                            </button>
                                        @endif
                                    </div>

                                    {{-- TYPE 1 : ONLY ROUND --}}
                                    @if ($displayType === 1)
                                        <div class="colors variant-round color-options">
                                            @foreach ($variantValues as $k => $variantValue)
                                                @php
                                                    $isActive = ($hasMain && (int) $variantValue['is_main'] === 1) || (!$hasMain && $k === 0);
                                                    $colorCode = $variantValue['color_code'] ?? '#ddd';
                                                @endphp
                                                <label>
                                                    <input type="radio" name="variant_{{ $variant['variant_id'] ?? $variant['variant_name'] }}"
                                                        value="{{ $variantValue['variant_value_id'] }}" class="attribute-input" {{ $isActive ? 'checked' : '' }}>
                                                    <span class="s-variant color-variant {{ $isActive ? 'active is-selected' : '' }}"
                                                        style="--swatch: {{ $colorCode }}; background: {{ $colorCode }};"
                                                        data-product-id="{{ $variant['product_id'] ?? $product->id }}"
                                                        data-id="{{ $variantValue['id'] }}" data-variant-id="{{ $variant['variant_id'] ?? '' }}"
                                                        data-type="{{ $variant['variant_name'] }}" data-value="{{ $variantValue['name'] }}"
                                                        data-vid="{{ $variantValue['variant_value_id'] }}"
                                                        data-value-id="{{ $variantValue['variant_value_id'] }}"
                                                        title="{{ $variantValue['name'] }}" onclick="selectVariant(this);"></span>
                                                </label>
                                            @endforeach
                                        </div>

                                        {{-- TYPE 2 : BOX / SIZE --}}
                                    @elseif ($displayType === 2)
                                        <div class="sizes variant-box size-options">
                                            @foreach ($variantValues as $k => $variantValue)
                                                @php
                                                    $isActive = ($hasMain && (int) $variantValue['is_main'] === 1) || (!$hasMain && $k === 0);
                                                @endphp
                                                <label>
                                                    <input type="radio" name="variant_{{ $variant['variant_id'] ?? $variant['variant_name'] }}"
                                                        value="{{ $variantValue['variant_value_id'] }}" class="attribute-input" {{ $isActive ? 'checked' : '' }}>
                                                    <span class="s-variant {{ $isActive ? 'active is-selected' : '' }}"
                                                        data-product-id="{{ $variant['product_id'] ?? $product->id }}"
                                                        data-id="{{ $variantValue['id'] }}" data-variant-id="{{ $variant['variant_id'] ?? '' }}"
                                                        data-type="{{ $variant['variant_name'] }}" data-value="{{ $variantValue['name'] }}"
                                                        data-vid="{{ $variantValue['variant_value_id'] }}"
                                                        data-value-id="{{ $variantValue['variant_value_id'] }}"
                                                        onclick="selectVariant(this);">{{ $variantValue['name'] }}</span>
                                                </label>
                                            @endforeach
                                        </div>

                                        {{-- TYPE 3 : ROUND WITH IMAGE --}}
                            
@elseif ($displayType === 3)

    <div class="sizes variant-box-color size-options color-box-options">

        @foreach ($variantValues as $k => $variantValue)

            @php
                $isActive = ($hasMain && (int) $variantValue['is_main'] === 1)
                    || (!$hasMain && $k === 0);

                $colorName = trim($variantValue['name'] ?? '');

                $colorCode = !empty($variantValue['color_code'])
                    ? $variantValue['color_code']
                    : strtolower($colorName);
            @endphp

            <label class="color-box-label">

                <input
                    type="radio"
                    name="variant_{{ $variant['variant_id'] ?? $variant['variant_name'] }}"
                    value="{{ $variantValue['variant_value_id'] }}"
                    class="attribute-input"
                    {{ $isActive ? 'checked' : '' }}
                >

                <span
                    class="s-variant color-name-box {{ $isActive ? 'active is-selected' : '' }}"
                    data-product-id="{{ $variant['product_id'] ?? $product->id }}"
                    data-id="{{ $variantValue['id'] }}"
                    data-variant-id="{{ $variant['variant_id'] ?? '' }}"
                    data-type="{{ $variant['variant_name'] }}"
                    data-value="{{ $colorName }}"
                    data-vid="{{ $variantValue['variant_value_id'] }}"
                    data-value-id="{{ $variantValue['variant_value_id'] }}"
                    title="{{ $colorName }}"
                    onclick="selectVariant(this);"
                >

                    <span
                        class="color-name-swatch"
                        style="background-color: {{ $colorCode }};">
                    </span>

                  

                </span>

            </label>

        @endforeach

    </div>

                                        {{-- TYPE 4 : ROUND WITH COLOR --}}
                                    @elseif ($displayType === 4)
                                        <div class="colors variant-round color-options">
                                            @foreach ($variantValues as $k => $variantValue)
                                                @php
                                                    $isActive = ($hasMain && (int) $variantValue['is_main'] === 1) || (!$hasMain && $k === 0);
                                                    $colorCode = $variantValue['color_code'] ?? '#ddd';
                                                @endphp
                                                <label>
                                                    <input type="radio" name="variant_{{ $variant['variant_id'] ?? $variant['variant_name'] }}"
                                                        value="{{ $variantValue['variant_value_id'] }}" class="attribute-input" {{ $isActive ? 'checked' : '' }}>
                                                    <span class="s-variant color-variant {{ $isActive ? 'active is-selected' : '' }}"
                                                        style="--swatch: {{ $colorCode }}; background: {{ $colorCode }};"
                                                        data-product-id="{{ $variant['product_id'] ?? $product->id }}"
                                                        data-id="{{ $variantValue['id'] }}" data-variant-id="{{ $variant['variant_id'] ?? '' }}"
                                                        data-type="{{ $variant['variant_name'] }}" data-value="{{ $variantValue['name'] }}"
                                                        data-vid="{{ $variantValue['variant_value_id'] }}"
                                                        data-value-id="{{ $variantValue['variant_value_id'] }}"
                                                        title="{{ $variantValue['name'] }}" onclick="selectVariant(this);"></span>
                                                </label>
                                            @endforeach
                                        </div>

                                        {{-- TYPE 5 : BOX WITH COLOR --}}
                                        
                                   @elseif ($displayType === 5)

    <div class="sizes variant-box-color size-options color-box-options">

        @foreach ($variantValues as $k => $variantValue)

            @php
                $isActive = ($hasMain && (int) $variantValue['is_main'] === 1)
                    || (!$hasMain && $k === 0);

                $colorName = trim($variantValue['name'] ?? '');

                // Use database color code if available,
                // otherwise use the actual color name.
                $colorCode = !empty($variantValue['color_code'])
                    ? $variantValue['color_code']
                    : strtolower($colorName);
            @endphp

            <label class="color-box-label">

                <input
                    type="radio"
                    name="variant_{{ $variant['variant_id'] ?? $variant['variant_name'] }}"
                    value="{{ $variantValue['variant_value_id'] }}"
                    class="attribute-input"
                    {{ $isActive ? 'checked' : '' }}
                >

                <span
                    class="s-variant color-name-box {{ $isActive ? 'active is-selected' : '' }}"
                    data-product-id="{{ $variant['product_id'] ?? $product->id }}"
                    data-id="{{ $variantValue['id'] }}"
                    data-variant-id="{{ $variant['variant_id'] ?? '' }}"
                    data-type="{{ $variant['variant_name'] }}"
                    data-value="{{ $colorName }}"
                    data-vid="{{ $variantValue['variant_value_id'] }}"
                    data-value-id="{{ $variantValue['variant_value_id'] }}"
                    title="{{ $colorName }}"
                    onclick="selectVariant(this);"
                >

                    <span class="color-name-swatch"
                        style="background-color: {{ $colorCode }};">
                    </span>

                    <span>{{ $colorName }}</span>

                </span>

            </label>

        @endforeach

    </div>

                                        {{-- TYPE 6 : BOX WITH IMAGE --}}
                                    @elseif ($displayType === 6)
                                        <div class="sizes variant-box-image size-options">
                                            @foreach ($variantValues as $k => $variantValue)
                                                @php
                                                    $isActive = ($hasMain && (int) $variantValue['is_main'] === 1) || (!$hasMain && $k === 0);
                                                    $image = $variantValue['image'] ?? null;
                                                @endphp
                                                <label>
                                                    <input type="radio" name="variant_{{ $variant['variant_id'] ?? $variant['variant_name'] }}"
                                                        value="{{ $variantValue['variant_value_id'] }}" class="attribute-input" {{ $isActive ? 'checked' : '' }}>
                                                    <span class="s-variant {{ $isActive ? 'active is-selected' : '' }}"
                                                        data-product-id="{{ $variant['product_id'] ?? $product->id }}"
                                                        data-id="{{ $variantValue['id'] }}" data-variant-id="{{ $variant['variant_id'] ?? '' }}"
                                                        data-type="{{ $variant['variant_name'] }}" data-value="{{ $variantValue['name'] }}"
                                                        data-vid="{{ $variantValue['variant_value_id'] }}"
                                                        data-value-id="{{ $variantValue['variant_value_id'] }}" onclick="selectVariant(this);">
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
                                        <div class="sizes variant-rectangle size-options">
                                            @foreach ($variantValues as $k => $variantValue)
                                                @php
                                                    $isActive = ($hasMain && (int) $variantValue['is_main'] === 1) || (!$hasMain && $k === 0);
                                                @endphp
                                                <label>
                                                    <input type="radio" name="variant_{{ $variant['variant_id'] ?? $variant['variant_name'] }}"
                                                        value="{{ $variantValue['variant_value_id'] }}" class="attribute-input" {{ $isActive ? 'checked' : '' }}>
                                                    <span class="s-variant {{ $isActive ? 'active is-selected' : '' }}"
                                                        data-product-id="{{ $variant['product_id'] ?? $product->id }}"
                                                        data-id="{{ $variantValue['id'] }}" data-variant-id="{{ $variant['variant_id'] ?? '' }}"
                                                        data-type="{{ $variant['variant_name'] }}" data-value="{{ $variantValue['name'] }}"
                                                        data-vid="{{ $variantValue['variant_value_id'] }}"
                                                        data-value-id="{{ $variantValue['variant_value_id'] }}"
                                                        onclick="selectVariant(this);">{{ $variantValue['name'] }}</span>
                                                </label>
                                            @endforeach
                                        </div>

                                        {{-- TYPE 8 : RECTANGLE WITH IMAGE --}}
                                    @elseif ($displayType === 8)
                                        <div class="sizes variant-rectangle-image size-options">
                                            @foreach ($variantValues as $k => $variantValue)
                                                @php
                                                    $isActive = ($hasMain && (int) $variantValue['is_main'] === 1) || (!$hasMain && $k === 0);
                                                    $image = $variantValue['image'] ?? null;
                                                @endphp
                                                <label>
                                                    <input type="radio" name="variant_{{ $variant['variant_id'] ?? $variant['variant_name'] }}"
                                                        value="{{ $variantValue['variant_value_id'] }}" class="attribute-input" {{ $isActive ? 'checked' : '' }}>
                                                    <span class="s-variant {{ $isActive ? 'active is-selected' : '' }}"
                                                        data-product-id="{{ $variant['product_id'] ?? $product->id }}"
                                                        data-id="{{ $variantValue['id'] }}" data-variant-id="{{ $variant['variant_id'] ?? '' }}"
                                                        data-type="{{ $variant['variant_name'] }}" data-value="{{ $variantValue['name'] }}"
                                                        data-vid="{{ $variantValue['variant_value_id'] }}"
                                                        data-value-id="{{ $variantValue['variant_value_id'] }}" onclick="selectVariant(this);">
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
                                        <div class="sizes variant-rectangle-color size-options">
                                            @foreach ($variantValues as $k => $variantValue)
                                                @php
                                                    $isActive = ($hasMain && (int) $variantValue['is_main'] === 1) || (!$hasMain && $k === 0);
                                                    $colorCode = $variantValue['color_code'] ?? '#ddd';
                                                @endphp
                                                <label>
                                                    <input type="radio" name="variant_{{ $variant['variant_id'] ?? $variant['variant_name'] }}"
                                                        value="{{ $variantValue['variant_value_id'] }}" class="attribute-input" {{ $isActive ? 'checked' : '' }}>
                                                    <span class="s-variant {{ $isActive ? 'active is-selected' : '' }}"
                                                        style="background: {{ $colorCode }};"
                                                        data-product-id="{{ $variant['product_id'] ?? $product->id }}"
                                                        data-id="{{ $variantValue['id'] }}" data-variant-id="{{ $variant['variant_id'] ?? '' }}"
                                                        data-type="{{ $variant['variant_name'] }}" data-value="{{ $variantValue['name'] }}"
                                                        data-vid="{{ $variantValue['variant_value_id'] }}"
                                                        data-value-id="{{ $variantValue['variant_value_id'] }}"
                                                        onclick="selectVariant(this);">{{ $variantValue['name'] }}</span>
                                                </label>
                                            @endforeach
                                      
                                    @endif
                                          </div>
                            @endif
                        @endforeach
                    @endif
                    </div>

                    @if($sizeChartData && !$hasSizeVariant)
                        <div class="size-heading">
                            <span>Size / Measurements</span>
                            <button type="button" class="size-chart-link" data-bs-toggle="modal"
                                data-bs-target="#centimeterPreviewModal{{ $sizeChartData->id }}">
                                Size chart
                            </button>
                        </div>
                    @endif

                    <div class="purchase-actions">
                        <div class="quantity-control" aria-label="Quantity">
                            <button type="button" class="qty-btn minus" aria-label="Decrease quantity" data-quantity="-1">
                                <svg class="svg-icon" viewBox="0 0 24 24">
                                    <path d="M5 12h14"></path>
                                </svg>
                            </button>
                            <input id="quantity" class="qty" type="number" min="{{ $minSellingQty }}"
                                max="{{ max(1, min((int) ($product->qty ?? 1), $maxSellingQty > 0 ? $maxSellingQty : (int) ($product->qty ?? 1))) }}"
                                value="{{ $minSellingQty }}" aria-label="Quantity">
                            <button type="button" class="qty-btn plus" aria-label="Increase quantity" data-quantity="1">
                                <svg class="svg-icon" viewBox="0 0 24 24">
                                    <path d="M12 5v14M5 12h14"></path>
                                </svg>
                            </button>
                        </div>

                        <button class="detail-add-cart product_addtocart_btn addToCartBtn addToCartBtnText" type="button"
                            disabled aria-disabled="true"
                            data-id="{{ $product->id }}" data-product-sku="{{ $product->sku }}"
                            data-name="{{ $product->name }}" data-slug="{{ $product->slug }}"
                            data-producttype="{{ $product->product_type }}" data-sku="{{ $product->sku }}"
                            data-price="{{ $buying_price }}" data-saleprice="{{ $selling_price }}"
                            data-discounttype="{{ $product->discount_type }}" data-discount="{{ $discount_product }}"
                            data-tax-arr="{{ e(json_encode($categoryTaxes)) }}">
                            <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="M2 3h2l2.4 12.2a2 2 0 0 0 2 1.6h9.9a2 2 0 0 0 2-1.6L22 7H5"></path>
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="18" cy="21" r="1"></circle>
                            </svg>
                            <span class="addToCartText">Checking availability...</span>
                        </button>

                        <button class="detail-wishlist {{ $isWishlisted ? 'is-active' : '' }}" type="button"
                            data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}"
                            aria-pressed="{{ $isWishlisted ? 'true' : 'false' }}"
                            aria-label="{{ $isWishlisted ? 'Remove ' . $product->name . ' from wishlist' : 'Add ' . $product->name . ' to wishlist' }}">
                            <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path
                                    d="M20.8 8.8c0 5.2-8.8 11-8.8 11s-8.8-5.8-8.8-11A4.8 4.8 0 0 1 12 6.1a4.8 4.8 0 0 1 8.8 2.7Z">
                                </path>
                            </svg>
                        </button>
                    </div>

                    <p class="varient_less_than_min_qty_notice"></p>
                    <div id="variantOutOfStock" class="d-none" data-product-out="{{ $product->qty <= 0 ? 1 : 0 }}">Out Of
                        Stock</div>

                    {{-- New-design offer carousel. These are display-only sample offers in the supplied HTML. --}}
                    <div class="offer-promo">
                        <p>Best Offers for you</p>
                        <div class="offer-promo-card" role="group" aria-label="Special offers">
                            <article class="offer-slide is-active"
                                style="--offer-bg:#621d34;--offer-border:#c9a6a7;--offer-text:#fff;--offer-button:#edaa35;--offer-button-text:#fff"
                                aria-hidden="false">
                                <img src="{{ asset('assets/img/details-page-side.jpg') }}"
                                    alt="Models in yellow and pink festive outfits">
                                <div class="offer-slide-copy"><small>SALE</small>
                                    <h2>Enjoy up to <span>70%</span> off!</h2>
                                    <p>Grab your limited-time discount and enjoy 70% off on all our products</p><a
                                        href="product-list.html">SHOP NOW</a>
                                </div>
                            </article>
                            <article class="offer-slide"
                                style="--offer-bg:#dcc7a5;--offer-border:#ccb48d;--offer-text:#211b19;--offer-button:#6d2038;--offer-button-text:#fff"
                                aria-hidden="true" inert="">
                                <img src="{{ asset('assets/img/festive-4.jpg') }}"
                                    alt="Festive collection in warm traditional colors">
                                <div class="offer-slide-copy"><small>SALE</small>
                                    <h2>Enjoy up to <span>70%</span> off!</h2>
                                    <p>Grab your limited-time discount and enjoy 70% off on all our products</p><a
                                        href="product-list.html">SHOP NOW</a>
                                </div>
                            </article>
                            <article class="offer-slide"
                                style="--offer-bg:#898b72;--offer-border:#b4b69d;--offer-text:#fff;--offer-button:#fff;--offer-button-text:#393a30"
                                aria-hidden="true" inert="">
                                <img src="{{ asset('assets/img/festive-2.jpg') }}"
                                    alt="Models wearing the festive collection">
                                <div class="offer-slide-copy"><small>SALE</small>
                                    <h2>Enjoy up to <span>70%</span> off!</h2>
                                    <p>Grab your limited-time discount and enjoy 70% off on all our products</p><a
                                        href="product-list.html">SHOP NOW</a>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
        </section>

        {{-- Delivery / product information layout from the new design.
        The original Blade did not provide live delivery, review or specification datasets. --}}
        <section class="detail-information" aria-labelledby="finerDetailsTitle">
            <div class="detail-section-heading">
                <h2 id="finerDetailsTitle">The finer details</h2>
                <span>PRODUCT &amp; DELIVERY</span>
            </div>

            <div class="detail-info-grid">
                <div class="delivery-column">
                    <div class="service-benefits">
                        <div><span><i class="fa-solid fa-truck-fast"></i></span><strong>Free<br>Shipping</strong><small>On
                                eligible orders</small></div>
                        <div><span><i
                                    class="fa-solid fa-rotate"></i></span><strong>Easy<br>Returns</strong><small>Hassle-free
                                returns</small></div>
                        <div><span><i class="fa-solid fa-lock"></i></span><strong>Secure<br>Checkout</strong><small>Secure
                                payments</small></div>
                        <div><span><i class="fa-solid fa-money-bill"></i></span><strong>Cash
                                On<br>Delivery</strong><small>At checkout, if available</small></div>
                    </div>

                    <form class="delivery-check" id="deliveryCheck">
                        <h3><i class="fa-solid fa-location-dot"></i> Delivery &amp; Availability</h3>
                        <label for="pincode">Check delivery to your location</label>
                        <div class="pincode-row">
                            <input id="pincode" name="pincode" inputmode="numeric" maxlength="6" placeholder="Enter Pincode"
                                required pattern="[0-9]{6}">
                            <button type="submit">Check</button>
                        </div>
                        <p class="delivery-result" hidden>
                            <strong><i class="fa-solid fa-truck"></i> <span>Enter a pincode to check</span></strong>
                            <span>Delivery estimate will be confirmed by the shipping service.</span>
                        </p>
                    </form>
                </div>

<div class="product-specification">
    <h3>Product details</h3>

    <div class="specification-box">

        <h4>Product information</h4>

        <div class="specification-grid">

            <p>
                <strong>Product</strong>
                <span>{{ $product->name }}</span>
            </p>

            <p>
                <strong>SKU</strong>
                <span id="productSku">{{ $product->sku }}</span>
            </p>

            <p>
                <strong>Category</strong>
                <span>{{ $productcat->name ?? '—' }}</span>
            </p>

            <p>
                <strong>Price</strong>
                <span id="specificationPrice">
                    ₹{{ floor($selling_price) }}
                </span>
            </p>

            {{-- Dynamic specifications from content_1 --}}
            @if(!empty($product->content_1))

                @php
                    $specifications = [];

                    $dom = new \DOMDocument();

                    libxml_use_internal_errors(true);

                    $dom->loadHTML(
                        '<?xml encoding="UTF-8">' . $product->content_1
                    );

                    libxml_clear_errors();

                    $listItems = $dom->getElementsByTagName('li');

                    foreach ($listItems as $item) {

                        $strong = $item->getElementsByTagName('strong')->item(0);

                        if ($strong) {

                            $title = trim($strong->textContent);

                            // Clone li and remove strong tag to get only value
                            $clone = $item->cloneNode(true);

                            $strongClone = $clone->getElementsByTagName('strong')->item(0);

                            if ($strongClone) {
                                $strongClone->parentNode->removeChild($strongClone);
                            }

                            $value = trim($clone->textContent);

                            // Remove starting separator
                            $value = preg_replace('/^\s*[-:–—]\s*/u', '', $value);

                            if ($title && $value) {
                                $specifications[] = [
                                    'title' => $title,
                                    'value' => $value
                                ];
                            }
                        }
                    }
                @endphp

                @foreach($specifications as $specification)

                    <p>
                        <strong>{{ $specification['title'] }}</strong>
                        <span>{{ $specification['value'] }}</span>
                    </p>

                @endforeach

            @endif

        </div>

    </div>
</div>
            </div>
        </section>

        {{-- Supplied new-design reviews and related-products UI (sample/static content). --}}
        <section class="detail-reviews" aria-labelledby="reviewsTitle">
            <div class="detail-section-heading">
                <h2 id="reviewsTitle">Product Reviews</h2><span>from 1.25k reviews</span>
            </div>
            <div class="review-summary">
                <div class="review-score"><strong>4.5</strong><span>/ 5</span>
                    <div class="review-stars" aria-label="5 out of 5 stars"><svg class="svg-icon" aria-hidden="true"
                            viewBox="0 0 24 24">
                            <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                fill="currentColor" stroke="none"></path>
                        </svg><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                            <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                fill="currentColor" stroke="none"></path>
                        </svg><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                            <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                fill="currentColor" stroke="none"></path>
                        </svg><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                            <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                fill="currentColor" stroke="none"></path>
                        </svg><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                            <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                fill="currentColor" stroke="none"></path>
                        </svg></div><small>from 1,25k reviews</small>
                </div>
                <div class="rating-bars">
                    <div><span>5.0</span><b><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg></b><i><em style="width:82%"></em></i><small>2923</small></div>
                    <div><span>4.0</span><b><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg></b><i><em style="width:45%"></em></i><small>38</small></div>
                    <div><span>3.0</span><b><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg></b><i><em style="width:14%"></em></i><small>4</small></div>
                    <div><span>2.0</span><b><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg></b><i><em style="width:0"></em></i><small>0</small></div>
                    <div><span>1.0</span><b><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg></b><i><em style="width:0"></em></i><small>0</small></div>
                </div>
            </div>
            <div class="review-layout">
                <aside class="review-filters">
                    <h3>Reviews Filter</h3>
                    <details open>
                        <summary>Rating</summary><label><input type="checkbox"> <svg class="svg-icon" aria-hidden="true"
                                viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg> 5</label><label><input type="checkbox"> <svg class="svg-icon" aria-hidden="true"
                                viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg> 4</label><label><input type="checkbox"> <svg class="svg-icon" aria-hidden="true"
                                viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg> 3</label><label><input type="checkbox"> <svg class="svg-icon" aria-hidden="true"
                                viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg> 2</label><label><input type="checkbox"> <svg class="svg-icon" aria-hidden="true"
                                viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg> 1</label>
                    </details>
                    <details open>
                        <summary>Review Topics</summary><label><input type="checkbox"> Product Quality</label><label><input
                                type="checkbox"> Seller Services</label><label><input type="checkbox"> Product
                            Price</label><label><input type="checkbox"> Shipment</label><label><input type="checkbox"> Match
                            with
                            Description</label>
                    </details>
                </aside>
                <div class="review-list">
                    <h3>Review Lists</h3>
                    <div class="review-tabs" role="tablist" aria-label="Review filter tabs">
                        <button class="is-active" type="button" data-review-filter="all" role="tab" aria-selected="true">All
                            Reviews</button>
                        <button type="button" data-review-filter="photos" role="tab" aria-selected="false">With Photos &amp;
                            Video</button>
                        <button type="button" data-review-filter="description" role="tab" aria-selected="false">With
                            Description</button>
                    </div>
                    <article class="review-item" data-has-photos="true" data-has-description="true">
                        <div class="review-stars" aria-label="5 out of 5 stars"><svg class="svg-icon" aria-hidden="true"
                                viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg></div>
                        <p>This is an amazing product! The fabric is lightweight, soft and breathable. The embroidery on the
                            neckline is extremely neat and detailed. Loved the overall festive look!</p>
                        <div class="review-media">
                            <img src="{{ asset('assets/img/details-page-1.jpg') }}"
                                alt="Customer review photo by Darrell Steward" loading="lazy">
                            <img src="{{ asset('assets/img/details-page-2.jpg') }}"
                                alt="Customer review photo by Darrell Steward" loading="lazy">
                        </div>
                        <time>July 2, 2020 03:29 PM</time>
                        <div class="review-author"><span class="avatar avatar-one">C</span> Darrell Steward <span
                                class="review-votes"><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                    <path
                                        d="M7 10v11H3V10h4Zm0 10h10.2a2 2 0 0 0 1.9-1.4l2-6A2 2 0 0 0 19.2 10H14l.8-4.2A2.4 2.4 0 0 0 12.4 3L7 10v10Z">
                                    </path>
                                </svg> 126 <button type="button" aria-label="Dislike review"><svg class="svg-icon"
                                        aria-hidden="true" viewBox="0 0 24 24">
                                        <path
                                            d="M7 14V3H3v11h4Zm0-10h10.2a2 2 0 0 1 1.9 1.4l2 6a2 2 0 0 1-1.9 2.6H14l.8 4.2a2.4 2.4 0 0 1-2.4 2.8L7 14V4Z">
                                        </path>
                                    </svg></button></span></div>
                    </article>
                    <article class="review-item" data-has-photos="false" data-has-description="true">
                        <div class="review-stars" aria-label="5 out of 5 stars"><svg class="svg-icon" aria-hidden="true"
                                viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg></div>
                        <p>Loved the fitting and the vibrant festive color. Cotton inner lining keeps it super comfortable
                            for
                            day-long wear. Highly recommended for family functions.</p>
                        <time>July 2, 2020 1:04 PM</time>
                        <div class="review-author"><span class="avatar avatar-two">D</span> Darlene Robertson <span
                                class="review-votes"><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                    <path
                                        d="M7 10v11H3V10h4Zm0 10h10.2a2 2 0 0 0 1.9-1.4l2-6A2 2 0 0 0 19.2 10H14l.8-4.2A2.4 2.4 0 0 0 12.4 3L7 10v10Z">
                                    </path>
                                </svg> 82 <button type="button" aria-label="Dislike review"><svg class="svg-icon"
                                        aria-hidden="true" viewBox="0 0 24 24">
                                        <path
                                            d="M7 14V3H3v11h4Zm0-10h10.2a2 2 0 0 1 1.9 1.4l2 6a2 2 0 0 1-1.9 2.6H14l.8 4.2a2.4 2.4 0 0 1-2.4 2.8L7 14V4Z">
                                        </path>
                                    </svg></button></span></div>
                    </article>
                    <article class="review-item" data-has-photos="true" data-has-description="true">
                        <div class="review-stars" aria-label="5 out of 5 stars"><svg class="svg-icon" aria-hidden="true"
                                viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg></div>
                        <p>The dress looks exactly as shown on the website. Attaching photos of the stitch quality and
                            sleeve
                            border.</p>
                        <div class="review-media">
                            <img src="{{ asset('assets/img/details-page-3.jpg') }}"
                                alt="Customer review photo by Kathryn Murphy" loading="lazy">
                            <img src="{{ asset('assets/img/details-page-4.jpg') }}"
                                alt="Customer review photo by Kathryn Murphy" loading="lazy">
                        </div>
                        <time>June 26, 2020 10:03 PM</time>
                        <div class="review-author"><span class="avatar avatar-three">K</span> Kathryn Murphy <span
                                class="review-votes"><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                    <path
                                        d="M7 10v11H3V10h4Zm0 10h10.2a2 2 0 0 0 1.9-1.4l2-6A2 2 0 0 0 19.2 10H14l.8-4.2A2.4 2.4 0 0 0 12.4 3L7 10v10Z">
                                    </path>
                                </svg> 9 <button type="button" aria-label="Dislike review"><svg class="svg-icon"
                                        aria-hidden="true" viewBox="0 0 24 24">
                                        <path
                                            d="M7 14V3H3v11h4Zm0-10h10.2a2 2 0 0 1 1.9 1.4l2 6a2 2 0 0 1-1.9 2.6H14l.8 4.2a2.4 2.4 0 0 1-2.4 2.8L7 14V4Z">
                                        </path>
                                    </svg></button></span></div>
                    </article>
                    <article class="review-item" data-has-photos="false" data-has-description="true">
                        <div class="review-stars" aria-label="5 out of 5 stars"><svg class="svg-icon" aria-hidden="true"
                                viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg></div>
                        <p>Great product quality and timely delivery! Fits perfectly as per the size chart and the color is
                            very
                            flattering.</p>
                        <time>July 7, 2020 10:14 AM</time>
                        <div class="review-author"><span class="avatar avatar-four">R</span> Ronald Richards <span
                                class="review-votes"><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                    <path
                                        d="M7 10v11H3V10h4Zm0 10h10.2a2 2 0 0 0 1.9-1.4l2-6A2 2 0 0 0 19.2 10H14l.8-4.2A2.4 2.4 0 0 0 12.4 3L7 10v10Z">
                                    </path>
                                </svg> 124 <button type="button" aria-label="Dislike review"><svg class="svg-icon"
                                        aria-hidden="true" viewBox="0 0 24 24">
                                        <path
                                            d="M7 14V3H3v11h4Zm0-10h10.2a2 2 0 0 1 1.9 1.4l2 6a2 2 0 0 1-1.9 2.6H14l.8 4.2a2.4 2.4 0 0 1-2.4 2.8L7 14V4Z">
                                        </path>
                                    </svg></button></span></div>
                    </article>
                    <article class="review-item" data-has-photos="false" data-has-description="false">
                        <div class="review-stars" aria-label="5 out of 5 stars"><svg class="svg-icon" aria-hidden="true"
                                viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg></div>
                        <time>July 10, 2020 02:15 PM</time>
                        <div class="review-author"><span class="avatar avatar-one">S</span> Sunita Sharma <span
                                class="review-votes"><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                    <path
                                        d="M7 10v11H3V10h4Zm0 10h10.2a2 2 0 0 0 1.9-1.4l2-6A2 2 0 0 0 19.2 10H14l.8-4.2A2.4 2.4 0 0 0 12.4 3L7 10v10Z">
                                    </path>
                                </svg> 45 <button type="button" aria-label="Dislike review"><svg class="svg-icon"
                                        aria-hidden="true" viewBox="0 0 24 24">
                                        <path
                                            d="M7 14V3H3v11h4Zm0-10h10.2a2 2 0 0 1 1.9 1.4l2 6a2 2 0 0 1-1.9 2.6H14l.8 4.2a2.4 2.4 0 0 1-2.4 2.8L7 14V4Z">
                                        </path>
                                    </svg></button></span></div>
                    </article>
                    <nav class="review-pagination" aria-label="Review pages"><button class="is-active"
                            type="button">1</button><button type="button">2</button><button
                            type="button">...</button><button type="button">19</button><button type="button"
                            aria-label="Next page"><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="M5 12h14m-6-6 6 6-6 6"></path>
                            </svg></button></nav>
                </div>
            </div>
        </section>

        @if($relatedProducts->isNotEmpty())
        <section class="related-products" aria-labelledby="relatedTitle">
            <div class="related-heading">
                <div>
                    <p>CURATED FOR YOU</p>
                    <h2 id="relatedTitle">Related Product</h2>
                </div>
                <div class="related-controls"><button class="related-prev" type="button" aria-label="Previous products"><svg
                            class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                            <path d="M19 12H5m6-6-6 6 6 6"></path>
                        </svg></button><button class="related-next" type="button" aria-label="Next products"><svg
                            class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                            <path d="M5 12h14m-6-6 6 6-6 6"></path>
                        </svg></button></div>
            </div>
            <div class="related-viewport">
                <div class="related-grid" aria-live="off">
   @foreach($relatedProducts as $relatedProduct)

    @php
        $relatedImage = $relatedProduct->product_main_images->first();

        $relatedProductUrl = route('front-product.detail', [
            'product' => 'product',
            'title' => $relatedProduct->slug . '.html',
            'sku' => $relatedProduct->sku ?: $relatedProduct->slug,
        ]);

        $relatedImagePath = ($relatedImage && !empty($relatedImage->graphic))
            ? asset('uploads/products/' . $relatedImage->graphic)
            : asset("assets/images/no-image.png");
    @endphp

    <a class="related-card" href="{{ $relatedProductUrl }}">

        <img
            src="{{ $relatedImagePath }}"
            alt="{{ $relatedProduct->name }}"
        >

        <h6>{{ $relatedProduct->name }}</h6>

        <strong>
            ₹ {{ number_format((float) ($relatedProduct->selling_price ?? 0), 0) }}
        </strong>

    </a>

@endforeach
                    {{--
                    <a class="related-card" href="{{ url('#') }}">
                        <img src="{{ asset('assets/img/bastsaller-1.jpg') }}" alt="Mizoya red cotton dress">
                        <h6>Mizoya Red Cotton Dress</h6>
                        <strong>&#8377; 2600</strong>
                        <small><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg> 4.8 &nbsp; · &nbsp; 1,258 Sold</small>
                    </a>
                    <a class="related-card" href="{{ url('#') }}">
                        <img src="{{ asset('assets/img/festive-2.jpg') }}" alt="Mizoya red cotton dress">
                        <h6>Mizoya Red Cotton Dress</h6>
                        <strong>&#8377; 2600</strong>
                        <small><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg> 4.8 &nbsp; · &nbsp; 1,258 Sold</small>
                    </a>
                    <a class="related-card" href="{{ url('#') }}">
                        <img src="{{ asset('assets/img/festive-3.jpg') }}" alt="Mizoya red cotton dress">
                        <h6>Mizoya Red Cotton Dress</h6>
                        <strong>&#8377; 2600</strong>
                        <small><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg> 4.8 &nbsp; · &nbsp; 1,258 Sold</small>
                    </a>
                    <a class="related-card" href="{{ url('#') }}">
                        <img src="{{ asset('assets/img/festive-4.jpg') }}" alt="Mizoya red cotton dress">
                        <h6>Mizoya Red Cotton Dress</h6>
                        <strong>&#8377; 2600</strong>
                        <small><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg> 4.8 &nbsp; · &nbsp; 1,258 Sold</small>
                    </a>
                    <a class="related-card" href="{{ url('#') }}">
                        <img src="{{ asset('assets/img/festive-5.jpg') }}" alt="Mizoya red cotton dress">
                        <h6>Mizoya Red Cotton Dress</h6>
                        <strong>&#8377; 2600</strong>
                        <small><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg> 4.8 &nbsp; · &nbsp; 1,258 Sold</small>
                    </a>
                    <a class="related-card" href="{{ url('#') }}">
                        <img src="{{ asset('assets/img/img-3.jpg') }}" alt="Mizoya red cotton dress">
                        <h6>Mizoya Red Cotton Dress</h6>
                        <strong>&#8377; 2600</strong>
                        <small><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg> 4.8 &nbsp; · &nbsp; 1,258 Sold</small>
                    </a>
                    <a class="related-card" href="{{ url('#') }}">
                        <img src="{{ asset('assets/img/img-2.jpg') }}" alt="Mizoya red cotton dress">
                        <h6>Mizoya Red Cotton Dress</h6>
                        <strong>&#8377; 2600</strong>
                        <small><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg> 4.8 &nbsp; · &nbsp; 1,258 Sold</small>
                    </a>
                    <a class="related-card" href="{{ url('#') }}">
                        <img src="{{ asset('assets/img/img-1.jpg') }}" alt="Mizoya red cotton dress">
                        <h6>Mizoya Red Cotton Dress</h6>
                        <strong>&#8377; 2600</strong>
                        <small><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                                <path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.5 1.1 6.3-5.6-3-5.6 3 1.1-6.3L3 9.4l6.3-.9L12 2.8Z"
                                    fill="currentColor" stroke="none"></path>
                            </svg> 4.8 &nbsp; · &nbsp; 1,258 Sold</small>
                    </a>
                    --}}
                </div>
            </div>
        </section>
        @endif
    </main>



    <div class="review-lightbox" id="reviewLightbox" hidden aria-hidden="true">
        <div class="review-lightbox-backdrop"></div>
        <button type="button" class="review-lightbox-close" aria-label="Close review photo">×</button>
        <img id="reviewLightboxImg" src="" alt="Review photo">
    </div>

    {{-- Existing size-chart preview modal --}}
    @if($sizeChartData)
        <div class="modal fade" id="centimeterPreviewModal{{ $sizeChartData->id }}" tabindex="-1" aria-hidden="true">

            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">{{ $sizeChartData->title }}</h5>
                        <h4 class="textcentercss">{{ ucwords(str_replace('_', ' ', $sizeChartData->chart_format)) }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <ul class="nav nav-tabs mb-3" id="sizeChartTabs{{ $sizeChartData->id }}" role="tablist">

                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" data-bs-toggle="tab"
                                    data-bs-target="#inchTab{{ $sizeChartData->id }}" type="button">
                                    Inch
                                </button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#cmTab{{ $sizeChartData->id }}"
                                    type="button">
                                    CM
                                </button>
                            </li>

                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="inchTab{{ $sizeChartData->id }}">

                                @include('front.modals.chart_preview', [
                                    'result' => $sizeChartData,
                                    'unit' => 'inch'
                                ])

                            </div>
                            <div class="tab-pane fade" id="cmTab{{ $sizeChartData->id }}">

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
    @endif
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flickity@2/dist/flickity.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flickity@2/dist/flickity.pkgd.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6/dist/fancybox/fancybox.css" />
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6/dist/fancybox/fancybox.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

    <script src="{{ asset('assets/js/custom.js') }}"></script>

    <script src="{{ asset('assets/js/product-page.js') }}"></script>
    <script src="{{ asset('assets/js/cart.js') }}?v={{ filemtime(public_path('assets/js/cart.js')) }}"></script>
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

            $('.s-variant.active[data-type]').each(function () {
                const type = ($(this).data('type') || '').toString().toLowerCase();
                const value = $(this).data('value');

                if (type && value !== undefined) {
                    selected[type] = value;
                }
            });

            const exists = cartItems.some(function (item) {
                if (String(item.productId) !== String(productId)) return false;

                const variant = item.selectedVariants || {};
                const keys1 = Object.keys(selected);
                const keys2 = Object.keys(variant);

                if (keys1.length !== keys2.length) return false;

                return keys1.every(function (key) {
                    return String(variant[key]) === String(selected[key]);
                });
            });

            $('.addToCartBtn .addToCartText').text(exists ? 'Go To Cart' : 'Add to cart');
        }

        let latestVariantRequestId = 0;
        let activeGalleryIndex = 0;

        function updateStockAvailability(qty, message, isOutOfStock, availabilityError) {
            const parsedQty = Number.parseInt(qty, 10);
            const stockQty = Number.isFinite(parsedQty) ? Math.max(parsedQty, 0) : 0;
            const outOfStock = Number(isOutOfStock) === 1
                || isOutOfStock === true
                || (!availabilityError && stockQty === 0);
            const minQty = Math.max(Number.parseInt(minSellingQty, 10) || 1, 1);
            const configuredMax = Math.max(Number.parseInt(maxSellingQty, 10) || 0, 0);
            const maxQty = configuredMax > 0 ? Math.min(stockQty, configuredMax) : stockQty;
            const available = !availabilityError && !outOfStock && stockQty >= minQty && maxQty >= minQty;
            const $quantity = $('#quantity');
            const $addToCartBtn = $('.addToCartBtn');

            $quantity.attr({ min: minQty, max: available ? maxQty : Math.max(stockQty, 1) });
            if (!available || Number.parseInt($quantity.val(), 10) < minQty
                || Number.parseInt($quantity.val(), 10) > maxQty) {
                $quantity.val(minQty);
            }

            $addToCartBtn
                .prop('disabled', !available)
                .toggleClass('disabled-action', !available)
                .attr('aria-disabled', String(!available));
            $addToCartBtn.find('.addToCartText').text(
                available ? 'Add to cart' : (outOfStock ? 'Out of stock' : 'Unavailable')
            );
            if (available) {
                checkItemInCart();
            }
            $('#variantOutOfStock').toggleClass('d-none', available);
            $('.varient_less_than_min_qty_notice').text(
                available
                    ? (stockQty < maxQty ? 'Only ' + stockQty + ' items are available.' : '')
                    : (message || (outOfStock
                        ? 'Out of stock'
                        : 'Minimum order quantity is ' + minQty + ', but only ' + stockQty + ' are available.'))
            );
        }

        function setVariantAvailabilityPending() {
            $('.addToCartBtn')
                .prop('disabled', true)
                .addClass('disabled-action')
                .attr('aria-disabled', 'true');
            $('.addToCartBtn .addToCartText').text('Checking availability...');
            $('#variantOutOfStock').addClass('d-none');
            $('.varient_less_than_min_qty_notice').text('Checking availability...');
        }

        function selectVariant(el) {
            const productId = el.dataset.productId;
            const variantId = el.dataset.variantId;
            const variantValueId = el.dataset.vid;
            const value = el.dataset.value;
            const valueId = el.dataset.id;

            $('.s-variant[data-variant-id="' + variantId + '"]').removeClass('active is-selected');
            $(el).addClass('active is-selected');

            $('input.attribute-input[name="variant_' + variantId + '"]').prop('checked', false);
            $('input.attribute-input[name="variant_' + variantId + '"][value="' + variantValueId + '"]').prop('checked', true);

            const selectedSpan = document.getElementById('selected-value-' + valueId);
            if (selectedSpan) {
                selectedSpan.textContent = value;
            }

            const selectedVariantIds = $('.s-variant.active[data-type]').map(function () {
                return $(this).data('vid');
            }).get();

            checkItemInCart();

            const requestId = ++latestVariantRequestId;
            setVariantAvailabilityPending();

            $.ajax({
                url: getVarient,
                type: 'POST',
                data: JSON.stringify({
                    product_id: productId,
                    vsku: selectedVariantIds,
                    variant_id: variantId,
                    variant_value_id: variantValueId,
                    _token: window.csrfToken
                }),
                contentType: 'application/json',
                success: function (response) {
                    if (requestId !== latestVariantRequestId) return;

                    const combination = response?.combination;
                    if (!combination) {
                        updateStockAvailability(0, 'This variation combination is unavailable.');
                        return;
                    }

                    const buyingPrice = Number(combination.price) || 0;
                    const sellingPrice = Number(combination.selling_price) || 0;

                    $('#productSku').text(combination.sku || '');
                    $('#productPrice').text(`₹${Math.floor(sellingPrice)}`);
                    $('#pdpStrikedMrp').text(`₹${Math.floor(buyingPrice)}`);
                    $('#discountShow').text(
                        `₹ ${Math.max(Math.floor(buyingPrice - sellingPrice), 0)} OFF`
                    );

                    $('.addToCartBtn')
                        .attr('data-sku', combination.sku || '')
                        .attr('data-price', buyingPrice)
                        .attr('data-saleprice', sellingPrice)
                        .attr('data-discounttype', combination.discount_type || '')
                        .attr('data-discount', combination.discount || 0);

                    updateStockAvailability(combination.qty, null, combination.is_out_of_stock);
                    if (Array.isArray(response.images) && response.images.length) {
                        updateProductGallery(response.images);
                    }
                },
                error: function (xhr) {
                    if (requestId !== latestVariantRequestId) return;

                    console.error('Variant fetch error:', xhr.responseText);
                    updateStockAvailability(
                        0,
                        xhr.status === 404
                            ? 'This variation combination is unavailable.'
                            : 'Unable to check availability. Please try again.',
                        xhr.status === 404,
                        xhr.status !== 404
                    );
                }
            });
        }

        $(document).on('change.detailVariant', '.product-detail-page input.attribute-input', function () {
            const $variant = $(this).siblings('.s-variant');
            if ($variant.length && !$variant.hasClass('active')) {
                selectVariant($variant[0]);
            }
        });

        function showGalleryImage(index) {
            const $thumbnails = $('.detail-thumbnail');
            if (!$thumbnails.length) return;

            activeGalleryIndex = (index + $thumbnails.length) % $thumbnails.length;
            const $activeThumbnail = $thumbnails.eq(activeGalleryIndex);

            $('#detailMainImage')
                .attr('src', $activeThumbnail.attr('data-image'))
                .attr('alt', $activeThumbnail.find('img').attr('alt') || '');
            $thumbnails.removeClass('is-active');
            $activeThumbnail.addClass('is-active');
            $('.gallery-caption span').last().text(
                (activeGalleryIndex + 1) + '/' + $thumbnails.length
            );
        }

        function updateProductGallery(images) {
            const imageList = images.filter(function (image) {
                return image.graphic && image.graphic_type === 'image';
            });
            if (!imageList.length) return;

            const $thumbnails = $('.detail-thumbnails').empty();
            imageList.forEach(function (image, index) {
                const $thumbnail = $('<button>', {
                    type: 'button',
                    class: 'detail-thumbnail' + (index === 0 ? ' is-active' : ''),
                    'data-image': image.graphic,
                    'aria-label': 'View product image ' + (index + 1)
                });
                $thumbnail.append($('<img>', {
                    src: image.graphic,
                    alt: '{{ addslashes($product->name) }} image ' + (index + 1)
                }));
                $thumbnails.append($thumbnail);
            });

            activeGalleryIndex = 0;
            showGalleryImage(0);
        }

        $(document).on('click.detailGallery', '.detail-thumbnail', function () {
            showGalleryImage($(this).index());
        });
        $(document).on('click.detailGallery', '.gallery-prev', function () {
            showGalleryImage(activeGalleryIndex - 1);
        });
        $(document).on('click.detailGallery', '.gallery-next', function () {
            showGalleryImage(activeGalleryIndex + 1);
        });

        const $initialVariant = $('.s-variant.active[data-type]').first();
        if ($initialVariant.length) {
            selectVariant($initialVariant[0]);
        } else {
            const initialStock = Number($('#variantOutOfStock').data('product-out')) === 1
                ? 0
                : $('#quantity').attr('max');
            updateStockAvailability(initialStock);
        }



    </script>
    <script>

        // Best Offer Slider
        $(document).ready(function () {
            const $slides = $('.offer-promo-card .offer-slide');

            if (!$slides.length) return;

            let currentSlide = 0;
            let sliderInterval;

            function showOfferSlide(index) {
                $slides
                    .removeClass('is-active')
                    .attr('aria-hidden', 'true')
                    .attr('inert', '');

                currentSlide = (index + $slides.length) % $slides.length;

                $slides.eq(currentSlide)
                    .addClass('is-active')
                    .attr('aria-hidden', 'false')
                    .removeAttr('inert');
            }

            function startOfferSlider() {
                clearInterval(sliderInterval);

                sliderInterval = setInterval(function () {
                    showOfferSlide(currentSlide + 1);
                }, 3000);
            }

            showOfferSlide(0);
            startOfferSlider();

            // Pause autoplay when hovering
            $('.offer-promo-card')
                .on('mouseenter', function () {
                    clearInterval(sliderInterval);
                })
                .on('mouseleave', function () {
                    startOfferSlider();
                });
        });

    </script>
@endsection
@extends('front.layout.app')
@section('content')




    <section class="product-list-page page-container">

        <div class="product-list-layout">

            {{-- Mobile Filter Backdrop --}}
            <button class="filter-backdrop" type="button" aria-label="Close filters" aria-hidden="true"
                tabindex="-1"></button>

            {{-- FILTER SIDEBAR --}}
            <aside class="product-filters" id="filters" aria-label="Product filters" aria-labelledby="filterTitle">

                <div class="filter-heading">
                    <h1 id="filterTitle">Filters</h1>

                    <button class="filter-clear-button" type="button" data-filter-action="{{ url()->current() }}">
                        Clear all
                    </button>

                    <button class="filter-close-button" type="button" aria-label="Close filters">
                        <svg class="svg-icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="m6 6 12 12M18 6 6 18"></path>
                        </svg>
                    </button>
                </div>

                <form id="productFilters" action="{{ url()->current() }}" method="GET">

                    {{-- PRICE FILTER --}}
                    <details class="filter-group filter-price-group" open>
                        <summary>Price Range</summary>

                        <div class="filter-price-content">

                            <div class="filter-range-values" aria-hidden="true">
                                <span>&#8377;<span class="filter-range-min-value">{{ $selectedMinPrice }}</span></span>
                                <span>&#8377;<span class="filter-range-max-value">{{ $selectedMaxPrice }}</span></span>
                            </div>

                            <div class="filter-range-slider">
                                <input type="range" class="filter-price-min-range" min="{{ $priceFloor }}" max="{{ $priceCeiling }}" value="{{ $selectedMinPrice }}"
                                    aria-label="Minimum price slider">

                                <input type="range" class="filter-price-max-range" min="{{ $priceFloor }}" max="{{ $priceCeiling }}" value="{{ $selectedMaxPrice }}"
                                    aria-label="Maximum price slider">
                            </div>

                            <div class="filter-price-values">

                                <label>
                                    Min Price
                                    <input type="number" name="price_min" value="{{ $selectedMinPrice }}" min="{{ $priceFloor }}" max="{{ $priceCeiling }}">
                                </label>

                                <label>
                                    Max Price
                                    <input type="number" name="price_max" value="{{ $selectedMaxPrice }}" min="{{ $priceFloor }}" max="{{ $priceCeiling }}">
                                </label>

                            </div>
                        </div>
                    </details>

                    {{-- CATEGORY FILTER --}}
                    <details class="filter-group">
                        <summary>Select category</summary>

                        <div class="filter-options filter-category-options">
                            <label>
                                Parent category
                                <select name="category_id" class="filter-category-parent">
                                    <option value="">All categories</option>
                                    @foreach($filterCategories as $filterCategory)
                                        <option value="{{ $filterCategory->id }}"
                                            {{ (int) $selectedCategoryId === (int) $filterCategory->id ? 'selected' : '' }}>
                                            {{ $filterCategory->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </label>
                            <label>
                                Subcategory
                                <select name="sub_category_id" class="filter-category-child"
                                    data-selected="{{ $selectedSubCategoryId }}">
                                    <option value="">All subcategories</option>
                                    @foreach($allSubCategory as $filterCategory)
                                        <option value="{{ $filterCategory->id }}" data-parent="{{ $filterCategory->parent_id }}"
                                            {{ (int) $selectedSubCategoryId === (int) $filterCategory->id ? 'selected' : '' }}>
                                            {{ $filterCategory->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </label>
                            <label>
                                Sub-child category
                                <select name="sub_child_category_id" class="filter-category-grandchild"
                                    data-selected="{{ $selectedChildCategoryId }}">
                                    <option value="">All sub-child categories</option>
                                    @foreach($allChildCategory as $filterCategory)
                                        <option value="{{ $filterCategory->id }}" data-parent="{{ $filterCategory->parent_id }}"
                                            {{ (int) $selectedChildCategoryId === (int) $filterCategory->id ? 'selected' : '' }}>
                                            {{ $filterCategory->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                    </details>

                    {{-- COLOR FILTER --}}
                    <details class="filter-group filter-color-group">
                        <summary>Select colors</summary>

                        <div class="filter-options filter-color-options">

                            @foreach($variantColor as $filterColor)
                                <label>
                                    <input type="checkbox" name="variantValuesColor[]"
                                        value="{{ $filterColor->id }}"
                                        {{ in_array((int) $filterColor->id, $selectedColorIds, true) ? 'checked' : '' }}>
                                    <span class="filter-color-swatch"
                                        style="background-color: {{ $filterColor->color_code ?: '#ddd' }}"></span>
                                    <span>{{ $filterColor->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </details>

                    {{-- SIZE FILTER --}}
                    <details class="filter-group">
                        <summary>Select size</summary>

                        <div class="filter-options filter-size-options">

                            @foreach($variantSize as $filterSize)
                                <label>
                                    <input type="checkbox" name="variantValuesSize[]"
                                        value="{{ $filterSize->id }}"
                                        {{ in_array((int) $filterSize->id, $selectedSizeIds, true) ? 'checked' : '' }}>
                                    {{ $filterSize->name }}
                                </label>
                            @endforeach
                        </div>
                    </details>

                    {{-- FABRIC FILTER --}}
                    <details class="filter-group">
                        <summary>Product attributes</summary>

                        <div class="filter-options filter-fabric-options">
                            @foreach($attributes as $filterAttribute)
                                @if($filterAttribute->attribute_values->isNotEmpty())
                                    <strong>{{ $filterAttribute->name }}</strong>
                                @endif
                                @foreach($filterAttribute->attribute_values as $filterAttributeValue)
                                    <label>
                                        <input type="checkbox" name="attribute_value_id[]"
                                            value="{{ $filterAttributeValue->id }}"
                                            {{ in_array((int) $filterAttributeValue->id, $selectedAttributeValueIds, true) ? 'checked' : '' }}>
                                        {{ $filterAttributeValue->name }}
                                    </label>
                                @endforeach
                            @endforeach
                        </div>
                    </details>

                </form>

                <div class="filter-drawer-footer">
                    <button class="filter-apply-button" type="submit" form="productFilters">
                        Show results
                    </button>
                </div>

            </aside>


            {{-- PRODUCT RESULTS --}}
            <section class="product-results" aria-label="Product collection">

                {{-- PRODUCT TOOLBAR --}}
                <div class="product-toolbar">

                    <p class="product-count">
                        Total-{{ $totalResults }} items
                    </p>

                    <div class="product-toolbar-actions">

                        {{-- MOBILE FILTER BUTTON --}}
                        <button class="mobile-filter-button" type="button" aria-controls="filters"
                            aria-expanded="false">

                            <svg class="svg-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M4 6h16M7 12h10m-7 6h4"></path>
                            </svg>

                            <span>Filter</span>

                            <span class="filter-selection-count" aria-live="polite" hidden>0</span>

                        </button>

                        {{-- PRODUCT VIEW SWITCH --}}
                        <div class="view-switch" role="group" aria-label="Products per row">

                            <button type="button" class="view-switch-button" data-columns="3"
                                aria-label="Show 3 products per row" aria-pressed="false">

                                <svg class="view-icon" viewBox="0 0 24 20" aria-hidden="true">
                                    <path d="M6 3v14M12 3v14M18 3v14"></path>
                                </svg>

                            </button>

                            <button type="button" class="view-switch-button is-active" data-columns="4"
                                aria-label="Show 4 products per row" aria-pressed="true">

                                <svg class="view-icon" viewBox="0 0 24 20" aria-hidden="true">
                                    <path d="M4.5 3v14M9.5 3v14M14.5 3v14M19.5 3v14"></path>
                                </svg>

                            </button>

                        </div>

                        {{-- SORTING --}}
                        <label class="sort-select">

                            <span class="visually-hidden">Sort products</span>

                            <select name="sortBy" form="productFilters" aria-label="Sort products"
                                class="product-sort-select">
                                <option value="" {{ request('sortBy') ? '' : 'selected' }}>Sort By</option>
                                <option value="low_high" {{ request('sortBy') === 'low_high' ? 'selected' : '' }}>Price: low to high</option>
                                <option value="high_low" {{ request('sortBy') === 'high_low' ? 'selected' : '' }}>Price: high to low</option>
                                <option value="newest" {{ request('sortBy') === 'newest' ? 'selected' : '' }}>Newest arrivals</option>
                            </select>

                        </label>

                    </div>

                </div>


                {{-- DYNAMIC PRODUCT GRID --}}
                <div class="product-grid" id="productGrid" style="--product-columns: 4;">

                    @foreach($results as $product)

                        @php

                            $discount = "";

                            if (!empty($product->discount) && !empty($product->discount_type)) {
                                $discount = $product->discount;
                            }

                            $productUrl = !empty($product->sku)
                                ? route('front-product.detail', [
                                    'product' => 'product',
                                    'title' => $product->slug . '.html',
                                    'sku' => $product->sku
                                ])
                                : '#';

                        @endphp

                        <div class="listing-card">

                            {{-- PRODUCT IMAGE --}}
                            <div class="listing-image-wrap">

                                <a href="{{ $productUrl }}">
                                    <img src="{{ $product->images['first'] }}" alt="{{ $product->name }}">
                                </a>

                                {{-- PRODUCT BADGE --}}
                                @if(empty($discount))
                                    <span class="new-badge">New Arrival</span>
                                @endif

                                {{-- WISHLIST --}}
                                @php
                                    $isWishlisted = in_array((int) $product->id, array_map('intval', $isWishlisteddata ?? []), true);
                                @endphp
                                <button class="wishlist-button addtoWishList {{ $isWishlisted ? 'is-active' : '' }}" type="button"
                                    data-product-id="{{ $product->id }}"
                                    data-product-name="{{ $product->name }}"
                                    aria-pressed="{{ $isWishlisted ? 'true' : 'false' }}"
                                    aria-label="{{ $isWishlisted ? 'Remove ' : 'Add ' }}{{ $product->name }}{{ $isWishlisted ? ' from' : ' to' }} wishlist">

                                    <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">

                                        <path
                                            d="M20.8 8.8c0 5.2-8.8 11-8.8 11s-8.8-5.8-8.8-11A4.8 4.8 0 0 1 12 6.1a4.8 4.8 0 0 1 8.8 2.7Z">
                                        </path>

                                    </svg>

                                </button>

                            </div>


                            {{-- PRODUCT DETAILS --}}
                            <div class="listing-details">

                                {{-- PRODUCT NAME --}}
                                <h2>
                                    <a href="{{ $productUrl }}">
                                        {{ $product->name }}
                                    </a>
                                </h2>


                                {{-- PRODUCT PRICE --}}
                                <p class="listing-price">

                                    &#8377; {{ $product->selling_price }}

                                    @if(!empty($product->buying_price))
                                        <del>
                                            &#8377; {{ $product->buying_price }}
                                        </del>
                                    @endif

                                    @if(!empty($discount))
                                        <strong>
                                            ({{ $discount }}{{ $product->discount_type == 1 ? '%' : '' }} off)
                                        </strong>
                                    @endif

                                </p>


                             
                                {{-- <div class="listing-product-variants">

                                    @foreach($product->productVariants as $productVariant)

                                        @php
                                            $type = $productVariant->variant->type ?? null;
                                            $variantValues = $productVariant->variantValues ?? collect();
                                        @endphp


                                     
                                        @if($type == 1)

                                            <div class="product_colors variant-round">

                                                @foreach($variantValues as $item)

                                                    <span title="{{ $item->variant_value->name ?? '' }}"
                                                        data-variant-value-id="{{ $item->variant_value_id }}">
                                                    </span>

                                                @endforeach

                                            </div>


                                          
                                        @elseif($type == 2)

                                            <div class="product_sizes variant-box">

                                                @foreach($variantValues as $item)

                                                    <span data-variant-value-id="{{ $item->variant_value_id }}">
                                                        {{ $item->variant_value->name ?? '' }}
                                                    </span>

                                                @endforeach

                                            </div>


                                          
                                        @elseif($type == 3)

                                            <div class="product_colors variant-round variant-with-image">

                                                @foreach($variantValues as $item)

                                                    @php
                                                        $variantIcon = $product->product_main_images
                                                            ->where('variant_id', $item->variant_value_id)
                                                            ->where('is_variant_icon', 1)
                                                            ->first();
                                                    @endphp

                                                    <span data-variant-value-id="{{ $item->variant_value_id }}"
                                                        title="{{ $item->variant_value->name ?? '' }}">

                                                        @if($variantIcon)
                                                            <img src="{{ asset('uploads/products/' . $variantIcon->graphic) }}"
                                                                alt="{{ $item->variant_value->name ?? '' }}"
                                                                style="height:25px;width:25px;">
                                                        @endif

                                                    </span>

                                                @endforeach

                                            </div>


                                          
                                        @elseif($type == 4)

                                            <div class="product_colors variant-round">

                                                @foreach($variantValues as $item)

                                                    @php
                                                        $color = $item->variant_value->color_code ?? '#ffffff';
                                                    @endphp

                                                    <span data-variant-value-id="{{ $item->variant_value_id }}"
                                                        title="{{ $item->variant_value->name ?? '' }}"
                                                        style="background-color:{{ $color }}">
                                                    </span>

                                                @endforeach

                                            </div>


                                           
                                        @elseif($type == 5)

                                            <div class="product_sizes variant-box-color">

                                                @foreach($variantValues as $item)

                                                    @php
                                                        $color = $item->variant_value->color_code ?? '#ffffff';
                                                    @endphp

                                                    <span data-variant-value-id="{{ $item->variant_value_id }}"
                                                        title="{{ $item->variant_value->name ?? '' }}"
                                                        style="background-color:{{ $color }}">

                                                        {{ $item->variant_value->name ?? '' }}

                                                    </span>

                                                @endforeach

                                            </div>


                                            
                                        @elseif($type == 6)

                                            <div class="product_sizes variant-box-image">

                                                @foreach($variantValues as $item)

                                                    @php
                                                        $variantIcon = $product->product_main_images
                                                            ->where('variant_id', $item->variant_value_id)
                                                            ->where('is_variant_icon', 1)
                                                            ->first();
                                                    @endphp

                                                    <span data-variant-value-id="{{ $item->variant_value_id }}"
                                                        title="{{ $item->variant_value->name ?? '' }}">

                                                        @if($variantIcon)

                                                            <img src="{{ asset('uploads/products/' . $variantIcon->graphic) }}"
                                                                alt="{{ $item->variant_value->name ?? '' }}"
                                                                style="height:25px;width:25px;">

                                                        @else

                                                            {{ $item->variant_value->name ?? '' }}

                                                        @endif

                                                    </span>

                                                @endforeach

                                            </div>


                                          
                                        @elseif($type == 7)

                                            <div class="product_sizes variant-rectangle">

                                                @foreach($variantValues as $item)

                                                    <span data-variant-value-id="{{ $item->variant_value_id }}">
                                                        {{ $item->variant_value->name ?? '' }}
                                                    </span>

                                                @endforeach

                                            </div>


                                            
                                        @elseif($type == 8)

                                            <div class="product_sizes variant-rectangle-image">

                                                @foreach($variantValues as $item)

                                                    @php
                                                        $variantIcon = $product->product_main_images
                                                            ->where('variant_id', $item->variant_value_id)
                                                            ->where('is_variant_icon', 1)
                                                            ->first();
                                                    @endphp

                                                    <span data-variant-value-id="{{ $item->variant_value_id }}"
                                                        title="{{ $item->variant_value->name ?? '' }}">

                                                        @if($variantIcon)

                                                            <img src="{{ asset('uploads/products/' . $variantIcon->graphic) }}"
                                                                alt="{{ $item->variant_value->name ?? '' }}"
                                                                style="height:25px;width:25px;">

                                                        @else

                                                            {{ $item->variant_value->name ?? '' }}

                                                        @endif

                                                    </span>

                                                @endforeach

                                            </div>


                                            
                                        @elseif($type == 9)

                                            <div class="product_sizes variant-rectangle-color">

                                                @foreach($variantValues as $item)

                                                    @php
                                                        $color = $item->variant_value->color_code ?? '#ffffff';
                                                    @endphp

                                                    <span data-variant-value-id="{{ $item->variant_value_id }}"
                                                        title="{{ $item->variant_value->name ?? '' }}"
                                                        style="background-color:{{ $color }}">

                                                        {{ $item->variant_value->name ?? '' }}

                                                    </span>

                                                @endforeach

                                            </div>

                                        @endif

                                    @endforeach

                                </div> --}}


                            
                                @if($product->isOutOfStock)
                                    <span class="add-to-cart is-out-of-stock" aria-disabled="true">Out of stock</span>
                                @else
                                    <a href="{{ $productUrl }}" class="add-to-cart">
                                        Add to cart
                                    </a>
                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            </section>

        </div>

    </section>
@endsection
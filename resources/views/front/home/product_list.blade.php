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

                    <button type="reset" form="productFilters">
                        Clear all
                    </button>

                    <button class="filter-close-button" type="button" aria-label="Close filters">
                        <svg class="svg-icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="m6 6 12 12M18 6 6 18"></path>
                        </svg>
                    </button>
                </div>

                <form id="productFilters">

                    {{-- PRICE FILTER --}}
                    <details class="filter-group filter-price-group" open>
                        <summary>Price Range</summary>

                        <div class="filter-price-content">

                            <div class="filter-range-values" aria-hidden="true">
                                <span>&#8377;<span class="filter-range-min-value">500</span></span>
                                <span>&#8377;<span class="filter-range-max-value">10000</span></span>
                            </div>

                            <div class="filter-range-slider">
                                <input type="range" class="filter-price-min-range" min="500" max="10000" value="500"
                                    aria-label="Minimum price slider">

                                <input type="range" class="filter-price-max-range" min="500" max="10000" value="10000"
                                    aria-label="Maximum price slider">
                            </div>

                            <div class="filter-price-values">

                                <label>
                                    Min Price
                                    <input type="number" name="price-min" value="500" min="500" max="10000">
                                </label>

                                <label>
                                    Max Price
                                    <input type="number" name="price-max" value="10000" min="500" max="10000">
                                </label>

                            </div>
                        </div>
                    </details>

                    {{-- CATEGORY FILTER --}}
                    <details class="filter-group">
                        <summary>Select category</summary>

                        <div class="filter-options filter-category-options">

                            <label>
                                <input type="checkbox" name="category" value="kurta">
                                Kurta
                            </label>

                            <label>
                                <input type="checkbox" name="category" value="kurti">
                                Kurti
                            </label>

                            <label>
                                <input type="checkbox" name="category" value="kurta-pant">
                                Kurta-pant
                            </label>

                            <label>
                                <input type="checkbox" name="category" value="kaftan">
                                Kaftan
                            </label>

                            <label>
                                <input type="checkbox" name="category" value="co-ord-set">
                                Co-ord set
                            </label>

                            <label>
                                <input type="checkbox" name="category" value="new-arrivals">
                                New arrivals
                            </label>

                        </div>
                    </details>

                    {{-- COLOR FILTER --}}
                    <details class="filter-group filter-color-group">
                        <summary>Select colors</summary>

                        <div class="filter-options filter-color-options">

                            <label><input type="checkbox" name="color" value="purple"><span
                                    class="filter-color-swatch color-purple"></span><span>Purple</span></label>

                            <label><input type="checkbox" name="color" value="black"><span
                                    class="filter-color-swatch color-black"></span><span>Black</span></label>

                            <label><input type="checkbox" name="color" value="red"><span
                                    class="filter-color-swatch color-red"></span><span>Red</span></label>

                            <label><input type="checkbox" name="color" value="orange"><span
                                    class="filter-color-swatch color-orange"></span><span>Orange</span></label>

                            <label><input type="checkbox" name="color" value="navy"><span
                                    class="filter-color-swatch color-navy"></span><span>Navy</span></label>

                            <label><input type="checkbox" name="color" value="white"><span
                                    class="filter-color-swatch color-white"></span><span>White</span></label>

                            <label><input type="checkbox" name="color" value="brown"><span
                                    class="filter-color-swatch color-brown"></span><span>Brown</span></label>

                            <label><input type="checkbox" name="color" value="green"><span
                                    class="filter-color-swatch color-green"></span><span>Green</span></label>

                            <label><input type="checkbox" name="color" value="yellow"><span
                                    class="filter-color-swatch color-yellow"></span><span>Yellow</span></label>

                            <label><input type="checkbox" name="color" value="grey"><span
                                    class="filter-color-swatch color-grey"></span><span>Grey</span></label>

                            <label><input type="checkbox" name="color" value="pink"><span
                                    class="filter-color-swatch color-pink"></span><span>Pink</span></label>

                            <label><input type="checkbox" name="color" value="blue"><span
                                    class="filter-color-swatch color-blue"></span><span>Blue</span></label>

                        </div>
                    </details>

                    {{-- SIZE FILTER --}}
                    <details class="filter-group">
                        <summary>Select size</summary>

                        <div class="filter-options filter-size-options">

                            <label><input type="checkbox" name="size" value="xs"> XS</label>
                            <label><input type="checkbox" name="size" value="s"> S</label>
                            <label><input type="checkbox" name="size" value="m"> M</label>
                            <label><input type="checkbox" name="size" value="l"> L</label>
                            <label><input type="checkbox" name="size" value="xl"> XL</label>
                            <label><input type="checkbox" name="size" value="xxl"> XXL</label>

                        </div>
                    </details>

                    {{-- FABRIC FILTER --}}
                    <details class="filter-group">
                        <summary>Fabric material</summary>

                        <div class="filter-options filter-fabric-options">

                            <label><input type="checkbox" name="fabric" value="cotton"> Cotton</label>
                            <label><input type="checkbox" name="fabric" value="chanderi"> Chanderi</label>
                            <label><input type="checkbox" name="fabric" value="chiffon"> Chiffon</label>
                            <label><input type="checkbox" name="fabric" value="georgette"> Georgette</label>
                            <label><input type="checkbox" name="fabric" value="rayon"> Rayon</label>
                            <label><input type="checkbox" name="fabric" value="tissue"> Tissue</label>
                            <label><input type="checkbox" name="fabric" value="silk"> Silk</label>

                        </div>
                    </details>

                </form>

                <div class="filter-drawer-footer">
                    <button class="filter-apply-button" type="button">
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

                            <select aria-label="Sort products">
                                <option value="">Sort By</option>
                                <option value="low-high">Price: low to high</option>
                                <option value="high-low">Price: high to low</option>
                                <option value="newest">Newest arrivals</option>
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


                                {{-- PRODUCT VARIANTS --}}
                                <div class="listing-product-variants">

                                    @foreach($product->productVariants as $productVariant)

                                        @php
                                            $type = $productVariant->variant->type ?? null;
                                            $variantValues = $productVariant->variantValues ?? collect();
                                        @endphp


                                        {{-- TYPE 1: COLOR --}}
                                        @if($type == 1)

                                            <div class="product_colors variant-round">

                                                @foreach($variantValues as $item)

                                                    <span title="{{ $item->variant_value->name ?? '' }}"
                                                        data-variant-value-id="{{ $item->variant_value_id }}">
                                                    </span>

                                                @endforeach

                                            </div>


                                            {{-- TYPE 2: SIZE --}}
                                        @elseif($type == 2)

                                            <div class="product_sizes variant-box">

                                                @foreach($variantValues as $item)

                                                    <span data-variant-value-id="{{ $item->variant_value_id }}">
                                                        {{ $item->variant_value->name ?? '' }}
                                                    </span>

                                                @endforeach

                                            </div>


                                            {{-- TYPE 3: IMAGE VARIANT --}}
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


                                            {{-- TYPE 4: COLOR CODE --}}
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


                                            {{-- TYPE 5: SIZE WITH COLOR --}}
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


                                            {{-- TYPE 6: SIZE WITH IMAGE --}}
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


                                            {{-- TYPE 7: RECTANGLE SIZE --}}
                                        @elseif($type == 7)

                                            <div class="product_sizes variant-rectangle">

                                                @foreach($variantValues as $item)

                                                    <span data-variant-value-id="{{ $item->variant_value_id }}">
                                                        {{ $item->variant_value->name ?? '' }}
                                                    </span>

                                                @endforeach

                                            </div>


                                            {{-- TYPE 8: RECTANGLE IMAGE --}}
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


                                            {{-- TYPE 9: RECTANGLE COLOR --}}
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

                                </div>


                                {{-- ADD TO CART --}}
                                <a href="{{ $productUrl }}" class="add-to-cart">
                                    Add to cart
                                </a>

                            </div>

                        </div>

                    @endforeach

                </div>

            </section>

        </div>

    </section>
        @include('front.includes.footer')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
        <script src="{{asset('assets/js/custom.js')}}"></script>



@endsection
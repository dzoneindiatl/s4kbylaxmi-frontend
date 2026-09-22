<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SYK: Fashion Hero</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,100..700,0,0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body style="background-color: #fefaf8;">
    @include('front.includes.header')    
    <section class="product-listing">
        <div class="container">
            <div class="listing-layout">
                <aside class="filter-sidebar">
                    <div class="filter-header">
                        <h3>Filters</h3>
                    </div>
                    <div class="filter-group">
                        <h4>Categories</h4>
                        <label class="custom-check">
                            <input type="checkbox">
                            <span class="checkmark"></span>
                            Anarkali Sets
                        </label>
                        <label class="custom-check">
                            <input type="checkbox">
                            <span class="checkmark"></span>
                            Kurta Sets
                        </label>
                        <label class="custom-check">
                            <input type="checkbox">
                            <span class="checkmark"></span>
                            Co-Ord Sets
                        </label>
                        <label class="custom-check">
                            <input type="checkbox">
                            <span class="checkmark"></span>
                            Ethnic Sets
                        </label>
                    </div>

                    <div class="filter-group">
                        <h4>Size</h4>

                        <div class="size-filter">
                            <span>S</span>
                            <span>M</span>
                            <span>L</span>
                            <span>XL</span>
                            <span>XXL</span>
                        </div>
                    </div>

                    <div class="filter-group">
                        <h4>Color</h4>

                        <div class="color-filter">
                            <span style="background:#922B21"></span>
                            <span style="background:#D4A64A"></span>
                            <span style="background:#000"></span>
                            <span style="background:#F4E7E5"></span>
                        </div>
                    </div>

                    <div class="filter-group">
                        <h4>Fabric</h4>


                        <label class="custom-check">
                            <input type="checkbox">
                            <span class="checkmark"></span>
                            Cotton
                        </label>
                        <label class="custom-check">
                            <input type="checkbox">
                            <span class="checkmark"></span>
                            Rayon
                        </label>
                        <label class="custom-check">
                            <input type="checkbox">
                            <span class="checkmark"></span>
                            Silk
                        </label>
                        <label class="custom-check">
                            <input type="checkbox">
                            <span class="checkmark"></span>
                            Georgette
                        </label>
                    </div>

                    <div class="filter-group">
                        <h4>Price</h4>
                        <label class="custom-check">
                            <input type="checkbox">
                            <span class="checkmark"></span>
                            Below 1000
                        </label>
                        <label class="custom-check">
                            <input type="checkbox">
                            <span class="checkmark"></span>
                            Below 2000
                        </label>
                        <label class="custom-check">
                            <input type="checkbox">
                            <span class="checkmark"></span>
                            Below 3000
                        </label>
                        <label class="custom-check">
                            <input type="checkbox">
                            <span class="checkmark"></span>
                            Below 4000
                        </label>
                    </div>
                </aside>

                <div class="product-grid-wrap">
                    <div class="listing-topbar">
                        <p>Showing {{ $totalResults }} Products</p>
                        <select>
                            <option>Featured</option>
                            <option>Price Low to High</option>
                            <option>Price High to Low</option>
                            <option>Newest First</option>
                        </select>
                    </div>

                    <div class="product-grid">
                    @foreach($results as $product)    
                        <div class="product_item">
                            <div class="product_image">
                               <a href="@if(!empty($product->sku)){{ route('front-product.detail', ['product' => 'product', 'title' => $product->slug . '.html', 'sku' => $product->sku]) }} @endif"><img src="{{ $product->images['first'] }}" alt=""></a> 
                                <div class="product_hover">
                                    <svg class="svg_shape" viewBox="0 0 368 94" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0,94 L0,82 C12,82 18,78 24,70 C32,58 45,48 68,48 C92,48 106,28 126,18 C144,9 162,12 184,18
                                            C206,12 224,9 242,18
                                            C262,28 276,48 300,48
                                            C323,48 336,58 344,70
                                            C350,78 356,82 368,82 L368,94 Z" fill="#f8f3ee"/>
                                    </svg>
                                    <div class="product_colors">
                                    {{-- @foreach($product->productVariants as $productVariant)     --}}
                                        @foreach($product->color_variants as $color) 
                                            <span style="background: {{ $color['color_code'] }}"></span>
                                        @endforeach 
                                    {{-- @endforeach         --}}
                                    </div>
                                    <div class="product_sizes">
                                        @foreach($product->size_variants as $size)    
                                            <span>{{ $size }}</span>
                                        @endforeach 
                                    </div>
                                    <a href="@if(!empty($product->sku)){{ route('front-product.detail', ['product' => 'product', 'title' => $product->slug . '.html', 'sku' => $product->sku]) }} @endif" class="btn_cart">
                                        Add To Cart
                                    </a>
                                </div>
                                
                                @php 
                                    $discount ="";
                                    if(!empty($product->discount) && !empty($product->discount_type)){
                                        $discount = $product->discount; 
                                    }
                                @endphp 
                                <span class="product_tag"> @if(!empty($discount)) @else New @endif </span>

                                <div class="product_tag_wishlist">
                                    <span class="material-symbols-outlined">
                                        favorite
                                    </span>
                                </div>
                            </div>
                            <div class="product_data">
                                <div class="product_title">
                                    <a href=""> {{ $product->name }}</a>
                                </div>
                                <div class="product_price"><span class="price_orignal">Rs.{{ $product->selling_price }}</span> <span
                                        class="price_old">Rs.{{ $product->buying_price }}</span></div>
                            </div>
                        </div>
                    @endforeach   

                    </div>

                </div>

            </div>

        </div>

    </section>
    @include('front.includes.footer')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script src="{{asset('assets/js/custom.js')}}"></script>

</body>

</html>
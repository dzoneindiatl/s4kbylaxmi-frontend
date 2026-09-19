@extends('front.layout.app')
@section('content')
<div class="offer-tag">
        <span>OFFER</span>
        <p>Buy for ₹2499 & More, Get flat ₹100 Off I Use Code: SALE100</p>
    </div>

    <!-- HERO -->
    <section class="hero-gallery">
        <div class="hero-main-slider">
            <div><img src="{{asset('assets/images/slider_01.png')}}" alt=""></div>
            <div><img src="{{asset('assets/images/slider_02.png')}}" alt=""></div>
            <div><img src="{{asset('assets/images/slider_03.png')}}" alt=""></div>
            <div><img src="{{asset('assets/images/slider_04.png')}}" alt=""></div>
            <div><img src="{{asset('assets/images/slider_01.png')}}" alt=""></div>
            <div><img src="{{asset('assets/images/slider_02.png')}}" alt=""></div>
            <div><img src="{{asset('assets/images/slider_03.png')}}" alt=""></div>
            <div><img src="{{asset('assets/images/slider_04.png')}}" alt=""></div>
        </div>
        <div class="hero-thumb-slider">
            <div><img src="{{asset('assets/images/slider_01.png')}}" alt=""></div>
            <div><img src="{{asset('assets/images/slider_02.png')}}" alt=""></div>
            <div><img src="{{asset('assets/images/slider_03.png')}}" alt=""></div>
            <div><img src="{{asset('assets/images/slider_04.png')}}" alt=""></div>
            <div><img src="{{asset('assets/images/slider_01.png')}}" alt=""></div>
            <div><img src="{{asset('assets/images/slider_02.png')}}" alt=""></div>
            <div><img src="{{asset('assets/images/slider_03.png')}}" alt=""></div>
            <div><img src="{{asset('assets/images/slider_04.png')}}" alt=""></div>
        </div>
    </section>

    <!-- IMAGES GRID -->
    <section class="lookbook-section">
        <div class="container">
            <div class="lookbook-grid">

                <!-- Left Column -->
                <div class="lookbook-column left">

                    <a href="#" class="lookbook-item small">
                        <img src="{{asset('assets/images/category_image_03.png')}}" alt="">
                        <div class="overlay">
                            <h3>Kaftan Dress</h3>
                            <span>Explore Collection</span>
                        </div>
                    </a>

                    <a href="#" class="lookbook-item medium">
                        <img src="{{asset('assets/images/category_image_03.png')}}" alt="">
                        <div class="overlay">
                            <h3>Kaftan Dress</h3>
                            <span>Explore Collection</span>
                        </div>
                    </a>

                    <a href="#" class="lookbook-item small">
                        <img src="{{asset('assets/images/category_image_01.png')}}" alt="">
                        <div class="overlay">
                            <h3>New Arrivals</h3>
                            <span>Explore Collection</span>
                        </div>
                    </a>

                </div>

                <!-- Center Column -->
                <div class="lookbook-column center">

                    <div class="lookbook-column-inner">
                        <a href="#" class="lookbook-item large">
                            <img src="{{asset('assets/images/large_cat1.jpg')}}" alt="">
                            <div class="overlay">
                                <h3>Anarkali Gown</h3>
                                <span>Explore Collection</span>
                            </div>
                        </a>
                        <a href="#" class="lookbook-item large">
                            <img src="{{asset('assets/images/large_cat1.jpg')}}" alt="">
                            <div class="overlay">
                                <h3>Anarkali Gown</h3>
                                <span>Explore Collection</span>
                            </div>
                        </a>
                        <a href="#" class="lookbook-item large">
                            <img src="{{asset('assets/images/large_cat1.jpg')}}" alt="">
                            <div class="overlay">
                                <h3>Anarkali Gown</h3>
                                <span>Explore Collection</span>
                            </div>
                        </a>
                        <a href="#" class="lookbook-item large">
                            <img src="{{asset('assets/images/large_cat1.jpg')}}" alt="">
                            <div class="overlay">
                                <h3>Anarkali Gown</h3>
                                <span>Explore Collection</span>
                            </div>
                        </a>
                    </div>

                    <div class="lookbook-column-inner">
                        <a href="#" class="lookbook-item large">
                            <img src="{{asset('assets/images/collection_image_03.jpg')}}" alt="">
                            <div class="overlay">
                                <h3>Luxury Edit</h3>
                                <span>Explore Collection</span>
                            </div>
                        </a>
                        <a href="#" class="lookbook-item large">
                            <img src="{{asset('assets/images/collection_image_03.jpg')}}" alt="">
                            <div class="overlay">
                                <h3>Luxury Edit</h3>
                                <span>Explore Collection</span>
                            </div>
                        </a>
                        <a href="#" class="lookbook-item large">
                            <img src="{{asset('assets/images/collection_image_03.jpg')}}" alt="">
                            <div class="overlay">
                                <h3>Luxury Edit</h3>
                                <span>Explore Collection</span>
                            </div>
                        </a>
                        <a href="#" class="lookbook-item large">
                            <img src="{{asset('assets/images/collection_image_03.jpg')}}" alt="">
                            <div class="overlay">
                                <h3>Luxury Edit</h3>
                                <span>Explore Collection</span>
                            </div>
                        </a>
                    </div>

                </div>

                <!-- Right Column -->
                <div class="lookbook-column right">

                    <a href="#" class="lookbook-item tall">
                        <img src="{{asset("assets/images/category_image_02.png")}}" alt="">
                        <div class="overlay">
                            <h3>Co-Ord Set</h3>
                            <span>Explore Collection</span>
                        </div>
                    </a>

                    <a href="#" class="lookbook-item tall">
                        <img src="{{asset("assets/images/category_image_10.png")}}" alt="">
                        <div class="overlay">
                            <h3>Festive Wear</h3>
                            <span>Explore Collection</span>
                        </div>
                    </a>

                </div>

            </div>
        </div>
    </section>
    <section class="feature-category featured-category-inner">
        <div class="comman-container">
            <div class="sec-head text-center">
                <span class="curated mb-2">Curated for You</span>
                <h2 class="featured-title letter-spacing-2 fw-normal">Featured Categories</h2>
            </div>
            <div class="feature-category-wrapper">

                <div class="feature-category-item">
                    <div class="feature-cat-img">
                        <img src="images/category_image_01.png" alt="Short Kurtis">
                        <div class="collection-brd"></div>
                    </div>
                    <div class="feature-category-overlay">
                        <h5><a href="#">Short Kurtis</a></h5>
                    </div>
                </div>

                <div class="feature-category-item">
                    <div class="feature-cat-img">
                        <img src="images/category_image_02.png" alt="New Arrivals">
                        <div class="collection-brd"></div>
                    </div>
                    <div class="feature-category-overlay">
                        <h5><a href="#">New Arrivals</a></h5>
                    </div>
                </div>

                <div class="feature-category-item">
                    <div class="feature-cat-img">
                        <img src="images/category_image_03.png" alt="Best Sellers">
                        <div class="collection-brd"></div>
                    </div>
                    <div class="feature-category-overlay">
                        <h5><a href="#">Best Sellers</a></h5>
                    </div>
                </div>

                <div class="feature-category-item">
                    <div class="feature-cat-img">
                        <img src="images/category_image_04.png" alt="Kurtas">
                        <div class="collection-brd"></div>
                    </div>
                    <div class="feature-category-overlay">
                        <h5><a href="#">Kurtas</a></h5>
                    </div>
                </div>

                <div class="feature-category-item">
                    <div class="feature-cat-img">
                        <img src="images/category_image_05.png" alt="Co-ords">
                        <div class="collection-brd"></div>
                    </div>
                    <div class="feature-category-overlay">
                        <h5><a href="#">Co-ords</a></h5>
                    </div>
                </div>

                <div class="feature-category-item">
                    <div class="feature-cat-img">
                        <img src="images/category_image_06.png" alt="Dresses">
                        <div class="collection-brd"></div>
                    </div>
                    <div class="feature-category-overlay">
                        <h5><a href="#">Dresses</a></h5>
                    </div>
                </div>

                <div class="feature-category-item">
                    <div class="feature-cat-img">
                        <img src="images/category_image_07.png" alt="Maternity Wear">
                        <div class="collection-brd"></div>
                    </div>
                    <div class="feature-category-overlay">
                        <h5><a href="#">Maternity Wear</a></h5>
                    </div>
                </div>

                <div class="feature-category-item">
                    <div class="feature-cat-img">
                        <img src="images/category_image_08.png" alt="Kurta Set">
                        <div class="collection-brd"></div>
                    </div>
                    <div class="feature-category-overlay">
                        <h5><a href="#">Kurta Set</a></h5>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="make-it-sec-main" style="display: none;">
        <div class="make-it-sec-main-inner">
            <div class="make-it-sec-main-inner1">
                <div class="make-it-sec-main-left">
                    <div class="make-it-img-left">
                        <img src="images/category_image_08.png" alt="Kurta Set">
                    </div>
                    <div class="make-it-img-left">
                        <img src="images/category_image_01.png" alt="Kurta Set">
                    </div>
                    <div class="make-it-img-left">
                        <img src="images/category_image_02.png" alt="Kurta Set">
                    </div>
                </div>
                <div class="make-it-sec-main-right">
                    <div class="make-it-img-right">
                        <img src="images/category_image_03.png" alt="Kurta Set">
                    </div>
                    <div class="make-it-img-right-inner">
                        <div class="make-it-img-center">
                            <img src="images/category_image_04.png" alt="Kurta Set">
                        </div>
                        <div class="make-it-img-center">
                            <img src="images/category_image_05.png" alt="Kurta Set">
                        </div>
                    </div>
                    <div class="make-it-img-right">
                        <img src="images/category_image_06.png" alt="Kurta Set">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Trending collection -->
    <section class="trending-collection">
        <div class="container">
            <div class="sec-head text-center pb-4">
                <span class="curated mb-2">Curated for You</span>
                <h2 class="featured-title letter-spacing-2 fw-normal">Trending Collection</h2>
            </div>
            <div class="trending-collection-section">
                <div class="collection_grid">
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
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-center mt-50">
                <a href="#" class="btn_secondary">
                    View Collection
                    <span class="material-symbols-outlined">
                        arrow_right_alt
                    </span>
                </a>
            </div>
        </div>
    </section>

    <!-- Category Collection -->
    <section class="category_collection pt-70 pb-70">
        <div class="container">
            <div class="ethnic_wrapper">
                <div class="ethnic_gallery">
                    <div class="column-one">
                        <div class="galler-img-list">
                            <img src="images/large_cat1.jpg" alt="">
                        </div>
                        <div class="ehtinic_content">
                            <h2>Anarkali Gown Set</h2>
                            <p>Gracefully crafted silhouettes with timeless ethnic charm.</p>
                        </div>
                    </div>

                    <div class="column-one">
                        <div class="galler-img-list">
                            <img src="images/large_cat2.jpeg" alt="">
                        </div>
                        <div class="ehtinic_content">
                            <h2>Ethnic Set</h2>
                            <p>Gracefully crafted silhouettes with timeless ethnic charm.</p>
                        </div>
                    </div>

                    <div class="column-one">
                        <div class="galler-img-list">
                            <img src="images/large_cat3.jpeg" alt="">
                        </div>
                        <div class="ehtinic_content">
                            <h2>Kurta Pant dupatta</h2>
                            <p>Designed to make every occasion feel effortlessly special.</p>
                        </div>
                    </div>

                    <div class="column-one">
                        <div class="galler-img-list">
                            <img src="images/category_image_02.png" alt="">
                        </div>
                        <div class="ehtinic_content">
                            <h2>Ethnic Set</h2>
                            <p>Gracefully crafted silhouettes with timeless ethnic charm.</p>
                        </div>
                    </div>

                    <div class="column-one">
                        <div class="galler-img-list">
                            <img src="images/category_image_05.png" alt="">
                        </div>
                        <div class="ehtinic_content">
                            <h2>Kurta Pant dupatta</h2>
                            <p>Designed to make every occasion feel effortlessly special.</p>
                        </div>
                    </div>

                    <div class="column-one">
                        <div class="galler-img-list">
                            <img src="images/large_cat1.jpg" alt="">
                        </div>
                        <div class="ehtinic_content">
                            <h2>Anarkali Gown Set</h2>
                            <p>Gracefully crafted silhouettes with timeless ethnic charm.</p>
                        </div>
                    </div>

                    <div class="column-one">
                        <div class="galler-img-list">
                            <img src="images/large_cat2.jpeg" alt="">
                        </div>
                        <div class="ehtinic_content">
                            <h2>Ethnic Set</h2>
                            <p>Gracefully crafted silhouettes with timeless ethnic charm.</p>
                        </div>
                    </div>

                    <div class="column-one">
                        <div class="galler-img-list">
                            <img src="images/large_cat3.jpeg" alt="">
                        </div>
                        <div class="ehtinic_content">
                            <h2>Kurta Pant dupatta</h2>
                            <p>Designed to make every occasion feel effortlessly special.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="discover-section-main">
        <div class="discover-section-main-inner">
            <div class="discover-intro">
                <h2>Discover The<br> Laxmi Fabtax<br> With Us</h2>
                <div class="discover-cta">
                    <span>Now available at the Callum</span>
                    <a href="#" class="discover-arrow">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M5 12H19"></path>
                            <path d="M13 6L19 12L13 18"></path>
                        </svg>
                    </a>
                </div>
            </div>
            
            <a href="#" class="jewellery-card">
                <div class="jewellery-image">
                    <img src="images/s4k-3.jpeg" alt="img">
                </div>
                <h3>Fabtax 1</h3>
            </a>
            <a href="#" class="jewellery-card">
                <div class="jewellery-image">
                    <img src="images/s4k-2.jpeg" alt="img">
                </div>
                <h3>Fabtax 2</h3>
            </a>
            <a href="#" class="jewellery-card">
                <div class="jewellery-image">
                    <img src="images/s4k-3.jpeg" alt="img">
                </div>
                <h3>Fabtax 3</h3>
            </a>
            <a href="#" class="jewellery-card">
                <div class="jewellery-image">
                    <img src="images/s4k-2.jpeg" alt="img">
                </div>
                <h3>Fabtax 4</h3>
            </a>
            <a href="#" class="jewellery-card">
                <div class="jewellery-image">
                    <img src="images/s4k-3.jpeg" alt="img">
                </div>
                <h3>Fabtax 5</h3>
            </a>
            <a href="#" class="jewellery-card">
                <div class="jewellery-image">
                    <img src="images/s4k-2.jpeg" alt="img">
                </div>
                <h3>Fabtax 6</h3>
            </a>
            <a href="#" class="jewellery-card">
                <div class="jewellery-image">
                    <img src="images/s4k-3.jpeg" alt="img">
                </div>
                <h3>Fabtax 7</h3>
            </a>
            <a href="#" class="jewellery-card">
                <div class="jewellery-image">
                    <img src="images/s4k-2.jpeg" alt="img">
                </div>
                <h3>Fabtax 8</h3>
            </a>
            <a href="#" class="jewellery-card">
                <div class="jewellery-image">
                    <img src="images/s4k-3.jpeg" alt="img">
                </div>
                <h3>Fabtax 9</h3>
            </a>
            <a href="#" class="jewellery-card">
                <div class="jewellery-image">
                    <img src="images/s4k-2.jpeg" alt="img">
                </div>
                <h3>Fabtax 10</h3>
            </a>
        </div>
    </section>

    <section class="occasion-section">
        <div class="occasion-container">

            <!-- Occasion Cards -->
            <div class="occasion-grid">
                <a href="#" class="occasion-card">
                    <div class="occasion-img">
                        <img src="images/s4k-3.jpeg" alt="img">
                    </div>
                    <div class="occasion-name">Below 200</div>
                </a>
                <a href="#" class="occasion-card">
                    <div class="occasion-img">
                        <img src="images/s4k-2.jpeg" alt="img">
                    </div>
                    <div class="occasion-name">Below 599</div>
                </a>
                <a href="#" class="occasion-card">
                    <div class="occasion-img">
                        <img src="images/s4k-3.jpeg" alt="img">
                    </div>
                    <div class="occasion-name">Below 459</div>
                </a>
                <a href="#" class="occasion-card">
                    <div class="occasion-img">
                        <img src="images/s4k-2.jpeg" alt="img">
                    </div>
                    <div class="occasion-name">Below 325</div>
                </a>
                <a href="#" class="occasion-card">
                    <div class="occasion-img">
                        <img src="images/s4k-3.jpeg" alt="img">
                    </div>
                    <div class="occasion-name">Below 999</div>
                </a>
                <a href="#" class="occasion-card">
                    <div class="occasion-img">
                        <img src="images/s4k-2.jpeg" alt="img">
                    </div>
                    <div class="occasion-name">Below 2000</div>
                </a>
            </div>

            <!-- Right Content -->
            <div class="occasion-content">
                <div class="occasion-content-inner">
                    <h2>This is where your<br> Indian identity can<br> become very strong.</h2>
                    <!-- <p>Sparkle up your style<br> with a piece of jewelry<br> from the glam collection</p> -->
                    <div class="discover-cta">
                        <span>Explore Now</span>
                        <a href="#" class="discover-arrow">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M5 12H19"></path>
                                <path d="M13 6L19 12L13 18"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bridal-hero">

    <!-- Background -->
    <div class="bridal-bg"></div>
    <div class="bridal-overlay"></div>

    <div class="bridal-container">

        <!-- Image 1 -->
        <div class="bridal-img bridal-img-1">
            <!-- <div class="shape-image">
                <img src="images/large_cat1.jpg" alt="image1">
            </div> -->

            <div class="hex-img">
                <img src="images/large_cat1.jpg" alt="image1">

                <!-- Outer border -->
                <svg class="hex-border" viewBox="0 0 240 240">
                    <polygon points="
                        65,8
                        170,22
                        230,95
                        205,185
                        125,232
                        30,195
                        8,105
                    " />
                </svg>

                <!-- Inner decorative border -->
                <svg class="hex-border hex-border-inner" viewBox="0 0 240 240">
                    <polygon points="
                        65,8
                        170,22
                        230,95
                        205,185
                        125,232
                        30,195
                        8,105
                    " />
                </svg>
            </div>
        </div>


        <!-- Image 2 -->
        <div class="bridal-img bridal-img-2">
            <!-- <div class="shape-image">
                <img src="images/large_cat2.jpeg" alt="image2">
            </div> -->

            <div class="hex-img">
                <img src="images/large_cat2.jpeg" alt="image2">

                <!-- Outer border -->
                <svg class="hex-border" viewBox="0 0 240 240">
                    <polygon points="
                        65,8
                        170,22
                        230,95
                        205,185
                        125,232
                        30,195
                        8,105
                    " />
                </svg>

                <!-- Inner decorative border -->
                <svg class="hex-border hex-border-inner" viewBox="0 0 240 240">
                    <polygon points="
                        65,8
                        170,22
                        230,95
                        205,185
                        125,232
                        30,195
                        8,105
                    " />
                </svg>
            </div>
        </div>


        <!-- Image 3 -->
        <div class="bridal-img bridal-img-3">
            <!-- <div class="shape-image">
                <img src="images/large_cat3.jpeg" alt="image3">
            </div> -->

            <div class="hex-img">
                <img src="images/large_cat3.jpeg" alt="image3">

                <!-- Outer border -->
                <svg class="hex-border" viewBox="0 0 240 240">
                    <polygon points="
                        65,8
                        170,22
                        230,95
                        205,185
                        125,232
                        30,195
                        8,105
                    " />
                </svg>

                <!-- Inner decorative border -->
                <svg class="hex-border hex-border-inner" viewBox="0 0 240 240">
                    <polygon points="
                        65,8
                        170,22
                        230,95
                        205,185
                        125,232
                        30,195
                        8,105
                    " />
                </svg>
            </div>
        </div>


        <!-- Image 4 -->
        <div class="bridal-img bridal-img-4">
            <!-- <div class="shape-image">
                <img src="images/category_image_02.png" alt="image4">
            </div> -->

            <div class="hex-img">
                <img src="images/category_image_02.png" alt="image4">

                <!-- Outer border -->
                <svg class="hex-border" viewBox="0 0 240 240">
                    <polygon points="
                        65,8
                        170,22
                        230,95
                        205,185
                        125,232
                        30,195
                        8,105
                    " />
                </svg>

                <!-- Inner decorative border -->
                <svg class="hex-border hex-border-inner" viewBox="0 0 240 240">
                    <polygon points="
                        65,8
                        170,22
                        230,95
                        205,185
                        125,232
                        30,195
                        8,105
                    " />
                </svg>
            </div>
        </div>


        <!-- Image 5 -->
        <div class="bridal-img bridal-img-5">
<!--             <div class="shape-image">
                <img src="images/category_image_05.png" alt="image5">
            </div> -->

            <div class="hex-img">
                <img src="images/category_image_05.png" alt="image5">

                <!-- Outer border -->
                <svg class="hex-border" viewBox="0 0 240 240">
                    <polygon points="
                        65,8
                        170,22
                        230,95
                        205,185
                        125,232
                        30,195
                        8,105
                    " />
                </svg>

                <!-- Inner decorative border -->
                <svg class="hex-border hex-border-inner" viewBox="0 0 240 240">
                    <polygon points="
                        65,8
                        170,22
                        230,95
                        205,185
                        125,232
                        30,195
                        8,105
                    " />
                </svg>
            </div>
        </div>

        <!-- Image 6 -->
        <div class="bridal-img bridal-img-6">
<!--             <div class="shape-image">
                <img src="images/collection_image_02.jpg" alt="image6">
            </div> -->

            <div class="hex-img">
                <img src="images/collection_image_02.jpg" alt="image6">

                <!-- Outer border -->
                <svg class="hex-border" viewBox="0 0 240 240">
                    <polygon points="
                        65,8
                        170,22
                        230,95
                        205,185
                        125,232
                        30,195
                        8,105
                    " />
                </svg>

                <!-- Inner decorative border -->
                <svg class="hex-border hex-border-inner" viewBox="0 0 240 240">
                    <polygon points="
                        65,8
                        170,22
                        230,95
                        205,185
                        125,232
                        30,195
                        8,105
                    " />
                </svg>
            </div>
        </div>

        <!-- Content -->
        <div class="bridal-content">
            <h1>THE BRIDAL EDIT</h1>
            <p>Discover beautifully crafted Anarkali Gown Sets, Kaftan Dresses and Co-Ord collections designed for modern women who appreciate tradition.</p>
            <a href="#" class="bridal-btn">
                <span>EXPLORE BRIDAL</span>
                <span class="btn-arrow">
                    <svg viewBox="0 0 24 24">
                        <path d="M4 12h15"></path>
                        <path d="M13 6l6 6-6 6"></path>
                    </svg>
                </span>
            </a>
        </div>
    </div>
</section>

    <!-- BIG IMAGE SECTION -->
    <section class="big-image-section">
        <div class="left-card">
            <img src="images/left-image.png" alt="">
            <div class="content">
                <h2>LUXE EDIT</h2>
                <p>Timeless silhouettes, crafted to leave a lasting impression.</p>
                <a href="">Shop Now <span class="material-symbols-outlined">
                        arrow_right_alt
                    </span></a>
            </div>
        </div>
        <div class="right-card">
            <div class="column">
                <a href="" class="image-card">
                    <img src="images/category_image_02.png" alt="">
                    <div class="title">Ethnic Set</div>
                </a>
                <a href="" class="image-card">
                    <img src="images/category_image_03.png" alt="">
                    <div class="title">Ethnic Set</div>
                </a>
            </div>
            <div class="column">
                <a href="" class="image-card">
                    <img src="images/category_image_04.png" alt="">
                    <div class="title">Ethnic Set</div>
                </a>
                <a href="" class="image-card">
                    <img src="images/category_image_05.png" alt="">
                    <div class="title">Ethnic Set</div>
                </a>
            </div>
        </div>
    </section>

    <section class="our-best-seller-main">
        <div class="our-best-seller-inner">
            <div class="our-best-seller-inner1">
                <div class="our-best-seller-left">
                    <div class="sec-img-best-left">
                        <img src="images/category_image_02.png" alt="img">
                    </div>
                    <div class="sec-img-best-left">
                        <img src="images/category_image_03.png" alt="img">
                    </div>
                </div>
                <div class="our-best-seller-center">
                    <div class="sec-img-best-center">
                        <img src="images/category_image_05.png" alt="img">
                    </div>
                    <div class="sec-head text-center">
                        <span class="curated mb-2">Curated for You</span>
                        <h2 class="featured-title letter-spacing-2 fw-normal">Our Bestseller</h2>
                        <p>Gracefully crafted silhouettes with timeless.</p>
                    </div>
                    <div class="sec-img-best-center">
                        <img src="images/large_cat3.jpeg" alt="img">
                    </div>
                </div>
                <div class="our-best-seller-right">
                    <div class="sec-img-best-right">
                        <img src="images/category_image_03.png" alt="img">
                    </div>
                    <div class="sec-img-best-right">
                        <img src="images/category_image_04.png" alt="img">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ETHINIC SHOWCASE  -->
    <section class="ethnic-showcase">
        <div class="ethnic-pattern"></div>
        <div class="container">
            <div class="ethnic-main-grid">
                <div class="ethnic-image-wrap">
                    <div class="ethnic-card">
                        <div class="ethnic-image">
                            <img src="images/category_image_01.png" alt="">
                            <svg class="frame-border" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 700">
                                <path
                                    d="         M40 700         L40 250         Q40 200 85 190         Q65 145 105 130         Q95 85 145 80         Q150 40 210 40         Q235 0 250 0         Q265 0 290 40         Q350 40 355 80         Q405 85 395 130         Q435 145 415 190         Q460 200 460 250         L460 700         Z"
                                    fill="none" stroke="#E91E63" stroke-width="4" />
                            </svg>
                        </div>
                        <div class="ethnic-content mt-20">
                            <h3>Anarkali Gown</h3>
                            <span class="link"><a href="">Shop Collection</a></span>
                        </div>
                    </div>
                    <div class="ethnic-card">
                        <div class="ethnic-image">
                            <img src="images/category_image_06.png" alt="">
                            <svg class="frame-border" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 700">
                                <path
                                    d="         M40 700         L40 250         Q40 200 85 190         Q65 145 105 130         Q95 85 145 80         Q150 40 210 40         Q235 0 250 0         Q265 0 290 40         Q350 40 355 80         Q405 85 395 130         Q435 145 415 190         Q460 200 460 250         L460 700         Z"
                                    fill="none" stroke="#ffeb7c" stroke-width="4" />
                            </svg>
                        </div>
                        <div class="ethnic-content  mt-20">
                            <h3>Ethinic Set</h3>
                            <span class="link"><a href=""> Shop Collection</a></span>
                        </div>
                    </div>
                    <div class="ethnic-card">
                        <div class="ethnic-image">
                            <img src="images/category_image_01.png" alt="">
                            <svg class="frame-border" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 700">
                                <path
                                    d="         M40 700         L40 250         Q40 200 85 190         Q65 145 105 130         Q95 85 145 80         Q150 40 210 40         Q235 0 250 0         Q265 0 290 40         Q350 40 355 80         Q405 85 395 130         Q435 145 415 190         Q460 200 460 250         L460 700         Z"
                                    fill="none" stroke="#E91E63" stroke-width="4" />
                            </svg>
                        </div>
                        <div class="ethnic-content mt-20">
                            <h3>Anarkali Gown</h3>
                            <span class="link"><a href="">Shop Collection</a></span>
                        </div>
                    </div>
                    <div class="ethnic-card">
                        <div class="ethnic-image">
                            <img src="images/category_image_06.png" alt="">
                            <svg class="frame-border" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 700">
                                <path
                                    d="         M40 700         L40 250         Q40 200 85 190         Q65 145 105 130         Q95 85 145 80         Q150 40 210 40         Q235 0 250 0         Q265 0 290 40         Q350 40 355 80         Q405 85 395 130         Q435 145 415 190         Q460 200 460 250         L460 700         Z"
                                    fill="none" stroke="#ffeb7c" stroke-width="4" />
                            </svg>
                        </div>
                        <div class="ethnic-content  mt-20">
                            <h3>Ethinic Set</h3>
                            <span class="link"><a href=""> Shop Collection</a></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ethnic-content-btn">
                <a href="#" class="btn_secondary">
                    Explore Collection <span class="material-symbols-outlined">
                        arrow_right_alt
                    </span>
                </a>
            </div>
        </div>
    </section>

    <!-- Best Seller -->
    <section class="trending-collection best-seller" style="display: none;">
        <div class="container">
            <div class="heading">
                <h2 class="section-title">Best Seller</h2>
            </div>
            <div class="trending-collection-section">
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

                                <a href="#" class="btn_cart">Add To Cart</a>
                            </div>

                            <!-- new tag -->
                            <span class="product_tag">New</span>

                            <!-- whishlist tag -->
                            <div class="product_tag_wishlist">
                                <span class="material-symbols-outlined">favorite</span>
                            </div>
                        </div>
                        <div class="product_data">
                            <div class="product_title">
                                <a href=""> Mizoya Green Cotton A Line Dress</a>
                            </div>
                            <div class="product_price">
                                <span class="price_orignal">Rs. 3250</span>
                                <span class="price_old">4350</span>
                            </div>
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

                                <a href="#" class="btn_cart">Add To Cart</a>
                            </div>

                            <!-- new tag -->
                            <span class="product_tag">New</span>

                            <!-- whishlist tag -->
                            <div class="product_tag_wishlist">
                                <span class="material-symbols-outlined">favorite</span>
                            </div>
                        </div>
                        <div class="product_data">
                            <div class="product_title">
                                <a href=""> Mizoya Green Cotton A Line Dress</a>
                            </div>
                            <div class="product_price">
                                <span class="price_orignal">Rs. 3250</span>
                                <span class="price_old">4350</span>
                            </div>
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

                                <a href="#" class="btn_cart">Add To Cart</a>
                            </div>

                            <!-- new tag -->
                            <span class="product_tag">New</span>

                            <!-- whishlist tag -->
                            <div class="product_tag_wishlist">
                                <span class="material-symbols-outlined">favorite</span>
                            </div>
                        </div>
                        <div class="product_data">
                            <div class="product_title">
                                <a href=""> Mizoya Green Cotton A Line Dress</a>
                            </div>
                            <div class="product_price">
                                <span class="price_orignal">Rs. 3250</span>
                                <span class="price_old">4350</span></div>
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

                                <a href="#" class="btn_cart">Add To Cart</a>
                            </div>

                            <!-- new tag -->
                            <span class="product_tag">New</span>

                            <!-- whishlist tag -->
                            <div class="product_tag_wishlist">
                                <span class="material-symbols-outlined">favorite</span>
                            </div>
                        </div>
                        <div class="product_data">
                            <div class="product_title">
                                <a href=""> Mizoya Green Cotton A Line Dress</a>
                            </div>
                            <div class="product_price">
                                <span class="price_orignal">Rs. 3250</span>
                                <span class="price_old">4350</span></div>
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

                                <a href="#" class="btn_cart">Add To Cart</a>
                            </div>

                            <!-- new tag -->
                            <span class="product_tag">New</span>

                            <!-- whishlist tag -->
                            <div class="product_tag_wishlist">
                                <span class="material-symbols-outlined">favorite</span>
                            </div>
                        </div>
                        <div class="product_data">
                            <div class="product_title">
                                <a href=""> Mizoya Green Cotton A Line Dress</a>
                            </div>
                            <div class="product_price">
                                <span class="price_orignal">Rs. 3250</span>
                                <span class="price_old">4350</span>
                            </div>
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

                                <a href="#" class="btn_cart">Add To Cart</a>
                            </div>

                            <!-- new tag -->
                            <span class="product_tag">New</span>

                            <!-- whishlist tag -->
                            <div class="product_tag_wishlist">
                                <span class="material-symbols-outlined">favorite</span>
                            </div>
                        </div>
                        <div class="product_data">
                            <div class="product_title">
                                <a href=""> Mizoya Green Cotton A Line Dress</a>
                            </div>
                            <div class="product_price">
                                <span class="price_orignal">Rs. 3250</span>
                                <span class="price_old">4350</span>
                            </div>
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

                                <a href="#" class="btn_cart">Add To Cart</a>
                            </div>

                            <!-- new tag -->
                            <span class="product_tag">New</span>

                            <!-- whishlist tag -->
                            <div class="product_tag_wishlist">
                                <span class="material-symbols-outlined">favorite</span>
                            </div>
                        </div>
                        <div class="product_data">
                            <div class="product_title">
                                <a href=""> Mizoya Green Cotton A Line Dress</a>
                            </div>
                            <div class="product_price">
                                <span class="price_orignal">Rs. 3250</span>
                                <span class="price_old">4350</span>
                            </div>
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

                                <a href="#" class="btn_cart">Add To Cart</a>
                            </div>

                            <!-- new tag -->
                            <span class="product_tag">New</span>

                            <!-- whishlist tag -->
                            <div class="product_tag_wishlist">
                                <span class="material-symbols-outlined">favorite</span>
                            </div>
                        </div>
                        <div class="product_data">
                            <div class="product_title">
                                <a href=""> Mizoya Green Cotton A Line Dress</a>
                            </div>
                            <div class="product_price">
                                <span class="price_orignal">Rs. 3250</span>
                                <span class="price_old">4350</span></div>
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

    <!-- TESTIMONIAL -->
    <section class="ethnic-testimonial" style="display: none;">
        <div class="container">
            <div class="section-head text-center">
                <span class="small-heading">Customer Stories</span>
                <h2 class="section-title">Loved By Women Across India</h2>
            </div>
            <div class="testimonial-rope"></div>
            <div class="testimonial-slider">
                <div class="hanging-card rotate-left">
                    <div class="clip"></div>
                    <div class="card-image">
                        <img src="images/category_image_01.png" alt="">
                    </div>
                    <div class="card-content">
                        <div class="stars">★★★★★</div>
                        <p>Beautiful fabric quality and elegant finishing. Received countless compliments.</p>
                        <h4>Priya Sharma</h4>
                        <span>Delhi</span>
                    </div>
                </div>

                <div class="hanging-card rotate-right">
                    <div class="clip"></div>
                    <div class="card-image">
                        <img src="images/category_image_03.png" alt="">
                    </div>
                    <div class="card-content">
                        <div class="stars">★★★★★</div>
                        <p>Perfect fitting and exactly as shown in pictures.</p>
                        <h4>Neha Jain</h4>
                        <span>Jaipur</span>
                    </div>
                </div>

                <div class="hanging-card rotate-left">
                    <div class="clip"></div>
                    <div class="card-image">
                        <img src="images/category_image_05.png" alt="">
                    </div>
                    <div class="card-content">
                        <div class="stars">★★★★★</div>
                        <p>Premium embroidery and luxurious feel.</p>
                        <h4>Riya Kapoor</h4>
                        <span>Mumbai</span>
                    </div>
                </div>

                <div class="hanging-card rotate-right">
                    <div class="clip"></div>
                    <div class="card-image">
                        <img src="images/category_image_08.png" alt="">
                    </div>
                    <div class="card-content">
                        <div class="stars">★★★★★</div>
                        <p>Beautiful collection and fast delivery.</p>
                        <h4>Ananya Verma</h4>
                        <span>Lucknow</span>
                    </div>
                </div>

                <div class="hanging-card rotate-left">
                    <div class="clip"></div>
                    <div class="card-image">
                        <img src="images/category_image_01.png" alt="">
                    </div>
                    <div class="card-content">
                        <div class="stars">★★★★★</div>
                        <p>Beautiful fabric quality and elegant finishing. Received countless compliments.</p>
                        <h4>Priya Sharma</h4>
                        <span>Delhi</span>
                    </div>
                </div>

                <div class="hanging-card rotate-right">
                    <div class="clip"></div>
                    <div class="card-image">
                        <img src="images/category_image_03.png" alt="">
                    </div>
                    <div class="card-content">
                        <div class="stars">★★★★★</div>
                        <p>Perfect fitting and exactly as shown in pictures.</p>
                        <h4>Neha Jain</h4>
                        <span>Jaipur</span>
                    </div>
                </div>

                <div class="hanging-card rotate-left">
                    <div class="clip"></div>
                    <div class="card-image">
                        <img src="images/category_image_05.png" alt="">
                    </div>
                    <div class="card-content">
                        <div class="stars">★★★★★</div>
                        <p>Premium embroidery and luxurious feel.</p>
                        <h4>Riya Kapoor</h4>
                        <span>Mumbai</span>
                    </div>
                </div>

                <div class="hanging-card rotate-right">
                    <div class="clip"></div>
                    <div class="card-image">
                        <img src="images/category_image_08.png" alt="">
                    </div>
                    <div class="card-content">
                        <div class="stars">★★★★★</div>
                        <p>Beautiful collection and fast delivery.</p>
                        <h4>Ananya Verma</h4>
                        <span>Lucknow</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- NEWSLETTER SECION -->
    <section class="newsletter-section">
        <div class="newsletter-pattern"></div>
        <div class="container">
            <div class="social-connect">
                <span class="heading">Follow Us</span>
                <a href="#" class="social-link instagram">
                    <i class="fa-brands fa-instagram"></i>
                    <span>Instagram</span>
                </a>
                <a href="#" class="social-link facebook">
                    <i class="fa-brands fa-facebook-f"></i>
                    <span>Facebook</span>
                </a>
                <a href="#" class="social-link pinterest">
                    <i class="fa-brands fa-pinterest-p"></i>
                    <span>Pinterest</span>
                </a>
                <a href="#" class="social-link youtube">
                    <i class="fa-brands fa-youtube"></i>
                    <span>YouTube</span>
                </a>
            </div>
            <div class="newsletter-content">
                <span class="newsletter-tag">Stay Connected</span>
                <h2>Join The <span>SK4</span> Family</h2>
                <p>Get early access to exclusive launches, festive offers, handcrafted collections and style inspiration delivered directly to your inbox.</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Enter your email address">
                    <button type="submit">Subscribe</button>
                </form>
                <ul class="newsletter-benefits">
                    <li>Exclusive Launches</li>
                    <li>Festive Offers</li>
                    <li>Style Inspiration</li>
                </ul>
            </div>
        </div>
    </section>
    <section class="process-section">
        <div class="process-container">
            <div class="sec-head text-center">
                <span class="curated mb-2">HOW WE CREATE</span>
                <h2 class="featured-title letter-spacing-2 fw-normal">Our Process</h2>
                <p>From carefully selected fabrics to the final delivery, every piece passes through a thoughtful process.</p>
            </div>
            
            <div class="bridal-slider">
                <div class="bridal-slide">
                    <img src="images/s4k-1.jpeg" alt="img">
                </div>
                <div class="bridal-slide">
                    <img src="images/s4k-2.jpeg" alt="img">
                </div>
                <div class="bridal-slide">
                    <img src="images/s4k-3.jpeg" alt="img">
                </div>
                <div class="bridal-slide">
                    <img src="images/s4k-4.jpeg" alt="img">
                </div>
                <div class="bridal-slide">
                    <img src="images/s4k-5.jpeg" alt="img">
                </div>
                <div class="bridal-slide">
                    <img src="images/s4k-6.jpeg" alt="img">
                </div>
                <div class="bridal-slide">
                    <img src="images/s4k-7.jpeg" alt="img">
                </div>
                <div class="bridal-slide">
                    <img src="images/s4k-8.jpeg" alt="img">
                </div>
            </div>

            <div class="process-main-inner">
                <div class="process-main-inner1">
                    <div class="process-grid-list">
                        <div class="process-grid-left">
                            <div class="process-grid-left-img">
                                <div class="process-grid-left-img1">
                                    <img src="images/s4k-3.jpeg" alt="img">
                                    <div class="process-overlay">
                                        <div class="process-top1">
                                            <div class="process-icon1">
                                                <span class="material-symbols-outlined">styler</span>
                                            </div>
                                        </div>
                                        <div class="process-content1">
                                            <h3>Fabric Selection</h3>
                                            <p>Premium quality fabrics carefully chosen for comfort and elegance.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="process-grid-left-img1">
                                    <img src="images/s4k-4.jpeg" alt="img">
                                    <div class="process-overlay">
                                        <div class="process-top1">
                                            <div class="process-icon1">
                                                <span class="material-symbols-outlined">iron</span>
                                            </div>
                                        </div>
                                        <div class="process-content1">
                                            <h3>Stitching</h3>
                                            <p>Skilled craftsmanship ensuring perfect fitting and finish.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="process-grid-center">
                            <div class="process-grid-center-img">
                                <img src="images/s4k-7.jpeg" alt="img">
                                <div class="process-overlay">
                                    <div class="process-top1">
                                        <div class="process-icon1">
                                            <span class="material-symbols-outlined">fact_check</span>
                                        </div>
                                    </div>
                                    <div class="process-content1">
                                        <h3>Quality Check</h3>
                                        <p>Every piece undergoes strict quality inspection before approval.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="process-grid-right">
                            <div class="process-grid-right-img">
                                <div class="process-grid-right-img1">
                                    <img src="images/s4k-5.jpeg" alt="img">
                                    <div class="process-overlay">
                                        <div class="process-top1">
                                            <div class="process-icon1">
                                                <span class="material-symbols-outlined">local_laundry_service</span>
                                            </div>
                                        </div>
                                        <div class="process-content1">
                                            <h3>Finishing</h3>
                                            <p>Ironing, detailing and finishing touches for a premium look.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="process-grid-right-img1">
                                    <img src="images/s4k-6.jpeg" alt="img">
                                    <div class="process-overlay">
                                        <div class="process-top1">
                                            <div class="process-icon1">
                                                <span class="material-symbols-outlined">package_2</span>
                                            </div>
                                        </div>
                                        <div class="process-content1">
                                            <h3>Packing & Dispatch</h3>
                                            <p>Secure packaging and timely dispatch to your doorstep.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- INSTAGRAM SECTION -->
    <section class="instagram-section-main">
        <div class="sec-head text-center">
            <span class="curated mb-2">FOLLOW OUR JOURNEY</span>
            <h2 class="featured-title letter-spacing-2 fw-normal">Inspired by Instagram</h2>
            <p>Discover our latest designs, stories and moments. Follow us for daily inspiration.</p>
            <a href="#" class="instagram-follow">
                <span class="instagram-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="1.8"/>
                        <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.8"/>
                        <circle cx="17.3" cy="6.8" r="1" fill="currentColor"/>
                    </svg>
                </span> Follow us on Instagram <span class="insta-arrow">↗</span>
            </a>
        </div>

        <!-- TWO ROW INSTAGRAM -->
        <div class="instagram-gallery">

            <!-- ROW 1 -->
            <div class="instagram-row instagram-row-one">
                <a href="#" class="instagram-card">
                    <img src="images/category_image_03.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_04.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_05.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_06.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_07.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_08.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_03.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_04.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_05.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_06.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_07.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_08.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_03.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_04.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_05.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_06.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_07.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_08.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
            </div>

            <!-- ROW 2 -->
            <div class="instagram-row instagram-row-two">
                <a href="#" class="instagram-card">
                    <img src="images/category_image_09.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_10.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_01.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_02.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_03.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_04.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_09.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_10.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_01.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_02.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_03.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_04.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_09.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_10.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_01.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_02.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_03.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
                <a href="#" class="instagram-card">
                    <img src="images/category_image_04.png" alt="img">
                    <div class="instagram-overlay"><span>♡</span></div>
                </a>
            </div>
        </div>
    </section>

    <!-- BLOG SECTION -->
    <section class="blog-section-main">
        <div class="blog-container">
            <div class="sec-head text-center">
                <span class="curated mb-2">Fashion Journal</span>
                <h2 class="featured-title letter-spacing-2 fw-normal">Latest Style Stories</h2>
            </div>

            <div class="blog-section-inner">
                <div class="blog-item-main">
                    <div class="blog-image-main">
                        <span class="image-border"></span>
                        <img src="images/category_image_05.png" alt="img">
                    </div>

                    <div class="blog-content-main">
                        <h3>How To Style Anarkali Gowns For Wedding Season</h3>
                        <p>Mesmerising jewellery collection that encapsulates the essence of timeless elegance & sophistication</p>
                        <a href="#" class="blog-btn-main">
                            VIEW DETAILS
                            <span class="material-symbols-outlined">arrow_right_alt</span>
                        </a>
                    </div>
                </div>
                <div class="blog-item-main reverse">
                    <div class="blog-image-main">
                        <span class="image-border"></span>
                        <img src="images/category_image_04.png" alt="img">
                    </div>

                    <div class="blog-content-main">
                        <h3>How To Style Anarkali Gowns For Wedding Season</h3>
                        <p>Enchanting jewellery collection that echoes the untamed spirit of the wild.</p>
                        <a href="#" class="blog-btn-main">
                            VIEW DETAILS
                            <span class="material-symbols-outlined">arrow_right_alt</span>
                        </a>
                    </div>
                </div>
                <div class="blog-item-main text-first">
                    <div class="blog-content-main">
                        <h3>How To Style Anarkali Gowns For Wedding Season</h3>
                        <p>Captivating jewellery collection that celebrates the eternal bond of love.</p>
                        <a href="#" class="blog-btn-main">
                            VIEW DETAILS
                            <span class="material-symbols-outlined">arrow_right_alt</span>
                        </a>
                    </div>

                    <div class="blog-image-main">
                        <span class="image-border"></span>
                        <img src="images/category_image_03.png" alt="img">
                    </div>
                </div>
                <div class="blog-item-main reverse">
                    <div class="blog-image-main">
                        <span class="image-border"></span>
                        <img src="images/category_image_06.png" alt="img">
                    </div>

                    <div class="blog-content-main">
                        <h3>How To Style Anarkali Gowns For Wedding Season</h3>
                        <p>Radiant jewellery collection that captures the essence of molten gold.</p>
                        <a href="#" class="blog-btn-main">
                            VIEW DETAILS
                            <span class="material-symbols-outlined">arrow_right_alt</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
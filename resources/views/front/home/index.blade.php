@extends('front.layout.app')
@section('content')
{{-- <div class="offer-tag">
        <span>OFFER</span>
        <p>Buy for ₹2499 & More, Get flat ₹100 Off I Use Code: SALE100</p>
    </div> --}}

    <!-- HERO -->
    <section class="hero-section" aria-label="Featured collection">
      <div class="hero-layout">
        <div class="hero-carousel carousel slide" id="heroCarousel" data-bs-ride="carousel" data-bs-interval="4500">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="assets/img/silder-1.jpg" class="hero-image" alt="Model in a plum festive outfit">
              <div class="hero-copy">
                <span class="eyebrow">Crafted in Jaipur</span>
                <h1>Timeless elegance<br>Woven with Grace</h1>
                <p>Get 20% OFF</p>
              </div>
            </div>
            <div class="carousel-item">
              <img src="assets/img/silder-4.jpg" class="hero-image" alt="Model in a pink festive outfit">
              <div class="hero-copy">
                <span class="eyebrow">Crafted in Jaipur</span>
                <h2>Timeless elegance<br>Woven with Grace</h2>
                <p>Get 20% OFF</p>
              </div>
            </div>
            <div class="carousel-item">
              <img src="assets/img/silder-3.jpg" class="hero-image" alt="Model in an embroidered ivory outfit">
              <div class="hero-copy">
                <span class="eyebrow">Crafted in Jaipur</span>
                <h2>Timeless elegance<br>Woven with Grace</h2>
                <p>Get 20% OFF</p>
              </div>
            </div>
            <div class="carousel-item">
              <img src="assets/img/silder-2.jpg" class="hero-image" alt="Model in a deep purple outfit">
              <div class="hero-copy">
                <span class="eyebrow">Crafted in Jaipur</span>
                <h2>Timeless elegance<br>Woven with Grace</h2>
                <p>Get 20% OFF</p>
              </div>
            </div>
          </div>
          <button class="hero-deck-card hero-deck-card--next" type="button" data-bs-target="#heroCarousel"
            data-bs-slide-to="1" aria-label="Show next look, slide 2">
            <img src="assets/img/silder-4.jpg" alt="">
          </button>
        </div>
        <aside class="hero-rail" aria-label="Special offers">
          <button class="hero-rail-arrow" type="button" data-offer-scroll="up" aria-label="Show previous offer">
            <img src="assets/icons/arrow-up.svg" alt="">
          </button>
          <div class="hero-thumbnails">
            <a class="hero-thumb" href="product-list.html" aria-label="Shop the plum festive offer">
              <img src="assets/img/silder-1.jpg" alt="">
              <span class="hero-thumb-copy"><span class="eyebrow">Crafted in Jaipur</span><span
                  class="hero-thumb-title">Timeless elegance<br>Woven with Grace</span><span
                  class="hero-thumb-offer">Get 20% OFF</span></span>
            </a>
            <a class="hero-thumb" href="product-list.html" aria-label="Shop the pink festive offer">
              <img src="assets/img/silder-min-4.jpg" alt="">
              <span class="hero-thumb-copy"><span class="eyebrow">Crafted in Jaipur</span><span
                  class="hero-thumb-title">Timeless elegance<br>Woven with Grace</span><span
                  class="hero-thumb-offer">Get 20% OFF</span></span>
            </a>
            <a class="hero-thumb" href="product-list.html" aria-label="Shop the ivory festive offer">
              <img src="assets/img/silder-min-3.jpg" alt="">
              <span class="hero-thumb-copy"><span class="eyebrow">Crafted in Jaipur</span><span
                  class="hero-thumb-title">Timeless elegance<br>Woven with Grace</span><span
                  class="hero-thumb-offer">Get 20% OFF</span></span>
            </a>
            <a class="hero-thumb" href="product-list.html" aria-label="Shop the purple festive offer">
              <img src="assets/img/silder-min-2.jpg" alt="">
              <span class="hero-thumb-copy"><span class="eyebrow">Crafted in Jaipur</span><span
                  class="hero-thumb-title">Timeless elegance<br>Woven with Grace</span><span
                  class="hero-thumb-offer">Get 20% OFF</span></span>
            </a>
          </div>
          <button class="hero-rail-arrow" type="button" data-offer-scroll="down" aria-label="Show next offer">
            <img src="assets/icons/arrow-down.svg" alt="">
          </button>
        </aside>
      </div>
      <div class="hero-indicators carousel-indicators" aria-label="Featured slide progress">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true"
          aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
      </div>
    </section>
    <section class="bestseller-feature-section" aria-labelledby="bestsellerFeatureTitle">
      <div class="page-container bestseller-gallery">
        <a class="bestseller-card bestseller-card--top-left" href="#featured">
          <img src="assets/img/insta-2.jpg" alt="Model in a pink embroidered suit">
        </a>
        <a class="bestseller-card bestseller-card--top-center" href="#featured">
          <img src="assets/img/bastsaller-1.jpg" alt="Model wearing a navy and magenta festive outfit">
        </a>
        <a class="bestseller-card bestseller-card--top-right" href="#featured">
          <img src="assets/img/category-2.jpg" alt="Model wearing an elegant occasion outfit">
        </a>
        <div class="bestseller-gallery-heading">
          <span>Curated for you</span>
          <h2 id="bestsellerFeatureTitle">Our Bestseller</h2>
          <p>Gracefully crafted silhouettes with timeless appeal.</p>
        </div>
        <a class="bestseller-card bestseller-card--bottom-left" href="#featured">
          <img src="assets/img/featured-1.jpg" alt="Model in a soft pink printed outfit">
        </a>
        <a class="bestseller-card bestseller-card--bottom-center" href="#featured">
          <img src="assets/img/insta-1.jpg" alt="Model wearing a mustard yellow embroidered suit">
        </a>
        <a class="bestseller-card bestseller-card--bottom-right" href="#featured">
          <img src="assets/img/trending-2.jpg" alt="Model wearing a magenta festive outfit">
        </a>
      </div>
    </section>
    <section class="designer-section section-pad" id="new-arrivals">
      <div class="page-container">
        <div class="section-heading light-heading">
          <h4>Discover Anarkalis crafted to make every occasion unforgettable.From graceful silhouettes to exquisite
            details, find your perfect look for every celebration.</h4>

        </div>
        <div class="row g-3 g-lg-4 feature-products">
          <div class="col-12 col-sm-4">
            <a class="image-card" href="product-list.html">
              <img src="assets/img/img-1.jpg" alt="Green occasionwear styled for a celebration">
              <span class="image-card-caption">
                <span class="image-card-caption-title">ELEGANCE,<br>REDEFINED <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                    <path d="m12 2 2.8 7.2L22 12l-7.2 2.8L12 22l-2.8-7.2L2 12l7.2-2.8L12 2Z"></path>
                  </svg></span>
                <span class="image-card-cta">See More <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                    <path d="M5 12h14m-6-6 6 6-6 6"></path>
                  </svg></span>
              </span>
            </a>
          </div>
          <div class="col-6  col-sm-4">
            <a class="image-card" href="product-list.html">
              <img src="assets/img/img-2.jpg" alt="Warm-toned festive outfit">
              <span class="image-card-offer">
                <span><strong>FLAT 20% OFF</strong><small>on selected styles</small></span>
                <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                  <path d="M5 12h14m-6-6 6 6-6 6"></path>
                </svg>
              </span>
            </a>
          </div>
          <div class="col-6 col-sm-4">
            <a class="image-card" href="product-list.html">
              <img src="assets/img/img-3.jpg" alt="Elegant embroidered occasionwear">
              <span class="image-card-offer image-card-style">
                <span><small>Discover Your</small><small>Signature Style</small></span>
                <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                  <path d="M5 12h14m-6-6 6 6-6 6"></path>
                </svg>
              </span>
            </a>
          </div>
        </div>
        <div class="subsection-title">
          <h2>EVERYDAY ELEGANCE</h2>
          <div class="everyday-actions">
            <a class="btn-dark-theme" href="product-list.html">View all <span aria-hidden="true"> <svg class="svg-icon" viewBox="0 0 24 24">
                  <path d="M5 12h14m-6-6 6 6-6 6"></path>
                </svg></span></a>
          </div>
        </div>
        <div class="everyday-carousel" role="region" aria-label="Everyday elegance styles"
          aria-roledescription="carousel">
          <div class="everyday-track" aria-live="off">
            <a class="image-card everyday-card" href="product-details.html"><img src="assets/img/festive-1.jpg"
                alt="Magenta festive look"></a>
            <a class="image-card everyday-card" href="product-details.html"><img src="assets/img/festive-2.jpg"
                alt="Printed outfit for everyday wear"></a>
            <a class="image-card everyday-card" href="product-details.html"><img src="assets/img/festive-3.jpg"
                alt="Women browsing a fashion collection"></a>
            <a class="image-card everyday-card" href="product-details.html"><img src="assets/img/festive-4.jpg"
                alt="A joyful, colourful style"></a>
            <a class="image-card everyday-card" href="product-details.html"><img src="assets/img/festive-5.jpg"
                alt="An elegant everyday outfit"></a>
          </div>
        </div>
      </div>
    </section>


    <section class="shop-category-section pt-5" aria-label="Shop by category carousel">
      <div class="page-container">
        <h2>Shop By Category</h2>
        <div class="shop-category-carousel" role="region" aria-label="Shop by category" aria-roledescription="carousel">
          <button class="shop-category-control shop-category-prev" type="button" aria-label="Previous category"><svg
              class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
              <path d="M19 12H5m6-6-6 6 6 6"></path>
            </svg></button>
          <div class="shop-category-stage" aria-live="off">
            <div class="shop-category-slide">
              <img src="assets/img/category-3.jpg" alt="Woman wearing a dark green kaftan">
              <span>Kaftan</span>
            </div>
            <div class="shop-category-slide">
              <img src="assets/img/category-2.jpg" alt="Anarkali outfit in ivory">
              <span>Anarkali</span>
            </div>
            <div class="shop-category-slide">
              <img src="assets/img/category-4.jpg" alt="Woman wearing a neutral co-ord set">
              <span>Co-ord Set</span>
            </div>
            <div class="shop-category-slide">
              <img src="assets/img/category-5.jpg" alt="Woman wearing a neutral co-ord set">
              <span>Anarkali</span>
            </div>
             <div class="shop-category-slide">
              <img src="assets/img/category-6.jpg" alt="Woman wearing a neutral Anarkali">
              <span>Kaftan</span>
            </div>
             <div class="shop-category-slide">
              <img src="assets/img/category-7.jpg" alt="Woman wearing a neutral Anarkali">
              <span>Anarkali</span>
            </div>
             <div class="shop-category-slide">
              <img src="assets/img/category-8.jpg" alt="Woman wearing a neutral Anarkali">
              <span>Co-ord Set</span>
            </div>
          </div>
          <button class="shop-category-control shop-category-next" type="button" aria-label="Next category"><svg
              class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
              <path d="M5 12h14m-6-6 6 6-6 6"></path>
            </svg></button>
          <div class="shop-category-dots" aria-label="Category navigation"></div>
        </div>
        <div class="text-center mt-3">
          <a href="product-list.html" class="btn-dark-theme rounded">VIEW ALL THE COLLECTIONS</a>
        </div>
      </div>
    </section>

    <section class="category-section" id="featured" aria-label="Shop by category">
      <div class="page-container">

        <div class="subsection-title">
          <h2>Featured Categories</h2>
          <div class="everyday-actions">
            <a class="btn-dark-theme rounded" href="product-list.html">Explore Collection <svg class="svg-icon ms-2" aria-hidden="true" viewBox="0 0 24 24">
                <path d="M5 12h14m-6-6 6 6-6 6"></path>
              </svg></a>
          </div>
        </div>
        <div class="category-panel">
          <div class="category-copy">
            <h2>Explore Our Featured Categories</h2>
            <p>Discover styles made to elevate every occasion. From timeless classics to modern trends, find the perfect
              outfit to express your unique style.</p>
          </div>
          <div class="category-carousel" role="region" aria-label="Featured categories" aria-roledescription="carousel">
            <button class="category-control category-prev" type="button" aria-label="Previous category"><svg
                class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                <path d="M19 12H5m6-6-6 6 6 6"></path>
              </svg></button>
            <div class="category-grid" aria-live="off">
              <div class="category-track">
                <div class="category-card">
                  <img src="assets/img/featured-1.jpg" alt="Woman wearing a neutral co-ord set">
                  <span>Co-ord Sets</span>
                </div>
                <div class="category-card">
                  <img src="assets/img/featured-2.jpg" alt="Anarkali outfit in ivory">
                  <span>Anarkali</span>
                </div>
                <div class="category-card">
                  <img src="assets/img/featured-3.jpg" alt="Woman wearing a dark green kaftan">
                  <span>Kaftans</span>
                </div>
                <div class="category-card">
                  <img src="assets/img/featured-1.jpg" alt="Woman wearing a dark green kaftan">
                  <span>Kaftans</span>
                </div>
                <div class="category-card">
                  <img src="assets/img/featured-2.jpg" alt="Anarkali outfit in ivory">
                  <span>Anarkali</span>
                </div>
                <div class="category-card">
                  <img src="assets/img/featured-3.jpg" alt="Woman wearing a dark green kaftan">
                  <span>Kaftans</span>
                </div>
              </div>
            </div>
            <button class="category-control category-next" type="button" aria-label="Next category"><svg
                class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                <path d="M5 12h14m-6-6 6 6-6 6"></path>
              </svg></button>
            <div class="category-dots" aria-label="Category navigation">
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class=" section-pad trending-collections-section">
      <svg class="trending-collection-defs" aria-hidden="true" focusable="false">
        <defs>
          <clipPath id="trendingOptionsWave" clipPathUnits="objectBoundingBox">
            <path
              d="M0 .24C.12 .24 .16 .19 .24 .13C.31 .07 .38 .02 .46 .04C.53 .06 .54 0 .62 .01C.72 .02 .78 .12 .86 .18C.92 .23 .96 .24 1 .24V1H0Z">
            </path>
          </clipPath>
          <clipPath id="trendingCollectionFrame" clipPathUnits="objectBoundingBox">
            <path
              d="M 0 1 V 0.356 C 0 0.315 0.045 0.28 0.1032 0.2648 C 0.082 0.25 0.09 0.2 0.1534 0.1789 C 0.138 0.16 0.16 0.125 0.2515 0.108 C 0.238 0.088 0.29 0.062 0.409 0.05 C 0.425 0.03 0.45 0.01 0.5 0 C 0.55 0.01 0.575 0.03 0.591 0.05 C 0.71 0.062 0.762 0.088 0.7485 0.108 C 0.84 0.125 0.862 0.16 0.8466 0.1789 C 0.91 0.2 0.918 0.25 0.8968 0.2648 C 0.955 0.28 1 0.315 1 0.356 V 1 H 0 Z">
            </path>
          </clipPath>
        </defs>
      </svg>
      <div class="page-container">
        <div class="section-heading">
          <h2>Trending Collections</h2>
          <p>The Trending Edit — Fresh styles, timeless elegance.</p>
        </div>
        <div class="row">
          <div class="col-md-4 col-lg-3 col-6">
            <div class="collections-item">
              <div class="collections-img">
                <div class="img-collections">
                  <a href="product-details.html">
                    <img src="assets/img/trending-1.jpg" alt="Pink embroidered outfit">
                  </a>
                </div>
                <div class="card-btn-main">
                  <div class="cart-list">
                    <ul>
                      <li>
                        <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                          <path d="M20.8 8.8c0 5.2-8.8 11-8.8 11s-8.8-5.8-8.8-11A4.8 4.8 0 0 1 12 6.1a4.8 4.8 0 0 1 8.8 2.7Z"></path>
                        </svg>
                      </li>
                      <li>
                        <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                          <path d="M2 3h2l2.4 12.2a2 2 0 0 0 2 1.6h9.9a2 2 0 0 0 2-1.6L22 7H5"></path>
      <circle cx="9" cy="21" r="1"></circle>
      <circle cx="18" cy="21" r="1"></circle>
                        </svg>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="collections-content">
                <a href="product-details.html">
                  <h4>Mizoya Green Cotton A Line Dress</h4>
                  <div class="price-wist">
                    <span class="Wishlist"><svg class="svg-icon" viewBox="0 0 24 24">
                        <path d="M20.8 8.8c0 5.2-8.8 11-8.8 11s-8.8-5.8-8.8-11A4.8 4.8 0 0 1 12 6.1a4.8 4.8 0 0 1 8.8 2.7Z"></path>
                      </svg></span>
                    <h6 class="collections-price text-center">

                      <span class="collections-current-price">₹3500</span>
                      <del class="collections-compare-price">₹4500</del>
                      <span class="collections-discount">22% OFF</span>
                    </h6>
                  </div>
                </a>

                <div class="product-options">
                  <div class="product-colors" aria-label="Available colors">
                    <span class="option-label">Colors</span>
                    <div class="color-swatches" aria-hidden="true">
                      <span class="color-swatch swatch-wine"></span>
                      <span class="color-swatch swatch-gold is-selected"></span>
                      <span class="color-swatch swatch-black"></span>
                      <span class="color-swatch swatch-teal"></span>
                    </div>
                  </div>
                  <div class="product-sizes" aria-label="Available sizes">
                    <span class="option-label">Size</span>
                    <div class="size-options" aria-hidden="true">
                      <span>S</span><span>M</span><span>L</span><span>XL</span>
                    </div>
                  </div>
                  <button class="product-add-to-cart" type="button">Add To Cart</button>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-lg-3 col-6">
            <div class="collections-item">
              <div class="collections-img">
                <div class="img-collections">
                  <a href="product-details.html">
                    <img src="assets/img/trending-3.jpg" alt="Rust embroidered outfit">
                  </a>
                </div>
                <div class="card-btn-main">
                  <div class="cart-list">
                    <ul>
                      <li>
                        <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                          <path d="M20.8 8.8c0 5.2-8.8 11-8.8 11s-8.8-5.8-8.8-11A4.8 4.8 0 0 1 12 6.1a4.8 4.8 0 0 1 8.8 2.7Z"></path>
                        </svg>
                      </li>
                      <li>
                        <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                          <path d="M2 3h2l2.4 12.2a2 2 0 0 0 2 1.6h9.9a2 2 0 0 0 2-1.6L22 7H5"></path>
      <circle cx="9" cy="21" r="1"></circle>
      <circle cx="18" cy="21" r="1"></circle>
                        </svg>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="collections-content">
                <a href="product-details.html">
                  <h4>Mizoya Green Cotton A Line Dress</h4>
                  <div class="price-wist">
                    <span class="Wishlist"><svg class="svg-icon" viewBox="0 0 24 24">
                        <path d="M20.8 8.8c0 5.2-8.8 11-8.8 11s-8.8-5.8-8.8-11A4.8 4.8 0 0 1 12 6.1a4.8 4.8 0 0 1 8.8 2.7Z"></path>
                      </svg></span>
                    <h6 class="collections-price text-center">

                      <span class="collections-current-price">₹3500</span>
                      <del class="collections-compare-price">₹4500</del>
                      <span class="collections-discount">22% OFF</span>
                    </h6>
                  </div>
                </a>
                <div class="product-options">
                  <div class="product-colors" aria-label="Available colors">
                    <span class="option-label">Colors</span>
                    <div class="color-swatches" aria-hidden="true">
                      <span class="color-swatch swatch-wine"></span>
                      <span class="color-swatch swatch-gold is-selected"></span>
                      <span class="color-swatch swatch-black"></span>
                      <span class="color-swatch swatch-teal"></span>
                    </div>
                  </div>
                  <div class="product-sizes" aria-label="Available sizes">
                    <span class="option-label">Size</span>
                    <div class="size-options" aria-hidden="true">
                      <span>S</span><span>M</span><span>L</span><span>XL</span>
                    </div>
                  </div>
                  <button class="product-add-to-cart" type="button">Add To Cart</button>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-lg-3 col-6">
            <div class="collections-item">
              <div class="collections-img">
                <div class="img-collections">
                  <a href="product-details.html">
                    <img src="assets/img/trending-4.jpg" alt="Magenta embroidered outfit">
                  </a>
                </div>
                <div class="card-btn-main">
                  <div class="cart-list">
                    <ul>
                      <li>
                        <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                          <path d="M20.8 8.8c0 5.2-8.8 11-8.8 11s-8.8-5.8-8.8-11A4.8 4.8 0 0 1 12 6.1a4.8 4.8 0 0 1 8.8 2.7Z"></path>
                        </svg>
                      </li>
                      <li>
                        <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                          <path d="M2 3h2l2.4 12.2a2 2 0 0 0 2 1.6h9.9a2 2 0 0 0 2-1.6L22 7H5"></path>
      <circle cx="9" cy="21" r="1"></circle>
      <circle cx="18" cy="21" r="1"></circle>
                        </svg>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="collections-content">
                <a href="product-details.html">
                  <h4>Mizoya Green Cotton A Line Dress</h4>
                  <div class="price-wist">
                    <span class="Wishlist"><svg class="svg-icon" viewBox="0 0 24 24">
                        <path d="M20.8 8.8c0 5.2-8.8 11-8.8 11s-8.8-5.8-8.8-11A4.8 4.8 0 0 1 12 6.1a4.8 4.8 0 0 1 8.8 2.7Z"></path>
                      </svg></span>
                    <h6 class="collections-price text-center">

                      <span class="collections-current-price">₹3500</span>
                      <del class="collections-compare-price">₹4500</del>
                      <span class="collections-discount">22% OFF</span>
                    </h6>
                  </div>
                </a>
                <div class="product-options">
                  <div class="product-colors" aria-label="Available colors">
                    <span class="option-label">Colors</span>
                    <div class="color-swatches" aria-hidden="true">
                      <span class="color-swatch swatch-wine"></span>
                      <span class="color-swatch swatch-gold is-selected"></span>
                      <span class="color-swatch swatch-black"></span>
                      <span class="color-swatch swatch-teal"></span>
                    </div>
                  </div>
                  <div class="product-sizes" aria-label="Available sizes">
                    <span class="option-label">Size</span>
                    <div class="size-options" aria-hidden="true">
                      <span>S</span><span>M</span><span>L</span><span>XL</span>
                    </div>
                  </div>
                  <button class="product-add-to-cart" type="button">Add To Cart</button>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-lg-3 col-6">
            <div class="collections-item">
              <div class="collections-img">
                <div class="img-collections">
                  <a href="product-details.html">
                    <img src="assets/img/trending-2.jpg" alt="Magenta embroidered outfit">
                  </a>
                </div>
                <div class="card-btn-main">
                  <div class="cart-list">
                    <ul>
                      <li>
                        <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                          <path d="M20.8 8.8c0 5.2-8.8 11-8.8 11s-8.8-5.8-8.8-11A4.8 4.8 0 0 1 12 6.1a4.8 4.8 0 0 1 8.8 2.7Z"></path>
                        </svg>
                      </li>
                      <li>
                        <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                          <path d="M2 3h2l2.4 12.2a2 2 0 0 0 2 1.6h9.9a2 2 0 0 0 2-1.6L22 7H5"></path>
      <circle cx="9" cy="21" r="1"></circle>
      <circle cx="18" cy="21" r="1"></circle>
                        </svg>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="collections-content">
                <a href="product-details.html">
                  <h4>Mizoya Green Cotton A Line Dress</h4>
                  <div class="price-wist">
                    <span class="Wishlist"><svg class="svg-icon" viewBox="0 0 24 24">
                        <path d="M20.8 8.8c0 5.2-8.8 11-8.8 11s-8.8-5.8-8.8-11A4.8 4.8 0 0 1 12 6.1a4.8 4.8 0 0 1 8.8 2.7Z"></path>
                      </svg></span>
                    <h6 class="collections-price text-center">

                      <span class="collections-current-price">₹3500</span>
                      <del class="collections-compare-price">₹4500</del>
                      <span class="collections-discount">22% OFF</span>
                    </h6>
                  </div>
                </a>
                <div class="product-options">
                  <div class="product-colors" aria-label="Available colors">
                    <span class="option-label">Colors</span>
                    <div class="color-swatches" aria-hidden="true">
                      <span class="color-swatch swatch-wine"></span>
                      <span class="color-swatch swatch-gold is-selected"></span>
                      <span class="color-swatch swatch-black"></span>
                      <span class="color-swatch swatch-teal"></span>
                    </div>
                  </div>
                  <div class="product-sizes" aria-label="Available sizes">
                    <span class="option-label">Size</span>
                    <div class="size-options" aria-hidden="true">
                      <span>S</span><span>M</span><span>L</span><span>XL</span>
                    </div>
                  </div>
                  <button class="product-add-to-cart" type="button">Add To Cart</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="best-collections" aria-labelledby="collectionsTitle">
      <div class="collection-heading">
        <div class="collection-heading-inner page-container">
          <div>
            <h2 id="collectionsTitle">Our Best Collections</h2>
            <p>Discover how our styles come to life. Tag us in your looks for a chance to be<br
                class="collection-copy-break"> featured.</p>
          </div>
          <div class="collection-controls" aria-label="Collection slider controls">
            <button class="collection-prev" type="button" aria-label="Previous collection"><svg class="svg-icon"
                aria-hidden="true" viewBox="0 0 24 24">
                <path d="M19 12H5m6-6-6 6 6 6"></path>
              </svg></button>
            <button class="collection-next" type="button" aria-label="Next collection"><svg class="svg-icon"
                aria-hidden="true" viewBox="0 0 24 24">
                <path d="M5 12h14m-6-6 6 6-6 6"></path>
              </svg></button>
          </div>
        </div>
      </div>
      <div class="collection-window " role="region" aria-label="Our best collections" aria-roledescription="carousel">
        <div class="collection-track">
          <a href="#featured" aria-label="Explore festive collection">
            <img src="assets/img/Collections-1.jpg" alt="Woman wearing a magenta festive outfit">
          </a>
          <a href="#featured" aria-label="Explore celebration collection">
            <img src="assets/img/Collections-2.jpg" alt="Women celebrating together in colourful festive outfits">
          </a>
          <a href="#featured" aria-label="Explore occasionwear collection">
            <img src="assets/img/Collections-3.jpg" alt="Women wearing elegant pink occasionwear">
          </a>
          <a href="#featured" aria-label="Explore festive collection">
            <img src="assets/img/Collections-1.jpg" alt="Woman wearing a magenta festive outfit">
          </a>
          <a href="#featured" aria-label="Explore celebration collection">
            <img src="assets/img/Collections-2.jpg" alt="Women celebrating together in colourful festive outfits">
          </a>
          <a href="#featured" aria-label="Explore occasionwear collection">
            <img src="assets/img/Collections-3.jpg" alt="Women wearing elegant pink occasionwear">
          </a>
        </div>
      </div>
    </section>
    <section class="shop-look-section section-pad" aria-label="Featured products">
      <div class="page-container">
        <div class="section-heading">
          <h2>Trending Bestseller</h2>
          <p>The Trending Edit — Fresh styles, timeless elegance.</p>
        </div>
        <div class="row g-3 g-lg-4 ">
          <div class="col-6 col-md-4 col-lg-3">
            <article class="shop-look-card" tabindex="0"
              aria-label="Mizoya Green Cotton A Line Dress, ₹3500, was ₹4500, 22% off">
              <div class="shop-look-image">
                <a href="product-details.html">
                  <img src="assets/img/insta-3.jpg" alt="Woman wearing a mustard yellow printed kurta set">
                </a>
              </div>
              <div class="shop-content">
                <a href="product-details.html" class="shop-look-content">
                  <h3>Mizoya Green Cotton A Line Dress</h3>
                  <p class="shop-look-price">
                    <span class="shop-look-current-price">₹3500</span>
                    <del class="shop-look-compare-price">₹4500</del>
                    <span class="shop-look-discount">22% OFF</span>
                  </p>
                </a>
                <div class="shop-look-options" aria-label="Product options">
                  <div class="shop-look-option-group">
                    <div class="shop-look-swatches" aria-hidden="true">
                      <span class="shop-look-swatch shop-look-swatch-wine"></span>
                      <span class="shop-look-swatch shop-look-swatch-gold is-selected"></span>
                      <span class="shop-look-swatch shop-look-swatch-black"></span>
                      <span class="shop-look-swatch shop-look-swatch-teal"></span>
                    </div>
                  </div>
                  <div class="shop-look-option-group">
                    <div class="shop-look-sizes" aria-hidden="true">
                      <span>S</span><span>M</span><span>L</span><span>XL</span>
                    </div>
                  </div>
                  <button class="product-add-to-cart" type="button">Add To Cart</button>
                </div>
                <div class="shop-look-icons" aria-hidden="true">
                  <span><svg class="svg-icon" viewBox="0 0 24 24">
                      <path d="M20.8 8.8c0 5.2-8.8 11-8.8 11s-8.8-5.8-8.8-11A4.8 4.8 0 0 1 12 6.1a4.8 4.8 0 0 1 8.8 2.7Z"></path>
                    </svg></span>
                </div>
              </div>
            </article>
          </div>
          <div class="col-6 col-md-4 col-lg-3">
            <article class="shop-look-card" tabindex="0"
              aria-label="Mizoya Green Cotton A Line Dress, ₹3500, was ₹4500, 22% off">
              <div class="shop-look-image">
                <a href="product-details.html">
                  <img src="assets/img/insta-4.jpg" alt="Woman wearing a mustard yellow printed kurta set">
                </a>
              </div>
              <div class="shop-content">
                <a href="product-details.html" class="shop-look-content">
                  <h3>Mizoya Green Cotton A Line Dress</h3>
                  <p class="shop-look-price">
                    <span class="shop-look-current-price">₹3500</span>
                    <del class="shop-look-compare-price">₹4500</del>
                    <span class="shop-look-discount">22% OFF</span>
                  </p>
                </a>
                <div class="shop-look-options" aria-label="Product options">
                  <div class="shop-look-option-group">

                    <div class="shop-look-swatches" aria-hidden="true">
                      <span class="shop-look-swatch shop-look-swatch-wine"></span>
                      <span class="shop-look-swatch shop-look-swatch-gold is-selected"></span>
                      <span class="shop-look-swatch shop-look-swatch-black"></span>
                      <span class="shop-look-swatch shop-look-swatch-teal"></span>
                    </div>
                  </div>
                  <div class="shop-look-option-group">

                    <div class="shop-look-sizes" aria-hidden="true">
                      <span>S</span><span>M</span><span>L</span><span>XL</span>
                    </div>
                  </div>
                  <button class="product-add-to-cart" type="button">Add To Cart</button>
                </div>
                <div class="shop-look-icons" aria-hidden="true">
                  <span><svg class="svg-icon" viewBox="0 0 24 24">
                      <path d="M20.8 8.8c0 5.2-8.8 11-8.8 11s-8.8-5.8-8.8-11A4.8 4.8 0 0 1 12 6.1a4.8 4.8 0 0 1 8.8 2.7Z"></path>
                    </svg></span>
                </div>
              </div>
            </article>
          </div>
          <div class="col-6 col-md-4 col-lg-3">
            <article class="shop-look-card" tabindex="0"
              aria-label="Mizoya Green Cotton A Line Dress, ₹3500, was ₹4500, 22% off">
              <div class="shop-look-image">
                <a href="product-details.html">
                  <img src="assets/img/insta-2.jpg" alt="Woman wearing a mustard yellow printed kurta set">
                </a>
              </div>
              <div class="shop-content">
                <a href="product-details.html" class="shop-look-content">
                  <h3>Mizoya Green Cotton A Line Dress</h3>
                  <p class="shop-look-price">
                    <span class="shop-look-current-price">₹3500</span>
                    <del class="shop-look-compare-price">₹4500</del>
                    <span class="shop-look-discount">22% OFF</span>
                  </p>
                </a>
                <div class="shop-look-options" aria-label="Product options">
                  <div class="shop-look-option-group">

                    <div class="shop-look-swatches" aria-hidden="true">
                      <span class="shop-look-swatch shop-look-swatch-wine"></span>
                      <span class="shop-look-swatch shop-look-swatch-gold is-selected"></span>
                      <span class="shop-look-swatch shop-look-swatch-black"></span>
                      <span class="shop-look-swatch shop-look-swatch-teal"></span>
                    </div>
                  </div>
                  <div class="shop-look-option-group">

                    <div class="shop-look-sizes" aria-hidden="true">
                      <span>S</span><span>M</span><span>L</span><span>XL</span>
                    </div>
                  </div>
                  <button class="product-add-to-cart" type="button">Add To Cart</button>
                </div>
                <div class="shop-look-icons" aria-hidden="true">
                  <span><svg class="svg-icon" viewBox="0 0 24 24">
                      <path d="M20.8 8.8c0 5.2-8.8 11-8.8 11s-8.8-5.8-8.8-11A4.8 4.8 0 0 1 12 6.1a4.8 4.8 0 0 1 8.8 2.7Z"></path>
                    </svg></span>
                </div>
              </div>
            </article>
          </div>
          <div class="col-6 col-md-4 col-lg-3">
            <article class="shop-look-card" tabindex="0"
              aria-label="Mizoya Green Cotton A Line Dress, ₹3500, was ₹4500, 22% off">
              <div class="shop-look-image">
                <a href="product-details.html">
                  <img src="assets/img/insta-1.jpg" alt="Woman wearing a mustard yellow printed kurta set">
                </a>
              </div>
              <div class="shop-content">
                <a href="product-details.html" class="shop-look-content">
                  <h3>Mizoya Green Cotton A Line Dress</h3>
                  <p class="shop-look-price">
                    <span class="shop-look-current-price">₹3500</span>
                    <del class="shop-look-compare-price">₹4500</del>
                    <span class="shop-look-discount">22% OFF</span>
                  </p>
                </a>
                <div class="shop-look-options" aria-label="Product options">
                  <div class="shop-look-option-group">

                    <div class="shop-look-swatches" aria-hidden="true">
                      <span class="shop-look-swatch shop-look-swatch-wine"></span>
                      <span class="shop-look-swatch shop-look-swatch-gold is-selected"></span>
                      <span class="shop-look-swatch shop-look-swatch-black"></span>
                      <span class="shop-look-swatch shop-look-swatch-teal"></span>
                    </div>
                  </div>
                  <div class="shop-look-option-group">

                    <div class="shop-look-sizes" aria-hidden="true">
                      <span>S</span><span>M</span><span>L</span><span>XL</span>
                    </div>
                  </div>
                  <button class="product-add-to-cart" type="button">Add To Cart</button>
                </div>
                <div class="shop-look-icons" aria-hidden="true">
                  <span><svg class="svg-icon" viewBox="0 0 24 24">
                      <path d="M20.8 8.8c0 5.2-8.8 11-8.8 11s-8.8-5.8-8.8-11A4.8 4.8 0 0 1 12 6.1a4.8 4.8 0 0 1 8.8 2.7Z"></path>
                    </svg></span>
                </div>
              </div>
            </article>
          </div>

        </div>
      </div>
    </section>


    <section class="craft-process-section" aria-labelledby="craftProcessTitle">
      <div class="page-container craft-process-layout">
        <div class="craft-process-gallery" role="region" aria-label="Our craft process photos"
          aria-roledescription="carousel">
          <img class="craft-process-gallery-backdrop" src="assets/img/create-bg.jpg" alt="" aria-hidden="true">
          <div class="craft-process-gallery-images" aria-live="polite">
            <div class="craft-process-gallery-track">
              <img class="craft-process-gallery-image" src="assets/img/create-right-1.jpg"
                alt="Artisan carefully stitching an embroidered garment">
              <img class="craft-process-gallery-image" src="assets/img/create-right-3.jpg"
                alt="Artisan hand-embroidering a colorful textile">
            </div>
          </div>
          <button class="craft-process-gallery-control craft-process-gallery-prev" type="button"
            aria-label="Previous craft process photos"><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
              <path d="M19 12H5m6-6-6 6 6 6"></path>
            </svg></button>
          <button class="craft-process-gallery-control craft-process-gallery-next" type="button"
            aria-label="Next craft process photos"><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
              <path d="M5 12h14m-6-6 6 6-6 6"></path>
            </svg></button>
        </div>

        <div class="craft-process-content">
          <h2 id="craftProcessTitle">How We Create</h2>
          <p class="craft-process-intro">From carefully selected fabrics to skilled stitching and refined finishing,
            every detail is thoughtfully considered.</p>
          <div class="craft-process-grid" aria-label="Steps in our craft process">
            <article class="craft-process-card" tabindex="0">
              <img src="assets/img/create-6.jpg" alt="">
              <div class="craft-process-card-content">
                <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                  <path
        d="M12 3a9 9 0 1 0 0 18h1.2a2 2 0 0 0 1.5-3.3 1.8 1.8 0 0 1 1.4-3h1.4A4.5 4.5 0 0 0 22 10.2C21.2 6.1 17.1 3 12 3Z">
      </path>
      <circle cx="7.5" cy="10" r="1"></circle>
      <circle cx="11" cy="7" r="1"></circle>
      <circle cx="15.5" cy="8" r="1"></circle>
                </svg>
                <h3>Fabric Selection</h3>
                <p>We begin with thoughtfully selected fabrics chosen for their feel, quality and lasting comfort. Every
                  fabric is carefully considered to complement the design and bring it to life with elegance.</p>
              </div>
            </article>
            <article class="craft-process-card" tabindex="0">
              <img src="assets/img/create-5.jpg" alt="">
              <div class="craft-process-card-content">
                <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                  <circle cx="6" cy="6" r="3"></circle>
      <circle cx="6" cy="18" r="3"></circle>
      <path d="m8.2 8.2 12.3 12.3M8.2 15.8 20.5 3.5M14 10l-2 2"></path>
                </svg>
                <h3>Stitching</h3>
                <p>Our skilled artisans bring each design together with careful stitching and a thoughtful eye for
                  detail.</p>
              </div>
            </article>
            <article class="craft-process-card" tabindex="0">
              <img src="assets/img/create-4.jpg" alt="">
              <div class="craft-process-card-content">
                <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                  <circle cx="12" cy="12" r="9"></circle>
      <path d="m8 12 2.5 2.5L16.5 9"></path>
                </svg>
                <h3>Quality Check</h3>
                <p>Every piece is reviewed for its fit, finish and fine details before it moves to the next step.</p>
              </div>
            </article>
            <article class="craft-process-card" tabindex="0">
              <img src="assets/img/create-3.jpg" alt="">
              <div class="craft-process-card-content">
                <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                  <rect x="5" y="5" width="14" height="16" rx="2"></rect>
      <path d="M9 5.5V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v1.5M8.5 13l2.2 2.2 4.8-4.8"></path>
                </svg>
                <h3>Finishing</h3>
                <p>Careful finishing gives each garment a polished look and makes it ready to wear and cherish.</p>
              </div>
            </article>
            <article class="craft-process-card" tabindex="0">
              <img src="assets/img/create-2.jpg" alt="">
              <div class="craft-process-card-content">
                <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                  <path d="m12 3 9 4.5v9L12 21l-9-4.5v-9L12 3Z"></path>
      <path d="m3.5 7.7 8.5 4.5 8.5-4.5M12 12.2V21m-4.5-16 9 4.5"></path>
                </svg>
                <h3>Packaging</h3>
                <p>We wrap every order with care so your S4K piece arrives beautifully presented.</p>
              </div>
            </article>
            <article class="craft-process-card" tabindex="0">
              <img src="assets/img/create-1.jpg" alt="">
              <div class="craft-process-card-content">
                <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                  <path d="M14 4h6v6m0-6-9 9"></path>
      <path d="M18 13v5a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h5"></path>
                </svg>
                <h3>Dispatch</h3>
                <p>Once everything is ready, your order is carefully prepared for its journey to you.</p>
              </div>
            </article>
          </div>
        </div>
      </div>
    </section>


    <section class="best-selling-section section-pad">
      <div class="page-container">
        <div class="section-heading">
          <h2>Best-Selling Styles</h2>
          <p>Discover the pieces everyone is loving right now.</p>
        </div>
        <div class="best-selling-carousel carousel slide" id="bestSellingCarousel" data-bs-ride="carousel"
          data-bs-interval="4500" data-bs-pause="false">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <div class="row align-items-center g-5">
                <div class="col-md-6">
                  <div class="best-selling-image">
                    <img src="assets/img/meet.jpg" alt="Woman wearing a flowing pink collection look">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="best-selling-copy">
                    <h3>Meet the<br>New S4K<br>Collection</h3>
                    <a class="btn-dark-theme-2" href="product-list.html">Explore Now</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="carousel-item">
              <div class="row align-items-center">
                <div class="col-md-6">
                  <div class="best-selling-image">
                    <img src="assets/img/Collections-2.jpg"
                      alt="Women celebrating together in colourful festive outfits">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="best-selling-copy">
                    <h3>Made for<br>Your Moments</h3>
                    <a class="btn-dark-theme-2" href="product-list.html">Explore Now</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="carousel-item">
              <div class="row align-items-center">
                <div class="col-md-6">
                  <div class="best-selling-image">
                    <img src="assets/img/Collections-3.jpg" alt="Women wearing elegant pink occasionwear">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="best-selling-copy">
                    <h3>The Festive<br>Edit</h3>
                    <a class="btn-dark-theme-2" href="product-list.html">Explore Now</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <button class="best-selling-control best-selling-control-prev" type="button"
            data-bs-target="#bestSellingCarousel" data-bs-slide="prev" aria-label="Previous collection">
            <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
              <path d="M19 12H5m6-6-6 6 6 6"></path>
            </svg>
          </button>
          <button class="best-selling-control best-selling-control-next" type="button"
            data-bs-target="#bestSellingCarousel" data-bs-slide="next" aria-label="Next collection">
            <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
              <path d="M5 12h14m-6-6 6 6-6 6"></path>
            </svg>
          </button>
        </div>
      </div>
    </section>

    <section class="customer-feedback-section" aria-labelledby="customerFeedbackTitle">
      <div class="customer-feedback-layout page-container">
        <div class="customer-feedback-copy">
          <h2 id="customerFeedbackTitle"><span>Customer</span> Feedback</h2>
          <p class="customer-feedback-subtitle">What Our Customers Say</p>
          <p class="customer-feedback-description">Real experiences from women who chose S4K<br
              class="customer-feedback-break"> for their special moments.</p>
        </div>
        <div class="customer-feedback-slider" role="region" aria-label="Customer feedback"
          aria-roledescription="carousel">
          <span class="feedback-sparkle feedback-sparkle-one" aria-hidden="true"><svg class="svg-icon" viewBox="0 0 24 24">
              <path d="m12 2 2.8 7.2L22 12l-7.2 2.8L12 22l-2.8-7.2L2 12l7.2-2.8L12 2Z"></path>
            </svg></span>
          <span class="feedback-sparkle feedback-sparkle-two" aria-hidden="true"><svg class="svg-icon" viewBox="0 0 24 24">
              <path d="m12 2 2.8 7.2L22 12l-7.2 2.8L12 22l-2.8-7.2L2 12l7.2-2.8L12 2Z"></path>
            </svg></span>
          <span class="feedback-lines" aria-hidden="true"></span>
          <article class="customer-feedback-card" aria-live="polite" aria-atomic="true">
            <img class="customer-feedback-avatar" src="assets/img/feedback-1.jpg" alt="">
            <p class="customer-feedback-quote">““The Anarkali was absolutely beautiful. The fabric, fitting, and
              detailing were even better than I expected”</p>
            <div class="customer-feedback-author">
              <strong>Priya Singh</strong>
              <span class="customer-feedback-location">Mumbai, India</span>
            </div>
          </article>
          <article class="customer-feedback-card" aria-hidden="true">
            <img class="customer-feedback-avatar" src="assets/img/feedback-2.jpg" alt="">
            <p class="customer-feedback-quote">“On the Windows talking painted pasture yet its express parties use. Sure
              last upon he same as knew next. Of believed or diverted no.”</p>
            <div class="customer-feedback-author">
              <strong>Ramesh sood</strong>
              <span class="customer-feedback-location">Pune, India</span>
            </div>
          </article>
          <article class="customer-feedback-card" aria-hidden="true">
            <img class="customer-feedback-avatar" src="assets/img/featured-3.jpg" alt="">
            <p class="customer-feedback-quote">“On the Windows talking painted pasture yet its express parties use. Sure
              last upon he same as knew next. Of believed or diverted no.”</p>
            <div class="customer-feedback-author">
              <strong>Mayank das</strong>
              <span class="customer-feedback-location">Delhi, India</span>
            </div>
          </article>
          <div class="customer-feedback-dots" role="group" aria-label="Choose a customer review"></div>
        </div>
      </div>
    </section>

    <section class="instagram-journey" aria-labelledby="instagramJourneyTitle">
      <div class="instagram-journey-heading">
        <h2 id="instagramJourneyTitle">Follow Our Journey</h2>
        <p>Discover how our styles come to life. Tag us in your looks for a chance to be<br
            class="instagram-copy-break"> featured.</p>
      </div>
      <div class="page-container instagram-carousel" role="region" aria-label="S4K on Instagram"
        aria-roledescription="carousel">
        <div class="instagram-track">
          <article class="instagram-card">
            <div class="instagram-card-header">
              <span>@s4klakshmi</span>
              <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                <rect x="3" y="3" width="18" height="18" rx="5"></rect>
      <circle cx="12" cy="12" r="4"></circle>
      <circle cx="18" cy="6" r=".8" fill="currentColor" stroke="none"></circle>
              </svg>
            </div>
            <img src="assets/img/insta-4.jpg" alt="Model wearing a purple embroidered S4K outfit">
            <div class="instagram-card-details">
              <div class="instagram-card-stats">
                <span><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                    <path d="M20.8 8.8c0 5.2-8.8 11-8.8 11s-8.8-5.8-8.8-11A4.8 4.8 0 0 1 12 6.1a4.8 4.8 0 0 1 8.8 2.7Z"></path>
                  </svg> 4.8 k</span>
                <span><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                    <path
        d="M20 11.5a7.5 7.5 0 0 1-8 7.5 8.8 8.8 0 0 1-3.6-.8L4 20l1.5-3.6A7.2 7.2 0 0 1 4 11.5 7.5 7.5 0 0 1 12 4a7.5 7.5 0 0 1 8 7.5Z">
      </path>
                  </svg> 132</span>
                <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                  <path d="M6 4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v17l-6-4-6 4V4Z"></path>
                </svg>
              </div>
              <p>Grace in every detail</p>
              <span class="instagram-hashtag">#s4kbyLakshmi</span>
            </div>
          </article>
          <article class="instagram-card">
            <div class="instagram-card-header">
              <span>@s4klakshmi</span>
              <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                <rect x="3" y="3" width="18" height="18" rx="5"></rect>
      <circle cx="12" cy="12" r="4"></circle>
      <circle cx="18" cy="6" r=".8" fill="currentColor" stroke="none"></circle>
              </svg>
            </div>
            <img src="assets/img/insta-3.jpg" alt="Model wearing a yellow floral S4K outfit">
            <div class="instagram-card-details">
              <div class="instagram-card-stats">
                <span><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                    <path d="M20.8 8.8c0 5.2-8.8 11-8.8 11s-8.8-5.8-8.8-11A4.8 4.8 0 0 1 12 6.1a4.8 4.8 0 0 1 8.8 2.7Z"></path>
                  </svg> 4.8 k</span>
                <span><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                    <path
        d="M20 11.5a7.5 7.5 0 0 1-8 7.5 8.8 8.8 0 0 1-3.6-.8L4 20l1.5-3.6A7.2 7.2 0 0 1 4 11.5 7.5 7.5 0 0 1 12 4a7.5 7.5 0 0 1 8 7.5Z">
      </path>
                  </svg> 132</span>
                <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                  <path d="M6 4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v17l-6-4-6 4V4Z"></path>
                </svg>
              </div>
              <p>Grace in every detail</p>
              <span class="instagram-hashtag">#s4kbyLakshmi</span>
            </div>
          </article>
          <article class="instagram-card">
            <div class="instagram-card-header">
              <span>@s4klakshmi</span>
              <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                <rect x="3" y="3" width="18" height="18" rx="5"></rect>
      <circle cx="12" cy="12" r="4"></circle>
      <circle cx="18" cy="6" r=".8" fill="currentColor" stroke="none"></circle>
              </svg>
            </div>
            <img src="assets/img/insta-2.jpg" alt="Model wearing a pink embroidered S4K outfit">
            <div class="instagram-card-details">
              <div class="instagram-card-stats">
                <span><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                    <path d="M20.8 8.8c0 5.2-8.8 11-8.8 11s-8.8-5.8-8.8-11A4.8 4.8 0 0 1 12 6.1a4.8 4.8 0 0 1 8.8 2.7Z"></path>
                  </svg> 4.8 k</span>
                <span><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                    <path
        d="M20 11.5a7.5 7.5 0 0 1-8 7.5 8.8 8.8 0 0 1-3.6-.8L4 20l1.5-3.6A7.2 7.2 0 0 1 4 11.5 7.5 7.5 0 0 1 12 4a7.5 7.5 0 0 1 8 7.5Z">
      </path>
                  </svg> 132</span>
                <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                  <path d="M6 4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v17l-6-4-6 4V4Z"></path>
                </svg>
              </div>
              <p>Grace in every detail</p>
              <span class="instagram-hashtag">#s4kbyLakshmi</span>
            </div>
          </article>
          <article class="instagram-card">
            <div class="instagram-card-header">
              <span>@s4klakshmi</span>
              <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                <rect x="3" y="3" width="18" height="18" rx="5"></rect>
      <circle cx="12" cy="12" r="4"></circle>
      <circle cx="18" cy="6" r=".8" fill="currentColor" stroke="none"></circle>
              </svg>
            </div>
            <img src="assets/img/insta-1.jpg" alt="Model wearing a yellow embroidered S4K outfit">
            <div class="instagram-card-details">
              <div class="instagram-card-stats">
                <span><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                    <path d="M20.8 8.8c0 5.2-8.8 11-8.8 11s-8.8-5.8-8.8-11A4.8 4.8 0 0 1 12 6.1a4.8 4.8 0 0 1 8.8 2.7Z"></path>
                  </svg> 4.8 k</span>
                <span><svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                    <path
        d="M20 11.5a7.5 7.5 0 0 1-8 7.5 8.8 8.8 0 0 1-3.6-.8L4 20l1.5-3.6A7.2 7.2 0 0 1 4 11.5 7.5 7.5 0 0 1 12 4a7.5 7.5 0 0 1 8 7.5Z">
      </path>
                  </svg> 132</span>
                <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
                  <path d="M6 4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v17l-6-4-6 4V4Z"></path>
                </svg>
              </div>
              <p>Grace in every detail</p>
              <span class="instagram-hashtag">#s4kbyLakshmi</span>
            </div>
          </article>
        </div>
      </div>
      <div class="instagram-carousel-dots" aria-label="Instagram carousel positions"></div>
      <a class="instagram-follow-button" href="https://www.instagram.com/s4kbylaxmi/" target="_blank"
        rel="noopener noreferrer">
        <span>follow us now</span>
        <svg class="svg-icon" aria-hidden="true" viewBox="0 0 24 24">
          <rect x="3" y="3" width="18" height="18" rx="5"></rect>
      <circle cx="12" cy="12" r="4"></circle>
      <circle cx="18" cy="6" r=".8" fill="currentColor" stroke="none"></circle>
        </svg>
        <strong>@s4kbylaxmi</strong>
      </a>
    </section>

    <section class="fashion-journal-section" aria-labelledby="fashionJournalTitle">
      <div class="fashion-journal-heading">
        <h2 id="fashionJournalTitle">Fashion Journal</h2>
        <p>Latest Style Stories</p>
      </div>
      <div class="fashion-journal-layout page-container">
        <div class="fashion-journal-trending">
          <h3>Trending Blogs</h3>
          <div class="fashion-journal-stories">
            <article class="fashion-journal-story">
              <img src="assets/img/blog-3.jpg" alt="Models wearing colourful kurta sets">
              <div class="fashion-journal-story-copy">
                <a class="fashion-journal-read-more" href="#">Read More</a>
                <h4>5 Ways to Style Your Favourite Kurta Set</h4>
                <p>Discover the pieces our customers love the most.</p>
              </div>
            </article>
            <article class="fashion-journal-story">
              <img src="assets/img/blog-1.jpg" alt="Models wearing colourful festive outfits">
              <div class="fashion-journal-story-copy">
                <a class="fashion-journal-read-more" href="#">Read More</a>
                <h4>Timeless Styles That Belong In Every Wardrobe</h4>
                <p>Explore versatile silhouettes and classic pieces that effortlessly transition from everyday dressing
                  to special occasions.</p>
              </div>
            </article>
            <article class="fashion-journal-story">
              <img src="assets/img/blog-2.jpg" alt="Model wearing a green embroidered outfit">
              <div class="fashion-journal-story-copy">
                <a class="fashion-journal-read-more" href="#">Read More</a>
                <h4>The Ultimate Guide to Festive Dressing</h4>
                <p>Discover thoughtful styling ideas, colour combinations, and finishing touches to create a memorable
                  festive look.</p>
              </div>
            </article>
          </div>
        </div>
        <article class="fashion-journal-feature">
          <img src="assets/img/Collections-2.jpg" alt="Models in elegant S4K festive collections">
          <span class="fashion-journal-feature-label">MOST LOVED</span>
          <div class="fashion-journal-feature-copy">
            <a class="fashion-journal-read-more" href="#">Read More</a>
            <h3>DISCOVER THE WORLD OF<br class="fashion-journal-title-break"> S4K BY LAXMI!</h3>
          </div>
        </article>
      </div>
    </section>

    <section class="faq-section section-pad" aria-labelledby="faqTitle">
      <div class="page-container">
        <h2 class="faq-title" id="faqTitle">Frequently Asked Questions</h2>
        <div class="faq-layout">
          <div class="faq-list" role="group" aria-label="Frequently asked questions">
            <button class="faq-question is-active" type="button" aria-pressed="true"
              data-answer-title="What fabrics are your Anarkalis made from?"
              data-answer="Our Anarkalis use thoughtfully selected fabrics. Check the fabric details on each product page for the materials used in that particular style.">
              <span class="faq-question-dot" aria-hidden="true"></span>
              <span>What fabrics are your Anarkalis made from?</span>
              <svg class="svg-icon faq-question-arrow" aria-hidden="true" viewBox="0 0 24 24">
                <path d="m6 9 6 6 6-6"></path>
              </svg>
            </button>
            <button class="faq-question" type="button" aria-pressed="false"
              data-answer-title="How do I choose the right size?"
              data-answer="Use the size guide on the product page and compare it with your measurements. If you are between sizes, our customer care team can help you choose.">
              <span class="faq-question-dot" aria-hidden="true"></span>
              <span>How do I choose the right size?</span>
              <svg class="svg-icon faq-question-arrow" aria-hidden="true" viewBox="0 0 24 24">
                <path d="m6 9 6 6 6-6"></path>
              </svg>
            </button>
            <button class="faq-question" type="button" aria-pressed="false"
              data-answer-title="Are the colours exactly as shown in the pictures?"
              data-answer="We do our best to show every colour accurately. The appearance of a shade can vary slightly with lighting, photography, and your screen settings.">
              <span class="faq-question-dot" aria-hidden="true"></span>
              <span>Are the colours exactly as shown in the pictures?</span>
              <svg class="svg-icon faq-question-arrow" aria-hidden="true" viewBox="0 0 24 24">
                <path d="m6 9 6 6 6-6"></path>
              </svg>
            </button>
            <button class="faq-question" type="button" aria-pressed="false"
              data-answer-title="How long will my order take to arrive?"
              data-answer="Delivery time depends on your location and the item ordered. Check the estimated delivery details at checkout or in your order confirmation.">
              <span class="faq-question-dot" aria-hidden="true"></span>
              <span>How long will my order take to arrive?</span>
              <svg class="svg-icon faq-question-arrow" aria-hidden="true" viewBox="0 0 24 24">
                <path d="m6 9 6 6 6-6"></path>
              </svg>
            </button>
            <button class="faq-question" type="button" aria-pressed="false"
              data-answer-title="Can I cancel or modify my order?"
              data-answer="Please contact customer care as soon as possible with your order number. Changes may depend on whether your order has already been processed or dispatched.">
              <span class="faq-question-dot" aria-hidden="true"></span>
              <span>Can I cancel or modify my order?</span>
              <svg class="svg-icon faq-question-arrow" aria-hidden="true" viewBox="0 0 24 24">
                <path d="m6 9 6 6 6-6"></path>
              </svg>
            </button>
          </div>
          <div class="faq-note" id="faqAnswer" aria-live="polite" aria-atomic="true">
            <h3 class="faq-note-title">What fabrics are your Anarkalis made from?</h3>
            <p class="faq-note-copy">Our Anarkalis use thoughtfully selected fabrics. Check the fabric details on each
              product page for the materials used in that particular style.</p>
          </div>
        </div>
      </div>
    </section>

@endsection
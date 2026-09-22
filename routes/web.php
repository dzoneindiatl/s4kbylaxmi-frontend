<?php

use App\Http\Controllers\Front\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\controllers\Front\CartController; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::post('/get-variant-remaining-qty', [HomeController::class, 'getExactVariantComboQty'])->name('get-variant-remaining-qty');
Route::post('/is-outofstock', [HomeController::class, 'isOutOfStock'])->name('is-outofstock');
Route::get('/contact-us', [HomeController::class, 'contactUs'])->name('contact.index');
Route::get('/store-locator', [HomeController::class, 'storeLocator'])->name('store-locator');
Route::get('/franchise-enquiry', [HomeController::class, 'franchiseEnquiry'])->name('franchiseEnquiry');
Route::get('/wholesale-enquiry', [HomeController::class, 'wholesaleEnquiry'])->name('wholesaleEnquiry');
Route::get('/dashboard', [\App\Http\Controllers\Front\DashboardController::class, 'index'])->name('user.dashboard');
Route::post('/subscribe', [HomeController::class, 'subscribe'])->name('subscribe');
Route::get('/product/product-sort-filter', [ShopController::class, 'productSortFilter'])->name('sort.filter');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::match(['get', 'post'], '/add-to-cart', [CartController::class, 'addToCart'])->name('user.addToCart');
Route::match(['get', 'post'], '/get-cart-items', [CartController::class, 'getCartItems'])->name('user.get-cart-items');
Route::post('/apply-coupons', [CartController::class, 'applyCoupon'])->name('apply.coupon');
Route::post('/get-coupons', [CartController::class, 'getCoupon'])->name('get.coupon');
Route::get('/filter-new-arrivals/{categoryId}', [HomeController::class, 'filterNewArrivals'])->name('filter.new.arrivals');
Route::get('/best-seller-filter', [HomeController::class, 'bestSellerFilter'])->name('front.best.seller.filter');
Route::get('/cart/view', [HomeController::class, 'viewBag'])->name('product.viewBag');
Route::post('/product/recently-viewed-products', [ShopController::class, 'getRecentlyViewed']);
Route::post('/submit-bulk-order', [BulkOrderController::class, 'submitBulkOrder'])->name('bulk.order.submit');
Route::post('/wholesale-enquiry', [App\Http\Controllers\Front\WholesaleEnquiryController::class, 'store'])->name('wholesale-enquiry');
Route::post('/franchise-enquiry', [App\Http\Controllers\Front\WholesaleEnquiryController::class, 'franchiseStore'])->name('franchise-enquiry');
Route::post('/contact-store', [App\Http\Controllers\Front\WholesaleEnquiryController::class, 'contact'])->name('contact');
Route::get('/product-search-default', [HomeController::class, 'productSearchDefault'])->name('product.search-default');
Route::get('/product-search', [HomeController::class, 'productSearch'])->name('product.search');
Route::get('/search/view-all', [HomeController::class, 'viewAll'])->name('search.viewall');
Route::get('/page/{slug}', [HomeController::class, 'dynamicPages'])->name('page.details');
Route::get('/blogs', [HomeController::class, 'blogList'])->name('blog.blog');
Route::get('/blog-details/{slug}', [HomeController::class, 'blogDetails'])->name('blog.detail');
Route::match(['get', 'post'], '/check-delevery', [CartController::class, 'checkDelevery'])->name('check.delevery');

Route::post('subscribers/create',[HomeController::class,'storeNewsletterRecord'])->name('create-subscriber'); 


Route::post('/set-currency', [HomeController::class, 'setCurrency'])->name('set-currency');
Route::match(['get', 'post'], 'variant-combination/prices', [HomeController::class, 'variantCombinationPrices'])->name('variant.combination.prices');

Route::get('/get-states/{country_id}', [App\Http\Controllers\Front\HomeController::class, 'getStates']);
Route::get('/get-cities/{state_id}', [App\Http\Controllers\Front\HomeController::class, 'getCities']);
Route::name('front-')->group(function () {
    Route::get('/', [App\Http\Controllers\Front\HomeController::class, 'index'])->name('home.index');
    Route::get('/shop/{categoryId?}/{subCategoryId?}/{childCategoryId?}', [App\Http\Controllers\Front\ShopController::class, 'index'])->name('shop.index');

    Route::middleware(['GuestCustomer'])->group(function () {        
        Route::get('/login', [App\Http\Controllers\Front\Auth\AuthController::class, 'login'])->name('user.login');
        Route::get('/signup', [App\Http\Controllers\Front\Auth\AuthController::class, 'signup'])->name('user.signup');
        Route::post('/sign-in', [App\Http\Controllers\Front\Auth\AuthController::class, 'postLogin'])->name('user.postLogin');
        Route::post('/signup', [App\Http\Controllers\Front\Auth\AuthController::class, 'postSignup'])->name('user.postSignup');
        Route::post('/postSignupVerify', [App\Http\Controllers\Front\Auth\AuthController::class, 'postSignupVerify'])->name('user.postSignupVerify');
        Route::get('auth/{provider}', [SocialAuthController::class, 'redirect'])->name('social.redirect');
        Route::get('auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');
        Route::match(['get', 'post'], 'forget-password', [App\Http\Controllers\Front\Auth\AuthController::class, 'forgetPassword'])->name('user.forgetPassword');
        Route::match(['get', 'post'], 'send-password', [App\Http\Controllers\Front\Auth\AuthController::class, 'sendResetLinkEmail'])->name('user.sendPassword');
        Route::match(['get', 'post'], 'reset-password/{validstring}', [App\Http\Controllers\Front\Auth\AuthController::class, 'resetPassword'])->name('user.resetPassword');
        Route::match(['get', 'post'], 'reset-password-save/{validstring}', [App\Http\Controllers\Front\Auth\AuthController::class, 'resetPasswordSave'])->name('user.resetPasswordSave');
        Route::post('/resentotp', [App\Http\Controllers\Front\Auth\AuthController::class, 'resentotp'])->name('user.resentotp');
    });



    Route::middleware(['AuthCustomer'])->group(function () {
        Route::get('/order-confirm/{id}', [App\Http\Controllers\Front\HomeController::class, 'order_confirm'])->name('user.order.confirm');

        /* dashboard routes */
        Route::get('/wishlist', [App\Http\Controllers\Front\DashboardController::class, 'wishlist'])->name('user.wishlist');
        Route::get('/logout', [App\Http\Controllers\Front\Auth\AuthController::class, 'logout'])->name('user.logout');
        Route::post('/update-profile', [App\Http\Controllers\Front\DashboardController::class, 'updateProfile'])->name('user.updateProfile');
        Route::post('/change-password', [App\Http\Controllers\Front\DashboardController::class, 'changePassword'])->name('user.changePassword');
        Route::get('/addresses', [App\Http\Controllers\Front\DashboardController::class, 'addresses'])->name('user.addresses');
        Route::post('/addresses/add-address', [App\Http\Controllers\Front\DashboardController::class, 'addAddress'])->name('user.addAddress');
        Route::post('/addresses/edit-address/{addressId}', [App\Http\Controllers\Front\DashboardController::class, 'editAddress'])->name('user.editAddress');
        Route::get('/addresses/make-primary-address/{addressId}', [App\Http\Controllers\Front\DashboardController::class, 'makeAddressPrimary'])->name('user.makeAddressPrimary');
        Route::get('/addresses/delete-address/{addressId}', [App\Http\Controllers\Front\DashboardController::class, 'deleteAddress'])->name('user.deleteAddress');
        Route::get('/orders', [App\Http\Controllers\Front\DashboardController::class, 'orders'])->name('user.orders');

       /* new dashboard routes */
        Route::get('/mypurchase', [App\Http\Controllers\Front\DashboardController::class, 'myPurchase'])->name('user.myPurchase');
        Route::get('/mypurchasedetail/{id}', [App\Http\Controllers\Front\DashboardController::class, 'myPurchaseDetail'])->name('user.myPurchaseDetail');
        Route::get('/accountsetting', [App\Http\Controllers\Front\DashboardController::class, 'accountSetting'])->name('user.accountSetting');
        Route::get('/walletpayment', [App\Http\Controllers\Front\DashboardController::class, 'walletPayment'])->name('user.walletPayment');
        Route::get('/suggestion', [App\Http\Controllers\Front\DashboardController::class, 'suggestion'])->name('user.suggestion');
        Route::get('/contactwithus', [App\Http\Controllers\Front\DashboardController::class, 'contactwithus'])->name('user.contactwithus');
        Route::post('/contactSuggestionSave', [App\Http\Controllers\Front\DashboardController::class, 'contactSuggestionSave'])->name('user.contactSuggestionSave');
        Route::get('/rateing-review', [App\Http\Controllers\Front\DashboardController::class, 'rateingReview'])->name('user.rateingReview');
        Route::get('/invite-friends', [App\Http\Controllers\Front\DashboardController::class, 'inviteFriends'])->name('user.inviteFriends');
        /* new dashboard routes */

        /* dashboard routes */

        Route::match(['get', 'post'], '/add-to-wishlist', [App\Http\Controllers\Front\CartController::class, 'addToWishlist'])->name('user.addToWishlist');
        Route::match(['get', 'post'], '/remove-from-wishlist', [App\Http\Controllers\Front\CartController::class, 'removeFromWishlist'])->name('user.removeFromWishlist');

        Route::any('/save-user-address', [App\Http\Controllers\Front\CheckoutController::class, 'saveAddress'])->name('user.save_address');
        Route::get('/get-user-address/{addressId}', [App\Http\Controllers\Front\CheckoutController::class, 'getUserAddress'])->name('user.get_user_address');
        Route::post('/update-address', [App\Http\Controllers\Front\CheckoutController::class, 'updateAddress'])->name('user.update_user_address');
        Route::get('/get-user-wallet/{user_id}', [App\Http\Controllers\Front\CheckoutController::class, 'getuserWallet']);

        Route::POST('/place-order', [App\Http\Controllers\Front\CheckoutController::class, 'placeOrder']);
        Route::any('/checkout-callback', [App\Http\Controllers\Front\CheckoutController::class, 'checkout_callback'])->name('product.checkout.callback');
        Route::get('/order-details/{orderId}', [App\Http\Controllers\Front\OrderController::class, 'orderDetails']);
        //Route::get('/order-confirm/{id}', [App\Http\Controllers\Front\HomeController::class, 'order_confirm'])->name('user.order.confirm');
        Route::get('/checkout', [HomeController::class, 'checkoutBag'])->name('product.checkoutBag');
        Route::match(['get', 'post'], '/orders/change-status', [OrderController::class, 'change_status'])->name('orders.change-status');
        Route::post('/cancel-order-submit', [OrderController::class, 'submitCancelOrder'])->name('cancel.order.submit');
        Route::post('/refund-submit', [OrderController::class, 'submitRefundRequest'])->name('refund.submit');
        Route::get('/order-success/{order_id}', [OrderController::class, 'orderSuccess'])->name('order.success');
        Route::post('/add-review', [App\Http\Controllers\Front\DashboardController::class, 'addReview'])->name('user.addReview');
        Route::post('/wishlist/toggle', [App\Http\Controllers\Front\DashboardController::class, 'toggle'])->name('addwish');
        Route::match(['get', 'post'], '/orders/generate-invoice/{id}', [OrderController::class, 'generateNewInvoice'])->name('orders.generate.invoice');
        Route::match(['post'], '/orders/generate-items-invoice', [OrderController::class, 'generateItemsInvoice'])->name('orders.generate.items.invoice');
        Route::match(['get', 'post'], '/get-shipping-address', [CheckoutController::class, 'getShippingData'])->name('get.shipping.address');

        Route::post('order/cancel',[CheckoutController::class,'orderCancelled'])->name('order.cancel');
        Route::post('order/delivered',[CheckoutController::class,'orderReturn'])->name('order.return');
    });

    Route::get('header-product-search',[HomeController::class,'headerProductSearch'])->name('header-product-search'); 
    Route::get('/product/{product}/{title}/{sku}', [HomeController::class, 'productDetail'])->name('product.detail');

    Route::get('get/sub/child/category',[HomeController::class,'getSubAndChildCategory'])->name('get-category'); 
    Route::post('/remove-cart-product',[HomeController::class,'removeCartProduct'])->name('remove-cart-product'); 

    Route::get('get-product-variant-image',[HomeController::class,'getProductVariantImages'])->name('product-variant-image'); 
    });
    
Route::post('/checkVarientStock', [App\Http\Controllers\Front\HomeController::class, 'variantStockCheck'])->name('variant.stock.check');
Route::get('product/collection/{slug}', [HomeController::class, 'collectionListing'])->name('collections.show');
Route::get('{path}', [HomeController::class, 'productListing'])->where('path', '.*')->name('category.show');



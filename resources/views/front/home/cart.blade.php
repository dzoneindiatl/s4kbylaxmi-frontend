@extends('front.layout.app')
@section('content')
  <main class="cart-page page-container" data-dynamic-cart>
    <h1 class="cart-title" data-cart-title>Cart</h1>
    @if (session('cart_merge_warning') || session('cart_merge_error'))
      <p class="cart-merge-message" role="status">
        {{ session('cart_merge_warning') ?: session('cart_merge_error') }}
      </p>
    @endif
    <ol class="cart-steps" aria-label="Checkout progress">
      <li class="cart-step is-active" data-cart-step="1" aria-current="step"><span class="cart-step-number">1</span><span>Shopping cart</span></li>
      <li class="cart-step" data-cart-step="2"><span class="cart-step-number">2</span><span>Checkout details</span></li>
      <li class="cart-step" data-cart-step="3"><span class="cart-step-number">3</span><span>Order complete</span></li>
    </ol>

    <div class="cart-layout" data-cart-view="cart">
      <section class="cart-items" aria-label="Items in your shopping cart">
        @if (Auth::guard('customer')->check())
          @forelse($carts as $cart)
            @php
              $cartProduct = $cart->product;
            $cartImage = $cart->imageUrl
                ?? data_get($cartProduct, 'images.first')
                ?: data_get($cartProduct, 'product_main_images.0.image')
                ?: asset('assets/img/placeholder.png');
              $cartProductUrl = !empty($cartProduct->sku)
                  ? route('front-product.detail', [
                      'product' => 'product',
                      'title' => $cartProduct->slug . '.html',
                      'sku' => $cartProduct->sku
                    ])
                  : '#';
            @endphp
            <article class="cart-item dbCartData"
              data-cart-id="{{ $cart->id }}"
              data-product-id="{{ $cartProduct->id }}">
              <div class="cart-item-product">
                <a href="{{ $cartProductUrl }}">
                  <img class="cart-item-image" src="{{ $cartImage }}" alt="{{ $cartProduct->name }}">
                </a>
                <div>
                  <h2 class="cart-item-name"><a href="{{ $cartProductUrl }}">{{ $cartProduct->name }}</a></h2>
                  <p class="cart-item-price cart-country-price" data-base-amount="{{ $cart->sellingPrice }}">₹{{ number_format((float) $cart->sellingPrice, 2) }}</p>
                  <p class="cart-item-sku">{{ $cart->sku }}</p>
                </div>
              </div>
              <div class="cart-quantity quantity" aria-label="Quantity"
                   data-cart-id="{{ $cart->id }}"
                   data-min-quantity="{{ $cart->minQuantity ?? 1 }}"
                   data-max-quantity="{{ $cart->maxQuantity ?? ($cartProduct->qty ?? 1) }}">
                <button type="button" class="minus" data-quantity-change="-1" aria-label="Decrease quantity">−</button>
                <output class="cart-quantity-value" aria-live="polite">{{ $cart->quantity }}</output>
                <button type="button" class="plus" data-quantity-change="1" aria-label="Increase quantity">+</button>
              </div>
              <button class="cart-remove remove-cart-product" type="button"
                      data-cartid="{{ $cart->id }}"
                      data-productid="{{ $cartProduct->id }}"
                      aria-label="Remove {{ $cartProduct->name }}">&times;</button>
            </article>
          @empty
            <p class="cart-empty is-visible" role="status">Your cart is empty.</p>
          @endforelse
        @else
          <div class="localCartData"></div>
          <p class="cart-empty" role="status" style="display:none;">Your cart is empty.</p>
        @endif
      </section>

      <aside class="cart-sidebar">
        @if (Auth::guard('customer')->check())
          <section class="cart-coupon" aria-labelledby="cartCouponHeading">
            <h2 id="cartCouponHeading">Have a coupon?</h2>
            <p>Add your code for an instant cart discount</p>
            <form class="cart-coupon-entry" id="cartCouponForm">
              <svg class="cart-coupon-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 7.5A2.5 2.5 0 0 1 5.5 5h13A2.5 2.5 0 0 1 21 7.5v9a2.5 2.5 0 0 1-2.5 2.5h-13A2.5 2.5 0 0 1 3 16.5z"></path><path d="M8 5v2m8-2v2M8 17l8-8"></path><circle cx="8" cy="10" r="1"></circle><circle cx="16" cy="14" r="1"></circle></svg>
              <input id="couponCode" class="int_coup_txt" name="couponCode" type="text" aria-label="Coupon code" placeholder="Coupon Code" autocomplete="off">
              <button type="submit" class="btn_coupon_apply">Apply</button>
            </form>
            <p class="cart-coupon-message coupon_error" aria-live="polite"></p>
            <div class="flate-off-l"></div>
            @if(!empty($allCoupons))
              <div class="cart-available-coupons">
                <h3>Available offers</h3>
                @foreach($allCoupons as $couponUser)
                  @php
                    $coupon = $couponUser->coupon ?? null;
                    if (!$coupon) continue;
                    $couponDiscountLabel = $coupon->discount_type === 'percentage'
                        ? $coupon->discount_value . '% OFF'
                        : $coupon->discount_value . ' Rs OFF';
                    $couponApplicable = (float) $subtotal >= (float) $coupon->min_cart_value;
                  @endphp
                  <div class="cart-available-coupon">
                    <strong>{{ $couponDiscountLabel }}</strong>
                    <span>{{ $coupon->coupon_code }}</span>
                    <small>Valid till {{ date('M d Y', strtotime($coupon->end_date)) }}</small>
                    <small>{{ $couponApplicable ? 'Applicable' : 'Not Applicable' }}</small>
                  </div>
                @endforeach
              </div>
            @endif
          </section>
        @endif

        <section class="cart-summary" aria-labelledby="cartSummaryHeading">
          <h2 id="cartSummaryHeading">Cart summary</h2>
          <div class="cart-summary-row totalMRP"><span>Total MRP</span><strong class="price-values cart-country-price" data-base-amount="{{ $totalMRP }}">₹{{ number_format((float) $totalMRP, 2) }}</strong></div>
          <div class="cart-summary-row discount"><span>Discount</span><span class="price-values cart-country-price" data-base-amount="{{ $totalDiscount }}">₹{{ number_format((float) $totalDiscount, 2) }}</span></div>
          <div class="cart-summary-row subtotal"><span>Sub Total</span><span class="price-values cart-country-price" data-base-amount="{{ $subtotal }}">₹{{ number_format((float) $subtotal, 2) }}</span></div>
          <div class="cart-summary-row couponDiscount"><span>Coupon Discount</span><strong class="price-values cart-country-price" data-base-amount="0" data-display-sign="-">-₹0.00</strong></div>
          <div class="cart-summary-row is-total grandTotal"><span>Grand Total</span><strong class="price-values cart-country-price" data-base-amount="{{ $grandTotal }}">₹{{ number_format((float) $grandTotal, 2) }}</strong></div>
          <div class="cart-summary-row taxableAmount"><span>Taxable Amount</span><span class="price-values cart-country-price" data-base-amount="{{ $taxableAmount }}">₹{{ number_format((float) $taxableAmount, 2) }}</span></div>
          <div class="cart-summary-row totalGst"><span>Total GST (Tax)</span><span class="price-values cart-country-price" data-base-amount="{{ $totalGst }}" data-display-sign="+ ">+ ₹{{ number_format((float) $totalGst, 2) }}</span></div>
          <div class="cart-summary-row is-payable totalPayable"><span>Total Payable<br>(Tax Included)</span><strong class="price-values cart-country-price" data-base-amount="{{ $totalPayable }}">₹{{ number_format((float) $totalPayable, 2) }}</strong></div>
          <button class="cart-checkout btn_place_order" type="button">Checkout</button>
          <p class="cart-returns">15-Day Hassle Free Returns</p>
        </section>
      </aside>
    </div>

    <section class="checkout-view" data-cart-view="checkout" hidden aria-labelledby="billingHeading">
      <div class="checkout-columns">
        <div class="checkout-form-column">
          <section class="checkout-section">
            <h2 id="billingHeading">Billing Address</h2>
            <p class="checkout-help">Select the address that matches your delivery and payment details.</p>
            <label class="checkout-address-label" for="checkoutAddress">Shipping address</label>
            <select id="checkoutAddress" name="shipping-address" class="form-select" required>
              @if (($addresses ?? collect())->isEmpty())
                <option value="">Select a saved address</option>
              @endif
              @foreach ($addresses ?? [] as $address)
                <option value="{{ $address->id }}">
                  {{ $address->name }} — {{ $address->address }}{{ $address->landmark ? ', ' . $address->landmark : '' }}, {{ $address->postal_code }}
                </option>
              @endforeach
            </select>
            @if (($addresses ?? collect())->isEmpty())
              <p class="checkout-help">Add a delivery address to continue.</p>
            @endif
            <div class="billing-options" role="group" aria-label="Billing address">
              <label class="checkout-option">
                <input type="radio" name="billing-address" value="shipping" checked>
                <span>Same as shipping address</span>
              </label>
              <label class="checkout-option">
                <input type="radio" name="billing-address" value="different">
                <span>Use a different billing address</span>
              </label>
            </div>
            <div class="billing-fields" data-billing-fields hidden>
              <label for="billingAddress">Billing address</label>
              <select id="billingAddress" name="billing-address-id" class="form-select">
                @if (($addresses ?? collect())->isEmpty())
                  <option value="">Add an address first</option>
                @endif
                @foreach ($addresses ?? [] as $address)
                  <option value="{{ $address->id }}">
                    {{ $address->name }} — {{ $address->address }}{{ $address->landmark ? ', ' . $address->landmark : '' }}, {{ $address->postal_code }}
                  </option>
                @endforeach
              </select>
            </div>
            <button type="button" class="checkout-cancel address-form-toggle" data-show-address-form>
              Add a new address
            </button>
            <form class="checkout-address-form billing-fields" data-address-form hidden>
              @csrf
              <label for="checkoutAddressName">Full name</label>
              <input id="checkoutAddressName" name="name" type="text" autocomplete="name" required>
              <label for="checkoutAddressPhone">Phone number</label>
              <input id="checkoutAddressPhone" name="phone_number" type="tel" autocomplete="tel" required>
              <label for="checkoutAddressCountry">Country</label>
              <select id="checkoutAddressCountry" name="country_id" required>
                <option value="">Select country</option>
                @foreach ($countries ?? [] as $country)
                  <option value="{{ $country->id }}" @selected(strcasecmp($country->name, 'India') === 0)>
                    {{ $country->name }}
                  </option>
                @endforeach
              </select>
              <label for="checkoutAddressState">State</label>
              <select id="checkoutAddressState" name="state_id" required disabled>
                <option value="">Select country first</option>
              </select>
              <label for="checkoutAddressCity">City</label>
              <select id="checkoutAddressCity" name="city_id" required disabled>
                <option value="">Select state first</option>
              </select>
              <label for="checkoutAddressPostal">PIN code</label>
              <input id="checkoutAddressPostal" name="postal_code" type="text" inputmode="numeric" autocomplete="postal-code" required>
              <label for="checkoutAddressStreet">Address</label>
              <textarea id="checkoutAddressStreet" name="address" autocomplete="street-address" required></textarea>
              <label for="checkoutAddressLandmark">Landmark (optional)</label>
              <input id="checkoutAddressLandmark" name="landmark" type="text">
              <label for="checkoutAddressType">Address type</label>
              <select id="checkoutAddressType" name="address_type" required>
                <option value="1">Home</option>
                <option value="2">Office</option>
                <option value="3">Other</option>
              </select>
              <p class="checkout-error" data-address-error role="alert" hidden></p>
              <button type="submit" class="checkout-place-order" data-save-address>Save address</button>
            </form>
            <p class="checkout-error" data-checkout-error role="alert" hidden></p>
          </section>

          <section class="checkout-section payment-section">
            <h2>Payment</h2>
            <p class="checkout-help">Select a payment method.</p>
            <div class="payment-options">
              <label class="payment-option payment-option-simple">
                <input type="radio" name="payment-method" value="cod" checked>
                <span>Cash on Delivery</span>
                <svg class="payment-method-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="m3 7 9-4 9 4v10l-9 4-9-4z"></path><path d="m3.5 7.2 8.5 4 8.5-4M12 11.2V21m-4.5-16L16 9"></path></svg>
              </label>
            </div>
            <p class="checkout-error" data-checkout-error role="alert" hidden></p>
          </section>
          <div class="checkout-actions">
            <button class="checkout-place-order" type="button" data-place-order>Place Order</button>
            <button class="checkout-cancel" type="button" data-cart-next="1">Cancel</button>
          </div>
        </div>

        <aside class="checkout-order-summary" aria-labelledby="orderSummaryHeading">
          <h2 id="orderSummaryHeading">Order summary</h2>
          <div class="checkout-summary-items" data-checkout-items></div>
          <form class="checkout-coupon" data-checkout-coupon>
            <label class="visually-hidden" for="checkoutCoupon">Coupon code</label>
            <input id="checkoutCoupon" type="text" placeholder="Enter your coupon" autocomplete="off">
            <button type="submit" class="btn_coupon_apply">Apply</button>
          </form>
          <p class="checkout-error checkout-coupon-message" role="status" aria-live="polite"></p>
          <div class="checkout-coupon-applied" data-checkout-coupon-applied hidden>
            <span class="checkout-coupon-label"><span data-checkout-coupon-code></span></span>
            <span><span data-checkout-coupon-savings></span> <button type="button" data-remove-coupon>[Remove]</button></span>
          </div>
          <div class="checkout-total-row"><span>Shipping</span><span>Free</span></div>
          <div class="checkout-total-row"><span>Subtotal</span><strong data-checkout-subtotal>₹0.00</strong></div>
          <div class="checkout-total-row"><span>Discount</span><strong data-checkout-discount>₹0.00</strong></div>
          <div class="checkout-total-row checkout-grand-total"><span>Total</span><strong data-checkout-total>₹0.00</strong></div>
        </aside>
      </div>
    </section>

    <section class="complete-view" data-cart-view="complete" hidden aria-labelledby="completeTitle">
      <div class="complete-card">
        <p class="complete-thanks">Thank you!!!! <svg class="complete-celebration-icon" viewBox="0 0 24 24" aria-label="Celebration" role="img"><path d="m5 19 8-8m-6 10 5-5m1-8 1.5-4L16 8l4 1.5-4 1.5-1.5 4L13 11zM5 4l1 2 2 1-2 1-1 2-1-2-2-1 2-1zM18 17l1 1 2 1-2 1-1 2-1-2-2-1 2-1z"></path><path d="m7 17 2 2"></path></svg></p>
        <h2 id="completeTitle">Your order has been<br>received</h2>
        <div class="complete-products" data-complete-products aria-label="Purchased products"></div>
        <dl class="complete-details">
          <div><dt>Order code:</dt><dd data-order-code></dd></div>
          <div><dt>Date:</dt><dd data-order-date></dd></div>
          <div><dt>Total:</dt><dd data-order-total></dd></div>
          <div><dt>Payment method:</dt><dd data-order-payment></dd></div>
        </dl>
        <a class="purchase-history-button" href="{{ route('front-user.orders') }}">Purchase History</a>
      </div>
    </section>
  </main>

@php
    $minSellingQty = 1;
    $maxSellingQty = 10;
    $couponDiscount = 0;
@endphp

@push('scripts')
<script>
    window.isLoggedIn = @json(Auth::guard('customer')->check());
    window.dbCartItems = @json($cartItems ?? []);
    window.cartSummaryBase = {
        grandTotal: @json((float) ($grandTotal ?? 0)),
        totalPayable: @json((float) ($totalPayable ?? 0))
    };
    window.couponApplyVersion = 0;
    window.cartRoutes = {
        applyCoupon: @json(route('apply.coupon')),
        removeCart: @json(route('cart.remove.item')),
        updateQuantity: @json(route('cart.update.quantity')),
        guestSync: @json(route('cart.guest-sync')),
        placeOrder: @json(route('front-checkout.place-order')),
        cart: @json(route('product.viewBag')),
        login: @json(route('front-user.login', ['return_to' => 'checkout'])),
        addresses: @json(route('front-user.addresses')),
        saveAddress: @json(route('front-user.save_address')),
        addressStates: @json(route('address.states')),
        addressCities: @json(route('address.cities')),
        orderSuccess: @json(route('front-order.success', ['order_id' => 'ORDER_ID']))
    };
    window.cartCsrf = @json(csrf_token());
</script>
<script src="{{ asset('assets/js/cart.js') }}?v={{ filemtime(public_path('assets/js/cart.js')) }}"></script>
<script>
(function ($) {
    'use strict';

    const money = (value) => Number(value || 0).toLocaleString('en-IN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    function getSelectedCurrency() {
        let country = {};
        try {
            country = JSON.parse($('#countryData').val() || '{}');
        } catch (error) {
            country = {};
        }
        const code = country.currency_code || 'INR';
        return {
            code,
            symbol: country.currency_symbol || '₹',
            rate: code === 'INR' ? 1 : Number(country.exchange_rates || 0)
        };
    }

    window.updateCartCountryPrices = function () {
        const currency = getSelectedCurrency();
        if (!currency.rate || currency.rate <= 0) return;

        $('.cart-country-price').each(function () {
            const base = Number($(this).attr('data-base-amount'));
            if (!Number.isFinite(base)) return;
            const sign = $(this).attr('data-display-sign') || '';
            $(this).text(sign + currency.symbol + money(base * currency.rate));
        });
    };

    function escapeHtml(value) {
        return $('<div>').text(value == null ? '' : String(value)).html();
    }

    function productUrl(item) {
        if (!item.sku || !item.slug) return '#';
        return "{{ route('front-product.detail', ['product' => 'product', 'title' => 'SLUG.html', 'sku' => 'SKU']) }}"
            .replace('SLUG.html', encodeURIComponent(item.slug) + '.html')
            .replace('SKU', encodeURIComponent(item.sku));
    }

    function renderLocalCart() {
        const items = JSON.parse(localStorage.getItem('cartItems') || '[]');
        const $container = $('.localCartData');
        if (!$container.length) return;

        if (!items.length) {
            $container.empty();
            $('.cart-empty').show();
            return;
        }

        $('.cart-empty').hide();
        const html = items.map(function (item, index) {
            const url = productUrl(item);
            return `
                <article class="cart-item local-cart-item" data-random-id="${escapeHtml(item.randomId)}">
                    <div class="cart-item-product">
                        <a href="${url}"><img class="cart-item-image" src="${escapeHtml(item.image || '')}" alt="${escapeHtml(item.name || 'Product')}"></a>
                        <div>
                            <h2 class="cart-item-name"><a href="${url}">${escapeHtml(item.name || '')}</a></h2>
                            <p class="cart-item-price">₹${money(item.sellingPrice)}</p>
                            <p class="cart-item-sku">${escapeHtml(item.sku || '')}</p>
                            ${Object.entries(item.selectedVariants || {}).map(([name, value]) => `<p class="cart-item-variant">${escapeHtml(name)}: ${escapeHtml(value)}</p>`).join('')}
                    </div>
                        </div>
                        <div class="cart-quantity" aria-label="Quantity"
                            data-min-quantity="${Number(item.minQuantity) || 1}"
                            data-max-quantity="${Number(item.maxQuantity || item.stockQuantity) || 1}">
                            <button type="button" data-quantity-change="-1" aria-label="Decrease quantity">−</button>
                            <output aria-live="polite">${parseInt(item.quantity, 10) || 1}</output>
                            <button type="button" data-quantity-change="1" aria-label="Increase quantity">+</button>
                    </div>
                    <button class="cart-remove remove-cart-product" type="button"
                        data-randomid="${escapeHtml(item.randomId)}" data-index="${index}"
                        data-productid="${escapeHtml(item.productId || '')}" aria-label="Remove ${escapeHtml(item.name || 'product')}">&times;</button>
                </article>`;
        }).join('');
        $container.html(html);
    }

    function updateLocalQuantity(randomId, quantity) {
        const items = JSON.parse(localStorage.getItem('cartItems') || '[]');
        const item = items.find(row => String(row.randomId) === String(randomId));
        if (!item) return;
        const minimum = Math.max(Number(item.minQuantity) || 1, 1);
        const maximum = Math.max(Number(item.maxQuantity || item.stockQuantity) || 1, minimum);
        item.quantity = Math.min(maximum, Math.max(minimum, quantity));
        localStorage.setItem('cartItems', JSON.stringify(items));
        renderLocalCart();
        updateCartSummary();
    }

    function clearStoredCoupon() {
        localStorage.removeItem('coupon_id');
        localStorage.removeItem('coupon_discount');
        localStorage.removeItem('coupon_code');
    }

    function updateCouponOrderSummary(discount) {
        const amount = Math.max(0, Number(discount) || 0);
        const totals = window.cartSummaryBase || {};
        const subtotal = Number(totals.subtotal) || 0;
        const productDiscount = Number(totals.discount) || 0;
        const grandTotal = Math.max(0, subtotal - amount);
        const totalPayable = Math.max(0, (Number(totals.totalPayable) || subtotal) - amount);

        $('.couponDiscount .price-values')
            .attr('data-base-amount', amount)
            .attr('data-display-sign', '-');
        $('.grandTotal .price-values').attr('data-base-amount', grandTotal);
        $('.totalPayable .price-values').attr('data-base-amount', totalPayable);
        $('[data-checkout-subtotal]').text('₹' + money(subtotal));
        $('[data-checkout-discount]').text('-₹' + money(productDiscount + amount));
        $('[data-checkout-total]').text('₹' + money(totalPayable));
        $('[data-checkout-coupon-code]').text(localStorage.getItem('coupon_code') || '');
        $('[data-checkout-coupon-savings]').text('-₹' + money(amount));
        $('[data-checkout-coupon-applied]').prop('hidden', amount <= 0);
        $('[data-checkout-coupon]').prop('hidden', amount > 0);
        window.updateCartCountryPrices();
    }

    function clearCouponAfterCartChange() {
        window.couponApplyVersion++;
        clearStoredCoupon();
        $('#couponCode').val('');
        $('#checkoutCoupon').val('');
        $('.flate-off-l').empty();
        $('.cart-coupon-message, .checkout-coupon-message').empty();
        updateCouponOrderSummary(0);
    }

    function currentCartItems() {
        return window.isLoggedIn
            ? (window.dbCartItems || [])
            : JSON.parse(localStorage.getItem('cartItems') || '[]');
    }

    function cartSubtotal() {
        return currentCartItems().reduce((sum, item) => {
            return sum + (Number(String(item.sellingPrice || 0).replace(/,/g, '')) || 0) * (parseInt(item.quantity, 10) || 1);
        }, 0);
    }

    function itemTax(item, unitPrice) {
            let taxes = item.rawTaxArr || [];
            if (typeof taxes === 'string') {
                try {
                    taxes = JSON.parse($('<textarea>').html(taxes).text());
                } catch (error) {
                    taxes = [];
                }
            }
            if (!Array.isArray(taxes)) return { amount: 0, option: 'inclusive' };

            const tax = taxes.find(function (row) {
                if (row.tax_type !== 'floating') return row.tax_type === 'flat';
                return unitPrice >= Number(row.tax_from || 0) && unitPrice <= Number(row.tax_to || 0);
            });
            const rate = Math.max(Number(tax?.tax_rate || 0), 0);
            if (!tax || !rate) return { amount: 0, option: tax?.tax_option || 'inclusive' };
            const amount = tax.tax_option === 'inclusive'
                ? unitPrice - (unitPrice / (1 + rate / 100))
                : unitPrice * rate / 100;
            return { amount, option: tax.tax_option || 'inclusive' };
        }

    function updateCartSummary() {
            const items = currentCartItems();
            let mrp = 0;
            let selling = 0;
            let inclusiveTax = 0;
            let exclusiveTax = 0;

            items.forEach(function (item) {
                const quantity = Math.max(parseInt(item.quantity, 10) || 1, 1);
                const price = Math.max(Number(item.price) || 0, 0);
                const sellingPrice = Math.max(Number(item.sellingPrice) || 0, 0);
                const tax = itemTax(item, sellingPrice);
                mrp += price * quantity;
                selling += sellingPrice * quantity;
                if (tax.option === 'exclusive') {
                    exclusiveTax += tax.amount * quantity;
                } else {
                    inclusiveTax += tax.amount * quantity;
                }
            });

            const couponDiscount = window.isLoggedIn
                ? Math.max(Number(localStorage.getItem('coupon_discount')) || 0, 0)
                : 0;
            const subtotal = selling;
            const grandTotal = Math.max(0, subtotal - couponDiscount);
            const totalPayable = grandTotal + exclusiveTax;
            const totalTax = inclusiveTax + exclusiveTax;
            const taxable = Math.max(0, subtotal - inclusiveTax) + exclusiveTax;
            const values = {
                '.totalMRP .price-values': mrp,
                '.discount .price-values': mrp - selling,
                '.subtotal .price-values': subtotal,
                '.couponDiscount .price-values': couponDiscount,
                '.grandTotal .price-values': grandTotal,
                '.taxableAmount .price-values': taxable,
                '.totalGst .price-values': totalTax,
                '.totalPayable .price-values': totalPayable
            };
            Object.entries(values).forEach(function ([selector, value]) {
                $(selector).attr('data-base-amount', value);
            });

            window.cartSummaryBase = {
                subtotal: subtotal,
                discount: mrp - selling,
                grandTotal: subtotal,
                totalPayable: subtotal + exclusiveTax
            };
            const $checkoutItems = $('[data-checkout-items]').empty();
            items.forEach(function (item) {
                const quantity = Math.max(parseInt(item.quantity, 10) || 1, 1);
                const $row = $('<div>', { class: 'checkout-summary-item' });
                const $imageWrap = $('<span>', { class: 'checkout-summary-image-wrap' });
                $imageWrap.append($('<img>', {
                    class: 'checkout-summary-image',
                    src: item.image || "{{ asset('assets/img/placeholder.png') }}",
                    alt: item.name || 'Product'
                }));
                $imageWrap.append($('<span>', {
                    class: 'checkout-summary-quantity',
                    text: quantity
                }));
                $row.append($imageWrap);
                $row.append($('<span>', {
                    class: 'checkout-summary-name',
                    text: item.name || 'Product'
                }));
                $row.append($('<strong>', {
                    class: 'checkout-summary-price',
                    text: '₹' + money((Number(item.sellingPrice) || 0) * quantity)
                }));
                $checkoutItems.append($row);
            });
            $('[data-checkout-subtotal]').text('₹' + money(subtotal));
            $('[data-checkout-discount]').text('-₹' + money((mrp - selling) + couponDiscount));
            $('[data-checkout-total]').text('₹' + money(totalPayable));
            window.updateCartCountryPrices();
            $('.cart-checkout').prop('disabled', !items.length);
        }

    function showCartStep(step) {
            const names = { 1: 'Shopping cart', 2: 'Checkout details', 3: 'Order complete' };
            $('[data-cart-view]').prop('hidden', true);
            $('[data-cart-view="' + (step === 1 ? 'cart' : step === 2 ? 'checkout' : 'complete') + '"]').prop('hidden', false);
            $('.cart-step').each(function () {
                const current = Number($(this).attr('data-cart-step'));
                $(this).toggleClass('is-active', current === step)
                    .toggleClass('is-complete', current < step)
                    .attr('aria-current', current === step ? 'step' : null);
            });
            $('[data-cart-title]').text(names[step]);
        }

    function showCheckoutError(message) {
            $('[data-checkout-error]').text(message).prop('hidden', !message);
        }

    function completeOrder(response, paymentLabel) {
            const order = response.order || {};
            const cartItems = currentCartItems();
            const $products = $('[data-complete-products]').empty();
            cartItems.forEach(function (item) {
                const $product = $('<span>', { class: 'complete-product' });
                $product.append($('<img>', {
                    src: item.image || "{{ asset('assets/img/placeholder.png') }}",
                    alt: item.name || 'Product'
                }));
                $product.append($('<span>').text(parseInt(item.quantity, 10) || 1));
                $products.append($product);
            });
            $('[data-order-code]').text(order.order_number ? '#' + order.order_number : '');
            $('[data-order-date]').text(order.created_at || new Date().toLocaleDateString());
            $('[data-order-total]').text('₹' + money(order.total || $('[data-checkout-total]').text().replace(/[^\d.]/g, '')));
            $('[data-order-payment]').text(paymentLabel || 'Cash on Delivery');
            localStorage.removeItem('cartItems');
            clearStoredCoupon();
            window.dbCartItems = [];
            renderLocalCart();
            updateCartSummary();
            showCartStep(3);
    }

    function applyCoupon(code, restoring) {
        const requestVersion = ++window.couponApplyVersion;
        code = String(code || '').trim();

        if (!window.isLoggedIn) {
            window.location.href = window.cartRoutes.login;
            return;
        }
        if (!code) {
            $('.cart-coupon-message, .checkout-coupon-message').text('Please enter coupon code!');
            return;
        }
        if (!Array.isArray(window.dbCartItems) || !window.dbCartItems.length) {
            $('.cart-coupon-message, .checkout-coupon-message').text('Your cart is empty!');
            return;
        }

        $.ajax({
            url: window.cartRoutes.applyCoupon,
            type: 'POST',
            dataType: 'json',
            data: {
                coupon_code: code,
                cart_total: cartSubtotal(),
                cart_items: window.dbCartItems,
                _token: window.cartCsrf
            },
            success: function (response) {
                if (requestVersion !== window.couponApplyVersion) return;

                if (response.status) {
                    $('.cart-coupon-message, .checkout-coupon-message').text(response.message || 'Coupon applied successfully.');
                    const safeCode = escapeHtml(response.coupon.coupon_code);
                    $('.flate-off-l').html(
                        `<div class="checkout-coupon-applied"><strong>${safeCode}</strong><span>Save ₹${money(response.discount)} on this order</span></div>`
                    );
                    localStorage.setItem('coupon_id', response.coupon.id);
                    localStorage.setItem('coupon_discount', response.discount);
                    localStorage.setItem('coupon_code', response.coupon.coupon_code);
                    $('#couponCode, #checkoutCoupon').val(response.coupon.coupon_code);
                    $('[data-checkout-coupon-code]').text(response.coupon.coupon_code);
                    $('[data-checkout-coupon-savings]').text('-₹' + money(response.discount));
                    $('[data-checkout-coupon-applied]').prop('hidden', Number(response.discount) <= 0);
                    $('[data-checkout-coupon]').prop('hidden', Number(response.discount) > 0);
                    updateCouponOrderSummary(response.discount);
                } else {
                    if (restoring) {
                        clearStoredCoupon();
                        updateCouponOrderSummary(0);
                    }
                    $('.cart-coupon-message, .checkout-coupon-message').text(response.message || 'Coupon is not applicable.');
                }
            },
            error: function (xhr) {
                if (requestVersion !== window.couponApplyVersion) return;
                if (restoring) {
                    clearStoredCoupon();
                    updateCouponOrderSummary(0);
                }
                $('.cart-coupon-message, .checkout-coupon-message').text(xhr.responseJSON?.message || 'Unable to apply coupon. Please try again.');
            }
        });
    }

    $(document).on('submit', '#cartCouponForm', function (event) {
        event.preventDefault();
        applyCoupon($('#couponCode').val(), false);
    });

    $(document).on('submit', '[data-checkout-coupon]', function (event) {
        event.preventDefault();
        applyCoupon($('#checkoutCoupon').val(), false);
    });

    $(document).on('click', '[data-remove-coupon]', function () {
        clearCouponAfterCartChange();
        updateCartSummary();
    });

    $(document).on('change', 'input[name="billing-address"]', function () {
        $('[data-billing-fields]').prop('hidden', $(this).val() !== 'different');
    });

    $(document).on('click', '[data-show-address-form]', function () {
        $('[data-address-form]').prop('hidden', false);
        $(this).prop('hidden', true);
    });

    function populateAddressSelect($select, rows, placeholder) {
        $select.empty().append(new Option(placeholder, ''));
        rows.forEach(function (row) {
            $select.append(new Option(row.name, row.id));
        });
        $select.prop('disabled', rows.length === 0);
    }

    $(document).on('change', '#checkoutAddressCountry', function () {
        const countryId = $(this).val();
        const $state = $('#checkoutAddressState');
        const $city = $('#checkoutAddressCity');
        $('[data-address-error]').text('').prop('hidden', true);
        populateAddressSelect($state, [], 'Loading states...');
        populateAddressSelect($city, [], 'Select state first');
        if (!countryId) {
            $state.empty().append(new Option('Select country first', '')).prop('disabled', true);
            return;
        }

        $.getJSON(window.cartRoutes.addressStates, { countryId: countryId }).done(function (rows) {
            if ($('#checkoutAddressCountry').val() !== countryId) return;
            populateAddressSelect($state, rows, rows.length ? 'Select state' : 'No states available');
        }).fail(function () {
            if ($('#checkoutAddressCountry').val() !== countryId) return;
            $state.empty().append(new Option('Unable to load states', '')).prop('disabled', true);
            $('[data-address-error]').text('Unable to load states. Please try again.').prop('hidden', false);
        });
    });

    $(document).on('change', '#checkoutAddressState', function () {
        const stateId = $(this).val();
        const countryId = $('#checkoutAddressCountry').val();
        const $city = $('#checkoutAddressCity');
        $('[data-address-error]').text('').prop('hidden', true);
        populateAddressSelect($city, [], 'Loading cities...');
        if (!stateId || !countryId) {
            $city.empty().append(new Option('Select state first', '')).prop('disabled', true);
            return;
        }

        $.getJSON(window.cartRoutes.addressCities, {
            countryId: countryId,
            stateId: stateId
        }).done(function (rows) {
            if ($('#checkoutAddressCountry').val() !== countryId || $('#checkoutAddressState').val() !== stateId) return;
            populateAddressSelect($city, rows, rows.length ? 'Select city' : 'No cities available');
        }).fail(function () {
            if ($('#checkoutAddressCountry').val() !== countryId || $('#checkoutAddressState').val() !== stateId) return;
            $city.empty().append(new Option('Unable to load cities', '')).prop('disabled', true);
            $('[data-address-error]').text('Unable to load cities. Please try again.').prop('hidden', false);
        });
    });

    $(document).on('submit', '[data-address-form]', function (event) {
        event.preventDefault();
        const $form = $(this);
        const $button = $form.find('[data-save-address]');
        const $error = $('[data-address-error]');
        $error.text('').prop('hidden', true);
        $button.prop('disabled', true).text('Saving address...');

        $.ajax({
            url: window.cartRoutes.saveAddress,
            type: 'POST',
            dataType: 'json',
            data: $form.serialize(),
            success: function (response) {
                if (!response.success || !response.address) {
                    $error.text(response.message || 'Unable to save this address.').prop('hidden', false);
                    return;
                }

                const address = response.address;
                const shippingOption = new Option(address.label, address.id, true, true);
                $('#checkoutAddress').append(shippingOption).val(String(address.id));
                const $billing = $('#billingAddress');
                if ($billing.length) {
                    $billing.append(new Option(address.label, address.id)).val(String(address.id));
                }
                $('input[name="billing-address"][value="shipping"]').prop('checked', true).trigger('change');
                $form[0].reset();
                $form.prop('hidden', true);
                $('[data-show-address-form]').prop('hidden', false);
                showCheckoutError('');
            },
            error: function (xhr) {
                const errors = xhr.responseJSON?.errors || {};
                const firstError = Object.values(errors).flat()[0];
                $error.text(firstError || xhr.responseJSON?.message || 'Unable to save this address. Please check the form and try again.').prop('hidden', false);
            },
            complete: function () {
                $button.prop('disabled', false).text('Save address');
            }
        });
    });

    $(document).on('click', '.remove-cart-product', function () {
        const $button = $(this);

        if (window.isLoggedIn) {
            $.ajax({
                url: window.cartRoutes.removeCart,
                type: 'POST',
                data: { _token: window.cartCsrf, cartId: $button.data('cartid') },
                success: function (response) {
                    if (response.success) {
                        clearCouponAfterCartChange();
                        window.location.reload();
                    } else {
                        alert(response.message || 'Unable to remove this item.');
                    }
                },
                error: function () {
                    alert('Unable to remove this item. Please try again.');
                }
            });
            return;
        }

        const items = JSON.parse(localStorage.getItem('cartItems') || '[]');
        const index = Number($button.data('index'));
        if (Number.isInteger(index) && items[index]) {
            items.splice(index, 1);
            localStorage.setItem('cartItems', JSON.stringify(items));
        }
        clearCouponAfterCartChange();
        renderLocalCart();
        updateCartSummary();
    });

    $(document).on('click', '.localCartData [data-quantity-change]', function () {
        const $item = $(this).closest('.local-cart-item');
        const randomId = $item.data('random-id');
        const items = JSON.parse(localStorage.getItem('cartItems') || '[]');
        const item = items.find(row => String(row.randomId) === String(randomId));
        if (!item) return;

        const change = Number($(this).attr('data-quantity-change'));
        const minimum = Math.max(Number(item.minQuantity) || 1, 1);
        const maximum = Math.max(Number(item.maxQuantity || item.stockQuantity) || 1, minimum);
        const next = Math.min(maximum, Math.max(minimum, (parseInt(item.quantity, 10) || minimum) + change));
        updateLocalQuantity(randomId, next);
    });

    $(document).on('click', '.dbCartData [data-quantity-change]', function () {
        const $quantity = $(this).closest('.quantity');
        const current = parseInt($quantity.find('.cart-quantity-value').text(), 10) || 1;
        const min = parseInt($quantity.attr('data-min-quantity'), 10) || 1;
        const max = parseInt($quantity.attr('data-max-quantity'), 10) || 1;
        const next = current + Number($(this).attr('data-quantity-change'));
        if (next < min || next > max) return;

        $.ajax({
            url: window.cartRoutes.updateQuantity,
            type: 'POST',
            data: { _token: window.cartCsrf, cart_id: $quantity.data('cart-id'), quantity: next },
            success: function (response) {
                if (response.status) {
                    $quantity.find('.cart-quantity-value').text(response.quantity);
                    window.location.reload();
                } else {
                    alert(response.message || 'Unable to update quantity.');
                }
            },
            error: function (xhr) {
                alert(xhr.responseJSON?.message || 'Something went wrong.');
            }
        });
    });

    $(document).on('click', '.country-option', window.updateCartCountryPrices);

    $(document).on('click', '.cart-checkout', function () {
        const items = currentCartItems();
        if (!items.length) return;

        const invalidQuantity = items.find(function (item) {
            const quantity = Number(item.quantity);
            const min = Math.max(Number(item.minQuantity) || 1, 1);
            const max = Number(item.maxQuantity || item.stockQuantity) || 0;
            return !Number.isInteger(quantity) || quantity < min || quantity > max;
        });
        if (invalidQuantity) {
            alert('Please correct the quantity for ' + (invalidQuantity.name || 'a cart item') + '.');
            return;
        }

        if (!window.isLoggedIn) {
            $.ajax({
                url: window.cartRoutes.guestSync,
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: window.cartCsrf,
                    cart_items: items
                },
                success: function (response) {
                    if (response.success) {
                        window.location.href = window.cartRoutes.login;
                    } else {
                        alert(response.message || 'Unable to save your cart. Please try again.');
                    }
                },
                error: function (xhr) {
                    alert(xhr.responseJSON?.message || 'Unable to save your cart. Please try again.');
                }
            });
            return;
        }

        showCheckoutError('');
        showCartStep(2);
    });

    $(document).on('click', '[data-cart-next="1"]', function () {
        showCartStep(1);
    });

    $(document).on('click', '[data-place-order]', function () {
        const $button = $(this);
        const items = currentCartItems();
        const shippingAddressId = $('#checkoutAddress').val();
        const billingAddressId = $('input[name="billing-address"]:checked').val() === 'different'
            ? $('#billingAddress').val()
            : shippingAddressId;
        if (!items.length) {
            showCheckoutError('Your cart is empty.');
            return;
        }
        if (!shippingAddressId || !billingAddressId) {
            showCheckoutError('Add and select a delivery address before placing your order.');
            return;
        }

        $button.prop('disabled', true).text('Placing order...');
        showCheckoutError('');
        const cartItems = items.map(function (item) {
            return {
                ...item,
                product_id: item.productId || item.product_id,
                quantity: Number(item.quantity)
            };
        });

        $.ajax({
            url: window.cartRoutes.placeOrder,
            type: 'POST',
            dataType: 'json',
            data: {
                _token: window.cartCsrf,
                payment_mode: $('input[name="payment-method"]:checked').val() || 'cod',
                billing_id: billingAddressId,
                shipping_id: shippingAddressId,
                shippingcharge: 0,
                sub_total: Number($('[data-checkout-total]').text().replace(/[^\d.]/g, '')) || 0,
                coupon_id: localStorage.getItem('coupon_id'),
                coupon_discount: localStorage.getItem('coupon_discount') || 0,
                cartItems: cartItems
            },
            success: function (response) {
                if (!response.success) {
                    showCheckoutError(String(response.message || 'Unable to place your order.').replace(/<[^>]*>/g, ' '));
                    return;
                }
                completeOrder(response, 'Cash on Delivery');
            },
            error: function (xhr) {
                showCheckoutError(xhr.responseJSON?.message || 'Unable to place your order. Please try again.');
            },
            complete: function () {
                $button.prop('disabled', false).text('Place Order');
            }
        });
    });

    $(function () {
        renderLocalCart();
        if ($('#checkoutAddressCountry').val()) {
            $('#checkoutAddressCountry').trigger('change');
        }
        if (window.location.search.indexOf('cart_merged=1') !== -1 && window.isLoggedIn && window.dbCartItems.length) {
            localStorage.removeItem('cartItems');
        }
        updateCartSummary();
        window.updateCartCountryPrices();

        const savedCoupon = localStorage.getItem('coupon_code');
        if (window.isLoggedIn && savedCoupon) {
            $('#couponCode, #checkoutCoupon').val(savedCoupon);
            applyCoupon(savedCoupon, true);
        }

        const params = new URLSearchParams(window.location.search);
        if (window.isLoggedIn && params.get('step') === 'checkout') {
            showCartStep(2);
        }
    });
})(jQuery);
</script>
@endpush

@endsection
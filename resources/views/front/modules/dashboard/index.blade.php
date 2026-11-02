@extends('front.layout.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/account-design.css') }}?v={{ filemtime(public_path('assets/css/account-design.css')) }}">
@endpush

@section('content')
<main class="account-page page-container"
    data-account-page
    data-states-url="{{ route('address.states') }}"
    data-cities-url="{{ route('address.cities') }}">
    <nav class="account-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('front-home.index') }}"><i class="bi bi-house-door"></i> Home</a>
        <span class="account-breadcrumb-sep">/</span>
        <span class="account-breadcrumb-current">My Account</span>
    </nav>

    <div class="account-header-bar">
        <div>
            <h1 class="account-page-title">My Account</h1>
            <p class="account-page-desc">Manage your profile, addresses, orders, and wishlist.</p>
        </div>
        <div class="account-header-badge-card">
            <div class="account-badge-avatar"><i class="bi bi-gem"></i></div>
            <div class="account-badge-text">
                <span class="account-badge-title">S4K Customer</span>
                <span class="account-badge-sub">Member since {{ optional($user->created_at)->format('M Y') }}</span>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success" role="status">{{ session('success') }}</div>
    @endif
    @if (session('error') || session('flash_notice'))
        <div class="alert alert-danger" role="alert">{{ session('error') ?? session('flash_notice') }}</div>
    @endif

    <div class="account-layout">
        <aside class="account-sidebar" aria-label="Account navigation">
            <div class="account-profile">
                <div class="account-avatar-wrapper">
                    <div class="account-avatar-img account-avatar-initials" aria-hidden="true">
                        {{ mb_strtoupper(mb_substr($user->name ?: 'S', 0, 1)) }}
                    </div>
                </div>
                <h2 class="account-user-name">{{ $user->name }}</h2>
                <p class="account-user-email">{{ $user->email }}</p>
                <div class="account-tier-badge"><i class="bi bi-patch-check-fill"></i> Customer account</div>
            </div>

            <nav class="account-tabs" role="tablist" aria-label="Account sections" aria-orientation="vertical">
                <a class="account-tab is-active" id="account-tab-account" href="#account" data-page-title="My Account"
                    role="tab" aria-selected="true" aria-controls="account-panel">
                    <span class="account-tab-icon"><i class="bi bi-person-circle"></i></span>
                    <span class="account-tab-text">Account Details</span>
                    <i class="bi bi-chevron-right account-tab-arrow"></i>
                </a>
                <a class="account-tab" id="account-tab-address" href="#address" data-page-title="My Address"
                    role="tab" aria-selected="false" aria-controls="address-panel" tabindex="-1">
                    <span class="account-tab-icon"><i class="bi bi-geo-alt"></i></span>
                    <span class="account-tab-text">Address Book</span>
                    <span class="account-tab-badge">{{ $userAddressDetails->count() }}</span>
                    <i class="bi bi-chevron-right account-tab-arrow"></i>
                </a>
                <a class="account-tab" id="account-tab-orders" href="#orders" data-page-title="Order History"
                    role="tab" aria-selected="false" aria-controls="orders-panel" tabindex="-1">
                    <span class="account-tab-icon"><i class="bi bi-box-seam"></i></span>
                    <span class="account-tab-text">Order History</span>
                    <span class="account-tab-badge">{{ $orderDetails->count() }}</span>
                    <i class="bi bi-chevron-right account-tab-arrow"></i>
                </a>
                <a class="account-tab" id="account-tab-wishlist" href="#wishlist" data-page-title="My Wishlist"
                    role="tab" aria-selected="false" aria-controls="wishlist-panel" tabindex="-1">
                    <span class="account-tab-icon"><i class="bi bi-heart"></i></span>
                    <span class="account-tab-text">My Wishlist</span>
                    <span class="account-tab-badge">{{ $wishlistData->count() }}</span>
                    <i class="bi bi-chevron-right account-tab-arrow"></i>
                </a>
                <a class="account-tab account-logout" href="{{ route('front-user.logout') }}">
                    <span class="account-tab-icon"><i class="bi bi-box-arrow-right"></i></span>
                    <span class="account-tab-text">Log Out</span>
                </a>
            </nav>

            <div class="account-support-box">
                <div class="account-support-icon"><i class="bi bi-headset"></i></div>
                <div class="account-support-text">
                    <strong>Need Help?</strong>
                    <p>Contact our customer support team.</p>
                    <a href="tel:+917728090144" class="account-support-link">+91 7728090144</a>
                </div>
            </div>
        </aside>

        <div class="account-panels">
            <section class="account-panel" id="account-panel" role="tabpanel" aria-labelledby="account-tab-account">
                <div class="account-panel-header">
                    <div>
                        <h2 class="account-panel-title">Account Details</h2>
                        <p class="account-panel-subtitle">Update your contact details and manage account security.</p>
                    </div>
                    <div class="account-member-since">
                        <i class="bi bi-calendar-check"></i>
                        Member since {{ optional($user->created_at)->format('M d, Y') }}
                    </div>
                </div>

                <div class="account-overview-stats">
                    <div class="account-stat-card">
                        <div class="account-stat-icon-wrap"><i class="bi bi-box-seam"></i></div>
                        <div class="account-stat-data">
                            <span class="account-stat-val">{{ $orderDetails->count() }}</span>
                            <span class="account-stat-lbl">Total Orders</span>
                        </div>
                    </div>
                    <div class="account-stat-card">
                        <div class="account-stat-icon-wrap"><i class="bi bi-heart"></i></div>
                        <div class="account-stat-data">
                            <span class="account-stat-val">{{ $wishlistData->count() }}</span>
                            <span class="account-stat-lbl">Wishlist Items</span>
                        </div>
                    </div>
                    <div class="account-stat-card">
                        <div class="account-stat-icon-wrap"><i class="bi bi-geo-alt"></i></div>
                        <div class="account-stat-data">
                            <span class="account-stat-val">{{ $userAddressDetails->count() }}</span>
                            <span class="account-stat-lbl">Saved Addresses</span>
                        </div>
                    </div>
                    <div class="account-stat-card highlight">
                        <div class="account-stat-icon-wrap"><i class="bi bi-receipt"></i></div>
                        <div class="account-stat-data">
                            <span class="account-stat-val">₹{{ number_format((float) $orderDetails->sum('total'), 0) }}</span>
                            <span class="account-stat-lbl">Lifetime Spend</span>
                        </div>
                    </div>
                </div>

                <form class="account-form" method="POST" action="{{ route('front-user.updateProfile') }}">
                    @csrf
                    <div class="account-form-section">
                        <div class="account-form-section-title"><i class="bi bi-person-vcard"></i> Personal Information</div>
                        <p class="account-form-section-desc">Keep your contact details up to date for orders and correspondence.</p>
                        <div class="account-form-grid">
                            <div class="account-field account-field-full">
                                <label for="accountName">Full name <span>*</span></label>
                                <div class="account-input-wrap">
                                    <i class="bi bi-person account-input-icon"></i>
                                    <input id="accountName" name="name" type="text" value="{{ old('name', $user->name) }}"
                                        autocomplete="name" required maxlength="255">
                                </div>
                                @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="account-field">
                                <label for="accountEmail">Email address <span>*</span></label>
                                <div class="account-input-wrap">
                                    <i class="bi bi-envelope account-input-icon"></i>
                                    <input id="accountEmail" name="email" type="email" value="{{ old('email', $user->email) }}"
                                        autocomplete="email" required maxlength="255">
                                </div>
                                @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="account-field">
                                <label for="accountPhone">Phone number <span>*</span></label>
                                <div class="account-input-wrap">
                                    <i class="bi bi-telephone account-input-icon"></i>
                                    <input id="accountPhone" name="phone_number" type="tel"
                                        value="{{ old('phone_number', $user->phone_number) }}"
                                        autocomplete="tel" inputmode="numeric" pattern="[0-9]{10}" maxlength="10" required>
                                </div>
                                @error('phone_number')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="account-form-actions">
                        <button class="account-primary-button" type="submit"><i class="bi bi-check2"></i> Save changes</button>
                    </div>
                </form>

                <form class="account-form account-password-form" method="POST"
                    action="{{ route('front-user.changePassword') }}" data-password-form>
                    @csrf
                    <div class="account-form-section">
                        <div class="account-form-section-title"><i class="bi bi-shield-lock"></i> Security &amp; Password</div>
                        <p class="account-form-section-desc">Enter your current password to set a new one.</p>
                        <div class="account-form-grid">
                            <div class="account-field account-field-full">
                                <label for="currentPassword">Current password</label>
                                <div class="account-input-wrap">
                                    <i class="bi bi-key account-input-icon"></i>
                                    <input id="currentPassword" name="current_password" type="password" autocomplete="current-password">
                                </div>
                            </div>
                            <div class="account-field">
                                <label for="newPassword">New password</label>
                                <div class="account-input-wrap">
                                    <i class="bi bi-lock account-input-icon"></i>
                                    <input id="newPassword" name="new_password" type="password" autocomplete="new-password" minlength="8">
                                </div>
                            </div>
                            <div class="account-field">
                                <label for="repeatPassword">Confirm new password</label>
                                <div class="account-input-wrap">
                                    <i class="bi bi-lock-fill account-input-icon"></i>
                                    <input id="repeatPassword" name="confirm_password" type="password" autocomplete="new-password" minlength="8">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="account-form-actions">
                        <button class="account-primary-button" type="submit"><i class="bi bi-key"></i> Update password</button>
                        <p class="account-form-message" data-password-message role="status" aria-live="polite"></p>
                    </div>
                </form>
            </section>

            <section class="account-panel" id="address-panel" role="tabpanel" aria-labelledby="account-tab-address" hidden>
                <div class="account-panel-header">
                    <div>
                        <h2 class="account-panel-title">Address Book</h2>
                        <p class="account-panel-subtitle">Manage the addresses used for your billing and deliveries.</p>
                    </div>
                    <button class="account-add-address-btn" type="button" data-toggle-address-form>
                        <i class="bi bi-plus-lg"></i> Add New Address
                    </button>
                </div>

                <div class="account-address-grid">
                    @forelse ($userAddressDetails as $address)
                        <article class="account-address-card {{ $address->is_primary ? 'is-default' : '' }}">
                            <div class="account-address-card-header">
                                <div class="account-address-type">
                                    <div class="account-address-type-icon"><i class="bi bi-geo-alt"></i></div>
                                    <div>
                                        <h3>{{ (int) $address->address_type === 2 ? 'Office' : ((int) $address->address_type === 3 ? 'Other' : 'Home') }} Address</h3>
                                        @if ($address->is_primary)
                                            <span class="account-address-tag default-tag"><i class="bi bi-check2"></i> Default</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="account-address-card-actions">
                                    <button class="account-edit-button" type="button" data-toggle-edit-address="{{ $address->id }}">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <a class="account-edit-button account-address-delete"
                                        href="{{ route('front-user.deleteAddress', ['addressId' => $address->id]) }}"
                                        onclick="return confirm('Delete this saved address?')"
                                        aria-label="Delete saved address"><i class="bi bi-trash"></i></a>
                                </div>
                            </div>
                            <div class="account-address-body">
                                <h4 class="account-address-name">{{ $address->name }}</h4>
                                <div class="account-address-line"><i class="bi bi-telephone"></i><span>{{ $address->phone_number }}</span></div>
                                <div class="account-address-line">
                                    <i class="bi bi-geo-alt"></i>
                                    <span>{{ $address->address }}@if($address->landmark), {{ $address->landmark }}@endif,
                                        {{ $address->city->name ?? '' }}, {{ $address->state->name ?? '' }},
                                        {{ $address->postal_code }}, {{ $address->country->name ?? '' }}</span>
                                </div>
                            </div>
                            <div class="account-address-footer">
                                @if ($address->is_primary)
                                    <span class="account-address-status-pill"><i class="bi bi-shield-check"></i> Primary address</span>
                                @else
                                    <a class="account-address-status-pill" href="{{ route('front-user.makeAddressPrimary', ['addressId' => $address->id]) }}">Set as default</a>
                                @endif
                            </div>
                            <div id="edit-address-{{ $address->id }}" class="account-address-edit-form" hidden>
                                @include('front.modules.dashboard.partials.address-form', [
                                    'address' => $address,
                                    'countries' => $countries,
                                    'formId' => 'edit-address-form-' . $address->id,
                                    'action' => route('front-user.update_user_address'),
                                ])
                            </div>
                        </article>
                    @empty
                        <div class="account-wishlist-empty">
                            <div class="account-empty-icon"><i class="bi bi-geo-alt"></i></div>
                            <h3>No saved addresses yet</h3>
                            <p>Add an address to make checkout faster.</p>
                        </div>
                    @endforelse
                </div>

                <div class="account-address-new-form" id="new-address-form" hidden>
                    <h3 class="account-panel-title">Add a new address</h3>
                    @include('front.modules.dashboard.partials.address-form', [
                        'address' => null,
                        'countries' => $countries,
                        'formId' => 'add-address-form',
                        'action' => route('front-user.save_address'),
                    ])
                </div>
            </section>

            <section class="account-panel" id="orders-panel" role="tabpanel" aria-labelledby="account-tab-orders" hidden>
                <div class="account-panel-header">
                    <div>
                        <h2 class="account-panel-title">Order History</h2>
                        <p class="account-panel-subtitle">Review your orders, status, and purchased items.</p>
                    </div>
                    <div class="account-orders-filter" role="group" aria-label="Filter orders">
                        <button type="button" class="account-filter-pill active" data-order-filter="all">All ({{ $orderDetails->count() }})</button>
                        <button type="button" class="account-filter-pill" data-order-filter="delivered">Delivered</button>
                        <button type="button" class="account-filter-pill" data-order-filter="active">In progress</button>
                        <button type="button" class="account-filter-pill" data-order-filter="cancelled">Cancelled</button>
                    </div>
                </div>

                <div class="account-table-wrap">
                    <table class="account-orders-table">
                        <thead>
                            <tr><th>Order</th><th>Date</th><th>Items</th><th>Status</th><th>Total</th><th>Details</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($orderDetails as $order)
                                @php
                                    $orderStatus = strtolower((string) ($order->status ?: 'pending'));
                                    $statusFilter = str_contains($orderStatus, 'deliver') ? 'delivered'
                                        : (str_contains($orderStatus, 'cancel') ? 'cancelled' : 'active');
                                @endphp
                                <tr data-order-row data-order-status="{{ $statusFilter }}">
                                    <td>
                                        <div class="account-order-id">
                                            <strong>#{{ $order->order_number ?: $order->id }}</strong>
                                            <small>{{ strtoupper(str_replace('_', ' ', $order->payment_method ?? '')) }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="account-order-date"><i class="bi bi-calendar3"></i>
                                            <span>{{ optional($order->created_at)->format('F d, Y') }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="account-order-thumbs">
                                            @foreach ($order->items->take(3) as $item)
                                                <img src="{{ $item->image_url }}" alt="{{ $item->product_name }}" class="order-thumb">
                                            @endforeach
                                            @if ($order->items->count() > 3)
                                                <span class="order-thumb-count">+{{ $order->items->count() - 3 }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="account-status-badge status-{{ $statusFilter }}">
                                            <i class="bi {{ $statusFilter === 'delivered' ? 'bi-check-circle-fill' : ($statusFilter === 'cancelled' ? 'bi-x-circle-fill' : 'bi-clock-fill') }}"></i>
                                            {{ ucfirst(str_replace(['_', '-'], ' ', $order->status ?: 'pending')) }}
                                        </span>
                                    </td>
                                    <td><strong class="account-order-price">{{ $order->currency_code ?: 'INR' }} {{ number_format((float) $order->total, 2) }}</strong></td>
                                    <td><button class="account-order-action-btn" type="button" data-toggle-order-details="{{ $order->id }}" aria-expanded="false">Details <i class="bi bi-chevron-right"></i></button></td>
                                </tr>
                                <tr class="account-order-details-row" id="order-details-{{ $order->id }}" hidden>
                                    <td colspan="6">
                                        <div class="account-order-details">
                                            <h3>Items in this order</h3>
                                            @forelse ($order->items as $item)
                                                <div class="account-order-detail-item">
                                                    <img src="{{ $item->image_url }}" alt="{{ $item->product_name }}">
                                                    <span>{{ $item->product_name }}</span>
                                                    <span>Qty: {{ $item->qty }}</span>
                                                    <strong>{{ $order->currency_code ?: 'INR' }} {{ number_format((float) $item->total, 2) }}</strong>
                                                </div>
                                            @empty
                                                <p>No item details are available for this order.</p>
                                            @endforelse
                                            @if ($order->shipping_address)
                                                @php
                                                    $shippingAddress = json_decode($order->shipping_address, true) ?: [];
                                                @endphp
                                                <p class="account-order-shipping">
                                                    <strong>Delivery address:</strong>
                                                    {{ collect($shippingAddress)->filter()->values()->implode(', ') }}
                                                </p>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center">You have not placed any orders yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="account-panel" id="wishlist-panel" role="tabpanel" aria-labelledby="account-tab-wishlist" hidden>
                <div class="account-panel-header">
                    <div>
                        <h2 class="account-panel-title">Your Wishlist</h2>
                        <p class="account-panel-subtitle">Products you have saved to revisit later.</p>
                    </div>
                    <a href="{{ route('front-shop.index') }}" class="account-continue-shopping-btn">
                        <i class="bi bi-bag"></i> Continue Shopping
                    </a>
                </div>

                <div class="account-wishlist-table-wrap">
                    <table class="account-wishlist-table" data-wishlist-table @if($wishlistData->isEmpty()) hidden @endif>
                        <thead><tr><th aria-label="Remove item" class="account-wishlist-th-remove"></th><th>Product</th><th>Price</th><th>Status</th><th class="account-wishlist-th-action">Action</th></tr></thead>
                        <tbody>
                            @foreach ($wishlistData as $wishlistItem)
                                @php
                                    $product = $wishlistItem->getProduct;
                                @endphp
                                @if ($product)
                                    @php
                                        $productUrl = $product->sku
                                            ? route('front-product.detail', ['product' => 'product', 'title' => $product->slug . '.html', 'sku' => $product->sku])
                                            : route('front-shop.index');
                                        $productImage = data_get($product->images, 'first') ?: config('constant.IMAGE_URL') . 'noimage.png';
                                    @endphp
                                    <tr data-wishlist-row="{{ $wishlistItem->product_id }}">
                                        <td>
                                            <button class="account-remove-item" type="button" data-remove-wishlist="{{ $wishlistItem->product_id }}"
                                                aria-label="Remove {{ $product->name }} from wishlist" title="Remove from wishlist">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <div class="account-product">
                                                <a href="{{ $productUrl }}" class="account-product-img-link"><img src="{{ $productImage }}" alt="{{ $product->name }}"></a>
                                                <div class="account-product-meta">
                                                    <a href="{{ $productUrl }}" class="account-product-name">{{ $product->name }}</a>
                                                    @if ($product->sku)<span class="account-product-attr">SKU: {{ $product->sku }}</span>@endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="account-price-wrap">
                                                <span class="account-price-current">₹{{ number_format((float) $product->selling_price, 2) }}</span>
                                                @if ($product->price && $product->price > $product->selling_price)
                                                    <span class="account-price-old">₹{{ number_format((float) $product->price, 2) }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="account-stock-tag {{ $product->qty > 0 ? 'in-stock' : 'out-of-stock' }}">
                                                <i class="bi {{ $product->qty > 0 ? 'bi-check2' : 'bi-x' }}"></i>
                                                {{ $product->qty > 0 ? 'In Stock' : 'Out of Stock' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ((int) $product->product_type === 1)
                                                <button class="account-cart-button" type="button" data-add-wishlist-cart
                                                    data-product-id="{{ $product->id }}" data-product-sku="{{ $product->sku }}"
                                                    data-quantity="{{ max((int) ($product->min_selling_units ?? 1), 1) }}">
                                                    <i class="bi bi-cart-plus"></i> Add to cart
                                                </button>
                                            @else
                                                <a href="{{ $productUrl }}" class="account-cart-button">Choose options</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="account-wishlist-empty" data-wishlist-empty role="status" @if($wishlistData->isNotEmpty()) hidden @endif>
                    <div class="account-empty-icon"><i class="bi bi-heart"></i></div>
                    <h3>Your wishlist is empty</h3>
                    <p>Explore the collection and save your favorite products for later.</p>
                    <a href="{{ route('front-shop.index') }}" class="account-primary-button">Explore Products</a>
                </div>
                <p class="account-form-message" data-wishlist-message role="status" aria-live="polite"></p>
            </section>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
(() => {
    const page = document.querySelector('[data-account-page]');
    if (!page) return;

    const tabs = Array.from(page.querySelectorAll('[role="tab"]'));
    const panels = Array.from(page.querySelectorAll('[role="tabpanel"]'));
    const title = page.querySelector('.account-page-title');

    function activateTab(tab, updateHash) {
        const panelId = tab.getAttribute('aria-controls');
        tabs.forEach((item) => {
            const active = item === tab;
            item.classList.toggle('is-active', active);
            item.setAttribute('aria-selected', String(active));
            item.tabIndex = active ? 0 : -1;
        });
        panels.forEach((panel) => { panel.hidden = panel.id !== panelId; });
        title.textContent = tab.dataset.pageTitle;
        if (updateHash) history.replaceState(null, '', tab.getAttribute('href'));
    }

    const selectedTab = tabs.find((tab) => tab.getAttribute('href') === location.hash) || tabs[0];
    activateTab(selectedTab, false);
    tabs.forEach((tab, index) => {
        tab.addEventListener('click', (event) => {
            event.preventDefault();
            activateTab(tab, true);
        });
        tab.addEventListener('keydown', (event) => {
            let nextIndex = index;
            if (event.key === 'ArrowDown' || event.key === 'ArrowRight') nextIndex = (index + 1) % tabs.length;
            else if (event.key === 'ArrowUp' || event.key === 'ArrowLeft') nextIndex = (index - 1 + tabs.length) % tabs.length;
            else if (event.key === 'Home') nextIndex = 0;
            else if (event.key === 'End') nextIndex = tabs.length - 1;
            else return;
            event.preventDefault();
            tabs[nextIndex].focus();
            activateTab(tabs[nextIndex], true);
        });
    });

    page.querySelector('[data-toggle-address-form]')?.addEventListener('click', () => {
        const form = page.querySelector('#new-address-form');
        form.hidden = !form.hidden;
        if (!form.hidden) form.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });
    page.querySelectorAll('[data-toggle-edit-address]').forEach((button) => {
        button.addEventListener('click', () => {
            const form = page.querySelector(`#edit-address-${button.dataset.toggleEditAddress}`);
            form.hidden = !form.hidden;
            if (!form.hidden) form.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });
    });
    page.querySelectorAll('[data-cancel-address]').forEach((button) => {
        button.addEventListener('click', () => {
            const form = button.closest('[data-address-form]');
            const container = form.closest('.account-address-edit-form, .account-address-new-form');
            container.hidden = true;
        });
    });

    async function loadOptions(select, url, params, placeholder, selectedValue) {
        select.replaceChildren(new Option(placeholder, ''));
        if (!params || Object.values(params).some((value) => !value)) return;
        const query = new URLSearchParams(params);
        const response = await fetch(`${url}?${query.toString()}`, { headers: { Accept: 'application/json' } });
        if (!response.ok) throw new Error(`Could not load ${placeholder.toLowerCase()}.`);
        const result = await response.json();
        const options = Array.isArray(result) ? result : (result.data || result.states || result.cities || []);
        options.forEach((option) => {
            const value = option.id ?? option.value;
            const label = option.name ?? option.text;
            if (value !== undefined && label !== undefined) select.add(new Option(label, value, false, String(value) === String(selectedValue || '')));
        });
    }

    page.querySelectorAll('[data-address-form]').forEach((form) => {
        const country = form.elements.country_id;
        const state = form.elements.state_id;
        const city = form.elements.city_id;
        const stateUrl = page.dataset.statesUrl;
        const cityUrl = page.dataset.citiesUrl;
        const initialState = state.dataset.selected || '';
        const initialCity = city.dataset.selected || '';

        country.addEventListener('change', async () => {
            try {
                await loadOptions(state, stateUrl, { countryId: country.value }, 'Select state', '');
                await loadOptions(city, cityUrl, null, 'Select city', '');
            } catch (error) {
                form.querySelector('[data-address-message]').textContent = error.message;
            }
        });
        state.addEventListener('change', async () => {
            try {
                await loadOptions(city, cityUrl, { countryId: country.value, stateId: state.value }, 'Select city', '');
            } catch (error) {
                form.querySelector('[data-address-message]').textContent = error.message;
            }
        });
        if (country.value) {
            loadOptions(state, stateUrl, { countryId: country.value }, 'Select state', initialState)
                .then(() => loadOptions(city, cityUrl, { countryId: country.value, stateId: initialState }, 'Select city', initialCity))
                .catch((error) => { form.querySelector('[data-address-message]').textContent = error.message; });
        }

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const message = form.querySelector('[data-address-message]');
            const submit = form.querySelector('[type="submit"]');
            submit.disabled = true;
            message.textContent = '';
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': page.querySelector('input[name="_token"]').value
                    },
                    body: new FormData(form)
                });
                const result = await response.json();
                if (!response.ok || !result.success) {
                    const validationMessage = result.errors ? Object.values(result.errors).flat().join(' ') : '';
                    throw new Error(validationMessage || result.message || 'The address could not be saved.');
                }
                location.hash = 'address';
                location.reload();
            } catch (error) {
                message.textContent = error.message;
                submit.disabled = false;
            }
        });
    });

    page.querySelectorAll('[data-order-filter]').forEach((button) => {
        button.addEventListener('click', () => {
            page.querySelectorAll('[data-order-filter]').forEach((filter) => filter.classList.toggle('active', filter === button));
            const filter = button.dataset.orderFilter;
            page.querySelectorAll('[data-order-row]').forEach((row) => {
                const visible = filter === 'all' || row.dataset.orderStatus === filter;
                row.hidden = !visible;
                const details = page.querySelector(`#order-details-${row.querySelector('[data-toggle-order-details]')?.dataset.toggleOrderDetails}`);
                if (details) details.hidden = true;
            });
        });
    });
    page.querySelectorAll('[data-toggle-order-details]').forEach((button) => {
        button.addEventListener('click', () => {
            const details = page.querySelector(`#order-details-${button.dataset.toggleOrderDetails}`);
            details.hidden = !details.hidden;
            button.setAttribute('aria-expanded', String(!details.hidden));
        });
    });

    page.querySelector('[data-password-form]')?.addEventListener('submit', async (event) => {
        event.preventDefault();
        const form = event.currentTarget;
        const message = page.querySelector('[data-password-message]');
        const data = new FormData(form);
        if (data.get('new_password') !== data.get('confirm_password')) {
            message.textContent = 'The new password and confirmation do not match.';
            return;
        }
        message.textContent = '';
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: { Accept: 'application/json', 'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value },
                body: data
            });
            const result = await response.json();
            if (!response.ok || result.status !== 'success') {
                const validationMessage = result.errors ? Object.values(result.errors).flat().join(' ') : '';
                throw new Error(validationMessage || result.msg || 'The password could not be updated.');
            }
            message.textContent = result.msg;
            form.reset();
        } catch (error) {
            message.textContent = error.message;
        }
    });

    page.querySelector('[data-wishlist-table]')?.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-remove-wishlist]');
        const addButton = event.target.closest('[data-add-wishlist-cart]');
        const message = page.querySelector('[data-wishlist-message]');
        const csrf = page.querySelector('input[name="_token"]')?.value || @json(csrf_token());
        message.textContent = '';

        if (button) {
            button.disabled = true;
            try {
                const response = await fetch(@json(route('front-addwish')), {
                    method: 'POST',
                    headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: JSON.stringify({ product_id: button.dataset.removeWishlist })
                });
                const result = await response.json();
                if (!response.ok || result.status !== 'removed') throw new Error(result.message || 'The wishlist item could not be removed.');
                button.closest('tr').remove();
                const table = page.querySelector('[data-wishlist-table]');
                const empty = page.querySelector('[data-wishlist-empty]');
                const remaining = table.tBodies[0].rows.length;
                table.hidden = remaining === 0;
                empty.hidden = remaining !== 0;
                page.querySelector('#account-tab-wishlist .account-tab-badge').textContent = String(remaining);
                page.querySelector('.account-stat-card:nth-child(2) .account-stat-val').textContent = String(remaining);
                message.textContent = result.message;
            } catch (error) {
                message.textContent = error.message;
                button.disabled = false;
            }
            return;
        }

        if (addButton) {
            addButton.disabled = true;
            try {
                const response = await fetch(@json(route('user.addToCart')), {
                    method: 'POST',
                    headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: JSON.stringify({
                        product_id: addButton.dataset.productId,
                        product_sku: addButton.dataset.productSku,
                        quantity: Number(addButton.dataset.quantity) || 1,
                        addType: 'add'
                    })
                });
                const result = await response.json();
                if (!response.ok || !result.success) throw new Error(result.message || 'The product could not be added to your cart.');
                message.textContent = result.message || 'Product added to your cart.';
                addButton.disabled = false;
            } catch (error) {
                message.textContent = error.message;
                addButton.disabled = false;
            }
        }
    });
})();
</script>
@endpush


<header class="site-header">
    <nav class="navbar navbar-expand-xl container-fluid page-container" aria-label="Main navigation">

        {{-- Logo --}}
        <a class="navbar-brand brand-mark" href="{{ url('/') }}" aria-label="S4K home">
            <img src="{{ asset('assets/img/logo.svg') }}" alt="S4K">
        </a>

        {{-- Mobile Toggle --}}
        <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavigation"
            aria-controls="mainNavigation"
            aria-expanded="false"
            aria-label="Toggle navigation">

            <svg class="svg-icon navbar-menu-icon" aria-hidden="true" viewBox="0 0 24 24">
                <path d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <div class="collapse navbar-collapse" id="mainNavigation">

            {{-- Navigation Menu --}}
   

<ul class="navbar-nav">

    {{-- HOME --}}
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/') }}">
            Home
        </a>
    </li>


    {{-- DYNAMIC PARENT CATEGORIES --}}
    @foreach($categories->whereNull('parent_id')->where('category_type_id', 2)->where('show_on_menu', 1) as $cat)

        <li class="nav-item dropdown">

            {{-- PARENT CATEGORY --}}
            <button class="nav-link dropdown-toggle"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">

                {{ $cat->name }}

            </button>


            {{-- CHILD CATEGORY DROPDOWN --}}
            <ul class="dropdown-menu">

          


                {{-- CHILD CATEGORIES --}}
                @foreach($subCategories as $sub)

                    @if($sub->parent_id == $cat->id)

                        <li class="dropdown-submenu">

                            {{-- CHILD CATEGORY LINK --}}
                            <a class="dropdown-item"
                               href="{{ route('category.show', [
                                   'path' => $cat->slug.'/'.$sub->slug
                               ]) }}">

                                <span>{{ $sub->name }}</span>

                                {{-- Show arrow only if sub-child exists --}}
                                @if($childCategories->where('parent_id', $sub->id)->isNotEmpty())
                                    <span class="submenu-arrow" aria-hidden="true"></span>
                                @endif

                            </a>


                            {{-- SUB-CHILD CATEGORY DROPDOWN --}}
                            @if($childCategories->where('parent_id', $sub->id)->isNotEmpty())

                                <ul class="dropdown-menu dropdown-submenu-menu">

                             


                                    {{-- SUB-CHILD CATEGORIES --}}
                                    @foreach($childCategories as $child)

                                        @if($child->parent_id == $sub->id)

                                            <li>
                                                <a class="dropdown-item"
                                                   href="{{ route('category.show', [
                                                       'path' => $cat->slug.'/'.$sub->slug.'/'.$child->slug
                                                   ]) }}">

                                                    {{ $child->name }}

                                                </a>
                                            </li>

                                        @endif

                                    @endforeach

                                </ul>

                            @endif

                        </li>

                    @endif

                @endforeach

            </ul>

        </li>

    @endforeach

</ul>

            {{-- Search Form --}}
            <form class="search-form" role="search" action="{{ url('/search') }}" method="GET">

                <label class="visually-hidden" for="siteSearch">
                    Search the collection
                </label>

                <input
                    id="siteSearch"
                    type="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search products..."
                    autocomplete="off">

                <button type="submit" aria-label="Search">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="10.8" cy="10.8" r="6.5"></circle>
                        <path d="m16 16 4 4"></path>
                    </svg>
                </button>

            </form>

            {{-- Header Actions --}}
            <div class="header-actions">
                @php
                    $customer = Auth::guard('customer')->user();
                @endphp

                {{-- Customer Profile --}}
                @if($customer)
                    <a class="icon-button header-account-link"
                        href="{{ route('user.dashboard') }}"
                        aria-label="My account for {{ $customer->name }}">

                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="8" r="3.5"></circle>
                            <path d="M5 20a7 7 0 0 1 14 0"></path>
                        </svg>
                        <span class="header-account-label">{{ $customer->name ?: 'My Account' }}</span>
                    </a>
                @else
                    <a class="icon-button header-account-link"
                        href="{{ route('front-user.login') }}"
                        aria-label="Login">

                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="8" r="3.5"></circle>
                            <path d="M5 20a7 7 0 0 1 14 0"></path>
                        </svg>
                        <span class="header-account-label">Login</span>
                    </a>
                @endif

                {{-- Wishlist --}}
                <a class="icon-button wishlist_button"
                    href="#"
                    aria-label="Wishlist">

                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M20.8 8.8c0 5.2-8.8 11-8.8 11s-8.8-5.8-8.8-11A4.8 4.8 0 0 1 12 6.1a4.8 4.8 0 0 1 8.8 2.7Z">
                        </path>
                    </svg>

                </a>

                <span class="action-divider" aria-hidden="true"></span>

                {{-- Shopping Cart --}}
                <a class="icon-button cart-button"
                    href="{{ route('product.viewBag') }}"
                    aria-label="Shopping cart">

                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M2 3h2l2.4 12.2a2 2 0 0 0 2 1.6h9.9a2 2 0 0 0 2-1.6L22 7H5"></path>
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="18" cy="21" r="1"></circle>
                    </svg>

                </a>

            </div>

        </div>

    </nav>
</header>

<header class="header">
        <div class="logo">
            <a href="{{ url('/') }}"><img src="{{ asset('assets/images/logo1.png') }}" alt="S4K"></a>
        </div>
        <nav class="menu">
            <ul>
                <li>
                    <a href="{{ url('/') }}">Home</a>
                </li>
                @foreach($categories->whereNull('parent_id') as $cat)
                    <li class="has-mega">
                        <a href="{{ route('category.show',['path'=>$cat->slug]) }}">{{ $cat->name }}
                            <span class="material-symbols-outlined">chevron_right</span>
                        </a>
                        <div class="mega-menu">
                            @foreach($subCategories as $sub)
                                @if($sub->parent_id == $cat->id)
                                    <div class="mega-column">
                                        <h4>{{ $sub->name }}</h4>
                                        @foreach($childCategories as $child)
                                            @if($child->parent_id == $sub->id)
                                                <a href="{{ route('category.show',['path'=>$cat->slug.'/'.$sub->slug.'/'.$child->slug]) }}">
                                                    <svg viewBox="0 0 14 10" fill="none" aria-hidden="true" focusable="false" class="icon icon-arrow" xmlns="https://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8.537.808a.5.5 0 01.817-.162l4 4a.5.5 0 010 .708l-4 4a.5.5 0 11-.708-.708L11.793 5.5H1a.5.5 0 010-1h10.793L8.646 1.354a.5.5 0 01-.109-.546z" fill="currentColor">
                                                            </path>
                                                    </svg> {{ $child->name }}
                                                </a>
                                            @endif     
                                        @endforeach 
                                    </div>
                                @endif     
                            @endforeach 
                        </div>
                    </li>
                @endforeach 
            </ul>
        </nav>
        <button class="menu-toggle">
            <span class="material-symbols-outlined">menu</span>
        </button>

        <div class="mobile-overlay"></div>
        <div class="mobile-menu">
            <div class="mobile-header">
                <img src="images/logo1.png" alt="Logo">
                <button class="close-menu">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <ul>
                <li>
                    <a href="#">Home</a>
                </li>
                <li class="has-submenu">
                    <a href="#">
                        Women Wear
                        <span class="material-symbols-outlined arrow">expand_more</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="#">Anarkali Sets</a></li>
                        <li><a href="#">Kurta Pant Sets</a></li>
                        <li><a href="#">Ethnic Sets</a></li>
                        <li><a href="#">Co-Ord Sets</a></li>
                        <li><a href="#">Gowns</a></li>
                    </ul>
                </li>

                <li class="has-submenu">
                    <a href="#">
                        Collections
                        <span class="material-symbols-outlined arrow">expand_more</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="#">New Arrival</a></li>
                        <li><a href="#">Festive</a></li>
                        <li><a href="#">Best Seller</a></li>
                    </ul>
                </li>

                <li><a href="#">Manufacturing Process</a></li>
                <li><a href="#">Wholesale</a></li>
                <li><a href="#">Gallery</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>
        </div>

        <div class="actions">
            @if (Auth::guard('customer')->check())
            <a href="{{ route('user.dashboard') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="17" viewBox="0 0 15 17" fill="none">
                    <path
                        d="M13.8347 15.439C13.8347 14.3195 13.8347 13.7598 13.6965 13.3043C13.3854 12.2788 12.5829 11.4763 11.5574 11.1652C11.102 11.0271 10.5422 11.0271 9.42274 11.0271H5.41192C4.29244 11.0271 3.7327 11.0271 3.27724 11.1652C2.25174 11.4763 1.44925 12.2788 1.13816 13.3043C1 13.7598 1 14.3195 1 15.439M11.0271 4.60975C11.0271 6.60336 9.41094 8.21949 7.41733 8.21949C5.42372 8.21949 3.80758 6.60336 3.80758 4.60975C3.80758 2.61614 5.42372 1 7.41733 1C9.41094 1 11.0271 2.61614 11.0271 4.60975Z"
                        stroke="#38271e" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="">
                    </path>
                </svg>
            </a>
            @else 
            <a href="{{ route('front-user.login') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="17" viewBox="0 0 15 17" fill="none">
                    <path
                        d="M13.8347 15.439C13.8347 14.3195 13.8347 13.7598 13.6965 13.3043C13.3854 12.2788 12.5829 11.4763 11.5574 11.1652C11.102 11.0271 10.5422 11.0271 9.42274 11.0271H5.41192C4.29244 11.0271 3.7327 11.0271 3.27724 11.1652C2.25174 11.4763 1.44925 12.2788 1.13816 13.3043C1 13.7598 1 14.3195 1 15.439M11.0271 4.60975C11.0271 6.60336 9.41094 8.21949 7.41733 8.21949C5.42372 8.21949 3.80758 6.60336 3.80758 4.60975C3.80758 2.61614 5.42372 1 7.41733 1C9.41094 1 11.0271 2.61614 11.0271 4.60975Z"
                        stroke="#38271e" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="">
                    </path>
                </svg>
            </a>
            @endif 
            <a href="#" class="wishlist_button">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="17" viewBox="0 0 18 17" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M9.01617 2.71328C7.41235 0.838283 4.73789 0.333916 2.72842 2.05085C0.718956 3.76778 0.436051 6.63841 2.0141 8.66903C3.32613 10.3574 7.29682 13.9182 8.5982 15.0707C8.74379 15.1996 8.81659 15.2641 8.90151 15.2894C8.97562 15.3115 9.05671 15.3115 9.13083 15.2894C9.21574 15.2641 9.28854 15.1996 9.43414 15.0707C10.7355 13.9182 14.7062 10.3574 16.0182 8.66903C17.5963 6.63841 17.3479 3.74972 15.3039 2.05085C13.2599 0.351976 10.62 0.838283 9.01617 2.71328Z"
                        stroke="#38271e" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    </path>
                </svg>
            </a>
            <a href="{{ route('product.viewBag') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="18" viewBox="0 0 17 18" fill="none">
                    <path
                        d="M11.5585 6.61516V4.20866C11.5585 2.43657 10.1219 1 8.34983 1C6.57773 1 5.14116 2.43657 5.14116 4.20866V6.61516M1.60522 7.69966L1.12392 12.8335C0.987073 14.2932 0.918649 15.0231 1.16086 15.5868C1.37363 16.082 1.74649 16.4915 2.21969 16.7495C2.75835 17.0433 3.4914 17.0433 4.95751 17.0433H11.7421C13.2083 17.0433 13.9413 17.0433 14.48 16.7495C14.9532 16.4915 15.326 16.082 15.5388 15.5868C15.781 15.0231 15.7126 14.2932 15.5757 12.8335L15.0944 7.69966C14.9789 6.46704 14.9211 5.85073 14.6439 5.38477C14.3998 4.9744 14.0391 4.64593 13.6077 4.44117C13.1179 4.20867 12.4989 4.20867 11.2609 4.20867L5.43881 4.20866C4.20078 4.20866 3.58176 4.20866 3.09197 4.44117C2.6606 4.64593 2.2999 4.9744 2.05576 5.38477C1.77856 5.85073 1.72078 6.46704 1.60522 7.69966Z"
                        stroke="#38271e" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="">
                    </path>
                </svg>
            </a>
        </div>
    </header>
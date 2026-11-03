<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php
    $siteLogo = \App\Models\Setting::where('key','Site.logo')->first();
    $siteFooterLogo = \App\Models\Setting::where('key','Site.footerlogo')->first();
    $optionallogo = \App\Models\Setting::where('key','Site.optionallogo')->first();
    $favIcon = \App\Models\Setting::where('key','Site.fav_icon')->first();
@endphp
<!-- Include Head -->
@include('front.includes.head')
    
   <body>
    <div class="wrapper">

        @include('front.includes.header')

        <div id="flash-msg" class="alert alert-info d-none"></div>

        @yield('content')

        @include('front.includes.footer')

        {{-- Load libraries first --}}
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

        {{-- Global variables and common functionality --}}
        @include('front.includes.script')

        {{-- Main design and interaction JS --}}
        <script src="{{ asset('assets/js/main.js') }}?v={{ filemtime(public_path('assets/js/main.js')) }}"></script>

        {{-- Page-specific JS --}}
        @stack('scripts')

    </div>
</body>
</html>

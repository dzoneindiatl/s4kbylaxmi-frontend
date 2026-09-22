<script>
    window.csrfToken = "{{ csrf_token() }}";
    var addToWish = "{{ route('front-addwish') }}";
    const isLoggedIn = "{{ Auth::guard('customer')->check() ? true : false }}";
    var addToCart = "{{ route('user.addToCart') }}";
    var getCouponUrl = "{{ route('get.coupon') }}";
    var wishlistUrl = "{{ route('front-user.wishlist') }}";
    var viewCartUrl = "{{ route('product.viewBag') }}";
    var headerSearchUrl = "{{ route('front-header-product-search') }}"; 
</script>
<script>
    $('.wishlist_button').on('click',function(){
        if(isLoggedIn){
            window.location.href="{{ route('front-user.wishlist') }}";
        }else{
            window.location.href="{{ route('front-user.login') }}";
        }
    }); 
</script>

<script>
     function showFlashMessage(msg, type = 'success') {
        const flash = document.getElementById('flash-msg');

        if (!flash) {
            console.warn('Flash message element not found.');
            return;
        }

        const icons = {
            success: '<i class="fas fa-check-circle"></i>',
            error: '<i class="fas fa-times-circle"></i>',
            warning: '<i class="fas fa-exclamation-circle"></i>',
            info: '<i class="fas fa-info-circle"></i>'
        };

        const alertTypes = {
            success: 'alert-success',
            error: 'alert-danger',
            warning: 'alert-warning',
            info: 'alert-info'
        };

        const icon = icons[type] || icons.info;
        const alertClass = alertTypes[type] || alertTypes.info;

        flash.classList.remove(
            'd-none',
            'alert-success',
            'alert-danger',
            'alert-warning',
            'alert-info'
        );

        flash.classList.add(alertClass);

        flash.innerHTML = `${icon} <span>${msg}</span>`;

        clearTimeout(window.flashMessageTimer);

        window.flashMessageTimer = setTimeout(function() {
            flash.classList.add('d-none');
        }, 3000);
    }
</script>
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

    $(document).off('click', '.addtoWishList').on('click', '.addtoWishList', function () {
    const productId = this.getAttribute('data-product-id');
    const heartIcon = this.querySelector('i');
   
    if (!isLoggedIn) {
        //const loginModal = new bootstrap.Modal(document.getElementById('login'));
        //loginModal.show();
        window.location.href = '/login';
        return;
    }
    
    fetch(addToWish, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => {
        if (!response.ok) throw new Error("Something went wrong");
        return response.json();
    })
    .then(data => { 
        if (data.status === 'added') {
            showFlashMessage("Product added in wishlist");
            // alert("Product added in wishlist");
            heartIcon.classList.remove('fa-regular');
            heartIcon.classList.add('fa-solid');
        } else if (data.status === 'removed') {
            showFlashMessage("Product remove in wishlist", "warning");
            // alert("Product remove in wishlist", "warning");
            heartIcon.classList.remove('fa-solid');
            heartIcon.classList.add('fa-regular');
        }
        //showToastr(data.status, data.message);
        localStorage.setItem('wishlistCount', JSON.stringify(data.wishlistCount));
        displayWishlistItem();
    })
    .catch(error => {
        console.error("Wishlist error:", error);
    });
});
</script>
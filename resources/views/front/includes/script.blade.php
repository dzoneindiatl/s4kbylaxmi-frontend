<script>
    window.csrfToken = "{{ csrf_token() }}";
    var addToWish = "{{ route('front-addwish') }}";
    var isLoggedIn = @json(Auth::guard('customer')->check());
    window.isCustomerLoggedIn = isLoggedIn;
   var addToCart = "{{ route('user.addToCart') }}";
    window.addToCartUrl = addToCart;
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

    $(document).off('click', '.addtoWishList, .detail-wishlist').on('click', '.addtoWishList, .detail-wishlist', function (event) {
        event.preventDefault();

        const button = this;
        const productId = button.getAttribute('data-product-id');
        const productName = button.getAttribute('data-product-name') || 'Product';

        if (!isLoggedIn) {
            window.location.href = @json(route('front-user.login'));
            return;
        }

        if (!productId) {
            console.error('Wishlist error: product ID is missing.');
            showFlashMessage('Unable to update wishlist. Please refresh the page and try again.', 'error');
            return;
        }

        button.disabled = true;
        fetch(addToWish, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            },
            credentials: 'same-origin',
            body: JSON.stringify({ product_id: productId })
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Unable to update wishlist.');
            }
            return data;
        })
        .then(data => {
            if (data.status !== 'added' && data.status !== 'removed') {
                throw new Error(data.message || 'The wishlist response was invalid.');
            }

            const isWishlisted = data.status === 'added';
            button.classList.toggle('is-active', isWishlisted);
            button.setAttribute('aria-pressed', String(isWishlisted));
            button.setAttribute(
                'aria-label',
                (isWishlisted ? 'Remove ' : 'Add ') + productName + (isWishlisted ? ' from' : ' to') + ' wishlist'
            );
            showFlashMessage(data.message || (isWishlisted ? 'Product added to wishlist.' : 'Product removed from wishlist.'), isWishlisted ? 'success' : 'warning');
            localStorage.setItem('wishlistCount', JSON.stringify(data.wishlistCount));
            if (typeof displayWishlistItem === 'function') {
                displayWishlistItem();
            }
        })
        .catch(error => {
            console.error('Wishlist error:', error);
            showFlashMessage(error.message || 'Unable to update wishlist. Please try again.', 'error');
        })
        .finally(() => {
            button.disabled = false;
        });
    });
</script>
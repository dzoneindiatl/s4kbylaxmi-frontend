$(function() {
    $('.plus').on('click', function() {
        let input = $(this).siblings('input');
        let value = parseInt(input.val()) || 1;
        input.val(value + 1);
    });

    $('.minus').on('click', function() {
        let input = $(this).siblings('input');
        let value = parseInt(input.val()) || 1;
        if (value > 1) {
            input.val(value - 1);
        }
    });

    $(window).on('scroll', function() {
        if ($(window).width() < 768) {
            if ($(window).scrollTop() > 700) {
                $('.mobile-cart').addClass('show');
            } else {
                $('.mobile-cart').removeClass('show');
            }
        }
    });

    $('.buy-now, .add-cart').mouseenter(function() {
        $(this).addClass('active');
    }).mouseleave(function() {
        $(this).removeClass('active');
    });

    $('.delivery-form button').click(function(e) {
        e.preventDefault();
        let pin = $('.delivery-form input').val();
        if (pin.length == 6) {
            alert("Delivery Available ✔");
        } else {
            alert("Please Enter Valid Pincode");
        }
    });

    $('.product-card').mouseenter(function() {
        $(this).css({
            transform: 'translateY(-8px)'
        });
    }).mouseleave(function() {
        $(this).css({
            transform: 'translateY(0px)'
        });
    });
});

/*=========================================
    IMAGE GALLERY
=========================================*/

var $gallery = $('.gallery-main').flickity({
    cellAlign: 'left',
    contain: true,
    pageDots: false,
    prevNextButtons: false,
    draggable: true,
    adaptiveHeight: true
});
function bindGalleryThumbs() {
    $('.thumb').off('click').on('click', function() {
        var index = $(this).index();
        $gallery.flickity('select', index);
    });
}
bindGalleryThumbs();

$gallery.on('change.flickity', function(event, index) {
    $('.thumb').removeClass('is-nav-selected');
    $('.thumb').eq(index).addClass('is-nav-selected');
});


/*=========================================
    IMAGE GALLERY ZOOM
=========================================*/

Fancybox.bind('[data-fancybox="product-gallery"]', {
    Toolbar: {
        display: {
            left: [],
            middle: [],
            right: [
                "zoomIn",
                "zoomOut",
                "toggle1to1",
                "rotateCCW",
                "rotateCW",
                "flipX",
                "flipY",
                "close"
            ]
        }
    },
    Thumbs: {
        autoStart: true
    },
    Images: {
        zoom: true
    }
});

$('.zoom-btn').on('click', function(e) {
    e.preventDefault();

    var flkty = $('.gallery-main').data('flickity');

    if (!flkty) {
        return;
    }

    var gallery = [];

    $('[data-fancybox="product-gallery"]').each(function() {
        gallery.push({
            src: $(this).attr('href'),
            type: 'image'
        });
    });

    Fancybox.show(gallery, {
        startIndex: flkty.selectedIndex,
        Toolbar: {
            display: {
                left: [],
                middle: [],
                right: [
                    "zoomIn",
                    "zoomOut",
                    "toggle1to1",
                    "rotateCCW",
                    "rotateCW",
                    "flipX",
                    "flipY",
                    "close"
                ]
            }
        },
        Thumbs: {
            autoStart: true
        }
    });
});
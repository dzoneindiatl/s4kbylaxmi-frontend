// ------------------- HOME PAGE MAIN BANNER

$('.hero-main-slider').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    fade: true,
    arrows: false,
    autoplay: true,
    dots: true,
    asNavFor: '.hero-thumb-slider'
});

$('.hero-thumb-slider').slick({
    slidesToShow: 5,
    slidesToScroll: 1,
    vertical: true,
    verticalSwiping: true,
    focusOnSelect: true,
    arrows: true,
    asNavFor: '.hero-main-slider',

    responsive: [
        {
            breakpoint: 1500,
            settings: {
                slidesToShow: 3
            }
        }, {
            breakpoint: 1200,
            settings: {
                slidesToShow: 3
            }
        },
        {
            breakpoint: 991,
            settings: {
                slidesToShow: 3
            }
        },
        {
            breakpoint: 767,
            settings: {
                vertical: false,
                verticalSwiping: false,
                slidesToShow: 3
            }
        },
        {
            breakpoint: 575,
            settings: {
                vertical: false,
                verticalSwiping: false,
                slidesToShow: 3
            }
        }
    ],

    prevArrow: '<button class="thumb-prev"><span class="material-symbols-outlined">keyboard_arrow_up</span></button>',
    nextArrow: '<button class="thumb-next"><span class="material-symbols-outlined">keyboard_arrow_down</span></button>'
});


// TESTIMONIAL SLIDER


$('.testimonial-slider').slick({
    slidesToShow: 5.2,
    slidesToScroll: 1,
    autoplay: true,
    arrows: true,
    dots: false,
    infinite: true,
    prevArrow: '<button class="testimonial-prev"><i class="fa-solid fa-arrow-left"></i></button>',
    nextArrow: '<button class="testimonial-next"><i class="fa-solid fa-arrow-right"></i></button>',
    responsive: [
        {
            breakpoint: 1200,
            settings: {
                slidesToShow: 3
            }
        },
        {
            breakpoint: 768,
            settings: {
                slidesToShow: 2
            }
        },
        {
            breakpoint: 576,
            settings: {
                slidesToShow: 1
            }
        }
    ]
});



// INSTAGRAM SLIDER-----------------------------

$('.instagram-slider').slick({
    slidesToShow: 5,
    slidesToScroll: 1,

    arrows: true,
    dots: false,

    autoplay: true,
    autoplaySpeed: 3000,
    speed: 600,

    infinite: true,
    swipe: true,
    draggable: true,

    prevArrow: '<button class="insta-prev"><span class="material-symbols-outlined">arrow_back</span></button>',
    nextArrow: '<button class="insta-next"><span class="material-symbols-outlined">arrow_forward</span></button>',

    responsive: [
        {
            breakpoint: 1200,
            settings: {
                slidesToShow: 4
            }
        },
        {
            breakpoint: 991,
            settings: {
                slidesToShow: 3
            }
        },
        {
            breakpoint: 767,
            settings: {
                slidesToShow: 2
            }
        }
    ]
});




// BLOG SECTION------------------------------------

$('.blog-slider').slick({
    slidesToShow: 4,
    slidesToScroll: 1,
    arrows: true,
    dots: false,
    infinite: true,

    prevArrow: '<button type="button" class="slick-prev"><span class="material-symbols-outlined">arrow_back</span></button>',
    nextArrow: '<button type="button" class="slick-next"><span class="material-symbols-outlined">arrow_forward</span></button>',

    responsive: [
        {
            breakpoint: 1200,
            settings: {
                slidesToShow: 3
            }
        },
        {
            breakpoint: 991,
            settings: {
                slidesToShow: 2
            }
        },
        {
            breakpoint: 600,
            settings: {
                slidesToShow: 1
            }
        }
    ]
});


// COLLECTION SECTION------------------------------------

$('.collection_section').slick({
    slidesToShow: 5,
    slidesToScroll: 1,
    arrows: true,
    dots: false,
    infinite: true,

    prevArrow: $('.trending-collection-section .slider-nav .prev-btn'),
    nextArrow: $('.trending-collection-section .slider-nav .next-btn'),

    responsive: [
        {
            breakpoint: 1200,
            settings: {
                slidesToShow: 3
            }
        },
        {
            breakpoint: 991,
            settings: {
                slidesToShow: 2
            }
        },
        {
            breakpoint: 600,
            settings: {
                slidesToShow: 1
            }
        }
    ]
});


// COLLECTION SECTION------------------------------------

$('.latest-collection-container').slick({
    slidesToShow: 2.5,
    slidesToScroll: 1,
    arrows: true,
    dots: false,
    infinite: false,


    prevArrow: $('.latest-collection .slider-nav .prev-btn'),
    nextArrow: $('.latest-collection .slider-nav .next-btn'),

    responsive: [
        {
            breakpoint: 1200,
            settings: {
                slidesToShow: 2.5
            }
        },
        {
            breakpoint: 991,
            settings: {
                slidesToShow: 1.5
            }
        },
        {
            breakpoint: 600,
            settings: {
                slidesToShow: 1.5
            }
        }
    ]
});

// BRIDAL COLLECTION------------------------------------

$('.bridal-slider').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    dots: true,
    autoplay: true,
    autoplaySpeed: 4000,
    fade: true,
    speed: 800
});


// MOBILE MENU DROPDOWN-------------------------------------------

// Open
$('.menu-toggle').click(function () {
    $('.mobile-menu').addClass('active');
    $('.mobile-overlay').addClass('active');
});

// Close
$('.close-menu, .mobile-overlay').click(function () {
    $('.mobile-menu').removeClass('active');
    $('.mobile-overlay').removeClass('active');

});

// Accordion
$('.has-submenu > a').click(function (e) {
    e.preventDefault();
    var parent = $(this).parent();
    if (parent.hasClass('active')) {
        parent.removeClass('active');
        parent.find('.submenu').slideUp();
    } else {
        $('.has-submenu').removeClass('active');
        $('.submenu').slideUp();
        parent.addClass('active');
        parent.find('.submenu').slideDown();
    }
});
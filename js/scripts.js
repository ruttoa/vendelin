var target = document.querySelector(".swiper-container .wp-block-group__inner-container");

var div = document.createElement('div');
div.classList.add('swiper-pagination');
var fragment = document.createDocumentFragment();
fragment.appendChild(div);

target.appendChild(fragment);

var mySwiper = new Swiper(target, {
    // Optional parameters
    direction: 'horizontal',
    //loop: true,
    wrapperClass: 'wp-block-columns',
    slideClass: 'wp-block-column',
    speed: 400,
    slidesPerView: 1,
    //spaceBetween: 32,
    autoplay: {
        delay: 3000,
    },

    // If we need pagination
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    breakpoints: {
        540: {
            slidesPerView: 2,
        },
        768: {
            slidesPerView: 3,
        },
    }
})
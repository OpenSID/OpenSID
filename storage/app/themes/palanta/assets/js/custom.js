jQuery(document).ready(function ($) {
    $('ul.nav li.dropdown').hover(function () {
        $(this).find('.dropdown-menu').stop(!0, !0).delay(200).fadeIn(200)
    }, function () {
        $(this).find('.dropdown-menu').stop(!0, !0).delay(200).fadeOut(200)
    });
    $('.slick_slider').slick({
        dots: !0,
        infinite: !0,
        speed: 500,
        slidesToShow: 1,
        slide: 'div',
        autoplay: !0,
        autoplaySpeed: 2000,
        cssEase: 'linear'
    });
    $('.slick_slider2').slick({
        dots: !0,
        infinite: !0,
        speed: 500,
        autoplay: !0,
        autoplaySpeed: 2000,
        fade: !0,
        slide: 'div',
        cssEase: 'linear'
    });
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.scrollToTop').fadeIn()
        } else {
            $('.scrollToTop').fadeOut()
        }
    });
    $('.scrollToTop').click(function () {
        $('html, body').animate({
            scrollTop: 0
        }, 800);
        return !1
    })

    $('#status').fadeOut();
    $('#preloader').delay(100).fadeOut('slow');
    $('body').delay(100).css({
        'overflow': 'visible'
    })
}) 
const wow = new WOW({
    animateClass: 'animated',
    offset: 100
});
wow.init() 

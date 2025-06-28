import $ from 'jquery';
$(window).on('scroll', function () {
    if ($(window).scrollTop() > 0) {
        $('header').addClass('scrolled');
    } else {
        $('header').removeClass('scrolled');
    }
});

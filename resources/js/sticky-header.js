import $ from 'jquery';

$(function() {
    const $header = $('header');
    const headerHeight = $header.outerHeight();

    $(window).on('scroll', function() {
        $header.toggleClass('sticky', $(this).scrollTop() > headerHeight);
    });

    // Инициализация при загрузке
    if ($(window).scrollTop() > headerHeight) {
        $header.addClass('sticky');
    }
});

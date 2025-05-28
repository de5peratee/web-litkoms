import $ from 'jquery';

$(document).ready(function() {
    const $menuTrigger = $('#menu-trigger');
    const $mobileMenu = $('#mobileMenu');
    const $mobileMenuOverlay = $('#mobileMenuOverlay');
    const $mobileMenuClose = $('#mobile-menu-close');

    function openMenu() {
        $mobileMenu.addClass('active');
        $mobileMenuOverlay.addClass('active');
        $('body').css('overflow', 'hidden');
    }

    function closeMenu() {
        $mobileMenu.removeClass('active');
        $mobileMenuOverlay.removeClass('active');
        $('body').css('overflow', '');
    }

    $menuTrigger.on('click', openMenu);
    $mobileMenuClose.on('click', closeMenu);

    $mobileMenuOverlay.on('click', closeMenu);

    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $mobileMenu.hasClass('active')) {
            closeMenu();
        }
    });

    $(window).on('resize', function() {
        if ($(this).width() > 1200) {
            closeMenu();
        }
    });
});

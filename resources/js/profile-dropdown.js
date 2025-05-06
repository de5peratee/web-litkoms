// resources/js/profile-dropdown.js
import $ from 'jquery';

$(document).ready(function() {
    const $profileInfo = $('.profile-info');
    const $profileDropdown = $('#profileDropdown');

    if ($profileInfo.length && $profileDropdown.length) {
        // Открытие/закрытие по клику
        $profileInfo.on('click', function(e) {
            e.preventDefault();
            $profileDropdown.toggleClass('active');
        });

        // Закрытие при клике вне меню
        $(document).on('click', function(e) {
            if (!$profileDropdown.is(e.target) &&
                $profileDropdown.has(e.target).length === 0 &&
                !$profileInfo.is(e.target) &&
                $profileInfo.has(e.target).length === 0) {
                $profileDropdown.removeClass('active');
            }
        });

        // Закрытие при нажатии ESC
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                $profileDropdown.removeClass('active');
            }
        });
    }
});

import $ from 'jquery';

$(document).ready(function() {
    const $profileContainer = $('.profile-container');
    const $profileDropdown = $('#profileDropdown');
    let closeTimer;

    if ($profileContainer.length && $profileDropdown.length) {
        const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints;

        // Для десктопов
        if (!isTouchDevice) {
            $profileContainer.on('mouseenter', function() {
                clearTimeout(closeTimer);
                $profileDropdown.css({
                    'transition-delay': '0.1s',
                    'visibility': 'visible',
                    'opacity': '1',
                    'transform': 'translateY(0)'
                });
            });

            $profileContainer.on('mouseleave', function() {
                closeTimer = setTimeout(() => {
                    $profileDropdown.css({
                        'transition-delay': '0s',
                        'opacity': '0',
                        'transform': 'translateY(-10px)'
                    });
                    // Скрываем после завершения анимации
                    setTimeout(() => {
                        $profileDropdown.css('visibility', 'hidden');
                    }, 150);
                }, 200); // 200ms задержка перед началом анимации закрытия
            });
        }

        // Для мобильных
        if (isTouchDevice) {
            $('.profile-info').on('click', function(e) {
                e.preventDefault();
                $profileDropdown.toggleClass('force-show');
            });

            $(document).on('click', function(e) {
                if (!$profileContainer.is(e.target) &&
                    $profileContainer.has(e.target).length === 0) {
                    $profileDropdown.removeClass('force-show');
                }
            });
        }
    }
});

import './bootstrap';
import $ from 'jquery';

$(function () {
    console.log('jQuery подключен и готов к работе');

    const $transitionElement = $('#page-transition');

    // Затухание после загрузки
    setTimeout(function () {
        $transitionElement.addClass('fade-out');
    }, 100);

    // Перед переходом по ссылке
    $(document).on('click', 'a[href]:not([target="_blank"]):not([href^="#"]):not([href^="javascript:"])', function (e) {
        const link = $(this).attr('href');

        // Игнорировать переход, если это внешняя ссылка
        if (!link || (link.startsWith('http') && !link.startsWith(window.location.origin))) {
            return;
        }

        e.preventDefault(); // Остановить переход
        $transitionElement.removeClass('fade-out').addClass('fade-in');

        setTimeout(() => {
            window.location.href = link;
        }, 300);
    });

    // Обработка возвращения назад (bfcache)
    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            // Сбросим состояние и повторно скроем белый фон
            $transitionElement.removeClass('fade-in').show();

            setTimeout(function () {
                $transitionElement.addClass('fade-out');
            }, 50);
        }
    });
});

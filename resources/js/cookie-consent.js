import $ from 'jquery';

$(document).ready(function() {
    // Проверяем, есть ли выбор пользователя в sessionStorage
    const consent = sessionStorage.getItem('userConsent');

    // Показываем плашку, если выбор не сделан
    if (!consent) {
        $('#cookie-consent').removeClass('hidden');
    } else if (consent === 'accepted') {
        loadAnalyticsScripts();
    }

    // Обработчик кнопки "Принять"
    $('#accept-cookies').click(function() {
        sessionStorage.setItem('userConsent', 'accepted');
        $('#cookie-consent').addClass('hidden');
        loadAnalyticsScripts();
    });

    // Функция для загрузки аналитических скриптов (если используются)
    function loadAnalyticsScripts() {
        // Пример: загрузка Яндекс.Метрики или Google Analytics
        // Раскомментируйте и настройте под ваши нужды
        /*
        const script = document.createElement('script');
        script.src = 'https://www.googletagmanager.com/gtag/js?id=UA-XXXXX-Y';
        document.head.appendChild(script);
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-XXXXX-Y');
        */
        console.log('Analytics scripts loaded');
    }
});

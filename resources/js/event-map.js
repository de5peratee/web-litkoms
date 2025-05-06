import $ from 'jquery';
$(document).ready(function() {
    // Ваши координаты (можно поменять)
    const coords = [44.578042, 33.522845];
    const zoom = 15;

    // Создаем iframe с картой (проще всего через Яндекс)
    const mapHtml = `
        <iframe
            src="https://yandex.ru/map-widget/v1/?ll=${coords[1]}%2C${coords[0]}&z=${zoom}&pt=${coords[1]}%2C${coords[0]}%2Cpm2blm"
            width="100%"
            height="100%"
            frameborder="0">
        </iframe>
    `;

    // Вставляем карту в контейнер
    $('#map').html(mapHtml);
});

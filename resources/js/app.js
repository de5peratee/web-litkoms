import './bootstrap';
import $ from 'jquery';
window.jQuery = window.$ = $; // Делаем jQuery доступным глобально

$(document).ready(function() {
    $('.page-content').css({ opacity: 0 }).animate({
        opacity: 1
    }, 500);
});

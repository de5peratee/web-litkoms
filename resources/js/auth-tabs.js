import './bootstrap';
import $ from 'jquery';
window.jQuery = window.$ = $;

$(document).ready(function() {
    const $loginTab = $('#loginTab');
    const $registerTab = $('#registerTab');
    const $loginForm = $('#loginForm');
    const $registerForm = $('#registerForm');
    const $authHeader = $('.auth-header h3');
    const $authDescription = $('.auth-header .text-small');
    const ACTIVE_TAB_KEY = 'activeAuthTab';

    // Функция для переключения вкладок
    function switchTab(isLogin) {
        if (isLogin) {
            $loginTab.addClass('active');
            $registerTab.removeClass('active');
            $registerForm.css('display', 'none');
            $loginForm.css('display', 'flex');
            $authHeader.text('Вход в аккаунт');
            $authDescription.html('Войдите в профиль, чтобы публиковать авторские комиксы, <br> комментировать и оценивать другие и многое другое');
            localStorage.setItem(ACTIVE_TAB_KEY, 'login'); // Сохраняем состояние
        } else {
            $registerTab.addClass('active');
            $loginTab.removeClass('active');
            $loginForm.css('display', 'none');
            $registerForm.css('display', 'flex');
            $authHeader.text('Регистрация');
            $authDescription.html('Создайте аккаунт, чтобы делиться своими комиксами <br> и участвовать в жизни сообщества');
            localStorage.setItem(ACTIVE_TAB_KEY, 'register'); // Сохраняем состояние
        }
    }

    // Восстановление состояния при загрузке страницы
    const lastActiveTab = localStorage.getItem(ACTIVE_TAB_KEY);
    if (lastActiveTab === 'register') {
        switchTab(false); // Показываем регистрацию, если была выбрана ранее
    } else {
        switchTab(true); // По умолчанию показываем логин
    }

    $loginTab.on('click', function() {
        if (!$loginTab.hasClass('active')) {
            switchTab(true);
        }
    });

    $registerTab.on('click', function() {
        if (!$registerTab.hasClass('active')) {
            switchTab(false);
        }
    });
});
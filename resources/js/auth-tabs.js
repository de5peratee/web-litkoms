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

    // Изначально показываем форму логина
    $loginForm.css('display', 'flex');
    $registerForm.css('display', 'none');
    $loginTab.addClass('active');

    // Переключение на вкладку логина
    $loginTab.on('click', function() {
        if (!$loginTab.hasClass('active')) {
            $loginTab.addClass('active');
            $registerTab.removeClass('active');

            $registerForm.css('display', 'none');
            $loginForm.css('display', 'flex');

            $authHeader.text('Вход в аккаунт');
            $authDescription.html('Войдите в профиль, чтобы публиковать авторские комиксы, <br> комментировать и оценивать другие и многое другое');
        }
    });

    // Переключение на вкладку регистрации
    $registerTab.on('click', function() {
        if (!$registerTab.hasClass('active')) {
            $registerTab.addClass('active');
            $loginTab.removeClass('active');

            $loginForm.css('display', 'none');
            $registerForm.css('display', 'flex');

            $authHeader.text('Регистрация');
            $authDescription.html('Создайте аккаунт, чтобы делиться своими комиксами <br> и участвовать в жизни сообщества');
        }
    });
});

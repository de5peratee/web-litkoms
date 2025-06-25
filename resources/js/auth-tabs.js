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

    // Функция для очистки ошибок валидации
    function clearValidationErrors(form) {
        form.find('.error-message').remove();
        form.find('.is-invalid').removeClass('is-invalid');
    }

    // Функция для переключения вкладок
    function switchTab(isLogin) {
        if (isLogin) {
            // Очищаем ошибки в форме регистрации
            clearValidationErrors($registerForm);

            $loginTab.addClass('active');
            $registerTab.removeClass('active');
            $registerForm.css('display', 'none');
            $loginForm.css('display', 'flex');
            $authHeader.text('Вход в аккаунт');
            $authDescription.html('Войдите в профиль, чтобы публиковать авторские комиксы, <br> комментировать и оценивать другие и многое другое');
            localStorage.setItem(ACTIVE_TAB_KEY, 'login');
        } else {
            // Очищаем ошибки в форме входа
            clearValidationErrors($loginForm);

            $registerTab.addClass('active');
            $loginTab.removeClass('active');
            $loginForm.css('display', 'none');
            $registerForm.css('display', 'flex');
            $authHeader.text('Регистрация');
            $authDescription.html('Создайте аккаунт, чтобы делиться своими комиксами <br> и участвовать в жизни сообщества');
            localStorage.setItem(ACTIVE_TAB_KEY, 'register');
        }
    }

    // Восстановление состояния при загрузке страницы
    const lastActiveTab = localStorage.getItem(ACTIVE_TAB_KEY);
    if (lastActiveTab === 'register') {
        switchTab(false);
    } else {
        switchTab(true);
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

    // === ✅ Отключение кнопки регистрации, если чекбокс не активен ===
    const $agreeCheckbox = $('#registerForm input[name="agree"]');
    const $registerBtn = $('#registerForm button[type="submit"]');

    function toggleRegisterButton() {
        $registerBtn.prop('disabled', !$agreeCheckbox.prop('checked'));
    }

    if ($agreeCheckbox.length && $registerBtn.length) {
        toggleRegisterButton(); // Проверка при загрузке
        $agreeCheckbox.on('change', toggleRegisterButton);
    }
});

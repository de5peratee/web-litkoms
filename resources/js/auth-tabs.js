document.addEventListener('DOMContentLoaded', function () {
    const loginTab = document.getElementById('loginTab');
    const registerTab = document.getElementById('registerTab');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const authHeader = document.querySelector('.auth-header h3');
    const authDescription = document.querySelector('.auth-header .text-small');

    // Изначально показываем форму логина, скрываем регистрацию
    loginForm.style.display = 'flex';
    registerForm.style.display = 'none';

    loginTab.addEventListener('click', function () {
        // Переключаем активный класс
        loginTab.classList.add('active');
        registerTab.classList.remove('active');

        // Переключаем видимость форм
        loginForm.style.display = 'flex';
        registerForm.style.display = 'none';

        // Обновляем заголовок и описание
        authHeader.textContent = 'Вход в аккаунт';
        authDescription.innerHTML = 'Войдите в профиль, чтобы публиковать авторские комиксы, <br> комментировать и оценивать другие и многое другое';
    });

    registerTab.addEventListener('click', function () {
        // Переключаем активный класс
        registerTab.classList.remove('active');
        loginTab.classList.remove('active');
        registerTab.classList.add('active');

        // Переключаем видимость форм
        loginForm.style.display = 'none';
        registerForm.style.display = 'flex';

        // Обновляем заголовок и описание
        authHeader.textContent = 'Регистрация';
        authDescription.innerHTML = 'Создайте аккаунт, чтобы делиться своими комиксами <br> и участвовать в жизни сообщества';
    });
});

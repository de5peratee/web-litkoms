<!-- resources/views/home.blade.php -->
@extends('layouts.app')  <!-- Используем главный шаблон -->

@section('title', 'Регистрация/Вход')  <!-- Устанавливаем название страницы -->

@section('content')
    @vite(['resources/css/buttons.css'])
    @vite(['resources/css/inputs.css'])
    @vite(['resources/css/auth.css'])

    <div class="auth-container">
        <div class="auth-header">
            <div class="auth-icon">
                <img src="{{ asset('images/icons/hw/login-hw-icon.svg') }}" alt="icon">
            </div>
            <h3>Вход в аккаунт</h3>
            <p class="text-small">Войдите в профиль, чтобы публиковать авторские комиксы, комментировать и оценивать другие и многое другое</p>
        </div>

        <!-- Табы для переключения между логином и регистрацией -->
        <div class="auth-tabs">
            <div id="loginTab" class="tab active text-small">Вход</div>
            <div id="registerTab" class="tab text-small">Регистрация</div>
        </div>

        <!-- Форма логина -->
        <form method="POST" action="{{ route('login') }}" id="loginForm" class="lit-form">
            @csrf

            <div class="lit-field">
                <label for="login_email">Почта</label>
                <input type="email" name="email" id="login_email" required placeholder="Введите ваш email">
            </div>

            <div class="lit-field">
                <label for="login_password">Пароль</label>
                <input type="password" name="password" id="login_password" required placeholder="Введите ваш пароль">
            </div>

            <button type="submit" class="primary-btn">Войти</button>
        </form>

        <!-- Форма регистрации -->
        <form method="POST" action="{{ route('register') }}" id="registerForm" class="lit-form">
            @csrf
            <div class="lit-form-row">
                <div class="lit-field">
                    <label for="full_name">ФИО</label>
                    <input type="text" name="full_name" id="full_name" required placeholder="Введите ваше ФИО">
                </div>
                <div class="lit-field">
                    <label for="nickname">Ник</label>
                    <input type="text" name="nickname" id="nickname" required placeholder="Введите ваш ник">
                </div>
            </div>

            <div class="lit-form-row">
                <div class="lit-field">
                    <label for="dob">Дата рождения</label>
                    <input type="date" name="dob" id="dob" required>
                </div>
                <div class="lit-field">
                    <label for="reg_email">Почта</label>
                    <input type="email" name="email" id="reg_email" required placeholder="Введите ваш email">
                </div>
            </div>

            <div class="lit-form-row">
                <div class="lit-field">
                    <label for="reg_password">Пароль</label>
                    <input type="password" name="password" id="reg_password" required placeholder="Введите ваш пароль">
                </div>
                <div class="lit-field">
                    <label for="password_confirmation">Подтверждение пароля</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Повторите пароль">
                </div>
            </div>
            <button type="submit" class="primary-btn">Зарегистрироваться</button>
        </form>
    </div>
@endsection

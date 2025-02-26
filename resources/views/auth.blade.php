<!-- resources/views/home.blade.php -->
@extends('layouts.app')  <!-- Используем главный шаблон -->

@section('title', 'Регистрация/Вход')  <!-- Устанавливаем название страницы -->

@section('content')
    <div class="auth-container">

        <div class="auth-header">
            <div class="auth-icon">
                <img src="{{ asset('images/icons/hw/login-hw-icon.svg') }}" alt="icon">
            </div>

            <h3>Вход в аккаунт</h3>

            <p class="text-small">Войдите в профиль, чтобы публиковать авторские комиксы, <br>
                комментировать и оценивать другие и многое другое</p>
        </div>


        <!-- Табы для переключения между логином и регистрацией -->
        <div class="auth-tabs">
            <div id="loginTab" class="tab active text-small">Вход</div>
            <div id="registerTab" class="tab text-small">Регистрация</div>
        </div>

        <!-- Форма логина -->
        <form method="POST" id="loginForm" class="auth-form">
            @csrf
            <div class="auth-field">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required placeholder="Введите ваш email">
            </div>
            <div class="auth-field">
                <label for="password">Пароль:</label>
                <input type="password" name="password" id="password" required placeholder="Введите ваш пароль">
            </div>

            <button type="submit" class="primary-btn">Войти</button>
        </form>

        <!-- Форма регистрации -->
        <form method="POST" id="registerForm" class="auth-form">
            @csrf
            <div class="auth-form-row">
                <div>
                    <label for="full_name">ФИО:</label>
                    <input type="text" name="full_name" id="full_name" required placeholder="Введите ваше полное имя">
                </div>
            </div>

            <div class="auth-form-row">
                <div>
                    <label for="nickname">Ник:</label>
                    <input type="text" name="nickname" id="nickname" required placeholder="Введите ваш ник">
                </div>
            </div>

            <div class="auth-form-row">
                <div>
                    <label for="dob">Дата рождения:</label>
                    <input type="date" name="dob" id="dob" required>
                </div>
            </div>

            <div class="auth-form-row">
                <div>
                    <label for="email">Почта:</label>
                    <input type="email" name="email" id="email" required placeholder="Введите ваш email">
                </div>
            </div>

            <div class="auth-form-row">
                <div>
                    <label for="password">Пароль:</label>
                    <input type="password" name="password" id="password" required placeholder="Введите ваш пароль">
                </div>
            </div>

            <div class="auth-form-row">
                <div>
                    <label for="password_confirmation">Подтверждение пароля:</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Повторите пароль">
                </div>
            </div>

            <button type="submit" class="primary-btn">Зарегистрироваться</button>
        </form>
    </div>
@endsection

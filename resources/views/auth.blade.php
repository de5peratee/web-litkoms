<!-- resources/views/home.blade.php -->
@extends('layouts.app')  <!-- Используем главный шаблон -->

@section('title', 'Регистрация/Вход')  <!-- Устанавливаем название страницы -->

@section('content')
    <div class="auth-container">
        <!-- Табы для переключения между логином и регистрацией -->
        <div class="auth-tabs">
            <button id="loginTab" class="tab active" onclick="showLoginForm()">Войти</button>
            <button id="registerTab" class="tab" onclick="showRegisterForm()">Зарегистрироваться</button>
        </div>

        <!-- Форма логина -->
        <div id="loginForm" class="auth-form">
            <h2>Вход в аккаунт</h2>
            <form method="POST">
                @csrf
                <div class="auth-form-row">
                    <div>
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" required>
                    </div>
                    <div>
                        <label for="password">Пароль:</label>
                        <input type="password" name="password" id="password" required>
                    </div>
                </div>
                <button type="submit">Войти</button>
            </form>
        </div>

        <!-- Форма регистрации -->
        <div id="registerForm" class="auth-form" style="display: none;">
            <h2>Регистрация</h2>
            <form method="POST">
                @csrf
                <div class="auth-form-row">
                    <div>
                        <label for="name">Имя:</label>
                        <input type="text" name="name" id="name" required>
                    </div>
                    <div>
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" required>
                    </div>
                </div>

                <div class="auth-form-row">
                    <div>
                        <label for="password">Пароль:</label>
                        <input type="password" name="password" id="password" required>
                    </div>
                    <div>
                        <label for="password_confirmation">Подтвердите пароль:</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required>
                    </div>
                </div>

                <button type="submit">Зарегистрироваться</button>
            </form>
        </div>
    </div>
@endsection

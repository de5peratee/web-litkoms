<!-- resources/views/home.blade.php -->
@extends('layouts.app')  <!-- Используем главный шаблон -->

@section('title', 'Аутентификация')  <!-- Устанавливаем название страницы -->

@section('content')
    @vite(['resources/css/inputs.css'])
    @vite(['resources/css/auth.css'])
    @vite(['resources/js/auth-tabs.js'])

    <div class="auth-container">

        <img src="{{ asset('images/heroes/whiter.png') }}" alt="hero" class="hero" id="whiter-img">
        <img src="{{ asset('images/heroes/scientist.png') }}" alt="hero" class="hero" id="scientist-img">

        <div class="auth-wrapper">

            <div class="auth-header">
                <div class="auth-icon">
                    <img src="{{ asset('images/icons/hw/login-hw-icon.svg') }}" alt="icon">
                </div>

                <h3>Вход в аккаунт</h3>
                <p class="text-small">Войдите в профиль, чтобы публиковать авторские комиксы,
                    комментировать и оценивать другие и многое другое</p>
            </div>

            <!-- Табы для переключения между логином и регистрацией -->
            <div class="auth-tabs">
                <div id="loginTab" class="tab active text-small">Вход</div>
                <div id="registerTab" class="tab text-small">Регистрация</div>
            </div>

            <form method="POST" action="{{ route('auth.login') }}" id="loginForm" class="lit-form">
                @csrf
                <div class="lit-field">
                    <label for="email">Почта</label>
                    <input type="email" name="email" id="email" placeholder="Введите ваш email" value="{{old('email')}}">
                    @error('email')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="lit-field">
                    <label for="password">Пароль</label>
                    <input type="password" name="password" id="password" placeholder="Введите ваш пароль">
                    @error('password')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="primary-btn">Войти</button>

                <p class="text-hint"> Нажимая на кнопку я даю согласие с <a href="{{ route('manuals.policy') }}" target="_blank" class="link-text">политикой конфиденциальности</a> <br>и
                    <a href="#" target="_blank" class="link-text">правилами сообщества</a>.</p>
            </form>

            <form method="POST" action="{{ route('auth.register') }}" id="registerForm" class="lit-form">
                @csrf
                <div class="lit-form-row">
                    <div class="lit-field">
                        <label for="name">Имя</label>
                        <input type="text" name="name" id="name" placeholder="Введите ваше имя" value="{{old('name')}}">
                        @error('name')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="lit-field">
                        <label for="last_name">Фамилия</label>
                        <input type="text" name="last_name" id="last_name" placeholder="Введите вашу фамилию" value="{{old('last_name')}}">
                        @error('last_name')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="lit-form-row">
                    <div class="lit-field">
                        <label for="nickname">Ник</label>
                        <input type="text" name="nickname" id="nickname" placeholder="Введите ваш ник" value="{{old('nickname')}}">
                        @error('nickname')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="lit-form-row">
                    <div class="lit-field">
                        <label for="birth_date">Дата рождения</label>
                        <input type="date" name="birth_date" id="birth_date" value="{{old('birth_date')}}">
                        @error('birth_date')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="lit-field">
                        <label for="email">Почта</label>
                        <input type="email" name="email" id="email" placeholder="Введите ваш email" value="{{old('email')}}">
                        @error('email')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="lit-form-row">
                    <div class="lit-field">
                        <label for="reg_password">Пароль</label>
                        <input type="password" name="password" id="reg_password" placeholder="Введите ваш пароль">
                        @error('password')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="lit-field">
                        <label for="password_confirmation">Подтверждение пароля</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               placeholder="Повторите пароль">
                    </div>
                </div>
                <div class="lit-field">
                    <label>
                        <input type="checkbox" class="lit-checkbox" name="agree" {{ old('agree') ? 'checked' : '' }}>
                        Я подтверждаю согласие с <a href="{{ route('manuals.policy') }}" target="_blank" class="link-text">политикой конфиденциальности</a> <br>и
                        <a href="#" target="_blank" class="link-text">правилами сообщества</a>.
                    </label>
                    @error('agree')
                    <div class="input-error">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="primary-btn">Зарегистрироваться</button>
            </form>
        </div>

    </div>

@endsection

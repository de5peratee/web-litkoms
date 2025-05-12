@extends('layouts.app')

@section('title', 'Лит-AR')

@section('content')
    @vite(['resources/css/litar.css'])

    <div class="litar-container">
        <div class="hero-block">
            <p>Приложение дополненной реальности</p>
            <h1>Лит–AR приложение доступно прямо сейчас!</h1>
            <p>Попробуйте погрузится и узнать больше о библиотеке с помощью нашего приложения.</p>
            <a href="#" class="primary-btn">Скачать приложение</a>
            <p>платформа Android</p>
        </div>

        <div class="qr-block">
            <div class="qr-header">
                <h3 class="qr-title">Скачайте приложение по <span class="marker">Qr-коду</span></h3>
                <p class="text-medium qr-subtitle">Наведите телефон на код </p>
            </div>

            <div class="cta-qr-wrapper">
                <div class="qr-info">
                    <div class="qr-info-text">
                        <p class="text-big">Для Android</p>
                        <p class="text-small version-text">Android 6.0+</p>
                    </div>

                    <a href="https://disk.yandex.ru/d/-n7EoWQshMwN2g" class="primary-btn">Скачать приложение</a>
                </div>
                <img src="{{ asset('images/lit-ar_qr.svg') }}" alt="ar_code" class="qr-code">
            </div>

        </div>
    </div>
@endsection

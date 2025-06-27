<!DOCTYPE html>
<html lang="ru">
<head>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg">
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Литкомс — первый комикс-центр Севастополя. Комикс-школа, клуб, библиотека. Бесплатные занятия по средам и воскресеньям.')">
    <title>@yield('title', 'Шаблон')</title>

    @vite(['resources/css/app.css'])
    @vite(['resources/css/reset.css'])
    @vite(['resources/css/fonts.css'])
    @vite(['resources/css/colors.css'])
    @vite(['resources/css/icons.css'])
    @vite(['resources/css/buttons.css'])
    @vite(['resources/css/player.css'])

    @vite(['resources/js/player.js'])
    @vite(['resources/js/cookie-consent.js'])

    <script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.4/howler.min.js"></script>
</head>
<body>
<!-- Page transition overlay -->
<div id="page-transition" class="page-transition"></div>

@include('partials.header')

<main>
    @yield('content')

    <!-- Audio Player -->
    <div class="audio-player-container">
        <div class="audio-player collapsed">

            <div class="player-controls">
                <button class="player-btn play-btn" title="Play/Pause">
                    <img src="{{ asset('images/icons/player/player-play-white.svg') }}" alt="Play" class="icon-24 play-icon active">
                    <img src="{{ asset('images/icons/player/player-pause-white.svg') }}" alt="Pause" class="icon-24 pause-icon">
                </button>
            </div>

            <div class="player-info">
                <p class="track-title text-medium">Трек не загружен</p>
                <div class="player-progress-bar">
                    <div class="progress"></div>
                </div>
                <p class="time text-hint">0:00 / 0:00</p>
            </div>
            <div class="volume-control">
                <img src="{{ asset('images/icons/player/volume-up-white.svg') }}" alt="Volume Up" class="icon-24 volume-icon volume-up active">
                <img src="{{ asset('images/icons/player/volume-down-white.svg') }}" alt="Volume Down" class="icon-24 volume-icon volume-down">
                <img src="{{ asset('images/icons/player/volume-off-white.svg') }}" alt="Volume Off" class="icon-24 volume-icon volume-off">
                <input type="range" class="volume-slider" min="0" max="1" step="0.01" value="1">
                <button class="player-btn collapse-btn" title="Свернуть">
                    <img src="{{ asset('images/icons/player/player-shrink-secondary.svg') }}" alt="Shrink" class="icon-24 shrink-icon">
                </button>
            </div>
        </div>
    </div>

    <!-- Web Radio CTA (Klyaksa) -->
    <div class="web-radio-cta" id="floating-blob"> <!-- Убираем hidden -->
        <img src="{{ asset('images/blob.svg') }}" alt="Blob" class="blob-image">
    </div>

    <!-- Cookie Consent Popup -->
    <div id="cookie-consent" class="cookie-consent hidden">
        <p class="text-small">Мы используем cookie (localStorage) для сохранения настроек сайта и аналитики. Продолжая использовать сайт, вы соглашаетесь с <a href="{{ route('manuals.policy') }}" target="_blank">Политикой конфиденциальности</a>.</p>
        <div class="cookie-buttons">
            <button id="accept-cookies" class="primary-btn">Принять</button>
{{--            <button id="reject-cookies" class="secondary-btn">Отклонить</button>--}}
        </div>
    </div>
</main>

@include('partials.footer')

@vite(['resources/js/app.js'])
</body>
</html>

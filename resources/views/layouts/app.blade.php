<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $pageTitle = $__env->yieldContent('title', 'Литкомс — центр гуманитарно-технической информации');
        $pageDescription = $__env->yieldContent('meta_description', 'Литкомс — первый комикс‑центр Севастополя. Комикс‑школа, клуб, библиотека.');
        $pageImage = $__env->yieldContent('meta_image', asset('images/cover.png'));
        $logoImage = $__env->yieldContent('meta_logo', asset('images/logo.svg'));
    @endphp

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type"        content="website">
    <meta property="og:url"         content="{{ url()->current() }}">
    <meta property="og:title"       content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:image"       content="{{ $pageImage }}">
    <meta property="og:logo"        content="{{ $logoImage }}">

    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image"       content="{{ $pageImage }}">

    <meta name="robots" content="max-snippet:160">

    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "Organization",
          "name": "Литкомс",
          "url": "{{ config('app.url') }}",
      "logo": "{{ $logoImage }}"
    }
    </script>

    @vite([
        'resources/css/reset.css',
        'resources/css/fonts.css',
        'resources/css/colors.css',
        'resources/css/icons.css',
        'resources/css/buttons.css',
        'resources/css/player.css',
        'resources/css/app.css',
    ])
    @vite([
        'resources/js/player.js',
        'resources/js/cookie-consent.js',
        'resources/js/app.js',
    ])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.4/howler.min.js" defer></script>
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
                    <img src="{{ asset('images/icons/player/player-play-white.svg') }}"  alt="Play"  class="icon-24 play-icon active">
                    <img src="{{ asset('images/icons/player/player-pause-white.svg') }}" alt="Pause" class="icon-24 pause-icon">
                </button>
            </div>

            <div class="player-info">
                <p class="track-title text-medium">Трек не загружен</p>
                <div class="player-progress-bar"><div class="progress"></div></div>
                <p class="time text-hint">0:00 / 0:00</p>
            </div>

            <div class="volume-control">
                <img src="{{ asset('images/icons/player/volume-up-white.svg') }}"   alt="Volume Up"   class="icon-24 volume-icon volume-up active">
                <img src="{{ asset('images/icons/player/volume-down-white.svg') }}" alt="Volume Down" class="icon-24 volume-icon volume-down">
                <img src="{{ asset('images/icons/player/volume-off-white.svg') }}"  alt="Volume Off"  class="icon-24 volume-icon volume-off">
                <input type="range" class="volume-slider" min="0" max="1" step="0.01" value="1">
                <button class="player-btn collapse-btn" title="Свернуть">
                    <img src="{{ asset('images/icons/player/player-shrink-secondary.svg') }}" alt="Shrink" class="icon-24 shrink-icon">
                </button>
            </div>
        </div>
    </div>

    <!-- Web Radio CTA (Klyaksa) -->
    <div class="web-radio-cta" id="floating-blob">
        <img src="{{ asset('images/blob.svg') }}" alt="Blob" class="blob-image">
    </div>

    <!-- Cookie Consent Popup -->
    <div id="cookie-consent" class="cookie-consent hidden">
        <p class="text-small">Мы используем cookie (localStorage) для сохранения настроек сайта и аналитики. Продолжая использовать сайт, вы соглашаетесь с <a href="{{ route('manuals.policy') }}" target="_blank">Политикой конфиденциальности</a>.</p>
        <div class="cookie-buttons">
            <button id="accept-cookies" class="primary-btn">Принять</button>
        </div>
    </div>
</main>

@include('partials.footer')
</body>
</html>

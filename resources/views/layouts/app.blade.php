<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Шаблон')</title>

        @vite(['resources/css/app.css'])
        @vite(['resources/css/reset.css'])
        @vite(['resources/css/fonts.css'])
        @vite(['resources/css/colors.css'])
        @vite(['resources/css/icons.css'])
        @vite(['resources/css/buttons.css'])
        @vite(['resources/css/player.css'])

        @vite(['resources/js/player.js'])
    {{--        @vite(['resources/js/blob.js'])--}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.4/howler.min.js"></script>
</head>
<body>
<!-- Page transition overlay -->
<div id="page-transition" class="page-transition"></div>

@include('partials.header')

<main>
    @yield('content')

{{--    <div class="web-radio-cta" id="floating-blob">--}}
{{--        <img src="{{ asset('images/blob.svg') }}" alt="icon">--}}
{{--    </div>--}}

    <!-- Audio Player -->
    <!-- Audio Player -->
    <div class="audio-player">
        <div class="player-controls">
            <button class="player-btn prev-btn" title="Previous Track">
                <img src="{{ asset('images/icons/player/player-start-white.svg') }}" alt="Previous" class="icon-24">
            </button>
            <button class="player-btn play-btn" title="Play/Pause">
                <img src="{{ asset('images/icons/player/player-play-white.svg') }}" alt="Play" class="icon-24 play-icon">
                <img src="{{ asset('images/icons/player/player-pause-white.svg') }}" alt="Pause" class="icon-24 pause-icon">
            </button>
            <button class="player-btn next-btn" title="Next Track">
                <img src="{{ asset('images/icons/player/player-end-white.svg') }}" alt="Next" class="icon-24">
            </button>
            <button class="player-btn loop-btn" title="Toggle Loop">
                <img src="{{ asset('images/icons/player/player-repeat-list-white.svg') }}" alt="Loop" class="icon-24">
            </button>
        </div>
        <div class="player-info">
            <p class="track-title text-medium">Трек не загружен</p>
            <div class="progress-bar">
                <div class="progress"></div>
            </div>
            <p class="time text-hint">0:00 / 0:00</p>
        </div>
        <div class="volume-control">
            <img src="{{ asset('images/icons/player/volume-up-white.svg') }}" alt="Volume Up" class="icon-24 volume-icon volume-up">
            <img src="{{ asset('images/icons/player/volume-down-white.svg') }}" alt="Volume Down" class="icon-24 volume-icon volume-down">
            <img src="{{ asset('images/icons/player/volume-off-white.svg') }}" alt="Volume Off" class="icon-24 volume-icon volume-off">
            <input type="range" class="volume-slider" min="0" max="1" step="0.01" value="1">
        </div>
    </div>

</main>


@include('partials.footer')

@vite(['resources/js/app.js'])
</body>
</html>

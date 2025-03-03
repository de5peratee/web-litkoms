<!-- resources/views/partials/header.blade.php -->
<header>
    <nav class="header">

        <div class="logo">
            <img src="{{ asset('images/logo.svg') }}" alt="logo">
        </div>

        <ul class="nav-menu">
            <li><a href="{{ route('home') }}" class="nav-link {{ Route::currentRouteName() == 'home' ? 'active' : '' }}">Главная</a></li>
            <li><a href="{{ route('library') }}" class="nav-link {{ Route::currentRouteName() == 'library' ? 'active' : '' }}">Библиотека</a></li>
            <li><a href="{{ route('news') }}" class="nav-link {{ Route::currentRouteName() == 'news' ? 'active' : '' }}">Лента</a></li>
            <li><a href="{{ route('events') }}" class="nav-link {{ Route::currentRouteName() == 'events' ? 'active' : '' }}">Мероприятия</a></li>
            <li><a href="{{ route('authors_comics_landing') }}" class="nav-link {{ Route::currentRouteName() == 'authors_comics_landing' ? 'active' : '' }}">Авторские комиксы</a></li>
            <li><a href="{{ route('litar_landing') }}" class="nav-link {{ Route::currentRouteName() == 'litar_landing' ? 'active' : '' }}">Лит-AR</a></li>
        </ul>

{{--        @guest--}}
{{--            <a href="{{route('auth')}}" class="primary-btn">--}}
{{--                Войти--}}
{{--                <img src="{{asset('../images/icons/login_icon.svg')}}" alt="icon">--}}
{{--            </a>--}}
{{--        @endguest--}}

{{--        @auth--}}
            <div class="profile-bar">
                <div class="icon-wrapper">
                    <img src="{{ asset('images/icons/bell.svg') }}" alt="Icon">
                </div>

                <div class="avatar">
                    <img src="{{ asset('images/nigga.png') }}" alt="Img">
                </div>

                <div class="profile-info">

                    <p class="text-small">Владислав М.</p>

                    <div class="icon-wrapper">
                        <img src="{{ asset('images/icons/arrow-down.svg') }}" alt="Icon">
                    </div>

                </div>

            </div>
{{--        @endauth--}}

    </nav>
</header>

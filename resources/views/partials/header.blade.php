<!-- resources/views/partials/header.blade.php -->
@vite(['resources/css/buttons.css'])
@vite(['resources/css/header.css'])

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


        <div class="account-bar">

            @guest
                <a href="{{route('auth.index')}}" class="primary-btn">
                    Войти
                    <img src="{{asset('../images/icons/login_icon.svg')}}" alt="icon">
                </a>
            @endguest

            @auth
                <div class="icon-wrapper">
                    <img src="{{ asset('images/icons/bell.svg') }}" alt="Icon">
                </div>

                <div class="avatar">
                    <img src="{{ asset('images/nigga.png') }}" alt="Img">
                </div>

                <div class="profile-info">

                    <p class="text-small">
                        {{ Auth::user()->name }} {{ mb_substr(Auth::user()->last_name, 0, 1) }}.
                    </p>

                    <div class="icon-wrapper">
                        <img src="{{ asset('images/icons/arrow-down.svg') }}" alt="Icon">
                    </div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button style="cursor: pointer" type="submit">Выйти</button>
                    </form>

                </div>
            @endauth
        </div>

    </nav>
</header>

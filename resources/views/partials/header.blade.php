<!-- resources/views/partials/header.blade.php -->
<header>
    <nav class="header">

        <div class="logo">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo">
        </div>

        <ul class="nav-menu">
            <li><a href="{{ route('home') }}" class="nav-link {{ Route::currentRouteName() == 'home' ? 'active' : '' }}">Главная</a></li>
            <li><a href="{{ route('publications') }}" class="nav-link {{ Route::currentRouteName() == 'publications' ? 'active' : '' }}">Библиотека</a></li>
            <li><a href="{{ route('news') }}" class="nav-link {{ Route::currentRouteName() == 'news' ? 'active' : '' }}">Лента</a></li>
            <li><a href="{{ route('events') }}" class="nav-link {{ Route::currentRouteName() == 'events' ? 'active' : '' }}">Мероприятия</a></li>
            <li><a href="{{ route('authors_comics_landing') }}" class="nav-link {{ Route::currentRouteName() == 'authors_comics_landing' ? 'active' : '' }}">Авторские комиксы</a></li>
            <li><a href="{{ route('litar_landing') }}" class="nav-link {{ Route::currentRouteName() == 'litar_landing' ? 'active' : '' }}">Лит-AR</a></li>
        </ul>

        <div class="profile-bar">

            <div class="avatar">
                <div class="notify-count">
                    <span>9+</span>
                </div>

                <img src="{{ asset('images/nigga.png') }}" alt="Img">
            </div>

            <div class="profile-info">
                <p class="text-small">Имя пользователя</p>
                <div class="whistleblower">
                    <img src="{{ asset('images/icons/arrow-down.svg') }}" alt="Icon">
                </div>
            </div>

        </div>

    </nav>
</header>

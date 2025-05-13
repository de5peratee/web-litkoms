<!-- resources/views/partials/header.blade.php -->
@vite(['resources/css/header.css',
'resources/js/profile-dropdown.js',
'resources/js/mobile-menu.js',
'resources/js/sticky-header.js'])

<header>
    <div class="logo-wrapper">
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/logo.svg') }}" alt="logo" class="logo">
        </a>
    </div>

    <nav>
        <ul class="nav-menu">
            <li><a href="{{ route('home') }}" class="nav-link {{ Route::currentRouteName() == 'home' ? 'active-link' : '' }}">Главная</a></li>
            <li><a href="{{ route('library.index') }}" class="nav-link {{ Route::currentRouteName() == 'library.index' ? 'active-link' : '' }}">Библиотека</a></li>
            <li><a href="{{ route('news') }}" class="nav-link {{ Route::currentRouteName() == 'news' ? 'active-link' : '' }}">Лента</a></li>
            <li><a href="{{ route('events.index') }}" class="nav-link {{ Route::currentRouteName() == 'events.index' ? 'active-link' : '' }}">Мероприятия</a></li>
            <li><a href="{{ route('authors_comics_landing') }}" class="nav-link {{ Route::currentRouteName() == 'authors_comics_landing' ? 'active-link' : '' }}">Авторские комиксы</a></li>
            <li><a href="{{ route('litar_landing') }}" class="nav-link {{ Route::currentRouteName() == 'litar_landing' ? 'active-link' : '' }}">Лит-AR</a></li>
        </ul>
    </nav>

    <div class="account-bar">
        @guest
            <a href="{{route('auth')}}" class="primary-btn">
                Войти
                <img src="{{asset('../images/icons/login_icon.svg')}}" alt="icon">
            </a>
        @endguest

        @auth
            <div class="icon-wrapper" id="notify-trigger">
                <img src="{{ asset('images/icons/bell.svg') }}" alt="icon">
            </div>

            <div class="profile-container">
                <a href="{{route('profile.index', Auth::user()->nickname)}}" class="profile-info">
                    @if(Auth::user()->icon && Storage::exists(Auth::user()->icon))
                        <div class="avatar-wrapper">
                            <img src="{{ Storage::url(Auth::user()->icon) }}" alt="{{ Auth::user()->icon }}">
                        </div>
                    @else
                        <div class="profile-avatar-wrapper">
                            <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="ava_cover">
                        </div>
                    @endif

                    <p class="text-small">
                        {{ Auth::user()->name }} {{ mb_substr(Auth::user()->last_name, 0, 1) }}.
                    </p>
                </a>

                <div class="profile-dropdown" id="profileDropdown">
                    <div class="dropdown-content">
                        <a href="{{ route('profile.index', Auth::user()->nickname)}}" class="dropdown-item">
                            <img src="{{ asset('images/icons/user-profile.svg') }}" alt="icon" class="icon-24">
                            <p>Профиль</p>
                        </a>

                        <a href="{{ route('editor.dashboard')}}" class="dropdown-item">
                            <img src="{{ asset('images/icons/dashboard.svg') }}" alt="icon" class="icon-24">
                            <p>Панель редактора</p>
                        </a>

                        <div class="dropdown-divider"></div>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item logout-btn">
                                <img src="{{ asset('images/icons/exit.svg') }}" alt="icon" class="icon-24">
                                Выйти
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endauth

        <div class="icon-wrapper" id="menu-trigger">
            <img src="{{ asset('images/icons/burger.svg') }}" alt="icon">
        </div>
    </div>

    <!-- Мобильное меню -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu__header">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo.svg') }}" alt="logo" class="logo">
            </a>

            <div class="icon-wrapper" id="mobile-menu-close">
                <img src="{{ asset('images/icons/close-primary.svg') }}" alt="icon">
            </div>

        </div>
        <nav class="mobile-menu__content">
            <ul>
                <li><a href="{{ route('home') }}" class="{{ Route::currentRouteName() == 'home' ? 'active-link' : '' }}">Главная</a></li>
                <li><a href="{{ route('library.index') }}" class="{{ Route::currentRouteName() == 'library.index' ? 'active-link' : '' }}">Библиотека</a></li>
                <li><a href="{{ route('news') }}" class="{{ Route::currentRouteName() == 'news' ? 'active-link' : '' }}">Лента</a></li>
                <li><a href="{{ route('events.index') }}" class="{{ Route::currentRouteName() == 'events.index' ? 'active-link' : '' }}">Мероприятия</a></li>
                <li><a href="{{ route('authors_comics_landing') }}" class="{{ Route::currentRouteName() == 'authors_comics_landing' ? 'active-link' : '' }}">Авторские комиксы</a></li>
                <li><a href="{{ route('litar_landing') }}" class="{{ Route::currentRouteName() == 'litar_landing' ? 'active-link' : '' }}">Лит-AR</a></li>
            </ul>

            <div class="h-divider"></div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item logout-btn">
                    <img src="{{ asset('images/icons/exit.svg') }}" alt="icon" class="icon-24">
                    Выйти
                </button>
            </form>

        </nav>
    </div>

</header>

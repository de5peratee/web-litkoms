<!-- resources/views/partials/header.blade.php -->
@vite(['resources/css/header.css','resources/js/profile-dropdown.js'])

<header>
    <div class="logo">
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/logo.svg') }}" alt="logo">
        </a>
    </div>

    <nav>
        <ul class="nav-menu">
            <li><a href="{{ route('home') }}" class="nav-link {{ Route::currentRouteName() == 'home' ? 'active' : '' }}">Главная</a></li>
            <li><a href="{{ route('library.index') }}" class="nav-link {{ Route::currentRouteName() == 'library.index' ? 'active' : '' }}">Библиотека</a></li>
            <li><a href="{{ route('news') }}" class="nav-link {{ Route::currentRouteName() == 'news' ? 'active' : '' }}">Лента</a></li>
            <li><a href="{{ route('events.index') }}" class="nav-link {{ Route::currentRouteName() == 'events.index' ? 'active' : '' }}">Мероприятия</a></li>
            <li><a href="{{ route('authors_comics_landing') }}" class="nav-link {{ Route::currentRouteName() == 'authors_comics_landing' ? 'active' : '' }}">Авторские комиксы</a></li>
            <li><a href="{{ route('litar_landing') }}" class="nav-link {{ Route::currentRouteName() == 'litar_landing' ? 'active' : '' }}">Лит-AR</a></li>
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

            <a href="{{route('profile.index', Auth::user()->nickname)}}" class="profile-info">
                @if(Auth::user()->icon && Storage::exists(Auth::user()->icon))
                    <div class="avatar-wrapper">
                        <img src="{{ Storage::url(Auth::user()->icon) }}" alt="{{ Auth::user()->icon }}">
                    </div>
                @else
                    <div class="avatar-wrapper">
                        <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="ava_cover">
                    </div>
                @endif

                <p class="text-small">
                    {{ Auth::user()->name }} {{ mb_substr(Auth::user()->last_name, 0, 1) }}.
                </p>

{{--                <img src="{{ asset('images/icons/arrow-down.svg') }}" alt="Icon" class="icon-24">--}}
            </a>

            <div class="icon-wrapper" id="menu-trigger">
                <img src="{{ asset('images/icons/burger.svg') }}" alt="icon">
            </div>

            <div class="profile-dropdown" id="profileDropdown">
                <div class="dropdown-content">
                    <a href="{{ route('profile.index', Auth::user()->nickname)}}" class="dropdown-item">
                        {{--                                <img src="" alt="Профиль">--}}
                        <p>Профиль</p>
                    </a>

                    <a href="{{ route('editor.dashboard')}}" class="dropdown-item">
                        {{--                                <img src="" alt="Настройки">--}}
                        <p>Панель редактора</p>
                    </a>

                    <div class="dropdown-divider"></div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        {{--                                <img src="" alt="Выход">--}}
                        <button type="submit" class="dropdown-item logout-btn">Выйти</button>
                    </form>
                </div>
            </div>
        @endauth
    </div>

</header>

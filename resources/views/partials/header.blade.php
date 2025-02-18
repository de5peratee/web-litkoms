<!-- resources/views/partials/header.blade.php -->
<header>
    <nav class="header">

        <div class="logo">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo">
        </div>

        <ul class="nav-menu">
            <li><a href="{{ route('home') }}" class="nav-link {{ Route::currentRouteName() == 'home' ? 'active' : '' }}">Главная</a></li>
            <li><a href="{{ route('publications') }}" class="nav-link {{ Route::currentRouteName() == 'publications' ? 'active' : '' }}">Каталог</a></li>
            <li><a href="/news" class="nav-link">Лента</a></li>
            <li><a href="/events" class="nav-link">Мероприятия</a></li>
            <li><a href="/ak" class="nav-link">Авторские комиксы</a></li>
            <li><a href="/lit-ar" class="nav-link">Лит-AR</a></li>
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

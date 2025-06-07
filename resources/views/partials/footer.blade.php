<!-- resources/views/partials/footer.blade.php -->
@vite(['resources/css/footer.css'])

<footer>
    <div class="litcoms-info">
        <p class="text-big">litcoms@mail.ru</p>
        <p class="text-big">+7 (8692) 57-21-91</p>
    </div>

    <ul class="footer-nav-menu">
        <li><a href="{{ route('home') }}" class="nav-link">Главная</a></li>
        <li><a href="{{ route('library.index') }}" class="nav-link">Библиотека</a></li>
        <li><a href="{{ route('mediaposts') }}" class="nav-link">Лента</a></li>
        <li><a href="{{ route('events.index') }}" class="nav-link">Мероприятия</a></li>
        <li><a href="{{ route('authors_comics_landing') }}" class="nav-link">Авторские комиксы</a></li>
        <li><a href="{{ route('litar_landing') }}" class="nav-link ">Лит-AR</a></li>
    </ul>

    <p class="location-text">г. Севастополь, ул. Бирюзова, д. 9 Библиотека-филиал №5 "Центр гуманитарно-технической информации"</p>

    <div class="socials-block">
        <div class="media-link">
            Вконтакте
        </div>
        <div class="media-link">
            Телеграмм
        </div>
    </div>

    <div class="policy_desc">
        <span>&copy; 2025 Все права защищены</span>
        <span>Политика конфиденциальности</span>
        <span>Правила сообщества</span>
    </div>

</footer>

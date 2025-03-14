<!-- resources/views/partials/footer.blade.php -->
@vite(['resources/css/footer.css'])

<footer>
    <div class="litcoms-info">
        <p class="text-big">litcoms@mail.ru</p>
        <p class="text-big">+7(999) 999-99-99 </p>
    </div>

    <ul class="footer-nav-menu">
        <li><a href="{{ route('home') }}" class="nav-link">Главная</a></li>
        <li><a href="{{ route('library') }}" class="nav-link">Библиотека</a></li>
        <li><a href="{{ route('news') }}" class="nav-link">Лента</a></li>
        <li><a href="{{ route('events') }}" class="nav-link">Мероприятия</a></li>
        <li><a href="{{ route('authors_comics_landing') }}" class="nav-link">Авторские комиксы</a></li>
        <li><a href="{{ route('litar_landing') }}" class="nav-link ">Лит-AR</a></li>
    </ul>

    <div class="policy_desc">
        <span>&copy; 2025 Все права защищены</span>
        <span>Политика конфиденциальности</span>
        <span>Условия использования контента</span>
    </div>

</footer>

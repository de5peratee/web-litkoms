<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Главная')</title>
        @vite(['resources/css/reset.css'])
        @vite(['resources/css/fonts.css'])
        @vite(['resources/css/colors.css'])
        @vite(['resources/css/app.css'])

        @vite(['resources/js/auth-tabs.js'])
</head>
<body>
<!-- Включаем шапку -->
@include('partials.header')

<!-- Место для контента конкретной страницы -->
<main>
    @yield('content')
</main>

<!-- Включаем футер -->
@include('partials.footer')

{{--<script src="{{ mix('js/app.js') }}"></script>--}}
</body>
</html>

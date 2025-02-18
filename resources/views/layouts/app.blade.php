<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Главная')</title>
    <link href="{{ asset('css/reset.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/fonts.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/colors.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/header.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/library.css') }}?v={{ time() }}" rel="stylesheet">
    {{--    <link rel="stylesheet" href="{{ mix('css/app.css') }}">--}}
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

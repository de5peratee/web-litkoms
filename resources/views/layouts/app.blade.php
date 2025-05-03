<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Главная')</title>
        @vite(['resources/css/app.css'])
        @vite(['resources/css/reset.css'])
        @vite(['resources/css/fonts.css'])
        @vite(['resources/css/colors.css'])
        @vite(['resources/css/icons.css'])
        @vite(['resources/css/buttons.css'])

</head>
<body>

@include('partials.header')

<main>
    @yield('content')
</main>

@include('partials.footer')

@vite(['resources/js/app.js'])
</body>
</html>

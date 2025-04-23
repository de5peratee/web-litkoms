<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<div class="book-details">
    <h1>{{ $catalog->name }}</h1>
    <p><strong>Автор:</strong> {{ $catalog->author }}</p>
    <p><strong>Описание:</strong> {{ $catalog->description }}</p>
    <p><strong>Год выпуска:</strong> {{ $catalog->release_year }}</p>
    <p><strong>Жанры:</strong> {{ $catalog->genres->pluck('name')->join(', ') }}</p>
    <a href="{{ route('catalog.index') }}">Назад к списку</a>
</div>
</body>
</html>
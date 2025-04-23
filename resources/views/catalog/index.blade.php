<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        .catalog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card h2 {
            font-size: 1.2em;
            margin-bottom: 10px;
        }

        .card p {
            margin: 4px 0;
            color: #555;
        }

        .card a {
            margin-top: 15px;
            text-decoration: none;
            background: #007BFF;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            display: inline-block;
            text-align: center;
        }

        .card a:hover {
            background: #0056b3;
        }

        .pagination {
            margin-top: 40px;
            text-align: center;
        }

        .pagination .page-link {
            display: inline-block;
            margin: 0 5px;
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            color: #007BFF;
            text-decoration: none;
        }

        .pagination .page-link:hover {
            background-color: #007BFF;
            color: white;
        }

        .pagination .active {
            background-color: #007BFF;
            color: white;
            pointer-events: none;
        }
    </style>
</head>
<body>

<div class="catalog-grid">
    @foreach($catalogs as $catalog)
        <div class="card">
            <div>
                <h2>{{ $catalog->name }}</h2>
                <p><strong>Автор:</strong> {{ $catalog->author }}</p>
                <p><strong>Год выпуска:</strong> {{ $catalog->release_year }}</p>
                <p><strong>Жанры:</strong> {{ $catalog->genres->pluck('name')->join(', ') }}</p>
            </div>
            <a href="{{ route('catalog.show', $catalog->id) }}">Подробнее</a>
        </div>
    @endforeach
</div>

<div class="pagination">
    {{ $catalogs->links() }}
</div>

</body>
</html>

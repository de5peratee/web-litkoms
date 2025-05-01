<!-- resources/views/home.blade.php -->
@extends('layouts.app')  <!-- Используем главный шаблон -->

@section('title', 'Библиотека Литкомс')  <!-- Устанавливаем название страницы -->

@section('content')
    @vite(['resources/css/library.css'])

    <img src="{{ asset('images/icons/hw/library.svg') }}" alt="Catalog Icon">

    <h2>Библиотека Литкомс</h2>

    <div class="library-grid">
        @foreach($library as $book)
            <div class="book">
                <div>
                    <h3>{{ $book->name }}</h3>
                    <p>Автор: {{ $book->author }}</p>
                    <p>Год выпуска: {{ $book->release_year }}</p>
                    <p>Жанры: {{ $book->genres}}</p>
                </div>
                <a href="{{ route('library.get_book', $book->id) }}">Подробнее</a>
            </div>
        @endforeach
    </div>

    <div class="pagination">
        {{ $library->links() }}
    </div>


@endsection

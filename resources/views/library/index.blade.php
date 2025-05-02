@extends('layouts.app')

@section('title', 'Библиотека Литкомс')

@section('content')
    @vite(['resources/css/library.css', 'resources/js/library.js'])

    <img src="{{ asset('images/icons/hw/library.svg') }}" alt="Catalog Icon">

    <h2>Библиотека</h2>

    <div class="search-container">
        <form id="search-form" action="{{ route('library.index') }}" method="GET">
            <input type="text" name="search" placeholder="Поиск по названию или автору" value="{{ request('search') }}">
            <button type="submit">Найти</button>
        </form>
    </div>

    <div class="library-grid">
        @include('partials.books', ['library' => $library])
    </div>

    @if($library->hasMorePages())
        <div class="load-more-container">
            <button id="load-more" class="load-more-btn"
                    data-page="2"
                    data-search="{{ request('search') }}">
                Посмотреть еще
            </button>
        </div>
    @endif

@endsection


{{--@extends('layouts.app')  <!-- Используем главный шаблон -->--}}

{{--@section('title', 'Библиотека Литкомс')  <!-- Устанавливаем название страницы -->--}}

{{--@section('content')--}}
{{--    @vite(['resources/css/library.css'])--}}

{{--    <img src="{{ asset('images/icons/hw/library.svg') }}" alt="Catalog Icon">--}}

{{--    <h2>Библиотека</h2>--}}

{{--    <div class="library-grid">--}}
{{--        @foreach($library as $book)--}}
{{--            <a href="{{ route('library.get_book', $book->id) }}" class="book">--}}

{{--                @if($book->cover && Storage::exists($book->cover))--}}
{{--                    <div class="cover_wrapper">--}}
{{--                        <img src="{{ Storage::url($book->cover) }}" alt="{{ $book->name }}">--}}
{{--                    </div>--}}
{{--                @else--}}
{{--                    <div class="cover_wrapper">--}}
{{--                        <img src="{{ asset('images/default_template/comics.svg') }}" alt="comics_cover">--}}
{{--                    </div>--}}
{{--                @endif--}}

{{--                <div class="description">--}}
{{--                    <p class="text-big">{{ $book->name }}</p>--}}
{{--                    <p>Автор: {{ $book->author }}</p>--}}
{{--                    <p>Год выпуска: {{ $book->release_year }}</p>--}}
{{--                    <p>Жанры: {{ $book->genres->pluck('name')->join(', ') }}</p>--}}
{{--                </div>--}}

{{--            </a>--}}
{{--        @endforeach--}}
{{--    </div>--}}

{{--    <div class="pagination">--}}
{{--        {{ $library->links() }}--}}
{{--    </div>--}}


{{--@endsection--}}

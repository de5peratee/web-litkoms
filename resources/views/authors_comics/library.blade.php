@extends('layouts.app')

@section('title', 'Библиотека авторских комиксов')

@section('content')
    @vite(['resources/css/authors-comics-library.css'])

    <div class="library-container">
        <h1>Авторские комиксы</h1>

        <form method="GET" action="{{ route('authors_comics_library') }}" class="search-form">
            @csrf
{{--            Поиск--}}
{{--            <button type="submit" class="primary-btn">Найти</button>--}}
        </form>

        <div class="comics-grid">
            @forelse ($comics as $comic)
                <div class="comic-card">
                    <a href="{{ route('author_comic', $comic->slug) }}">
                        <img src="{{ $comic->cover ? Storage::url($comic->cover) : asset('images/default_template/comics.svg') }}"
                             alt="{{ $comic->name }}" class="comic-cover">
                        <h3>{{ $comic->name }}</h3>
                    </a>
                    <div class="genres">
                        @foreach ($comic->genres as $genre)
                            <span class="genre-tag">{{ $genre->name }}</span>
                        @endforeach
                    </div>
                    <p>Автор: {{ $comic->createdBy->nickname }}</p>
                    <p>Рейтинг: {{ number_format($comic->average_assessment ?? 0, 1) }}</p>
                    <p>Просмотры: {{ $comic->views }}</p>
                </div>
            @empty
                <p class="no-results">Комиксы не найдены.</p>
            @endforelse
        </div>

        {{ $comics->appends(request()->query())->links() }}
    </div>
@endsection
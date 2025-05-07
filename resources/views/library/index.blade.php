@extends('layouts.app')

@section('title', 'Библиотека')

@section('content')
    @vite(['resources/css/library.css', 'resources/js/loadBooks.js', 'resources/js/filterBooks.js'])

    <style>
        .filter-option input[type="checkbox"]:checked::after {
            content: "";
            background-image: url('{{ asset('images/icons/check-white.svg') }}');
            background-size: contain;
            background-repeat: no-repeat;
            position: absolute;
            top: 50%;
            left: 50%;
            width: 16px;
            height: 16px;
            transform: translate(-50%, -50%);
        }
    </style>

    <div class="library-container">

        <div class="library-header">
            <img src="{{ asset('images/icons/hw/library.svg') }}" class="icon-48" alt="icon">

            <h2>Библиотека</h2>

            <div class="search-container">

                <form id="search-form" action="{{ route('library.index') }}" method="GET">
                    <input type="text" name="search" placeholder="Что желаете найти..." value="{{ request('search') }}">
                    <button type="submit" class="primary-btn">Найти</button>
                </form>

                <div class="library-type-filter">
                    <div class="filter-option">
                        <input type="checkbox" id="filter-all" name="type" value="all" checked>
                        <label for="filter-all" class="text-small">Все</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="filter-books" name="type" value="books">
                        <label for="filter-books" class="text-small">Книги</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="filter-comics" name="type" value="comics">
                        <label for="filter-comics" class="text-small">Комиксы</label>
                    </div>
                </div>

                <div class="library-categories-wrapper">
                    <button class="scroll-btn scroll-left">
                        <img src="{{ asset('images/icons/arrow-left.svg') }}" alt="Назад" class="icon-24">
                    </button>

                    <div class="library-categories">
                        <div class="library-category-tag active" data-category="all">Все</div>
                        @foreach($genres as $genre)
                            <div class="library-category-tag" data-category="{{ $genre->id }}">{{ $genre->name }}</div>
                        @endforeach
                    </div>

                    <button class="scroll-btn scroll-right">
                        <img src="{{ asset('images/icons/arrow-right.svg') }}" alt="Вперед" class="icon-24">
                    </button>
                </div>

            </div>

        </div>

        <div class="library-grid">
            @include('partials.books', ['library' => $library])
        </div>

        @if($library->hasMorePages())
            <div class="load-more-container">
                <button id="load-more" class="primary-btn"
                        data-page="2"
                        data-search="{{ request('search') }}">
                    Загрузить еще
                </button>
            </div>
        @endif
    </div>


@endsection

@extends('layouts.app')

@section('title', 'Библиотека')

@section('content')
    @vite(['resources/css/library.css', 'resources/js/loadBooks.js', 'resources/js/filterBooks.js'])

    <div class="library-container">

        <div class="library-header">
            <img src="{{ asset('images/icons/hw/library.svg') }}" class="icon-48" alt="icon">
            <h2>Библиотека</h2>

            <div class="search-container">
                <form id="search-form" action="{{ route('library.index') }}" method="GET">
                    <div class="search-input-wrapper">
                        <input type="text" name="search" placeholder="Что желаете найти..." value="{{ request('search') }}">
                        <button type="submit" class="primary-btn">
                            Найти
                        </button>
                    </div>

                    <div class="h-divider"></div>

                    <div class="library-genres-input-container">
                        <p class="text-small genres-title-text">Жанры:</p>

                        <div class="genre-slider">
                            <button type="button" class="slider-btn prev">‹</button>
                            <div class="genre-tags-wrapper" id="genre-tags"></div>
                            <button type="button" class="slider-btn next">›</button>
                        </div>

                        <div class="genre-input-wrapper hidden" id="genre-input-wrapper">
                            <input type="text" id="genre-input" placeholder="Введите жанр...">
                        </div>

                        <button type="button" id="toggle-genre-input" class="genre-toggle-btn">
                            <img src="{{ asset('images/icons/search-add.svg') }}" alt="icon" class="icon-24">
                        </button>
                    </div>

                    <div id="genre-hidden-inputs"></div>
                </form>
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

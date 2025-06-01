@extends('layouts.app')

@section('title', 'Библиотека авторских комиксов')

@section('content')
    @vite(['resources/css/authors-comics-library.css'])
    @vite(['resources/js/authors-comics-library.js'])

    <div class="authors-comics-container">
        <div class="authors-comics-header">
            <img src="{{ asset('images/ak-picture1.svg') }}" class="icon-128" alt="image" id="ak-picture1">
            <img src="{{ asset('images/ak-picture2.svg') }}" class="icon-128" alt="image" id="ak-picture2">

            <div class="authors-comics-header-title">
                <img src="{{ asset('images/icons/hw/draw-pencil.svg') }}" class="icon-48" alt="icon">
                <h2>Авторские комиксы</h2>
            </div>

            <div class="search-container">
                <form id="search-form" action="{{ route('authors_comics_library') }}" method="GET" class="search-form">
                    <div class="search-input-wrapper">
                        <input type="text" name="search" placeholder="Что желаете найти..." value="{{ request('search') }}">
                        <div class="clear-search hidden">
                            <img src="{{ asset('images/icons/close-primary.svg') }}" class="icon-20" alt="clear">
                        </div>
                    </div>

                    <button type="submit" class="primary-btn">Найти</button>

                    <div class="filter-btn" id="filter-btn">
                        <img src="{{ asset('images/icons/filter.svg') }}" class="icon-20" alt="icon">
                        <p class="text-hint filter-count hidden" id="filter-count">0</p>
                    </div>
                </form>
            </div>
        </div>

        <div class="comics-grid">
            @include('partials.authors_comics', ['comics' => $comics])
        </div>

        @if($comics->hasMorePages())
            <div class="load-more-container">
                <button id="load-more" class="primary-btn"
                        data-page="2"
                        data-search="{{ request('search') ?? '' }}"
                        data-genres="{{ htmlspecialchars(json_encode(request('genres', [])), ENT_QUOTES, 'UTF-8') }}"
                        data-sort="{{ request('sort', 'desc') }}">
                    Загрузить еще
                </button>
            </div>
        @endif
    </div>

    <div class="modal hidden" id="filter-modal">
        <div class="modal-content">
            <div class="modal-close" id="modal-close">
                <img src="{{ asset('images/icons/close-primary.svg') }}" class="icon-24" alt="close">
            </div>

            <h3>Фильтры</h3>
            <div class="h-divider"></div>

            <div class="modal-section">
                <p class="text-hint">Жанры</p>
                <input type="text" id="genre-search" placeholder="Введите жанр...">
                <datalist id="genre-options">
                    @foreach ($genres as $genre)
                        <option value="{{ $genre->name }}">
                    @endforeach
                </datalist>
                <div class="selected-genres-wrapper">
                    @foreach (request('genres', []) as $genre)
                        <div class="selected-genre-tag" data-genre="{{ $genre }}">
                            {{ $genre }} <span class="remove-genre">×</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="modal-section">
                <p class="text-hint">Сортировка</p>
                <select id="sort-select">
                    <option value="date-desc" {{ request('sort', 'date-desc') == 'date-desc' ? 'selected' : '' }}>Новые сначала</option>
                    <option value="date-asc" {{ request('sort') == 'date-asc' ? 'selected' : '' }}>Старые сначала</option>
                    <option value="rating-desc" {{ request('sort') == 'rating-desc' ? 'selected' : '' }}>Высокий рейтинг</option>
                    <option value="rating-asc" {{ request('sort') == 'rating-asc' ? 'selected' : '' }}>Низкий рейтинг</option>
                </select>
            </div>

            <div class="modal-actions">
                <button class="secondary-btn" id="cancel-filter">Отмена</button>
                <button class="primary-btn" id="save-filter">
                    Применить <span id="filter-count-modal" class="hidden">0</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        window.allGenres = @json($genres->pluck('name'));
    </script>
@endsection

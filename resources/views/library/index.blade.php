@extends('layouts.app')

@section('title', 'Библиотека')

@section('content')
    @vite(['resources/css/library.css', 'resources/js/library.js'])

    <div class="library-container">
        <div class="library-header">
            <div class="library-header-title">
                <img src="{{ asset('images/icons/hw/library.svg') }}" class="icon-48" alt="icon">
                <h2>Библиотека комиксов</h2>
            </div>

            <div class="search-container">
                <form id="search-form" action="{{ route('library.index') }}" method="GET" class="search-form">

                    <div class="search-input-wrapper">
                        <input type="text" name="search" placeholder="Что желаете найти..." value="{{ request('search') }}">
                        <div class="clear-search hidden">
                            <img src="{{ asset('images/icons/close-primary.svg') }}" class="icon-20" alt="clear">
                        </div>
                    </div>

                    <button type="submit" class="primary-btn">Наити</button>

                    <div class="filter-btn" id="filter-btn">
                        <img src="{{ asset('images/icons/filter.svg') }}" class="icon-20" alt="icon">
                        <p class="text-hint filter-count hidden" id="filter-count">0</p>
                    </div>
                </form>
            </div>
        </div>

        <div class="library-grid">
            @include('partials.comics', ['comics' => $library])
        </div>

        @if($library->hasMorePages())
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
                            {{ $genre }} <span class="remove-genre">&times;</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="modal-section">
                <p class="text-hint">Сортировка по дате</p>
                <select id="sort-select">
                    <option value="desc" {{ request('sort', 'desc') == 'desc' ? 'selected' : '' }}>Новые сначала </option>
                    <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Старые сначала</option>
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

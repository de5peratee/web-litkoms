@extends('layouts.app')

@section('title', 'Мероприятия')

@section('content')
    @vite(['resources/css/events.css'])
    @vite(['resources/js/events.js'])
    @vite(['resources/js/swiper-slider.js'])

    <div class="events-container">
        <div class="events-slider-container">
            <div class="slider">
                @foreach($upcomingEvents as $event)
                    <div class="slide" style="background-image: url('{{ $event->cover ? Storage::url('/' . $event->cover) : asset('images/default_template/event-cover.svg') }}');">
                        <div class="slide-content">
                            <div class="slide-event-authors">
                                <p>{{ implode(' · ', $event->guests->pluck('name')->toArray()) }}</p>
                            </div>

                            <div class="slide-center-data">
                                <h1>{{ $event->name }}</h1>

                                <div class="slide-event-tags-wrapper">
                                    @foreach ($event->tags as $tag)
                                        <div class="slide-event-tag">{{ $tag->name }}</div>
                                    @endforeach
                                </div>

                                <div class="slide-event-datetime">
                                    <p class="slide-event-card-date">{{ $event->start_date->translatedFormat('j F Y', 'ru') }}</p>
                                    <p>·</p>
                                    <p class="slide-event-card-date">{{ $event->start_date->translatedFormat('H:i', 'ru') }}</p>
                                </div>
                            </div>


                            <a href="{{ route('events.get_event', $event->id) }}" class="primary-btn">
                                Подробнее
                                <img src="{{ asset('images/icons/arrow-top-right-white.svg') }}" class="icon-24" alt="icon">
                            </a>
                        </div>
                    </div>
                @endforeach

{{--                <img src="image1.jpg" alt="Слайд 1" class="slide">--}}
{{--                <img src="image2.jpg" alt="Слайд 2" class="slide">--}}
{{--                <img src="image3.jpg" alt="Слайд 3" class="slide">--}}
            </div>

            <button class="prev-button">
                <img src="{{ asset('images/icons/arrow-left-white.svg') }}" class="icon-24" alt="icon">
            </button>
            <button class="next-button">
                <img src="{{ asset('images/icons/arrow-right-white.svg') }}" class="icon-24" alt="icon">
            </button>

            <div class="dots"></div>

        </div>

        <div class="events-explore-container">
            <div class="events-explore-header">
                <h2>Мероприятия</h2>
                <div class="search-container">
                    <form id="search-form" action="{{ route('events.index') }}" method="GET" class="search-form">
                        <div class="search-input-wrapper">
                            <input type="text" name="search" placeholder="Что желаете найти..." value="{{ request('search') }}">
                            <div class="clear-search {{ request('search') ? '' : 'hidden' }}">
                                <img src="{{ asset('images/icons/close-primary.svg') }}" class="icon-20" alt="clear">
                            </div>
                        </div>

                        <button type="submit" class="primary-btn">Искать</button>

                        <div class="filter-btn" id="filter-btn">
                            <img src="{{ asset('images/icons/filter.svg') }}" class="icon-20" alt="icon">
                            <p class="text-hint filter-count {{ count(request('categories', [])) ? '' : 'hidden' }}" id="filter-count">{{ count(request('categories', [])) }}</p>
                        </div>
                    </form>
                </div>
            </div>

            <div class="h-divider"></div>

            <div class="events-grid" id="events-grid">
                @include('partials.event_cards', ['events' => $events])
            </div>

            @if ($events->hasMorePages())
                <div class="load-more-container">
                    <button id="load-more" data-page="2" class="primary-btn">Загрузить ещё</button>
                </div>
            @endif
        </div>

        <div class="info-block">
            <div class="info-header">
                <h2>Прошедшие мероприятия</h2>
                <h3>5</h3>
            </div>
        </div>
    </div>

    <div class="modal hidden" id="filter-modal">
        <div class="modal-content">
            <div class="modal-close" id="modal-close">
                <img src="{{ asset('images/icons/close-primary.svg') }}" class="icon-24" alt="close">
            </div>

            <h3>Фильтры</h3>
            <div class="h-divider"></div>

            <div class="modal-section">
                <p class="text-hint">Категории</p>
                <input type="text" id="category-search" placeholder="Введите категорию...">
                <datalist id="category-options">
                    @foreach ($categories as $category)
                        <option value="{{ $category->name }}">
                    @endforeach
                </datalist>
                <div class="selected-categories-wrapper">
                    @foreach (request('categories', []) as $category)
                        <div class="selected-category-tag" data-category="{{ $category }}">
                            {{ $category }} <span class="remove-category">&times;</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="modal-section">
                <p class="text-hint">Сортировка по дате</p>
                <select id="sort-select">
                    <option value="desc" {{ request('sort', 'desc') == 'desc' ? 'selected' : '' }}>Новые сначала</option>
                    <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Старые сначала</option>
                </select>
            </div>

            <div class="modal-actions">
                <button class="secondary-btn" id="cancel-filter">Отмена</button>
                <button class="primary-btn" id="save-filter">
                    Применить <span id="filter-count-modal" class="{{ count(request('categories', [])) ? '' : 'hidden' }}">{{ count(request('categories', [])) }}</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        window.allCategories = @json($categories->pluck('name'));
    </script>
@endsection

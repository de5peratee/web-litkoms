@extends('layouts.app')

@section('title', 'Мероприятия')

@section('content')
    @vite(['resources/css/events.css'])

    <div class="events-container">
        <div class="slider-container">

            <div class="event-slide">
                <p>Авторы</p>
                <p>Наименование мероприятие: краткое описание</p>
                <p>Описание мероприятия</p>
                <p>Дата и время</p>
                <a href="{{route('events.get_event')}}">Подробнее</a>
            </div>

            <div class="event-slide">
                <p>Авторы</p>
                <p>Наименование мероприятие: краткое описание</p>
                <p>Описание мероприятия</p>
                <p>Дата и время</p>
                <a href="{{route('events.get_event')}}">Подробнее</a>
            </div>

        </div>

        <div class="events-explore-container">
            <h2>Ближайшие мероприятия</h2>
            <p>Количество: n</p>

            <div class="search-container">
                <form id="search-form" action="{{ route('events.index') }}" method="GET">
                    <input type="text" name="search" placeholder="Что желаете найти..." value="{{ request('search') }}">
                    <button type="submit">Найти</button>
                </form>
            </div>

            <div class="filters-wrapper">
                <div class="filter">
                    <p>Фильтр</p>
                </div>
                <div class="filter">
                    <p>Фильтр</p>
                </div>
                <div class="filter">
                    <p>Фильтр</p>
                </div>
            </div>

            <div class="events-grid">

                <a href="{{ route('events.get_event') }}" class="event-card">
                    <div class="cover_wrapper">
                        <img src="{{ asset('images/default_template/event-cover.svg') }}" alt="event_cover">
                    </div>

                    <div class="event-description">
                        <div class="event-categories">
                            <span class="category">Арт-мастерская</span>
                            <span class="category">Образование</span>
                        </div>

                        <h3>Наименование мероприятия</h3>
                        <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">

                        <p>Мастер-класс для начинающих художников, посвященный
                            созданию персонажей в стиле комиксов.</p>

                        <p>Дата и время провередия</p>
                    </div>
                </a>
                <a href="{{ route('events.get_event') }}" class="event-card">
                    <div class="cover_wrapper">
                        <img src="{{ asset('images/default_template/event-cover.svg') }}" alt="comics_cover">
                    </div>

                    <div class="event-description">
                        <div class="event-categories">
                            <span class="category">Арт-мастерская</span>
                            <span class="category">Образование</span>
                        </div>

                        <h3>Наименование мероприятия</h3>
                        <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">

                        <p>Мастер-класс для начинающих художников, посвященный
                            созданию персонажей в стиле комиксов.</p>

                        <p>Дата и время провередия</p>
                    </div>
                </a>
                <a href="{{ route('events.get_event') }}" class="event-card">
                    <div class="cover_wrapper">
                        <img src="{{ asset('images/default_template/event-cover.svg') }}" alt="comics_cover">
                    </div>

                    <div class="event-description">
                        <div class="event-categories">
                            <span class="category">Арт-мастерская</span>
                            <span class="category">Образование</span>
                        </div>

                        <h3>Наименование мероприятия</h3>
                        <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">

                        <p>Мастер-класс для начинающих художников, посвященный
                            созданию персонажей в стиле комиксов.</p>

                        <p>Дата и время провередия</p>
                    </div>
                </a>

            </div>
        </div>
    </div>

    @endsection

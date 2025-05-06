@extends('layouts.app')

@section('title', 'Мероприятия')

@section('content')
    @vite(['resources/css/events.css'])

    <div class="events-container">
        <div class="slider-container">
            @foreach($events->take(2) as $event)
                <div class="event-slide" >
                    <p>{{ $event->authors }}</p>
                    <p>{{ $event->name }}</p>
                    <p>{{ Str::limit($event->description, 100) }}</p>
                    <p>{{ $event->start_date->format('d.m.Y H:i') }}</p>
                    <a href="{{ route('events.show', $event->id) }}">Подробнее</a>
                </div>
            @endforeach
        </div>

        <div class="events-explore-container">
            <h2>Ближайшие мероприятия</h2>
{{--            <p>Количество: {{ $events->count() }}</p>--}}

            <div class="search-container">
                <form id="search-form" action="{{ route('events.index') }}" method="GET">
                    <input type="text" name="search" placeholder="Что желаете найти..." value="{{ request('search') }}">
                    <button type="submit">Найти</button>
                </form>
            </div>

            <div class="filters-wrapper">
                <!-- Фильтры можно добавить позже -->
                <div class="filter"><p>Фильтр</p></div>
                <div class="filter"><p>Фильтр</p></div>
                <div class="filter"><p>Фильтр</p></div>
            </div>

            <div class="events-grid">
                @foreach($events as $event)
                    <a href="{{ route('events.show', $event->id) }}" class="event-card">
                        <div class="cover_wrapper">
                            <img src="{{ $event->cover ? Storage::url('events/' . $event->cover) : asset('images/default_template/event-cover.svg') }}" alt="event_cover">

                        </div>

                        <div class="event-description">
                            <div class="event-categories">
                                @foreach($event->tags as $tag)
                                    <span class="category">{{ $tag->name }}</span>
                                @endforeach
                            </div>

                            <h3>{{ $event->name }}</h3>
                            <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">

                            <p>{{ Str::limit($event->description, 100) }}</p>

                            <p>{{ $event->start_date->format('d.m.Y H:i') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
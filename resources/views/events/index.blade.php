@extends('layouts.app')

@section('title', 'Мероприятия')

@section('content')
    @vite(['resources/css/events.css'])
    @vite(['resources/js/loadEvents.js'])
    @vite(['resources/js/event-slider.js'])
    @vite(['resources/js/event-tags.js'])

    <div class="events-container">

        <div class="custom-slider">
            <div class="slides-wrapper">
                @foreach($events->sortBy('start_date')->take(3) as $event)
                    <div class="event-slide" style="background-image: url('{{ $event->cover ? Storage::url('events/' . $event->cover) : asset('images/default_template/event-cover.svg') }}');">
                        <div class="event-slide-content">
                            <div class="event-authors">
                                <p>{{ implode(' · ', $event->guests->pluck('name')->toArray()) }}</p>
                            </div>

                            <div class="event-bottom-text">
                                <div class="left-part-text">
                                    <h1>{{ $event->name }}</h1>
                                    <p>{{ $event->start_date->format('d.m.Y H:i') }}</p>
                                </div>

                                <a href={{ route('events.get_event', $event->id) }} class="primary-btn">Подробнее</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="slider-controls">
                <button class="slider-prev">←</button>
                <button class="slider-next">→</button>
            </div>
        </div>

        <div class="events-explore-container">
            <h2>Ближайшие мероприятия</h2>
            <div class="search-container">
                <form id="search-form" class="search-form" action="{{ route('events.index') }}" method="GET">
                    <input type="text" name="search" placeholder="Что желаете найти..." value="{{ request('search') }}">
                    <button type="submit" class="primary-btn">Найти</button>
                </form>
            </div>

            <div class="events-grid" id="events-grid">
                @include('partials.event_cards', ['events' => $events])
            </div>

            @if ($events->hasMorePages())
                <div class="load-more-container">
                    <button id="load-more" data-page="2" class="primary-btn">Загрузить ещё</button>
                </div>
            @endif


            {{--            <div class="events-grid">--}}
{{--                @foreach($events as $event)--}}
{{--                    <a href="{{ route('events.show', $event->id) }}" class="event-card">--}}
{{--                        <div class="cover_wrapper">--}}
{{--                            <img src="{{ $event->cover ? Storage::url('events/' . $event->cover) : asset('images/default_template/event-cover.svg') }}" alt="event_cover">--}}

{{--                        </div>--}}

{{--                        <div class="event-description">--}}
{{--                            <div class="event-categories">--}}
{{--                                @foreach($event->tags as $tag)--}}
{{--                                    <p class="text-small">{{ $tag->name }}</p>--}}
{{--                                @endforeach--}}
{{--                            </div>--}}

{{--                            <div class="event-title-block">--}}
{{--                                <h3>{{ $event->name }}</h3>--}}
{{--                                <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">--}}
{{--                            </div>--}}

{{--                            <p>{{ Str::limit($event->description, 100) }}</p>--}}
{{--                            <p>{{ $event->start_date->format('d.m.Y H:i') }}</p>--}}
{{--                        </div>--}}
{{--                    </a>--}}
{{--                @endforeach--}}
{{--            </div>--}}
        </div>
    </div>
@endsection

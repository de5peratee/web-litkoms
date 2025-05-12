@extends('layouts.app')

@section('title', 'Мероприятия')

@section('content')
    @vite(['resources/css/events.css'])
    @vite(['resources/js/loadEvents.js'])
{{--    @vite(['resources/js/event-slider.js'])--}}
    @vite(['resources/js/event-tags.js'])

    <div class="events-container">

{{--        <div class="custom-slider">--}}
{{--            <div class="slides-wrapper">--}}
{{--                @foreach($events->sortBy('start_date')->take(3) as $event)--}}
{{--                    <div class="event-slide" style="background-image: url('{{ $event->cover ? Storage::url('events/' . $event->cover) : asset('images/default_template/event-cover.svg') }}');">--}}
{{--                        <div class="event-slide-content">--}}
{{--                            <div class="event-authors">--}}
{{--                                <p>{{ implode(' · ', $event->guests->pluck('name')->toArray()) }}</p>--}}
{{--                            </div>--}}

{{--                            <div class="event-bottom-text">--}}
{{--                                <div class="left-part-text">--}}
{{--                                    <h1>{{ $event->name }}</h1>--}}
{{--                                    <p>{{ $event->start_date->format('d.m.Y H:i') }}</p>--}}
{{--                                </div>--}}

{{--                                <a href={{ route('events.get_event', $event->id) }} class="primary-btn">Подробнее</a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endforeach--}}
{{--            </div>--}}

{{--            <div class="slider-controls">--}}
{{--                <button class="slider-prev">←</button>--}}
{{--                <button class="slider-next">→</button>--}}
{{--            </div>--}}
{{--        </div>--}}

        <div class="events-explore-container">
            <div class="events-explore-header">
                <h2>Мероприятия</h2>

                <div class="search-container">
                    <form id="search-form" action="{{ route('events.index') }}" method="GET" class="search-form">
                        <input type="text" name="search" placeholder="Что желаете найти..." value="{{ request('search') }}">
                        <button type="submit" class="primary-btn">
                            Найти
                        </button>
                    </form>
                </div>

            </div>

            <div class="events-grid" id="events-grid">
                @include('partials.event_cards', ['events' => $events])
            </div>

            @if ($events->hasMorePages())
                <div class="load-more-container">
                    <button id="load-more" data-page="2" class="primary-btn">Загрузить ещё</button>
                </div>
            @endif

        </div>
    </div>
@endsection

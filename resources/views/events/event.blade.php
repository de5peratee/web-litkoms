@extends('layouts.app')

@section('title', $event->name)

@section('content')
    @vite(['resources/css/event.css', 'resources/js/event-map.js'])

    <div class="event-container">
        <div class="event-preview" style="background-image: url('{{ $event->cover ? Storage::url('events/' . $event->cover) : asset('images/default_template/event-cover.svg') }}');">
            <div class="event-preview-content">
                <div class="event-authors">
                    <p>{{ implode(' · ', $event->guests->pluck('name')->toArray()) }}</p>
                </div>

                <div class="event-bottom-text">
                    <div class="left-part-text">
                        <h1>{{ $event->name }}</h1>
                        <p>{{ $event->start_date->format('d.m.Y H:i') }}</p>
                    </div>

                    <div class="event-location">
                        @if($event->cover_image)
                            <img src="{{ asset($event->cover) }}" alt="Cover Image">
                        @else
                            <p>{{ $event->location }}</p>
                        @endif
                        <p>{{ $event->location }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="info-block">
            <div class="event-description">
                <div class="event-action-block">
                    <h3>О мероприятии</h3>

                    <p>{{ implode(', ', $event->tags->pluck('name')->toArray()) }}</p>

                    <p>{{ $event->description }}</p>

                    <a href="#" class="primary-btn">Буду на мероприятии</a>
                </div>

                <div class="event-datetime-wrapper">
                    <div class="event-date">
                        <p class="text-small">Дата</p>
                        <h3>{{ $event->start_date->format('d') }}</h3>
                        <p class="text-small">{{ $event->start_date->translatedFormat('F') }}</p>
                    </div>
                    <div class="event-time">
                        <p class="text-small">Время</p>
                        <h3>{{ $event->start_date->format('H:i') }}</h3>
                        <p class="text-small">по МСК</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="info-block">
            <h3>Где мы находимся?</h3>

            <div class="map" id="map" style="width: 100%; height: 400px;"></div>

            <div class="event-location">
                <img src="{{ asset('images/icons/location-gray.svg') }}" alt="icon">
                <p>ул. Маршала Бирюзова, 9</p>
            </div>
        </div>
    </div>
@endsection
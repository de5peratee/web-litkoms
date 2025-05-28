@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    @vite(['resources/css/home.css'])
    @vite(['resources/js/comics-list-wrapper-fix.js'])
    @vite(['resources/js/resources/js/home.js'])
    @vite(['resources/js/swiper-slider.js'])

    <div class="home-container">

        <div class="news-slider-container">
            <div class="slider">
{{--                @foreach($news as $post)--}}
{{--                    <div class="slide" style="background-image: url('{{ $event->cover ? Storage::url('/' . $event->cover) : asset('images/default_template/event-cover.svg') }}');">--}}
{{--                        <div class="slide-content">--}}
{{--                            <div class="slide-event-authors">--}}
{{--                                <p>{{ implode(' · ', $event->guests->map(function($guest) {--}}
{{--                                                        return $guest->name . ' ' . $guest->surname;--}}
{{--                                                    })->toArray()) }}</p>--}}
{{--                            </div>--}}

{{--                            <div class="slide-center-data">--}}
{{--                                <h1>Заголовок новости</h1>--}}

{{--                                Только если это МЕРОПРИЯТИЕ--}}
{{--                                <div class="slide-event-tags-wrapper">--}}
{{--                                    @foreach ($event->tags as $tag)--}}
{{--                                        <div class="slide-event-tag">{{ $tag->name }}</div>--}}
{{--                                    @endforeach--}}
{{--                                </div>--}}

{{--                                <div class="slide-event-datetime">--}}
{{--                                    <p class="slide-event-card-date">{{ $event->start_date->translatedFormat('j F Y', 'ru') }}</p>--}}
{{--                                    <p>·</p>--}}
{{--                                    <p class="slide-event-card-date">{{ $event->start_date->translatedFormat('H:i', 'ru') }}</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}


{{--                            <a href="{{ route('events.get_event', $event->id) }}" class="primary-btn">--}}
{{--                                Подробнее--}}
{{--                                <img src="{{ asset('images/icons/arrow-top-right-white.svg') }}" class="icon-24" alt="icon">--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endforeach--}}
            </div>

            <button class="prev-button">
                <img src="{{ asset('images/icons/arrow-left-white.svg') }}" class="icon-24" alt="icon">
            </button>
            <button class="next-button">
                <img src="{{ asset('images/icons/arrow-right-white.svg') }}" class="icon-24" alt="icon">
            </button>

            <div class="dots"></div>

        </div>




        <div class="info-block events-container">
            <div class="info-header">
                <h3>Мероприятия</h3>
                <a href="{{ route('events.index') }}" class="tertiary-btn">
                    Подробнее
                    <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                </a>
            </div>

            <div class="h-divider"></div>

            <div class="events-grid" id="events-grid">
{{--                @include('partials.event_cards', ['events' => $events])--}}
            </div>
        </div>

        <div class="info-block authors-comics-promo-container">
            <img src="{{ asset('images/comics_big.png') }}" alt="comics" class="big-comics">
            <img src="{{ asset('images/comics_small.png') }}" alt="comics" class="small-comics">

            <div class="hero-tag">Авторские комиксы</div>

            <div class="hero-title-text-wrapper">
                <h2>Публикуйте свои <br>комиксы бесплатно!</h2>
{{--                <p class="text-big">Публикуйте свои комиксы бесплатно!</p>--}}
            </div>

            <a href="{{ route('authors_comics_landing')}}"
               class="primary-btn">
                Подробнее
                <img src="{{ asset('images/icons/arrow-top-right-white.svg') }}" alt="icon" class="icon-24">
            </a>
        </div>

        <div class="info-block new-comics-list-container">
            <div class="info-header">
                <h3>Новые комиксы от Литкомс</h3>
                <a href="{{ route('authors_comics_library') }}" class="tertiary-btn">
                    Перейти в библиотеку
                    <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                </a>
            </div>

            <div class="h-divider"></div>

{{--            <div class="comics-list-wrapper">--}}
{{--                <button class="scroll-btn scroll-left" style="display: none;">--}}
{{--                    <img src="{{ asset('images/icons/arrow-full-left-white.svg') }}" alt="left" class="scroll-icon">--}}
{{--                </button>--}}
{{--                <div class="comics-scroll-container">--}}
{{--                    @forelse ($newComics as $comic)--}}
{{--                        <div class="comic">--}}
{{--                            <a href="{{ route('author_comic', $comic->slug) }}">--}}
{{--                                <div class="comic-cover-wrapper">--}}
{{--                                    <img src="{{ $comic->cover ? Storage::url($comic->cover) : asset('images/default_template/comics.svg') }}"--}}
{{--                                         alt="{{ $comic->name }}" class="comic-cover">--}}
{{--                                </div>--}}
{{--                            </a>--}}

{{--                            <div class="comic-tags-wrapper" data-genres="{{ $comic->genres->pluck('name')->join(',') }}">--}}
{{--                                @foreach ($comic->genres as $genre)--}}
{{--                                    <p class="text-hint comic-tag">{{ $genre->name }}</p>--}}
{{--                                @endforeach--}}
{{--                            </div>--}}

{{--                            <div class="comic-title">--}}
{{--                                <p class="text-big">{{ $comic->name }}</p>--}}
{{--                                <p class="comic-author-text">{{ '@' }}{{ $comic->createdBy->nickname }}</p>--}}
{{--                            </div>--}}

{{--                            <div class="comic-avg-grade">--}}
{{--                                <img src="{{ asset('images/icons/grade_star_fill.svg') }}" alt="icon" class="icon-24">--}}
{{--                                <p>{{ number_format($comic->average_assessment ?? 0, 1) }}</p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    @empty--}}
{{--                        <p class="no-results">Новые комиксы не найдены.</p>--}}
{{--                    @endforelse--}}
{{--                </div>--}}
{{--                <button class="scroll-btn scroll-right" style="display: none;">--}}
{{--                    <img src="{{ asset('images/icons/arrow-full-right-white.svg') }}" alt="right" class="scroll-icon">--}}
{{--                </button>--}}
{{--            </div>--}}
        </div>
    </div>

{{--    <div class="web-radio-cta" id="floating-blob">--}}
{{--        <img src="{{ asset('images/blob.svg') }}" alt="icon">--}}
{{--    </div>--}}

@endsection

@extends('layouts.app')

@section('title', 'Авторские комиксы')

@section('content')
    @vite(['resources/css/authors-comics-landing.css'])
    @vite(['resources/js/comics-list-wrapper-fix.js'])

    <div class="authors-comics-container">

        <div class="hero-block">

            <img src="{{ asset('images/comics_big.png') }}" alt="comics" class="big-comics">
            <img src="{{ asset('images/comics_small.png') }}" alt="comics" class="small-comics">

            <div class="hero-tag">Авторские комиксы</div>

            <div class="hero-title-text-wrapper">
                <h2>Как это работает?</h2>
                <p class="text-big">Публикуйте свои комиксы бесплатно!</p>
            </div>

            <div class="steps-wrapper">

                <div class="steps-list">
                    <div class="step-block">
                        <img src="{{ asset('images/icons/steps/step1.svg') }}" alt="icon" class="step-img">
                        <div class="h-divider"></div>

                        <div class="step-title">
                            <p class="text-medium">Загрузите комикс</p>
                            <p class="step-num">01</p>
                        </div>
                    </div>

                    <div class="step-block">
                        <img src="{{ asset('images/icons/steps/step2.svg') }}" alt="icon" class="step-img">
                        <div class="h-divider"></div>

                        <div class="step-title">
                            <p class="text-medium">Пройдите модерацию</p>
                            <p class="step-num">02</p>
                        </div>
                    </div>

                    <div class="step-block">
                        <img src="{{ asset('images/icons/steps/step3.svg') }}" alt="icon" class="step-img">
                        <div class="h-divider"></div>

                        <div class="step-title">
                            <p class="text-medium">Готово!</p>
                            <p class="step-num">03</p>
                        </div>
                    </div>
                </div>

            </div>

            <a href="{{ auth()->check() ? route('user.create_author_comics') : route('auth') }}"
               class="primary-btn">
                Загрузить комикс
                <img src="{{ asset('images/icons/arrow-top-right-white.svg') }}" alt="icon" class="icon-24">
            </a>

        </div>

        <div class="info-block new-comics">
            <div class="info-header">
                <h3>Новые публикации</h3>
                <a href="{{ route('authors_comics_library') }}" class="tertiary-btn">
                    Перейти в каталог
                    <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                </a>
            </div>

            <div class="h-divider"></div>

            <div class="comics-list-wrapper">
                <button class="scroll-btn scroll-left" style="display: none;">
                    <img src="{{ asset('images/icons/arrow-full-left-white.svg') }}" alt="left" class="scroll-icon">
                </button>
                <div class="comics-scroll-container">
                    @forelse ($newComics as $comic)
                        <div class="comic">
                            <a href="{{ route('author_comic', $comic->slug) }}">
                                <div class="comic-cover-wrapper">
                                    <img src="{{ $comic->cover ? Storage::url($comic->cover) : asset('images/default_template/comics.svg') }}"
                                         alt="{{ $comic->name }}" class="comic-cover">
                                </div>
                            </a>

                            <div class="comic-tags-wrapper" data-genres="{{ $comic->genres->pluck('name')->join(',') }}">
                                @foreach ($comic->genres as $genre)
                                    <p class="text-hint comic-tag">{{ $genre->name }}</p>
                                @endforeach
                            </div>

                            <div class="comic-title">
                                <p class="text-big">{{ $comic->name }}</p>
                                <p class="comic-author-text">{{ $comic->createdBy->nickname }}</p>
                            </div>

                            <div class="comic-avg-grade">
                                <img src="{{ asset('images/icons/grade_star_fill.svg') }}" alt="icon" class="icon-24">
                                <p>{{ number_format($comic->average_assessment ?? 0, 1) }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="no-results">Новые комиксы не найдены.</p>
                    @endforelse
                </div>
                <button class="scroll-btn scroll-right" style="display: none;">
                    <img src="{{ asset('images/icons/arrow-full-right-white.svg') }}" alt="right" class="scroll-icon">
                </button>
            </div>

        </div>

        <div class="info-block authors-rating">

            <img src="{{ asset('images/icons/hw/cup.svg') }}" class="icon-96 cup-icon" alt="icon">
            <img src="{{ asset('images/icons/hw/lamp.svg') }}" class="icon-48 lamp-icon" alt="icon">

            <div class="info-header">
                <h3>Топ авторов</h3>
                <p class="text-medium author-subtitle">Новые комиксы от новых авторов</p>
            </div>

            <div class="authors-grid">
                @forelse ($topAuthors as $author)
                    <a href="{{ route('profile.index', ['nickname' => $author->nickname]) }}" class="author-item">
                        <div class="author-avatar-wrapper">
                            <img src="{{ $author->avatar ? Storage::url($author->avatar) : asset('images/default_template/ava_cover.png') }}"
                                 alt="{{ $author->nickname }}">
                        </div>
                        <div class="author-item-text-wrapper">
                            <p class="text-big">{{ '@' . $author->nickname }}</p>
                            <p class="author-name-text">{{ $author->name }}</p>
                        </div>
                    </a>
                @empty
                    <p class="no-results">Авторы не найдены.</p>
                @endforelse
            </div>

            <a href="{{ auth()->check() ? route('user.create_author_comics') : route('auth') }}" class="secondary-btn">
                Стать автором
                <img src="{{ asset('images/icons/arrow-top-right-white.svg') }}" alt="icon" class="icon-24">
            </a>
        </div>
        @if (auth()->user())
            <div class="info-block subs-comics">
                <div class="info-header">
                    <h3>От любимых авторов</h3>
                    <a href="{{ route('authors_comics_library') }}" class="tertiary-btn">
                        Перейти в каталог
                        <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                    </a>
                </div>

                <div class="h-divider"></div>

                <div class="comics-list-wrapper">
                    <button class="scroll-btn scroll-left" style="display: none;">
                        <img src="{{ asset('images/icons/arrow-full-left-white.svg') }}" alt="left" class="scroll-icon">
                    </button>
                    <div class="comics-scroll-container">
                        @forelse ($subscribedComics as $comic)
                            <div class="comic">
                                <a href="{{ route('author_comic', $comic->slug) }}">
                                    <div class="comic-cover-wrapper">
                                        <img src="{{ $comic->cover ? Storage::url($comic->cover) : asset('images/default_template/comics.svg') }}"
                                             alt="{{ $comic->name }}" class="comic-cover">
                                    </div>
                                </a>

                                <div class="comic-tags-wrapper" data-genres="{{ $comic->genres->pluck('name')->join(',') }}">
                                    @foreach ($comic->genres as $genre)
                                        <p class="text-hint comic-tag">{{ $genre->name }}</p>
                                    @endforeach
                                </div>

                                <div class="comic-title">
                                    <p class="text-big">{{ $comic->name }}</p>
                                    <p class="comic-author-text">{{ $comic->createdBy->nickname }}</p>
                                </div>

                                <div class="comic-avg-grade">
                                    <img src="{{ asset('images/icons/grade_star_fill.svg') }}" alt="icon" class="icon-24">
                                    <p>{{ number_format($comic->average_assessment ?? 0, 1) }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="no-results">Комиксы от любимых авторов не найдены.</p>
                        @endforelse
                    </div>
                    <button class="scroll-btn scroll-right" style="display: none;">
                        <img src="{{ asset('images/icons/arrow-full-right-white.svg') }}" alt="right" class="icon-24">
                    </button>
                </div>

            </div>
        @endif
    </div>
    </div>

@endsection

@extends('layouts.app')

@section('title', 'Лента')

@section('content')
    @vite(['resources/css/news.css', 'resources/js/news.js'])
    @vite(['resources/js/newsfeed-slider.js'])
    <div class="news-container">
        <div class="news-header">
            <img src="{{ asset('images/icons/hw/news.svg') }}" class="icon-48" alt="icon">
            <h2>Лента</h2>
        </div>

        <div class="news-tabs-wrapper">
            <div class="news-tab active-tab" data-tab="all">
                Все
            </div>
            <div class="news-tab" data-tab="comics">
                <img src="{{ asset('images/icons/comics-icon-primary.svg') }}" alt="icon" class="icon-24">
                Комиксы
            </div>
            <div class="news-tab" data-tab="events">
                <img src="{{ asset('images/icons/event-primary.svg') }}" alt="icon" class="icon-24">
                Мероприятия
            </div>
            <div class="news-tab" data-tab="posts">
                <img src="{{ asset('images/icons/post-primary.svg') }}" alt="icon" class="icon-24">
                Посты
            </div>
        </div>

        <div class="newsfeed-container">
            <div class="posts-list">
                <div class="tab-content" data-content="all">
                    @include('partials.news-cards', ['items' => $allItems])
                </div>
                <div class="tab-content" data-content="comics" style="display: none;">
                    @include('partials.news-cards', ['items' => $allItems->filter(fn($item) => $item['type'] === 'comic')])
                </div>
                <div class="tab-content" data-content="events" style="display: none;">
                    @include('partials.news-cards', ['items' => $allItems->filter(fn($item) => $item['type'] === 'event')])
                </div>
                <div class="tab-content" data-content="posts" style="display: none;">
                    @include('partials.news-cards', ['items' => $allItems->filter(fn($item) => $item['type'] === 'post')])
                </div>
            </div>
        </div>

        @if ($hasMorePages)
            <div class="load-more-container">
                <button id="load-more" class="primary-btn"
                        data-page="{{ $currentPage + 1 }}"
                        data-tab="all">
                    Загрузить еще
                </button>
            </div>
        @endif
    </div>
@endsection

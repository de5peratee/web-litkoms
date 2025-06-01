@extends('layouts.app')

@section('title', 'Лента')

@section('content')
    @vite(['resources/css/news.css'])
    @vite(['resources/js/news.js'])

    <div class="news-container">
        <div class="news-header">
            <img src="{{ asset('images/icons/hw/news.svg') }}" class="icon-48" alt="icon">
            <h2>Лента</h2>
        </div>

        <div class="news-tabs-wrapper">
            <div class="news-tab active-tab">
                <img src="{{ asset('images/icons/comics-icon-white.svg') }}" alt="icon" class="icon-24">
                Комиксы
            </div>
            <div class="news-tab">
                <img src="{{ asset('images/icons/event-primary.svg') }}" alt="icon" class="icon-24">
                Мероприятия
            </div>
            <div class="news-tab">
                <img src="{{ asset('images/icons/post-primary.svg') }}" alt="icon" class="icon-24">
                Посты
            </div>
        </div>

        <div class="newsfeed-container">
            <div class="posts-list">
                @php
                    $allItems = [];
                    foreach ($comics as $comic) {
                        if (auth()->check() && auth()->user()->isSubscribedTo($comic->created_by)) {
                            $allItems[] = ['type' => 'comic', 'item' => $comic, 'created_at' => $comic->published_at];
                        }
                    }
                    foreach ($events as $event) {
                        $allItems[] = ['type' => 'event', 'item' => $event, 'created_at' => $event->created_at];
                    }
                    foreach ($posts as $post) {
                        $allItems[] = ['type' => 'post', 'item' => $post, 'created_at' => $post->created_at];
                    }
                    usort($allItems, function($a, $b) {
                        return $b['created_at'] <=> $a['created_at'];
                    });
                @endphp

                @foreach ($allItems as $item)
                    @if ($item['type'] === 'comic')
                        @php
                            $comic = $item['item'];
                            $isNew = $comic->published_at->diffInDays(now()) <= 3 && $comic->is_published && $comic->is_moderated === 'successful';
                        @endphp
                        <div class="post-wrapper author-comic-post">
                            <div class="comic-flex-wrapper">
                                <div class="author-comic-preview-wrapper">
                                    <img src="{{ $comic->cover ? Storage::url($comic->cover) : asset('images/default_template/comics.svg') }}" alt="{{ $comic->name }}">
                                </div>

                                <div class="post-text-data">
                                    @if ($isNew)
                                        <div class="new-tag">
                                            <img src="{{ asset('images/icons/energy-blue.svg') }}" class="icon-20" alt="icon">
                                            НОВЫЙ КОМИКС
                                        </div>
                                    @endif

                                    <h3>{{ $comic->name }}</h3>

                                    <div class="comic-genres" data-genres="{{ collect($comic->genres)->pluck('name')->join(',') }}">
                                        @foreach (collect($comic->genres) as $genre)
                                            <p class="comic-genre-tag text-hint">{{ $genre->name }}</p>
                                        @endforeach
                                    </div>

                                    <p class="text-small comic-description">{{ Str::limit($comic->description, 100) }}</p>

                                    <a href="{{ route('author_comic', $comic->slug) }}" class="primary-btn">
                                        Подробнее
                                        <img src="{{ asset('images/icons/arrow-top-right-white.svg') }}" class="icon-20" alt="icon">
                                    </a>
                                </div>
                            </div>

                            <div class="h-divider"></div>

                            <div class="post-meta-wrapper">
                                <div class="post-author-wrapper">
                                    <div class="post-author-avatar-wrapper">
                                        @if($comic->createdBy->icon && Storage::disk('public')->exists($comic->createdBy->icon))
                                            <img src="{{ Storage::url($comic->createdBy->icon) }}" alt="avatar">
                                        @else
                                            <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="default avatar">
                                        @endif
{{--                                        <img src="{{ asset($comic->createdBy->icon ?? 'images/default_template/ava_cover.png') }}" alt="author_avatar">--}}
                                    </div>
                                    <p class="post-author-text">{{ '@' . $comic->createdBy->nickname }}</p>
                                    @if (auth()->check() && auth()->user()->isSubscribedTo($comic->created_by))
                                        <div class="subscribed-wrapper">
                                            Вы подписаны
                                            <img src="{{ asset('images/icons/check-gray.svg') }}" class="icon-24" alt="icon">
                                        </div>
                                    @endif
                                </div>
                                <p class="post-datetime-text text-hint">{{ $comic->published_at->diffForHumans() }}</p>
                            </div>
                        </div>

                    @elseif ($item['type'] === 'event')
                        @php
                            $event = $item['item'];
                        @endphp
                        <div class="post-wrapper event-post">
                            <div class="post-event-wrapper">
                                <img src="{{ $event->cover ? Storage::url('/' . $event->cover) : asset('images/default_template/event-cover.svg') }}" alt="{{ $event->name }}">
                            </div>

                            <div class="post-text-data">
                                <div class="event-categories" data-categories="{{ collect($event->tags)->pluck('name')->join(',') }}">
                                    @foreach (collect($event->tags) as $tag)
                                        <p class="event-category-tag text-hint">{{ $tag->name }}</p>
                                    @endforeach
                                </div>

                                <div class="event-title-wrapper">
                                    <h3>{{ $event->name }}</h3>
                                    <a href="{{ route('events.get_event', $event->id) }}">
                                        <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                                    </a>
                                </div>

                                <p class="text-small">{{ Str::limit($event->description, 100) }}</p>

                                <div class="h-divider"></div>

                                <div class="post-meta-wrapper">
                                    <div class="event-datetime-wrapper">
                                        <img src="{{ asset('images/icons/calendar-tertiary.svg') }}" class="icon-20" alt="icon">

                                        <p class="slide-event-card-date">{{ $event->start_date->translatedFormat('j F Y', 'ru') }}</p>
                                        <p>·</p>
                                        <p class="slide-event-card-date">{{ $event->start_date->translatedFormat('H:i', 'ru') }}</p>
                                    </div>
                                    <div class="event-location-wrapper">
                                        <img src="{{ asset('images/icons/location-tertiary.svg') }}" class="icon-20" alt="icon">
                                        <p class="text-small">{{ $event->location ?? 'ул. Маршала Бирюзова, 9' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @elseif ($item['type'] === 'post')
                        @php
                            $post = $item['item'];
                            $mediaPreviewExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                            $imageMedias = $post->medias->filter(function($media) use ($mediaPreviewExtensions) {
                                $ext = strtolower(pathinfo($media->file, PATHINFO_EXTENSION));
                                return in_array($ext, $mediaPreviewExtensions);
                            });

                            $fileMedias = $post->medias->filter(function($media) use ($mediaPreviewExtensions) {
                                $ext = strtolower(pathinfo($media->file, PATHINFO_EXTENSION));
                                return !in_array($ext, $mediaPreviewExtensions);
                            });
                        @endphp

                        <div class="post-wrapper media-post">
                            @if ($imageMedias->count() > 0)
                                <div class="post-media-grid images-count-{{ min($imageMedias->count(), 6) }}">
                                    @foreach ($imageMedias->take(6) as $media)
                                        <div class="post-media-wrapper">
                                            <img src="{{ Storage::url($media->file) }}" alt="post_media">
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="post-text-data">
                                <h3>{{ $post->name }}</h3>
                                <p class="text-small">{{ Str::limit($post->description, 100) }}</p>

                                @if ($fileMedias->count() > 0)
                                    <div class="files-container">
                                        @foreach ($fileMedias as $media)
                                            <div class="file-wrapper">
                                                <img src="{{ asset('images/icons/attachment-secondary.svg') }}" class="icon-20" alt="icon">
                                                <a href="{{ Storage::url($media->file) }}" target="_blank">{{ basename($media->file) }}</a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="h-divider"></div>

                                <div class="post-meta-wrapper">
                                    <div class="post-author-wrapper">
                                        <div class="post-author-avatar-wrapper">
                                            @if($post->createdBy->icon && Storage::disk('public')->exists($post->createdBy->icon))
                                                <img src="{{ Storage::url($post->createdBy->icon) }}" alt="avatar">
                                            @else
                                                <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="default avatar">
                                            @endif
{{--                                            <img src="{{ asset($post->createdBy->icon ?? 'images/default_template/ava_cover.png') }}" alt="author_avatar">--}}
                                        </div>
                                        <p class="post-author-text">{{ '@' . $post->createdBy->nickname }}</p>
                                    </div>
                                    <p class="post-datetime-text text-hint">{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection

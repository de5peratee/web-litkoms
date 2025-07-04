@foreach ($items as $item)
    @if ($item['type'] === 'comic')
        @php
            $comic = $item['item'];
            $isNew = $comic->published_at->diffInDays(now()) <= 3 && $comic->is_published && $comic->is_moderated === 'successful';
        @endphp
        <div class="post-wrapper author-comic-post" data-type="comic">
            <div class="comic-flex-wrapper">
                <div class="author-comic-preview-wrapper">
                    <img src="{{ $comic->cover ? Storage::url($comic->cover) : asset('images/default_template/comics.svg') }}" alt="{{ $comic->name }}">
                </div>

                <div class="post-text-data">
                    @if ($isNew)
                        <div class="new-tag">
                            <img src="{{ asset('images/icons/energy-blue.svg') }}" class="icon-20" alt="icon">
                            Новый комикс
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
        <div class="post-wrapper event-post" data-type="event">
            @if(\Carbon\Carbon::parse($event->start_date)->isPast() && \Carbon\Carbon::parse($event->end_date)->isPast())
                <div class="past-sign">
                    <img src="{{ asset('images/icons/lock-secondary.svg') }}" class="icon-20" alt="icon">
                    <p class="text-hint">Уже прошло</p>
                </div>
            @endif

            <div class="post-event-wrapper">
                <img src="{{ $event->cover ? Storage::url($event->cover) : asset('images/default_template/event-cover.svg') }}" alt="{{ $event->name }}">
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
                        <p class="slide-event-card-date">{{ $event->start_date ? $event->start_date->translatedFormat('j F Y', 'ru') : 'Дата не указана' }}</p>
                        <p>·</p>
                        <p class="slide-event-card-date">{{ $event->start_date ? $event->start_date->translatedFormat('H:i', 'ru') : '' }}</p>
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

        <div class="post-wrapper media-post" data-type="post" data-post-id="{{ $post->id }}">
            @if ($imageMedias->count() > 0)
                <div class="post-media-grid images-count-{{ min($imageMedias->count(), 6) }}" data-images='@json($imageMedias->pluck('file')->map(fn($file) => Storage::url($file)))'>
                    @foreach ($imageMedias->take(6) as $media)
                        <div class="post-media-wrapper">
                            <img src="{{ Storage::url($media->file) }}" alt="post_media">
                        </div>
                    @endforeach
                    @if ($imageMedias->count() > 6)
                        <div class="post-media-overlay">
                            <p class="text-big">6+ Изображений</p>
                        </div>
                    @endif
                </div>
            @endif

            <div class="post-text-data">
                <h3>{{ $post->name }}</h3>
                <p class="text-small" id="description-{{ $post->id }}">
                    @if (strlen($post->description) > 100)
                        <span class="text-small short-description">{!! nl2br(e(Str::limit($post->description, 100))) !!}</span>
                        <span class="text-small full-description" style="display: none;">{!! nl2br(e($post->description)) !!}</span>
                        <a href="#" class="read-more" data-post-id="{{ $post->id }}">Подробнее</a>
                    @else
                        {!! nl2br(e($post->description)) !!}
                    @endif
                </p>

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
                        </div>
                        <p class="post-author-text">{{ '@' . $post->createdBy->nickname }}</p>
                    </div>
                    <p class="post-datetime-text text-hint">{{ $post->created_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>
    @endif
@endforeach
@if ($items->isEmpty())
    <p class="no-results">К сожалению, ничего не нашли :(</p>
@endif

<div class="media-slider" id="media-slider" style="display: none;">
    <div class="media-slider-overlay"></div>
    <div class="media-slider-content">
        <button class="slider-close-btn">
            <img src="{{ asset('images/icons/close-white.svg') }}" class="icon-24" alt="close">
        </button>
        <div class="slider-image-wrapper">
            <img id="slider-image" src="" alt="slider image">
        </div>
        <div class="slider-controls">
            <button class="slider-prev-btn">
                <img src="{{ asset('images/icons/arrow-left-white.svg') }}" class="icon-24" alt="prev">
            </button>
            <p class="slider-counter" id="slider-counter">1/1</p>
            <button class="slider-next-btn">
                <img src="{{ asset('images/icons/arrow-right-white.svg') }}" class="icon-24" alt="next">
            </button>
        </div>
    </div>
</div>

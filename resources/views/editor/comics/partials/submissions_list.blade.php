@forelse ($comics as $comic)
    <div class="submission-item {{ $comic->is_moderated === 'successful' && $comic->is_published ? 'clickable' : '' }}"
         data-comic-id="{{ $comic->id }}"
         data-comic-slug="{{ $comic->slug }}"
         data-age-restriction="{{ $comic->age_restriction }}"
         @if ($comic->is_moderated === 'successful' && $comic->is_published) onclick="window.location.href='{{ route('author_comic', $comic->slug) }}'" @endif>
        <div class="submission-item-left">
            <div class="item-cell num-cell">
                {{ $loop->index + 1 + ($comics->currentPage() - 1) * $comics->perPage() }}
            </div>

            <div class="submission-comic-preview item-cell">
                <div class="submission-comic-cover-wrapper">
                    <img src="{{ $comic->cover ? Storage::url($comic->cover) : asset('images/default_template/comics.svg') }}"
                         class="comic-cover">
                </div>

                <div class="submission-comic-preview-text-wrapper">
                    <div class="preview-text-flex">
                        <p class="text-big">{{ $comic->name }}</p>
                        @if ($comic->age_restriction >= 18)
                            <p class="text-hint age-restriction-tag">18+</p>
                        @endif
                    </div>

                    <div class="submission-author-text-wrapper">
                        <p>{{ $comic->createdBy->nickname ?? 'Неизвестный' }}</p>
                        <p class="submission-author-text">·</p>
                        <p class="submission-author-text">{{ $comic->createdBy->name ?? 'Неизвестный автор' }}</p>
                    </div>

                    <p class="text-hint submission-datetime-tag">
                        {{ $comic->updated_at->diffForHumans() }}
                    </p>
                </div>
            </div>

            @if($comic->is_moderated === 'under review')
                <div class="item-cell">
                    <a href="{{ route('editor.comic_moderation', $comic->slug) }}" class="tertiary-btn">
                        Модерация
                        <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24"
                             alt="icon">
                    </a>
                </div>
            @endif
        </div>

        @if ($comic->is_moderated === 'under review')
            <div class="submission-actions-tab-wrapper">
                <div class="submission-action-tab like-tab" data-action="accept">
                    <img src="{{ asset('images/icons/like-primary.svg') }}" class="icon-24" alt="icon">
                    Принять
                </div>
                <div class="submission-action-divider">|</div>
                <div class="submission-action-tab dislike-tab" data-action="reject">
                    <img src="{{ asset('images/icons/dislike-primary.svg') }}" class="icon-24" alt="icon">
                    Отклонить
                </div>
            </div>
        @endif
    </div>
@empty
    <p>Нет комиксов в выбранной категории</p>
@endforelse

@if ($comics->hasMorePages())
    <div class="load-more-container">
        <button id="load-more" class="primary-btn"
                data-page="{{ $comics->currentPage() + 1 }}"
                data-search="{{ request('search') ?? '' }}"
                data-status="{{ $status }}">
            Загрузить ещё
        </button>
    </div>
@endif
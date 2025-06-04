@foreach ($comics as $index => $comic)
    <div href="{{ route('author_comic', $comic->slug) }}" class="comic-item">
        <div class="comic-item-left-part">
            <div class="item-sell num-cell">{{ $loop->iteration + ($page ?? 0) * 10 }}</div>
            <div class="item-cell comic-preview-cell">
                <div class="comic-cover-wrapper">
                    <img src="{{ $comic->cover ? Storage::url($comic->cover) : asset('images/default_template/comics.svg') }}" class="comic-cover" alt="icon">
                </div>
                <div class="comic-preview-text-wrapper">
                    <div class="comic-title-flex">
                        <p class="text-big">{{ $comic->name }}</p>
                        @if ($comic->age_restriction >= 18)
                            <p class="text-hint age-restriction-tag">18+</p>
                        @endif
                    </div>
                    <p class="text-hint comic-datetime-tag">
                        {{ $comic->updated_at->diffForHumans() }}
                    </p>
                </div>
            </div>
            <div class="item-cell status-cell">
                <img src="{{ asset('images/icons/moderation/' . ($comic->is_moderated === 'successful' ? 'success-icon.svg' : ($comic->is_moderated === 'unsuccessful' ? 'reject-icon.svg' : 'hold-on-icon.svg')) ) }}" class="icon-24" alt="icon">
                <p>{{ $comic->status }}</p>
            </div>
            <div class="comic-links">
                @if ($comic->is_published === true)
                    <a href="{{ route('author_comic', $comic->slug) }}" target="_blank" class="tertiary-btn">
                        Читать комикс
                        <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                    </a>
                @endif
                @if ($comic->is_moderated !== 'successful' || !$comic->is_published)
                    <a href="{{ route('user.moderation-confirm-comics', $comic->slug) }}" target="_blank" class="tertiary-btn">
                        Модерация
                        <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                    </a>
                @endif
            </div>
        </div>
        <div class="comic-actions">
            @if($comic->is_published !== true)
                <a href="#" class="list-action-btn edit-comic-btn"
                   data-comic-id="{{ $comic->id }}"
                   data-comic-name="{{ $comic->name }}"
                   data-comic-description="{{ $comic->description }}"
                   data-comic-age_restriction="{{ $comic->age_restriction ? $comic->age_restriction . '+' : '0+' }}"
                   data-comic-genres="{{ $comic->genres_string }}"
                   data-comic-cover="{{ $comic->cover ? Storage::url($comic->cover) : '' }}"
                   data-comic-file="{{ $comic->comics_file ? Storage::url($comic->comics_file) : '' }}"
                   data-comic-file-name="{{ $comic->comics_file ? basename($comic->comics_file) : '' }}">
                    <img src="{{ asset('images/icons/edit-primary.svg') }}" class="icon-24" alt="edit-icon">
                </a>
            @endif
            <a href="#" class="list-action-btn delete-comic-btn"
               data-comic-id="{{ $comic->id }}"
               data-comic-name="{{ $comic->name }}">
                <img src="{{ asset('images/icons/trash-primary-red.svg') }}" class="icon-24" alt="delete-icon">
            </a>
        </div>
    </div>
@endforeach

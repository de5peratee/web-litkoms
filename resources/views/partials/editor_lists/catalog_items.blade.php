@foreach ($catalogs as $index => $catalog)
    <div class="catalog-item">
        <div class="comic-item-left">
            <div class="item-cell num-cell">{{ $loop->iteration + ($page ?? 0) * 10 }}</div>
            <div class="item-cell comic-preview-cell">
                <div class="comic-cover-wrapper">
                    <img src="{{ $catalog->cover ? Storage::url($catalog->cover) : asset('images/default_template/comics.svg') }}" class="comic-cover" alt="icon">
                </div>
                <div class="comic-preview-text-wrapper">
                    <p class="text-big">{{ $catalog->name }}</p>
                    <p class="text-hint author-text">{{ $catalog->author }}</p>
                </div>
            </div>
            <div class="item-cell">
                <a href="{{ route('library.get_book', $catalog->id) }}" target="_blank" class="tertiary-btn">
                    Подробнее
                    <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                </a>
            </div>
        </div>
        <div class="catalog-actions">
            <a href="#" class="list-action-btn edit-catalog-btn"
               data-catalog-id="{{ $catalog->id }}"
               data-catalog-name="{{ $catalog->name }}"
               data-catalog-author="{{ $catalog->author }}"
               data-catalog-description="{{ $catalog->description }}"
               data-catalog-release_year="{{ $catalog->release_year }}"
               data-catalog-genres="{{ $catalog->genres->pluck('name')->implode(', ') }}"
               data-catalog-cover="{{ $catalog->cover ? Storage::url('/' . $catalog->cover) : '' }}">
                <img src="{{ asset('images/icons/edit-primary.svg') }}" class="icon-24" alt="edit-icon">
            </a>
            <a href="#" class="list-action-btn delete-catalog-btn"
               data-catalog-id="{{ $catalog->id }}"
               data-catalog-name="{{ $catalog->name }}">
                <img src="{{ asset('images/icons/trash-primary-red.svg') }}" class="icon-24" alt="delete-icon">
            </a>
        </div>
    </div>
@endforeach

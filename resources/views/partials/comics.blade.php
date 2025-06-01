@foreach($comics as $comic)
    <a href="{{ route('library.get_book', $comic->id) }}" class="comic-card">
        @if($comic->cover)
            <div class="cover_wrapper">
                <img src="{{ Storage::url($comic->cover) }}" alt="{{ $comic->name }}">
            </div>
        @else
            <div class="cover_wrapper">
                <img src="{{ asset('images/default_template/comics.svg') }}" alt="comics_cover">
            </div>
        @endif

        <div class="comic-text-data">
            <div class="comic-genres" data-genres="{{ $comic->genres->pluck('name')->join(',') }}">
                @foreach ($comic->genres as $genre)
                    <span class="comic-genre-tag text-hint">{{ $genre->name }}</span>
                @endforeach
            </div>


            <p class="text-big comic-title-text" title="{{ $comic->name }}">{{ $comic->name }}</p>
            <p class="text-small comic-author-text" title="{{ $comic->author }}">{{ $comic->author }}</p>
        </div>
    </a>
@endforeach

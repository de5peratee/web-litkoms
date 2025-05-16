@foreach($library as $book)
    <a href="{{ route('library.get_book', $book->id) }}" class="book">
        @if($book->cover)
            <div class="cover_wrapper">
                <img src="{{ Storage::url($book->cover) }}" alt="{{ $book->name }}">
            </div>
        @else
            <div class="cover_wrapper">
                <img src="{{ asset('images/default_template/comics.svg') }}" alt="comics_cover">
            </div>
        @endif

        <div class="book-text-data">
            <div class="book-categories" data-genres="{{ $book->genres->pluck('name')->join(',') }}">
                @foreach ($book->genres as $genre)
                    <span class="book-category-tag text-hint">{{ $genre->name }}</span>
                @endforeach
            </div>


            <p class="text-big">{{ $book->name }}</p>
            <p class="text-small book-author-text">{{ $book->author }}</p>
        </div>
    </a>
@endforeach

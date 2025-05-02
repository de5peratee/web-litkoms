@foreach($library as $book)
    <a href="{{ route('library.get_book', $book->id) }}" class="book">
        @if($book->cover && Storage::exists($book->cover))
            <div class="cover_wrapper">
                <img src="{{ Storage::url($book->cover) }}" alt="{{ $book->name }}">
            </div>
        @else
            <div class="cover_wrapper">
                <img src="{{ asset('images/default_template/comics.svg') }}" alt="comics_cover">
            </div>
        @endif

        <div class="description">
            <p class="text-big">{{ $book->name }}</p>
            <p>Автор: {{ $book->author }}</p>
            <p>Год выпуска: {{ $book->release_year }}</p>
            <p>Жанры: {{ $book->genres->pluck('name')->join(', ') }}</p>
        </div>
    </a>
@endforeach

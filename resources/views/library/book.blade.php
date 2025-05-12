@extends('layouts.app')

@section('title', $book->name)

@section('content')
    @vite(['resources/css/book.css'])
    @vite(['resources/css/pdf-viewer.css'])
    @vite(['resources/js/pdf-viewer.js'])

    <div class="book-container">
        <div class="path-bar">
            <a href="{{ $backUrl }}" class="text-hint">Назад</a>
            <img src="{{ asset('images/icons/arrow-right.svg') }}"  class="icon-24" alt="icon">
            <a href="{{ route('library.index', ['search' => $book->name]) }}" class="text-hint">{{ $book->name }}</a>
        </div>

        <div class="info-block">
            <div class="book-preview-info">
                @if($book->cover && Storage::exists($book->cover))
                    <div class="cover_wrapper">
                        <img src="{{ Storage::url($book->cover) }}" alt="{{ $book->name }}">
                    </div>
                @else
                    <div class="cover_wrapper">
                        <img src="{{ asset('images/default_template/comics.svg') }}" alt="comics_cover">
                    </div>
                @endif

                <div class="book-text-description">
                    <h2 class="book-title">«{{ $book->name }}»</h2>
                    <div class="genres-wrapper">
                        @foreach ($book->genres as $genre)
                            <p class="text-small genre-tag">{{ $genre->name }}</p>
                        @endforeach
                    </div>

                    <div class="text-info-flex">
                        <div class="author-info-wrapper">
                            <p class="author-title">Автор(ы)</p>
                            <p class="text-big">{{ $book->author }}</p>
                        </div>

                        <div class="year-info-wrapper">
                            <p class="year-title">Год выпуска</p>
                            <p class="text-big">{{ $book->release_year }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="info-block">

            <div class="info-header">
                <img src="{{ asset('images/icons/hw/feather.svg') }}" class="icon-32" alt="icon">
                <h3>Сюжет</h3>
            </div>

            <div class="h-divider"></div>

            @isset($book->description)
                <p class="text-medium">{{ $book->description }}</p>
            @else
                <p class="text-medium">Описание отсутствует</p>
            @endisset
        </div>
        <div class="info-block">
            <div class="info-header">
                <h3>Читайте онлайн</h3>
            </div>

            <div class="h-divider"></div>

            <div class="pdf-view">
                <div class="pdf-controls">
                    <div class="pdf-desc">
                        <p class="text-big">PDF-Viewer (beta)</p>
                    </div>
                    <div class="page-controls">
                        <button id="prev-page" class="pdf-btn">Назад</button>
                        <div class="page-count-wrapper">
                            <p id="page-num"></p>
                            <p>/</p>
                            <p id="page-count"></p>
                        </div>
                        <button id="next-page" class="pdf-btn">Вперед</button>
                    </div>

                    <div class="zoom-controls">
                        <button id="zoom-in" class="pdf-btn">
                            <img src="{{ asset('images/icons/plus-icon-white.svg') }}" class="icon-20" alt="icon">
                        </button>
                        <button id="zoom-out" class="pdf-btn">
                            <img src="{{ asset('images/icons/minus-icon-white.svg') }}" class="icon-20" alt="icon">
                        </button>
                    </div>
                </div>
                <canvas id="pdf-canvas"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>
    <script>
        window.pdfUrl = '{{ Storage::url('design_book.pdf') }}';
    </script>

@endsection

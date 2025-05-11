@extends('layouts.app')

@section('title', $book->name)

@section('content')
    @vite(['resources/css/book.css'])
    @vite(['resources/css/pdf-viewer.css'])
    @vite(['resources/js/pdf-viewer.js'])

    <div class="book-container">
        <div class="path-bar">
            <a href="{{ $backUrl }}" class="text-hint">Назад</a>
            <img src="{{ asset('images/icons/arrow-left.svg') }}"  class="icon-24" alt="icon">
            <a href="{{ route('library.index', ['search' => $book->name]) }}" class="text-hint">{{ $book->name }}</a>
        </div>

        <div class="info-block">
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
        </div>

        <div class="info-block">

            <div class="info-header">
                <img src="{{ asset('images/icons/hw/feather.svg') }}" class="icon-32" alt="icon">
                <h3>Сюжет</h3>
            </div>

            @isset($book->description)
                <p class="text-medium">{{ $book->description }}</p>
            @else
                <p class="text-medium">Описание отсутствует</p>
            @endisset
        </div>


        <div class="info-block">
            <div class="pdf-view">
                <div class="pdf-controls">
                    <button id="prev-page" class="pdf-btn">Предыдущая</button>
                    <span id="page-num"></span> / <span id="page-count"></span>
                    <button id="next-page" class="pdf-btn">Следующая</button>
                    <button id="zoom-in" class="pdf-btn">+</button>
                    <button id="zoom-out" class="pdf-btn">-</button>
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

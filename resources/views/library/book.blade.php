@extends('layouts.app')

@section('title', $book->name)

@section('content')
    @vite(['resources/css/book.css'])

    <div class="path-bar">
        <a href="{{ $backUrl }}" class="text-hint">Назад</a>
        <img src="{{ asset('images/icons/arrow-left.svg') }}" alt="icon">
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
        <h3>Сюжет</h3>
        @isset($book->description)
            <p class="text-medium">{{ $book->description }}</p>
        @else
            <p class="text-medium">Описание отсутствует</p>
        @endisset
    </div>
@endsection

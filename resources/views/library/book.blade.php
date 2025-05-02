@extends('layouts.app')  <!-- Используем главный шаблон -->

@section('title', 'Название книги/комикса')  <!-- Устанавливаем название страницы -->

@section('content')
    <div class="book-details">
        @if($book->cover && Storage::exists($book->cover))
            <div class="cover_wrapper">
                <img src="{{ Storage::url($book->cover) }}" alt="{{ $book->name }}">
            </div>
        @else
            <div class="cover_wrapper">
                <img src="{{ asset('images/default_template/comics.svg') }}" alt="comics_cover">
            </div>
        @endif

        <h3>{{ $book->name }}</h3>
        <p>Автор: {{ $book->author }}</p>
        <p>Описание: {{ $book->description }}</p>
        <p>Год выпуска: {{ $book->release_year }}</p>
        <p>Жанры: {{$book->genres}}</p>
        <a href="{{ route('library.index') }}">Назад к списку</a>
    </div>
@endsection

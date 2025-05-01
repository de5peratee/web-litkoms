@extends('layouts.app')  <!-- Используем главный шаблон -->

@section('title', 'Название книги/комикса')  <!-- Устанавливаем название страницы -->

@section('content')
    <div class="book-details">
        <h1>{{ $book->name }}</h1>
        <p>Автор: {{ $book->author }}</p>
        <p>Описание: {{ $book->description }}</p>
        <p>Год выпуска: {{ $book->release_year }}</p>
        <p>Жанры: {{$book->genres}}</p>
        <a href="{{ route('library.index') }}">Назад к списку</a>
    </div>
@endsection

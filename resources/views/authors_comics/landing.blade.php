@extends('layouts.app')

@section('title', 'Авторские комиксы')

@section('content')
    @vite(['resources/css/authors-comics-landing.css'])

    <h1>Авторские комиксы</h1>
    <a href="{{ route('authors_comics_library')}}">
        Авторские комиксы библиотека (жмак)
    </a>
    <p>Здесь будет контент </p>
@endsection

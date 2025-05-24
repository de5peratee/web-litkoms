<!-- resources/views/home.blade.php -->
@extends('layouts.app')  <!-- Используем главный шаблон -->

@section('title', 'Главная')  <!-- Устанавливаем название страницы -->

@section('content')
    @vite(['resources/css/news.css'])

    <h1>Добро пожаловать на главную страницу!</h1>
    <p>Здесь будет контент вашей главной страницы.</p>
@endsection

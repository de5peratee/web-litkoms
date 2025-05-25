<!-- resources/views/home.blade.php -->
@extends('layouts.app')  <!-- Используем главный шаблон -->

@section('title', 'Главная')  <!-- Устанавливаем название страницы -->

@section('content')
    @vite(['resources/css/home.css'])

    <div class="hone-container">
        <h2>Главная</h2>
    </div>

{{--    <div class="web-radio-cta" id="floating-blob">--}}
{{--        <img src="{{ asset('images/blob.svg') }}" alt="icon">--}}
{{--    </div>--}}

@endsection

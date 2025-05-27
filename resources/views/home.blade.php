@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    @vite(['resources/css/home.css'])

    <div class="hone-container">
        <h2>Главная</h2>
    </div>

{{--    <div class="web-radio-cta" id="floating-blob">--}}
{{--        <img src="{{ asset('images/blob.svg') }}" alt="icon">--}}
{{--    </div>--}}

@endsection

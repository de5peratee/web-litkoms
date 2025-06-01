@extends('layouts.app')

@section('title', 'Подтверждение Email')

@section('content')
    @vite(['resources/css/verify-email.css'])
    @vite(['resources/js/verify-email.js'])

    <div class="verify-email-container">
        <img src="{{ asset('images/email-verify-picture.svg') }}" class="icon-128" alt="image">

        <h3>Подтвердите ваш Email</h3>

        @if (session('status'))
            <p class="success">{{ session('status') }}</p>
        @endif

        <p>Мы отправили письмо на {{ auth()->user()->email }}.
            <br>Пожалуйста, проверьте почту и перейдите по ссылке для подтверждения.</p>

        <div class="h-divider"></div>

{{--        Тут заменишь сам--}}
{{--        method="POST" action="{{ route('verification.resend') }}"--}}
        <form method="GET" action="">
            @csrf
            <button type="submit" class="secondary-btn" id="resend-btn">
                Отправить повторно
            </button>
        </form>

        <p id="resend-timer" class="text-hint">Повторить через 60 секунд</p>
    </div>

@endsection

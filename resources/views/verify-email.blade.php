@extends('layouts.app')

@section('title', 'Подтверждение Email')

@section('content')
    @vite(['resources/css/verify-email.css', 'resources/js/verify-email.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="verify-email-container">
        <img src="{{ asset('images/email-verify-picture.svg') }}" class="icon-128" alt="Иконка подтверждения email">

        <h3>Подтвердите ваш Email</h3>

        @if (session('status'))
            <p class="success">{{ session('status') }}</p>
        @endif

        @if ($errors->any())
            <p class="error">{{ $errors->first() }}</p>
        @endif

        <p>Мы отправили письмо на {{ e(auth()->user()->email) }}.
            <br>Пожалуйста, проверьте почту и перейдите по ссылке для подтверждения.</p>

        <div class="h-divider"></div>

        <form method="POST" action="{{ route('verification.send') }}" id="resend-form">
            @csrf
            <button type="submit" class="secondary-btn" id="resend-btn" disabled>
                Отправить повторно
            </button>
        </form>

        <p id="resend-timer" class="text-hint" style="display: block;">Повторить через 60 секунд</p>
    </div>
@endsection
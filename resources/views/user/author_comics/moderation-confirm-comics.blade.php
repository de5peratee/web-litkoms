@extends('layouts.app')

@section('title', 'Модерация и подтверждение')

@section('content')
    @vite(['resources/css/user/moderation-confirm-comics.css'])

    <div class="moderation-confirm-container">
        <div class="moderation-confirm-container-header">
            <h3>Модерация</h3>
            <div class="progress-bar">
                <div class="progress-bar-endpoint active-endpoint">
                    {{--                    <img src="{{ asset('images/icons/moderation/success-icon.svg') }}" class="icon-24" alt="icon">--}}
                    <p>Загрузка комикса</p>
                </div>
                <div class="h-divider"></div>
                <div class="progress-bar-endpoint">
                    {{--                    <img src="{{ asset('images/icons/moderation/hold-on-icon.svg') }}" class="icon-24" alt="icon">--}}
                    <p>Модерация</p>
                </div>
                <div class="h-divider"></div>
                <div class="progress-bar-endpoint">
                    {{--                    <img src="{{ asset('images/icons/moderation/success-icon.svg') }}" class="icon-24" alt="icon">--}}
                    <p>Подтверждение</p>
                </div>
            </div>
        </div>

        <div class="h-divider"></div>

        <div class="moderation-confirm-message-wrapper">
            <p>Комикс отправлен на модерацию</p>
            <p>Модераторы проверяют контент комикса на соответствие авторских прав.  Это займет немного времени</p>
        </div>

        <div class="author-comics-preview">
            {{--        Тут можешь вывести для теста данные комикса (например название и обложку, чтоб протестить)--}}
        </div>

        <a href="{{ route('user.author_comics')}}" class="secondary-btn">
            Мои авторские комиксы
        </a>
    </div>

@endsection

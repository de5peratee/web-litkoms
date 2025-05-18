@extends('layouts.app')

@section('title', 'Модерация и подтверждение')

@section('content')
    @vite(['resources/css/user/moderation-confirm-comics.css'])

    <div class="moderation-confirm-container">

        <div class="moderation-confirm-container-header">
            <h3>Модерация</h3>
            <div class="progress-bar">
                <div class="progress-bar-endpoint">
                                        <img src="{{ asset('images/icons/moderation/success-icon.svg') }}" class="icon-24" alt="icon">
                    <p>Загрузка комикса</p>
                </div>
                <div class="h-divider"></div>
                <div class="progress-bar-endpoint active-endpoint">
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
{{--            <div class="icon-wrapper hold-on-wrapper">--}}
{{--                <img src="{{ asset('images/icons/moderation/hold-on-icon.svg') }}" class="icon-32" alt="icon">--}}
{{--            </div>--}}

            <div class="icon-wrapper success-wrapper">
                <img src="{{ asset('images/icons/moderation/success-icon.svg') }}" class="icon-32" alt="icon">
            </div>

            <div class="message-text">
{{--                <p class="message-title text-big">Комикс отправлен на модерацию</p>--}}
                <p class="message-title text-big">Модерация успешно пройдена</p>
{{--                <p class="message-description text-hint">Модераторы проверяют контент комикса на соответствие авторских прав. <br>Это займет немного времени</p>--}}
                <p class="message-description text-hint">Ваш комикс полностью соответствует авторским правам. <br>Теперь вы можете его опубликовать!</p>
            </div>
        </div>
        <div class="author-comics-preview">
            <div class="comics-preview-wrapper">
                <img src="{{ asset('images/default_template/comics.svg') }}" alt="comics_cover">
            </div>

            <div class="comics-preview-text">
                <div class="author-text-wraper">

                </div>
                <h3>«Тетрадь смерти»</h3>
                <p class="text-small">Присоединяйтесь к уникальному вечеру, посвященному искусству, инновациям и общению.</p>
            </div>
            {{--        Тут можешь вывести для теста данные комикса (например название и обложку, чтоб протестить)--}}
        </div>

{{--        <div class="moderation-confirm-actions">--}}
{{--            <a href="{{ route('user.author_comics')}}" class="secondary-btn">--}}
{{--                Мои авторские комиксы--}}
{{--            </a>--}}
{{--        </div>--}}

        <div class="h-divider"></div>
        <div class="moderation-confirm-actions">
            <a href="{{ route('user.author_comics')}}" class="secondary-btn">
                В черновики
            </a>
            <a href="{{ route('user.author_comics')}}" class="primary-btn">
                Опубликовать
            </a>
        </div>

    </div>

@endsection

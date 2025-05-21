@extends('layouts.app')

@section('title', 'Заявки на модерацию')

@section('content')
    @vite(['resources/css/editor/authors_сomics_submissions_list.css'])
{{--    @vite(['resources/js/editor/authors-сomics-submissions-status-tabs.js'])--}}

    <div class="submissions-list-container">
        <div class="submissions-list-container-header">
            <h2>Заявки</h2>
            <p class="text-medium">кол-во заявок</p>
        </div>

        <div class="submissions-list">
            <div class="submission-item">

                <div class="submission-comic-preview">
                    <div class="submission-comic-cover-wrapper"></div>

                    <div class="submission-comic-preview-text-wrapper">
                        <div class="preview-text-flex">
                            <p class="text-big">Название комикса</p>
                            <p class="text-hint age-restriction-tag">18+</p>
                        </div>

                        <div class="submission-author-text-wrapper">
                            <p>ник</p>
                            <p class="submission-author-text">·</p>
                            <p class="submission-author-text">ФИО Автора</p>
                        </div>

                        <p class="text-hint submission-datetime-tag">1 секунду назад</p>
                    </div>

                    <a href="{{ route('editor.comic_moderation') }}" class="tertiary-btn">
                        Модерация
                        <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                    </a>
                </div>


{{--                <a href="{{ route('editor.comic_moderation') }}" class="tertiary-btn">--}}
{{--                    Модерация--}}
{{--                    <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">--}}
{{--                </a>--}}

                <div class="submission-actions-tab-wrapper">
                    <div class="submission-action-tab">
                        <img src="{{ asset('images/icons/dislike-primary.svg') }}" class="icon-24" alt="icon">
                    </div>
                    <div class="submission-action-tab active-submission-tab">
                        |
                    </div>
                    <div class="submission-action-tab">
                        <img src="{{ asset('images/icons/like-primary.svg') }}" class="icon-24" alt="icon">
                    </div>
                </div>
            </div>
            <div class="submission-item">

                <div class="submission-comic-preview">
                    <div class="submission-comic-cover-wrapper"></div>

                    <div class="submission-comic-preview-text-wrapper">
                        <div class="preview-text-flex">
                            <p class="text-big">Название комикса</p>
                            <p class="text-hint age-restriction-tag">18+</p>
                        </div>

                        <div class="submission-author-text-wrapper">
                            <p>ник</p>
                            <p class="submission-author-text">·</p>
                            <p class="submission-author-text">ФИО Автора</p>
                        </div>

                        <p class="text-hint submission-datetime-tag">1 секунду назад</p>
                    </div>

                    <a href="{{ route('editor.comic_moderation') }}" class="tertiary-btn">
                        Модерация
                        <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                    </a>
                </div>


                {{--                <a href="{{ route('editor.comic_moderation') }}" class="tertiary-btn">--}}
                {{--                    Модерация--}}
                {{--                    <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">--}}
                {{--                </a>--}}

                <div class="submission-actions-tab-wrapper">
                    <div class="submission-action-tab">
                        <img src="{{ asset('images/icons/dislike-primary.svg') }}" class="icon-24" alt="icon">
                    </div>
                    <div class="submission-action-tab active-submission-tab">
                        |
                    </div>
                    <div class="submission-action-tab">
                        <img src="{{ asset('images/icons/like-primary.svg') }}" class="icon-24" alt="icon">
                    </div>
                </div>
            </div>
            <div class="submission-item">

                <div class="submission-comic-preview">
                    <div class="submission-comic-cover-wrapper"></div>

                    <div class="submission-comic-preview-text-wrapper">
                        <div class="preview-text-flex">
                            <p class="text-big">Название комикса</p>
                            <p class="text-hint age-restriction-tag">18+</p>
                        </div>

                        <div class="submission-author-text-wrapper">
                            <p>ник</p>
                            <p class="submission-author-text">·</p>
                            <p class="submission-author-text">ФИО Автора</p>
                        </div>

                        <p class="text-hint submission-datetime-tag">1 секунду назад</p>
                    </div>

                    <a href="{{ route('editor.comic_moderation') }}" class="tertiary-btn">
                        Модерация
                        <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                    </a>
                </div>


                {{--                <a href="{{ route('editor.comic_moderation') }}" class="tertiary-btn">--}}
                {{--                    Модерация--}}
                {{--                    <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">--}}
                {{--                </a>--}}

                <div class="submission-actions-tab-wrapper">
                    <div class="submission-action-tab">
                        <img src="{{ asset('images/icons/dislike-primary.svg') }}" class="icon-24" alt="icon">
                    </div>
                    <div class="submission-action-tab active-submission-tab">
                        |
                    </div>
                    <div class="submission-action-tab">
                        <img src="{{ asset('images/icons/like-primary.svg') }}" class="icon-24" alt="icon">
                    </div>
                </div>
            </div>
            <div class="submission-item">

                <div class="submission-comic-preview">
                    <div class="submission-comic-cover-wrapper"></div>

                    <div class="submission-comic-preview-text-wrapper">
                        <div class="preview-text-flex">
                            <p class="text-big">Название комикса</p>
                            <p class="text-hint age-restriction-tag">18+</p>
                        </div>

                        <div class="submission-author-text-wrapper">
                            <p>ник</p>
                            <p class="submission-author-text">·</p>
                            <p class="submission-author-text">ФИО Автора</p>
                        </div>

                        <p class="text-hint submission-datetime-tag">1 секунду назад</p>
                    </div>

                    <a href="{{ route('editor.comic_moderation') }}" class="tertiary-btn">
                        Модерация
                        <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                    </a>
                </div>


                {{--                <a href="{{ route('editor.comic_moderation') }}" class="tertiary-btn">--}}
                {{--                    Модерация--}}
                {{--                    <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">--}}
                {{--                </a>--}}

                <div class="submission-actions-tab-wrapper">
                    <div class="submission-action-tab">
                        <img src="{{ asset('images/icons/dislike-primary.svg') }}" class="icon-24" alt="icon">
                    </div>
                    <div class="submission-action-tab active-submission-tab">
                        |
                    </div>
                    <div class="submission-action-tab">
                        <img src="{{ asset('images/icons/like-primary.svg') }}" class="icon-24" alt="icon">
                    </div>
                </div>
            </div>
            <div class="submission-item">

                <div class="submission-comic-preview">
                    <div class="submission-comic-cover-wrapper"></div>

                    <div class="submission-comic-preview-text-wrapper">
                        <div class="preview-text-flex">
                            <p class="text-big">Название комикса</p>
                            <p class="text-hint age-restriction-tag">18+</p>
                        </div>

                        <div class="submission-author-text-wrapper">
                            <p>ник</p>
                            <p class="submission-author-text">·</p>
                            <p class="submission-author-text">ФИО Автора</p>
                        </div>

                        <p class="text-hint submission-datetime-tag">1 секунду назад</p>
                    </div>

                    <a href="{{ route('editor.comic_moderation') }}" class="tertiary-btn">
                        Модерация
                        <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                    </a>
                </div>


                {{--                <a href="{{ route('editor.comic_moderation') }}" class="tertiary-btn">--}}
                {{--                    Модерация--}}
                {{--                    <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">--}}
                {{--                </a>--}}

                <div class="submission-actions-tab-wrapper">
                    <div class="submission-action-tab">
                        <img src="{{ asset('images/icons/dislike-primary.svg') }}" class="icon-24" alt="icon">
                    </div>
                    <div class="submission-action-tab active-submission-tab">
                        |
                    </div>
                    <div class="submission-action-tab">
                        <img src="{{ asset('images/icons/like-primary.svg') }}" class="icon-24" alt="icon">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

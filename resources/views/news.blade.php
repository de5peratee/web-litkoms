@extends('layouts.app')

@section('title', 'Лента')

@section('content')
    @vite(['resources/css/news.css'])

    <div class="news-container">
        <div class="news-header">
            <h2>Лента</h2>

            <div class="news-tabs-wrapper">
                <div class="news-tab active-tab">
                    <img src="{{ asset('images/icons/comics-icon-white.svg') }}" alt="icon" class="icon-24">
                    Комиксы
                </div>
                <div class="news-tab">
                    <img src="{{ asset('images/icons/event-primary.svg') }}" alt="icon" class="icon-24">
                    Мероприятия
                </div>
                <div class="news-tab">
                    <img src="{{ asset('images/icons/post-primary.svg') }}" alt="icon" class="icon-24">
                    Посты
                </div>
            </div>

        </div>

        <div class="newsfeed-container">
            <div class="posts-list">

                <div class="post-wrapper media-post">
                    <div class="post-media-grid images-count-5">
                        <div class="post-media-wrapper"></div>
                        <div class="post-media-wrapper"></div>
                        <div class="post-media-wrapper"></div>
                        <div class="post-media-wrapper"></div>
                        <div class="post-media-wrapper"></div>
{{--                        <div class="post-media-wrapper"></div>--}}
                    </div>

                    <div class="post-text-data">
                        <h3>Заголовок</h3>
                        <p class="text-small">Текст описания поста для чтения, сейчас пишу примерный
                            текст для прорисовки ширины контента поста</p>

                        <a href="" class="tertiary-btn">
                            Читать больше
                            <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                        </a>

                        <div class="files-container">
                            <div class="file-wrapper">
                                <img src="{{ asset('images/icons/attachment-secondary.svg') }}" class="icon-20" alt="icon">
                                название файла.pdf
                            </div>
                        </div>

                        <div class="h-divider"></div>

                        <div class="post-meta-wrapper">
                            <div class="post-author-wrapper">
                                <div class="post-author-avatar-wrapper">
                                    <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="ava_cover">
                                </div>
                                <p class="post-author-text">@fanat_of_manga</p>
                            </div>

                            <p class="post-datetime-text text-hint">1 день назад</p>
                        </div>
                    </div>

                </div>

                <div class="post-wrapper author-comic-post">
                    <div class="comic-flex-wrapper">
                        <div class="author-comic-preview-wrapper">
                        </div>

                        <div class="post-text-data">
                            <div class="new-tag">
                                <img src="{{ asset('images/icons/energy-blue.svg') }}" class="icon-20" alt="icon">
                                НОВЫЙ КОМИКС
                            </div>

                            <h3>«Тетрадь смерти»</h3>

                            <div class="comic-genres">
                                <p class="comic-genre-tag text-hint">Жанр</p>
                                <p class="comic-genre-tag text-hint">Жанр</p>
                                <p class="comic-genre-tag text-hint">Жанр</p>
                                <p class="comic-genre-tag text-hint more-genres">+n жанров</p>
                            </div>

                            <p class="text-small comic-description">Краткое описание комикса. Краткое описание комикса. Краткое описание комикса. Краткое описание комикса</p>

                            <a href="" class="primary-btn">
                                Подробнее
                                <img src="{{ asset('images/icons/arrow-top-right-white.svg') }}" class="icon-20" alt="icon">
                            </a>
                        </div>
                    </div>

                    <div class="h-divider"></div>

                    <div class="post-meta-wrapper">
                        <div class="post-author-wrapper">
                            <div class="post-author-avatar-wrapper">
                                <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="ava_cover">
                            </div>

                            <p class="post-author-text">@fanat_of_manga</p>

                            <div class="subscribed-wrapper">
                                Вы подписаны
                                <img src="{{ asset('images/icons/check-gray.svg') }}" class="icon-24" alt="icon">
                            </div>
                        </div>

                        <p class="post-datetime-text text-hint">1 день назад</p>
                    </div>
                </div>

                <div class="post-wrapper event-post">
                    <div class="post-event-wrapper">

                    </div>

                    <div class="post-text-data">

                        <div class="event-title-wrapper">
                            <h3>Заголовок мероприятия длинный</h3>
                            <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                        </div>

                        <p class="text-small">Текст описания поста для чтения, сейчас пишу примерный
                            текст для прорисовки ширины контента поста.</p>

                        <div class="h-divider"></div>

                        <div class="post-meta-wrapper">
                            <div class="event-datetime-wrapper">
                                <img src="{{ asset('images/icons/calendar-tertiary.svg') }}" class="icon-20" alt="icon">
                                <p class="text-small">15 мая 18:00</p>
                            </div>
                            <div class="event-location-wrapper">
                                <img src="{{ asset('images/icons/location-tertiary.svg') }}" class="icon-20" alt="icon">
                                <p class="text-small">ул. Маршала, д.9</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection

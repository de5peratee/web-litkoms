@extends('layouts.app')

@section('title', 'Авторские комиксы')

@section('content')
    @vite(['resources/css/authors-comics-landing.css'])

    <div class="authors-comics-container">

        <div class="hero-block">

            <img src="{{ asset('images/comics_big.png') }}" alt="comics" class="big-comics">
            <img src="{{ asset('images/comics_small.png') }}" alt="comics" class="small-comics">

            <div class="hero-tag">Авторские комиксы</div>

            <div class="hero-title-text-wrapper">
                <h2>Как это работает?</h2>
                <p class="text-big">Публикуйте свои комиксы бесплатно!</p>
            </div>

            <div class="steps-wrapper">

                <div class="steps-list">
                    <div class="step-block">
                        <img src="{{ asset('images/icons/steps/step1.svg') }}" alt="icon" class="step-img">
                        <div class="h-divider"></div>

                        <div class="step-title">
                            <p class="text-medium">Загрузите комикс</p>
                            <p class="step-num">01</p>
                        </div>
                    </div>

                    <div class="step-block">
                        <img src="{{ asset('images/icons/steps/step2.svg') }}" alt="icon" class="step-img">
                        <div class="h-divider"></div>

                        <div class="step-title">
                            <p class="text-medium">Пройдите модерацию</p>
                            <p class="step-num">02</p>
                        </div>
                    </div>

                    <div class="step-block">
                        <img src="{{ asset('images/icons/steps/step3.svg') }}" alt="icon" class="step-img">
                        <div class="h-divider"></div>

                        <div class="step-title">
                            <p class="text-medium">Готово!</p>
                            <p class="step-num">03</p>
                        </div>
                    </div>
                </div>

{{--                Добавить проверку, если не авторизировать, перенаправлять на аторизацию--}}
                <a href="{{ route('user.create_author_comics') }}" class="primary-btn">
                    Загрузить комикс
                    <img src="{{ asset('images/icons/arrow-top-right-white.svg') }}" alt="icon" class="icon-24">
                </a>
            </div>
        </div>

        <div class="info-block new-comics">
            <div class="info-header">
                <h3>Новые публикации</h3>
                <a href="{{ route('authors_comics_library') }}" class="tertiary-btn">
                    Перейти в каталог
                    <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                </a>
            </div>

            <div class="h-divider"></div>

            <div class="comics-list-wrapper">
                <div class="comic">
                    <div class="comic-cover-wrapper"></div>

                    <div class="comic-tags-wrapper">
                        <p class="text-hint comic-tag">Жанр</p>
                        <p class="text-hint comic-tag">Жанр</p>
                        <p class="text-hint comic-tag more-tag">+n жанров</p>
                    </div>

                    <div class="comic-title">
                        <p class="text-big">Название комикса</p>
                        <p class="comic-author-text">ФИО Автора</p>
                    </div>

                    <div class="comic-avg-grade">
                        <img src="{{ asset('images/icons/star.svg') }}" alt="icon" class="icon-24">
                        <p>0.0</p>
                    </div>
                </div>
                <div class="comic">
                    <div class="comic-cover-wrapper"></div>

                    <div class="comic-tags-wrapper">
                        <p class="text-hint comic-tag">Жанр</p>
                        <p class="text-hint comic-tag">Жанр</p>
                        <p class="text-hint comic-tag more-tag">+n жанров</p>
                    </div>

                    <div class="comic-title">
                        <p class="text-big">Название комикса</p>
                        <p class="comic-author-text">ФИО Автора</p>
                    </div>

                    <div class="comic-avg-grade">
                        <img src="{{ asset('images/icons/star.svg') }}" alt="icon" class="icon-24">
                        <p>0.0</p>
                    </div>
                </div>
                <div class="comic">
                    <div class="comic-cover-wrapper"></div>

                    <div class="comic-tags-wrapper">
                        <p class="text-hint comic-tag">Жанр</p>
                        <p class="text-hint comic-tag">Жанр</p>
                        <p class="text-hint comic-tag more-tag">+n жанров</p>
                    </div>

                    <div class="comic-title">
                        <p class="text-big">Название комикса</p>
                        <p class="comic-author-text">ФИО Автора</p>
                    </div>

                    <div class="comic-avg-grade">
                        <img src="{{ asset('images/icons/star.svg') }}" alt="icon" class="icon-24">
                        <p>0.0</p>
                    </div>
                </div>
                <div class="comic">
                    <div class="comic-cover-wrapper"></div>

                    <div class="comic-tags-wrapper">
                        <p class="text-hint comic-tag">Жанр</p>
                        <p class="text-hint comic-tag">Жанр</p>
                        <p class="text-hint comic-tag more-tag">+n жанров</p>
                    </div>

                    <div class="comic-title">
                        <p class="text-big">Название комикса</p>
                        <p class="comic-author-text">ФИО Автора</p>
                    </div>

                    <div class="comic-avg-grade">
                        <img src="{{ asset('images/icons/star.svg') }}" alt="icon" class="icon-24">
                        <p>0.0</p>
                    </div>
                </div>
                <div class="comic">
                    <div class="comic-cover-wrapper"></div>

                    <div class="comic-tags-wrapper">
                        <p class="text-hint comic-tag">Жанр</p>
                        <p class="text-hint comic-tag">Жанр</p>
                        <p class="text-hint comic-tag more-tag">+n жанров</p>
                    </div>

                    <div class="comic-title">
                        <p class="text-big">Название комикса</p>
                        <p class="comic-author-text">ФИО Автора</p>
                    </div>

                    <div class="comic-avg-grade">
                        <img src="{{ asset('images/icons/star.svg') }}" alt="icon" class="icon-24">
                        <p>0.0</p>
                    </div>
                </div>
            </div>


        </div>

        <div class="info-block authors-rating">

            <img src="{{ asset('images/icons/hw/cup.svg') }}" class="icon-96 cup-icon" alt="icon">
            <img src="{{ asset('images/icons/hw/lamp.svg') }}" class="icon-48 lamp-icon" alt="icon">

            <div class="info-header">
                <h3>Топ авторов</h3>
                <p class="text-medium author-subtitle">Новые комиксы от новых авторов</p>
            </div>

            <div class="authors-grid">
                <div class="author-item">
                    <div class="author-avatar-wrapper">
                        <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="ava_cover">
                    </div>

                    <div class="author-item-text-wrapper">
                        <p class="text-big">nick</p>
                        <p class="author-name-text">Имя Фамилия</p>
                    </div>
                </div>
                <div class="author-item">
                    <div class="author-avatar-wrapper">
                        <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="ava_cover">
                    </div>

                    <div class="author-item-text-wrapper">
                        <p class="text-big">nick</p>
                        <p class="author-name-text">Имя Фамилия</p>
                    </div>
                </div>
                <div class="author-item">
                    <div class="author-avatar-wrapper">
                        <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="ava_cover">
                    </div>

                    <div class="author-item-text-wrapper">
                        <p class="text-big">nick</p>
                        <p class="author-name-text">Имя Фамилия</p>
                    </div>
                </div>
                <div class="author-item">
                    <div class="author-avatar-wrapper">
                        <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="ava_cover">
                    </div>

                    <div class="author-item-text-wrapper">
                        <p class="text-big">nick</p>
                        <p class="author-name-text">Имя Фамилия</p>
                    </div>
                </div>
                <div class="author-item">
                    <div class="author-avatar-wrapper">
                        <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="ava_cover">
                    </div>

                    <div class="author-item-text-wrapper">
                        <p class="text-big">nick</p>
                        <p class="author-name-text">Имя Фамилия</p>
                    </div>
                </div>
                <div class="author-item">
                    <div class="author-avatar-wrapper">
                        <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="ava_cover">
                    </div>

                    <div class="author-item-text-wrapper">
                        <p class="text-big">nick</p>
                        <p class="author-name-text">Имя Фамилия</p>
                    </div>
                </div>
                <div class="author-item">
                    <div class="author-avatar-wrapper">
                        <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="ava_cover">
                    </div>

                    <div class="author-item-text-wrapper">
                        <p class="text-big">nick</p>
                        <p class="author-name-text">Имя Фамилия</p>
                    </div>
                </div>
                <div class="author-item">
                    <div class="author-avatar-wrapper">
                        <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="ava_cover">
                    </div>

                    <div class="author-item-text-wrapper">
                        <p class="text-big">nick</p>
                        <p class="author-name-text">Имя Фамилия</p>
                    </div>
                </div>
                <div class="author-item">
                    <div class="author-avatar-wrapper">
                        <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="ava_cover">
                    </div>

                    <div class="author-item-text-wrapper">
                        <p class="text-big">nick</p>
                        <p class="author-name-text">Имя Фамилия</p>
                    </div>
                </div>
                <div class="author-item">
                    <div class="author-avatar-wrapper">
                        <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="ava_cover">
                    </div>

                    <div class="author-item-text-wrapper">
                        <p class="text-big">nick</p>
                        <p class="author-name-text">Имя Фамилия</p>
                    </div>
                </div>
            </div>

            <a href="{{ route('user.create_author_comics') }}" class="secondary-btn">
                Стать автором
                <img src="{{ asset('images/icons/arrow-top-right-white.svg') }}" alt="icon" class="icon-24">
            </a>
        </div>

        <div class="info-block subs-comics">
            <div class="info-header">
                <h3>От любимых авторов</h3>
                <a href="{{ route('authors_comics_library') }}" class="tertiary-btn">
                    Перейти в каталог
                    <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                </a>
            </div>

            <div class="h-divider"></div>

            <div class="comics-list-wrapper">
                <div class="comic">
                    <div class="comic-cover-wrapper"></div>

                    <div class="comic-tags-wrapper">
                        <p class="text-hint comic-tag">Жанр</p>
                        <p class="text-hint comic-tag">Жанр</p>
                        <p class="text-hint comic-tag more-tag">+n жанров</p>
                    </div>

                    <div class="comic-title">
                        <p class="text-big">Название комикса</p>
                        <p class="comic-author-text">ФИО Автора</p>
                    </div>

                    <div class="comic-avg-grade">
                        <img src="{{ asset('images/icons/star.svg') }}" alt="icon" class="icon-24">
                        <p>0.0</p>
                    </div>
                </div>
                <div class="comic">
                    <div class="comic-cover-wrapper"></div>

                    <div class="comic-tags-wrapper">
                        <p class="text-hint comic-tag">Жанр</p>
                        <p class="text-hint comic-tag">Жанр</p>
                        <p class="text-hint comic-tag more-tag">+n жанров</p>
                    </div>

                    <div class="comic-title">
                        <p class="text-big">Название комикса</p>
                        <p class="comic-author-text">ФИО Автора</p>
                    </div>

                    <div class="comic-avg-grade">
                        <img src="{{ asset('images/icons/star.svg') }}" alt="icon" class="icon-24">
                        <p>0.0</p>
                    </div>
                </div>
                <div class="comic">
                    <div class="comic-cover-wrapper"></div>

                    <div class="comic-tags-wrapper">
                        <p class="text-hint comic-tag">Жанр</p>
                        <p class="text-hint comic-tag">Жанр</p>
                        <p class="text-hint comic-tag more-tag">+n жанров</p>
                    </div>

                    <div class="comic-title">
                        <p class="text-big">Название комикса</p>
                        <p class="comic-author-text">ФИО Автора</p>
                    </div>

                    <div class="comic-avg-grade">
                        <img src="{{ asset('images/icons/star.svg') }}" alt="icon" class="icon-24">
                        <p>0.0</p>
                    </div>
                </div>
                <div class="comic">
                    <div class="comic-cover-wrapper"></div>

                    <div class="comic-tags-wrapper">
                        <p class="text-hint comic-tag">Жанр</p>
                        <p class="text-hint comic-tag">Жанр</p>
                        <p class="text-hint comic-tag more-tag">+n жанров</p>
                    </div>

                    <div class="comic-title">
                        <p class="text-big">Название комикса</p>
                        <p class="comic-author-text">ФИО Автора</p>
                    </div>

                    <div class="comic-avg-grade">
                        <img src="{{ asset('images/icons/star.svg') }}" alt="icon" class="icon-24">
                        <p>0.0</p>
                    </div>
                </div>
                <div class="comic">
                    <div class="comic-cover-wrapper"></div>

                    <div class="comic-tags-wrapper">
                        <p class="text-hint comic-tag">Жанр</p>
                        <p class="text-hint comic-tag">Жанр</p>
                        <p class="text-hint comic-tag more-tag">+n жанров</p>
                    </div>

                    <div class="comic-title">
                        <p class="text-big">Название комикса</p>
                        <p class="comic-author-text">ФИО Автора</p>
                    </div>

                    <div class="comic-avg-grade">
                        <img src="{{ asset('images/icons/star.svg') }}" alt="icon" class="icon-24">
                        <p>0.0</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

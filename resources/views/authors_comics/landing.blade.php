@extends('layouts.app')

@section('title', 'Авторские комиксы')

@section('content')
    @vite(['resources/css/authors-comics-landing.css'])

    <div class="authors-comics-container">
        <div class="hero-block">
            <h2>Авторские комиксы</h2>
            <p class="text-big">Публикуйте свои комиксы бесплатно!</p>

            <div class="hero-tag">Как это работает?</div>

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

                <a href="" class="primary-btn">
                    Загрузить комикс
                    <img src="{{ asset('images/icons/arrow-top-right-white.svg') }}" alt="icon" class="icon-24">
                </a>
            </div>
        </div>

        <div class="info-block new-comics">
            <div class="info-header">
                <h3>Новые</h3>
                <a href="{{ route('authors_comics_library')}}">Перейти в каталог</a>
            </div>
        </div>

        <div class="info-block authors-rating">
            <div class="info-header">
                <h3>Топ авторов</h3>
                <p>Новые комиксы от новых авторов</p>
            </div>

            <div class="authors-list">
                <div class="author-wrapper">
                    <img src="" alt="">
                    <p>ник</p>
                    <p>имя ф.</p>
                </div>

                <div class="author-wrapper">
                    <img src="" alt="">
                    <p>ник</p>
                    <p>имя ф.</p>
                </div>

                <div class="author-wrapper">
                    <img src="" alt="">
                    <p>ник</p>
                    <p>имя ф.</p>
                </div>

                <div class="author-wrapper">
                    <img src="" alt="">
                    <p>ник</p>
                    <p>имя ф.</p>
                </div>
            </div>

            <a href="" class="secondary-btn">
                Стать автором
            </a>
        </div>

        <div class="info-block subs-comics">
            <div class="info-header">
                <p>От любимых авторов</p>
                <a href="{{ route('authors_comics_library')}}">Перейти в каталог</a>
            </div>
        </div>
    </div>

@endsection

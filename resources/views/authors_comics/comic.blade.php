@extends('layouts.app')

{{--@section('title', $comic->name)--}}
@section('title', 'Наименование авторского комикса')

@section('content')
    @vite(['resources/css/comic.css'])
    @vite(['resources/css/pdf-viewer.css'])
    @vite(['resources/js/pdf-viewer.js'])
    @vite(['resources/js/rating.js'])

    <div class="comic-container">
        <div class="path-bar">
            <a href="" class="text-hint">Назад</a>
{{--            <a href="{{ $backUrl }}" class="text-hint">Назад</a>--}}
            <img src="{{ asset('images/icons/arrow-right.svg') }}" class="icon-24" alt="icon">
{{--            <a href="{{ route('library.index', ['search' => $comic->name]) }}" class="text-hint">{{ $comic->name }}</a>--}}
            <a href="" class="text-hint">Наименование авторского комикса</a>
        </div>

        <div class="info-block">
            <div class="comic-preview-info">
{{--                @if($comic->cover)--}}
{{--                    <div class="cover_wrapper">--}}
{{--                        <img src="{{ Storage::url($comic->cover) }}" alt="{{ $comic->name }}">--}}
{{--                    </div>--}}
{{--                @else--}}
{{--                    <div class="cover_wrapper">--}}
{{--                        <img src="{{ asset('images/default_template/comics.svg') }}" alt="comics_cover">--}}
{{--                    </div>--}}
{{--                @endif--}}
                <div class="cover_wrapper">
                    <img src="{{ asset('images/default_template/comics.svg') }}" alt="comics_cover">
                </div>

                <div class="comic-main-text-data">
                    <div class="user-analyse-wrapper">
                        <div class="user-avg-grade">
                            <img src="{{ asset('images/icons/star.svg') }}" class="icon-24" alt="icon">
                            <p>0.0</p>
                        </div>
                        <div class="user-views">
                            <img src="{{ asset('images/icons/views-icon-sec.svg') }}" class="icon-24" alt="icon">
                            <p>0</p>
                        </div>
                    </div>

                    <div class="comic-near-text">
                        {{--                    <h2 class="comic-title">«{{ $comic->name }}»</h2>--}}
                        <h2 class="comic-title">«Наименование авторского комикса»</h2>
                        <div class="genres-wrapper">
                            {{--                        @foreach ($comic->genres as $genre)--}}
                            {{--                            <p class="text-small genre-tag">{{ $genre->name }}</p>--}}
                            {{--                        @endforeach--}}
                            <p class="text-small genre-tag">Тег1</p>
                            <p class="text-small genre-tag">Тег2</p>
                            <p class="text-small genre-tag">Тег3</p>
                            <p class="text-small genre-tag">Тег4</p>
                        </div>
                    </div>

                    <div class="text-info-flex">
                        <div class="author-info-wrapper">
                            <p class="author-title">Автор(ы)</p>

                            <div class="author-data">
                                <div class="comics-author-avatar-wrapper">
                                    <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="ava_cover">
                                </div>


                                {{--                            <p class="text-big">{{ $comic->author }}</p>--}}
                                <p class="text-big">Текст, текст</p>
                            </div>
                        </div>

                        <div class="year-info-wrapper">
                            <p class="year-title">Дата публикайии</p>
{{--                            <p class="text-big">{{ $comic->release_year }}</p>--}}
                            <p class="text-big">Число</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="info-block">
            <div class="info-header">
                <img src="{{ asset('images/icons/hw/feather.svg') }}" class="icon-32" alt="icon">
                <h3>Сюжет</h3>
            </div>

            <div class="h-divider"></div>

{{--            @isset($comic->description)--}}
{{--                <p class="text-medium">{{ $comic->description }}</p>--}}
{{--            @else--}}
{{--                <p class="text-medium">Описание отсутствует</p>--}}
{{--            @endisset--}}
            <p class="text-medium">Описание отсутствует</p>
        </div>

        <div class="info-block">
            <div class="info-header double-header">
                <div class="header-title">
                    <img src="{{ asset('images/icons/hw/book.svg') }}" class="icon-32" alt="icon">
                    {{--                <h3>«{{ $comic->name }}»</h3>--}}
                    <h3>«Наименование авторского комикса»</h3>
                </div>

                <div class="grade-wrapper">
                    <div class="grade-title">
                        <p>Оцените комикс</p>
                    </div>
                    <div class="rating-stars" data-selected="0">
                        @for ($i = 1; $i <= 5; $i++)
                            <img src="{{ asset('images/icons/grade_star_outline.svg') }}"
                                 data-index="{{ $i }}"
                                 class="star-icon icon-24"
                                 alt="star">
                        @endfor

                        <input type="hidden" name="rating" id="comic-rating" value="0">
                    </div>
                </div>

            </div>

            <div class="h-divider"></div>

            <div class="pdf-view">
                <div class="pdf-controls">
                    <div class="pdf-desc">
                        <p class="text-big">Литкомс</p>
                    </div>
                    <div class="page-controls">
                        <button id="prev-page" class="pdf-btn">Назад</button>
                        <div class="page-count-wrapper">
                            <p id="page-num"></p>
                            <p>/</p>
                            <p id="page-count"></p>
                        </div>
                        <button id="next-page" class="pdf-btn">Вперед</button>
                    </div>

                    <div class="zoom-controls">
                        <button id="zoom-out" class="pdf-btn">
                            <img src="{{ asset('images/icons/minus-icon-white.svg') }}" class="icon-20" alt="icon">
                        </button>
                        <button id="zoom-in" class="pdf-btn">
                            <img src="{{ asset('images/icons/plus-icon-white.svg') }}" class="icon-20" alt="icon">
                        </button>
                    </div>
                </div>
                <canvas id="pdf-canvas"></canvas>
            </div>

            <a href="" class="primary-btn">
                Скачать комикс
                <img src="{{ asset('images/icons/download-icon-white.svg') }}" class="icon-24" alt="icon">
            </a>

        </div>

        <div class="info-block">
            <div class="info-header">
{{--                <img src="{{ asset('images/icons/hw/feather.svg') }}" class="icon-32" alt="icon">--}}
                <h3>Комментарии</h3>
            </div>

            <div class="comments-form-wrapper">
                <div class="input-comment-wrapper">
                    <input type="text" placeholder="Поделитель мнением о комиксе...">
                </div>
                <div class="comment-list-wrapper">
                    <div class="comment-item">
                        Коммент1
                    </div>
                    <div class="comment-item">
                        Коммент2
                    </div>
                    <div class="comment-item">
                        Коммент3
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>
    <script>
        {{--window.pdfUrl = '{{ Storage::url($comic->comics_file) }}';--}}
            window.pdfUrl = '{{ Storage::url('design_book.pdf') }}';
            window.filledStar = "{{ asset('images/icons/grade_star_fill.svg') }}";
            window.outlineStar = "{{ asset('images/icons/grade_star_outline.svg') }}";
    </script>
@endsection

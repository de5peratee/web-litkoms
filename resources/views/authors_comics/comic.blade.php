@extends('layouts.app')

@section('title', $authorComic->name)

@section('content')
    @vite(['resources/css/comic.css'])
    @vite(['resources/css/pdf-viewer.css'])
    @vite(['resources/css/inputs.css'])

    @vite(['resources/js/pdf-viewer.js'])
    @vite(['resources/js/rating.js'])
    @vite(['resources/js/comment.js'])
    @vite(['resources/js/toggleSubscription.js'])


    <div class="comic-container">
        <!-- Навигация -->
        <div class="path-bar">
            <a href="{{ route('authors_comics_library') }}" class="text-hint">Назад</a>
            <img src="{{ asset('images/icons/arrow-right.svg') }}" class="icon-24" alt="icon">
            <a href="{{ route('authors_comics_library', ['search' => $authorComic->name]) }}"
               class="text-hint">{{ $authorComic->name }}</a>
        </div>

        <!-- Информация о комиксе -->
        <div class="info-block">
            <div class="comic-preview-info">
                <div class="cover_wrapper">
                    <img src="{{ $authorComic->cover ? Storage::url($authorComic->cover) : asset('images/default_template/comics.svg') }}"
                         alt="{{ $authorComic->name }}" class="comic-cover">
                </div>
                <div class="comic-main-text-data">
                    <div class="user-analyse-wrapper">
                        <div class="user-avg-grade">
                            <img src="{{ asset('images/icons/grade_star_fill.svg') }}" class="icon-24" alt="icon">
                            <p>{{ number_format($averageRating, 1) }}</p>
                        </div>
                        <div class="user-views">
                            <img src="{{ asset('images/icons/views-icon-sec.svg') }}" class="icon-24" alt="icon">
                            <p>{{ $authorComic->views }}</p>
                        </div>
                    </div>
                    <div class="comic-near-text">
                        <h2 class="comic-title">«{{ $authorComic->name }}»</h2>
                        <div class="genres-wrapper">
                            @foreach ($authorComic->genres as $genre)
                                <p class="text-small genre-tag">{{ $genre->name }}</p>
                            @endforeach
                        </div>
                    </div>
                    <div class="text-info-flex">
                        <div class="author-info-wrapper">
                            <p class="author-title">Автор</p>
                            <div class="author-data">
                                <div class="comics-author-avatar-wrapper">
                                    @if($authorComic->createdBy->icon && Storage::disk('public')->exists($authorComic->createdBy->icon))
                                        <img src="{{ Storage::url($authorComic->createdBy->icon) }}" alt="avatar">
                                    @else
                                        <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="default avatar">
                                    @endif
{{--                                    <img src="{{ $authorComic->createdBy->icon ? Storage::url($authorComic->createdBy->icon) : asset('images/default_template/ava_cover.png') }}"--}}
{{--                                         alt="{{ '@' . $authorComic->createdBy->nickname }}" class="author-avatar">--}}
                                </div>
                                <a href="{{ route('profile.index', ['nickname' => $authorComic->createdBy->nickname]) }}">
                                    <p class="text-big">{{ '@' . $authorComic->createdBy->nickname }}</p>
                                </a>
                            </div>
                        </div>
                        <div class="year-info-wrapper">
                            <p class="year-title">Дата публикации</p>
                            <p class="text-big">{{ $authorComic->created_at->format('d.m.Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Описание -->
        <div class="info-block">
            <div class="info-header">
                <img src="{{ asset('images/icons/hw/feather.svg') }}" class="icon-32" alt="icon">
                <h3>Сюжет</h3>
            </div>
            <div class="h-divider"></div>
            <p class="text-medium">{{ $authorComic->description ?? 'Описание отсутствует' }}</p>
        </div>

        <!-- Просмотр PDF -->
        <div class="info-block">
            <div class="info-header double-header">
                <div class="header-title">
                    <img src="{{ asset('images/icons/hw/book.svg') }}" class="icon-32" alt="icon">
                    <h3>«{{ $authorComic->name }}»</h3>
                </div>
                @auth
                    <div class="grade-wrapper">
                        <div class="grade-title">
                            <p>Оценка</p>
                        </div>
                        <div class="rating-stars" data-selected="{{ $userRating }}">
                            @for ($i = 1; $i <= 5; $i++)
                                <img src="{{ $userRating >= $i ? asset('images/icons/grade_star_fill.svg') : asset('images/icons/grade_star_outline.svg') }}"
                                     data-index="{{ $i }}"
                                     class="star-icon icon-24"
                                     alt="star">
                            @endfor
                            <input type="hidden" name="rating" id="comic-rating" value="{{ $userRating }}">
                        </div>
                    </div>
                @endauth
            </div>
            <div class="h-divider"></div>
            <div class="pdf-view">
                <div class="pdf-controls sticky-controls">
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
{{--                <canvas id="pdf-canvas"></canvas>--}}
                <div id="pdf-canvas-wrapper" class="pdf-canvas-wrapper">
                    <canvas id="pdf-canvas"></canvas>
                </div>
            </div>
            <a href="{{ route('author_comic.download', $authorComic->slug) }}" class="primary-btn">
                Скачать комикс
                <img src="{{ asset('images/icons/download-icon-white.svg') }}" class="icon-24" alt="icon">
            </a>
        </div>

        <!-- Комментарии -->
        <div class="info-block">
            <div class="info-header">
                <h3>Комментарии</h3>
                <p class="text-medium counter" id="comments-count">{{ $comments->count() }}</p>
            </div>
            <div class="h-divider"></div>

            <div class="info-content">
                <div class="comment-part-block">
                    @auth
                        <form id="comment-form" action="{{ route('author_comic.comment', $authorComic->slug) }}" method="POST" class="lit-form">
                            @csrf

                            <div class="input-comment-wrapper">
                                <div class="lit-field">
                                    <input type="text" name="comment" id="comment-input" placeholder="Введите текст комментария..." required>
                                    <p class="error-message" id="comment-error" style="display: none;"></p>
                                </div>

                                <button type="submit" class="primary-btn send-btn">
                                    <img src="{{ asset('images/icons/send.svg') }}" class="icon-24" alt="icon">
                                </button>
                            </div>

                        </form>
                    @else
                        <p class="text-medium">Войдите, чтобы оставить комментарий.</p>
                    @endauth

                        <div class="comment-list-wrapper">
                            <div id="comment-list">
                                @forelse ($comments as $comment)
                                    @include('partials._comment', ['comment' => $comment])
                                @empty
                                    <p class="no-comments" id="no-comments">Комментариев пока нет.</p>
                                @endforelse
                            </div>

                            @if ($comments->hasMorePages())
                                <button class="secondary-btn comment-btn-load"
                                        id="load-more-comments"
                                        data-url="{{ route('author_comic.comments', $authorComic->slug) . '?page=2' }}">
                                    Загрузить еще
                                </button>
                            @endif
                        </div>
                </div>

                <div class="author-part-block">
                    <div class="author-profile-data">
                        <div class="big-author-avatar-wrapper">
                            <img src="{{ $authorComic->createdBy->icon ? Storage::url($authorComic->createdBy->icon) : asset('images/default_template/ava_cover.png') }}"
                                 alt="{{ '@' . $authorComic->createdBy->nickname }}" class="author-avatar">
                        </div>

                        <div class="author-profile-text-data">
                            <h3>{{ '@' . $authorComic->createdBy->nickname }}</h3>
                            <p class="author-name-text">{{$authorComic->createdBy->name }} {{$authorComic->createdBy->last_name }}</p>
                        </div>

                    </div>

                    <div class="author-actions">
                        <div class="profile-sub-action">
                            @auth
                                @if (Auth::id() !== $authorComic->created_by)
                                    <button class="primary-btn {{ $isSub ? 'subscribed-btn' : '' }}"
                                            id="subscribeBtn"
                                            data-nickname="{{ $authorComic->createdBy->nickname }}"
                                            data-issub="{{ $isSub ? 'true' : 'false' }}">
                                        @if($isSub)
                                            <p>Вы подписаны</p>
                                            <img src="{{ asset('images/icons/check-gray.svg') }}" alt="✓" class="icon-24">
                                        @else
                                            <p>Подписаться</p>
                                        @endif
                                    </button>
                                @endif
                            @endauth
                            <a href="{{ route('profile.index', ['nickname' => $authorComic->createdBy->nickname]) }}" class="tertiary-btn">
                                В профиль
                                <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>
    <script>
        window.pdfUrl = '{{ Storage::url($authorComic->comics_file) }}';
        window.filledStar = "{{ asset('images/icons/grade_star_fill.svg') }}";
        window.outlineStar = "{{ asset('images/icons/grade_star_outline.svg') }}";
        window.rateUrl = "{{ route('author_comic.rate', $authorComic->slug) }}";
    </script>
@endsection

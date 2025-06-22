@extends('layouts.app')

@section('title', 'Модерация авторского комикса')

@section('content')
    @vite(['resources/css/inputs.css'])
    @vite(['resources/css/editor/authors_comics_moderation.css'])
    @vite(['resources/css/pdf-viewer.css'])
    @vite(['resources/js/pdf-viewer.js'])

    <div class="author_comics-form-container">
        <div class="path-bar">
            <a href="{{ URL::previous() }}" class="text-hint">Назад</a>
            <img src="{{ asset('images/icons/arrow-right.svg') }}"  class="icon-24" alt="icon">
            <p class="text-hint">Модерация. {{ $comic->name }}</p>
        </div>

        <div class="info-block moderation-block">
            <h3>Страница модерации</h3>
        </div>

        <div class="info-block">
            <div class="comic-preview-info">
                <div class="cover_wrapper">
                    <img src="{{ $comic->cover ? Storage::url($comic->cover) : asset('images/default_template/comics.svg') }}"
                         alt="{{ $comic->name }}" class="comic-cover">
                </div>
                <div class="comic-main-text-data">
                    <div class="comic-near-text">
                        <h2 class="comic-title">«{{ $comic->name }}»</h2>
                        <div class="genres-wrapper">
                            @foreach ($comic->genres as $genre)
                                <p class="text-small genre-tag">{{ $genre->name }}</p>
                            @endforeach
                        </div>
                    </div>
                    <div class="text-info-flex">

                        <div class="author-info-wrapper">
                            <p class="author-title">Автор</p>
                            <div class="author-data">
                                <div class="comics-author-avatar-wrapper">
                                    @if($comic->createdBy->icon && Storage::disk('public')->exists($comic->createdBy->icon))
                                        <img src="{{ Storage::url($comic->createdBy->icon) }}" alt="avatar">
                                    @else
                                        <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="default avatar">
                                    @endif
                                    {{--                                    <img src="{{ $authorComic->createdBy->icon ? Storage::url($authorComic->createdBy->icon) : asset('images/default_template/ava_cover.png') }}"--}}
                                    {{--                                         alt="{{ '@' . $authorComic->createdBy->nickname }}" class="author-avatar">--}}
                                </div>
                                <a href="{{ route('profile.index', ['nickname' => $comic->createdBy->nickname]) }}">
                                    <p class="text-big">{{ '@' . $comic->createdBy->nickname }}</p>
                                </a>
                            </div>
                        </div>

                        <div class="year-info-wrapper">
                            <p class="year-title">Дата заявки</p>
                            <p class="text-big">{{ $comic->created_at->format('d.m.Y') }}</p>
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
            <p class="text-medium">{{ $comic->description ?? 'Описание отсутствует' }}</p>
        </div>

        <div class="info-block">
            <div class="info-header double-header">
                <div class="header-title">
                    <img src="{{ asset('images/icons/hw/book.svg') }}" class="icon-32" alt="icon">
                    <h3>«{{ $comic->name }}»</h3>
                </div>
                <a href="{{ route('author_comic.download', $comic->slug) }}" class="primary-btn">
                    Скачать комикс
                    <img src="{{ asset('images/icons/download-icon-white.svg') }}" class="icon-24" alt="icon">
                </a>
            </div>

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
        </div>

        <div class="info-block">
            <div class="info-header double-header">
                <div class="header-title">
                    <img src="{{ asset('images/icons/hw/validation-icon.svg') }}" class="icon-32" alt="icon">
                    <h3>Итоговое решение</h3>
                </div>
            </div>

            <div class="h-divider"></div>

            <form method="POST" action="{{ route('editor.comic_moderation', $comic->slug) }}" class="lit-form {{ $errors->any() ? 'has-error' : '' }}">
                @csrf
                @method('PUT')

                <div class="lit-field">
                    <label for="age_restriction">Возрастное ограничение</label>
                    <select name="age_restriction" id="age_restriction" class="{{ $errors->has('age_restriction') ? 'is-invalid' : '' }}">
                        <option value="6" {{ $comic->age_restriction == 6 ? 'selected' : '' }}>6+</option>
                        <option value="12" {{ $comic->age_restriction == 12 ? 'selected' : '' }}>12+</option>
                        <option value="16" {{ $comic->age_restriction == 16 ? 'selected' : '' }}>16+</option>
                        <option value="18" {{ $comic->age_restriction == 18 ? 'selected' : '' }}>18+</option>
                    </select>
                    @error('age_restriction')
                    <div class="input-error">{{ $message }}</div>
                    @enderror
                </div>


                <div class="lit-field">
                    <label for="feedback">Обратная связь (обязательна при отклонении)</label>
                    <textarea name="feedback" id="feedback" rows="5" placeholder="Введите текст обратной связи для автора (если комикс отклоняется)" class="{{ $errors->has('feedback') ? 'is-invalid' : '' }}">{{ old('feedback', $comic->feedback) }}</textarea>
                    @error('feedback')
                    <div class="input-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="h-divider"></div>

                <div class="form-actions">
                    <button type="submit" name="moderation_status" value="unsuccessful" class="secondary-btn">Отклонить</button>
                    <button type="submit" name="moderation_status" value="successful" class="primary-btn">Принять</button>
                </div>

            </form>
        </div>
    </div>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>
    <script>
        window.pdfUrl = '{{ Storage::url($comic->comics_file) }}';
        window.filledStar = "{{ asset('images/icons/grade_star_fill.svg') }}";
        window.outlineStar = "{{ asset('images/icons/grade_star_outline.svg') }}";
        window.rateUrl = "{{ route('author_comic.rate', $comic->slug) }}";
    </script>

@endsection

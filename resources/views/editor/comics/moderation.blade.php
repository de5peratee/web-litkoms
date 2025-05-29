@extends('layouts.app')

@section('title', 'Модерация авторского комикса')

@section('content')
    @vite(['resources/css/inputs.css'])
    @vite(['resources/css/editor/authors_comics_moderation.css'])
    @vite(['resources/css/pdf-viewer.css'])

    @vite(['resources/js/pdf-viewer.js'])

    <div class="author_comics-form-container">
        <div class="author_comics-form-container-header">
            <h3>Модерация</h3>
        </div>

        <div class="h-divider"></div>

        <div class="cover_wrapper">
            <img src="{{ $comic->cover ? Storage::url($comic->cover) : asset('images/default_template/comics.svg') }}"
                 alt="{{ $comic->name }}" class="comic-cover">
        </div>

        <div class="lit-field">
            <label>Название комикса</label>
            <p>{{ $comic->name }}</p>
        </div>

        <div class="lit-field">
            <label>Описание</label>
            <p>{{ $comic->description }}</p>
        </div>

        <div class="lit-field">
            <label>Жанры</label>
            <p class="text-small genre-tag">{{ $comic->genres->pluck('name')->implode(', ') }}</p>
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

        <div class="h-divider"></div>

        <form method="POST" action="{{ route('editor.comic_moderation', $comic->slug) }}" class="lit-form {{ $errors->any() ? 'has-error' : '' }}">
            @csrf
            @method('PUT')

            <h3>Итоговое решение</h3>

            <div class="lit-form-row">
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
            </div>

            <div class="form-actions">
                <button type="submit" name="moderation_status" value="successful" class="primary-btn">Принять</button>
                <button type="submit" name="moderation_status" value="unsuccessful" class="secondary-btn">Отклонить</button>
            </div>

        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>
    <script>
        window.pdfUrl = '{{ Storage::url($comic->comics_file) }}';
        window.filledStar = "{{ asset('images/icons/grade_star_fill.svg') }}";
        window.outlineStar = "{{ asset('images/icons/grade_star_outline.svg') }}";
        window.rateUrl = "{{ route('author_comic.rate', $comic->slug) }}";
    </script>

@endsection

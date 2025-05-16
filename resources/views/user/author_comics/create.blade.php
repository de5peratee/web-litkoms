@extends('layouts.app')

@section('title', 'Новый авторский комикс')

@section('content')
    @vite(['resources/css/inputs.css'])
    @vite(['resources/css/user/create_author_comics.css'])

    <div class="author_comics-form-container">
        <div class="author_comics-form-container-header">
            <h3>Форма публикации</h3>
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

        <form method="POST" action="{{ route('user.store_author_comics') }}" enctype="multipart/form-data" class="lit-form">
            @csrf

            <div class="lit-form-row">
                <div class="lit-fields-group">
                    <div class="lit-field">
                        <label for="title">Название комикса</label>
                        <input type="text" name="title" id="title" placeholder="Введите название комикса" value="{{ old('title') }}" class="{{ $errors->has('title') ? 'is-invalid' : '' }}">
                        @error('title')
                        <div class="input-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="lit-field">
                        <label for="genres">Жанры</label>
                        <input type="text" name="genres" id="genres" placeholder="Укажите жанры через запятую" value="{{ old('genres') }}" class="{{ $errors->has('genres') ? 'is-invalid' : '' }}">
                        @error('genres')
                        <div class="input-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="lit-field">
                        <label for="description">Описание</label>
                        <textarea name="description" id="description" rows="5" placeholder="Введите описание комикса" class="{{ $errors->has('description') ? 'is-invalid' : '' }}">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="input-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="lit-field">
                        <label for="comic_file">Файл комикса</label>
                        <input type="file" name="comic_file" id="comic_file" accept="application/pdf,image/*" class="{{ $errors->has('comic_file') ? 'is-invalid' : '' }}">
                        @error('comic_file')
                        <div class="input-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="lit-fields-group">
                    <div class="lit-field">
                        <label for="cover">Обложка комикса</label>
                        <input type="file" name="cover" id="cover" accept="image/*" class="{{ $errors->has('cover') ? 'is-invalid' : '' }}">
                        @error('cover')
                        <div class="input-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>


            <div class="lit-field">
                <label>
                    <input type="checkbox" name="agree" {{ old('agree') ? 'checked' : '' }}>
                    Я подтверждаю согласие с <a href="{{ route('manuals.policy') }}" target="_blank" class="link-text">политикой конфиденциальности</a> и правилами сообщества.
                </label>
                @error('agree')
                <div class="input-error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="primary-btn">Отправить на модерацию</button>
        </form>

    </div>
@endsection

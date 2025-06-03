@extends('layouts.app')

@section('title', 'Новая книга')

@section('content')
    @vite(['resources/css/editor/create_catalog.css'])
    @vite(['resources/css/inputs.css'])

    <div class="form-container">
        <div class="info-block">
            <div class="info-header">
                <div class="info-header-title">
                    <img src="{{ asset('images/icons/hw/library-form-icon.svg') }}" alt="icon" class="icon-32">
                    <h3>Новый комикс</h3>
                </div>
            </div>

            <div class="h-divider"></div>

            <form method="POST" action="{{ route('editor.store_catalog') }}" enctype="multipart/form-data" class="lit-form">
                @csrf

                <div class="lit-form-row">
                    <div class="lit-field">
                        <label for="cover">Обложка комикса</label>
                        <div class="cover-upload">
                            <input type="file" name="cover" id="cover" accept="image/*" class="{{ $errors->has('cover') ? 'is-invalid' : '' }}">
                            <div class="cover-preview" id="coverPreview"></div>
                        </div>
                        @error('cover')
                        <div class="input-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="lit-field">
                        <label for="name">Название комикса</label>
                        <input type="text" name="name" id="name" placeholder="Введите название" value="{{ old('name') }}" class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
                        @error('name')
                        <div class="input-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="lit-form-row">
                    <div class="lit-field">
                        <label for="author">Автор</label>
                        <input type="text" name="author" id="author" placeholder="Введите автора" value="{{ old('author') }}" class="{{ $errors->has('author') ? 'is-invalid' : '' }}">
                        @error('author')
                        <div class="input-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="lit-field">
                        <label for="release_year">Год выпуска</label>
                        <input type="number" name="release_year" id="release_year" placeholder="Введите год выпуска" value="{{ old('release_year') }}" class="{{ $errors->has('release_year') ? 'is-invalid' : '' }}">
                        @error('release_year')
                        <div class="input-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                <div class="lit-field">
                    <label for="description">Описание</label>
                    <textarea name="description" id="description" rows="5" placeholder="Подробное описание книги" class="{{ $errors->has('description') ? 'is-invalid' : '' }}">{{ old('description') }}</textarea>
                    @error('description')
                    <div class="input-error">{{ $message }}</div>
                    @enderror
                </div>


                <div class="lit-field">
                    <label for="genres">Жанры</label>
                    <input type="text" name="genres" id="genres" placeholder="Жанры через запятую" value="{{ old('genres') }}" class="{{ $errors->has('genres') ? 'is-invalid' : '' }}">
                    @error('genres')
                    <div class="input-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="h-divider"></div>

                <button type="submit" class="primary-btn">Опубликовать комикс</button>
            </form>
        </div>
    </div>
@endsection

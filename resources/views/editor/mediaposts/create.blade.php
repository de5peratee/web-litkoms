@extends('layouts.app')

@section('title', 'Новый пост')

@section('content')
    @vite(['resources/css/editor/create_post.css'])
{{--    ЗАМЕНИ СТИЛИ!--}}
    @vite(['resources/css/editor/create_event.css'])
    @vite(['resources/css/inputs.css'])

    <div class="form-container">
        <form method="POST" action="{{ route('editor.store_mediapost') }}" enctype="multipart/form-data" class="lit-form">
            @csrf

            <div class="lit-field">
                <label for="media">Медиафайлы</label>
                <div class="media-upload">
                    <input type="file" name="media[]" id="media" accept="image/*,video/*,application/pdf" multiple class="{{ $errors->has('media') ? 'is-invalid' : '' }}">
                    <div class="media-preview" id="mediaPreview"></div>
                </div>
                @error('media')
                <div class="input-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="lit-field">
                <label for="name">Название поста</label>
                <input type="text" name="name" id="name" placeholder="Введите название" value="{{ old('name') }}" class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
                @error('name')
                <div class="input-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="lit-field">
                <label for="description">Описание</label>
                <textarea name="description" id="description" rows="5" placeholder="Подробное описание поста" class="{{ $errors->has('description') ? 'is-invalid' : '' }}">{{ old('description') }}</textarea>
                @error('description')
                <div class="input-error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="primary-btn">Создать пост</button>
        </form>
    </div>
@endsection

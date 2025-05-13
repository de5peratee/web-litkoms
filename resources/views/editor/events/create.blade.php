@extends('layouts.app')

@section('title', 'Новое мероприятие')

@section('content')
    @vite(['resources/css/editor/create_event.css'])
    @vite(['resources/css/inputs.css'])

    <div class="form-container">
        <form method="POST" action="{{ route('editor.store_event') }}" enctype="multipart/form-data" class="lit-form">
            @csrf

            <div class="lit-field">
                <label for="cover">Обложка мероприятия</label>
                <div class="cover-upload">
                    <input type="file" name="cover" id="cover" accept="image/*" class="{{ $errors->has('cover') ? 'is-invalid' : '' }}">
                    <div class="cover-preview" id="coverPreview"></div>
                </div>
                @error('cover')
                <div class="input-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="lit-field">
                <label for="name">Название мероприятия</label>
                <input type="text" name="name" id="name" placeholder="Введите название" value="{{ old('name') }}" class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
                @error('name')
                <div class="input-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="lit-field">
                <label for="description">Описание</label>
                <textarea name="description" id="description" rows="5" placeholder="Подробное описание мероприятия" class="{{ $errors->has('description') ? 'is-invalid' : '' }}">{{ old('description') }}</textarea>
                @error('description')
                <div class="input-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="lit-field-group">
                <div class="lit-field">
                    <label for="start_date">Дата проведения</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" class="{{ $errors->has('start_date') ? 'is-invalid' : '' }}">
                    @error('start_date')
                    <div class="input-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="lit-field">
                    <label for="time">Время начала</label>
                    <input type="time" name="time" id="time" value="{{ old('time') }}" class="{{ $errors->has('time') ? 'is-invalid' : '' }}">
                    @error('time')
                    <div class="input-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="lit-field">
                <label for="guests">Список гостей</label>
                <input type="text" name="guests" id="guests" value="{{ old('guests') }}" placeholder="Имена гостей через запятую" class="{{ $errors->has('guests') ? 'is-invalid' : '' }}">
                @error('guests')
                <div class="input-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="lit-field">
                <label for="tags">Теги</label>
                <input type="text" name="tags" id="tags" value="{{ old('tags') }}" placeholder="Жанры через запятую" class="{{ $errors->has('genres') ? 'is-invalid' : '' }}">
                @error('tags')
                <div class="input-error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="primary-btn">Создать мероприятие</button>
        </form>
    </div>
@endsection
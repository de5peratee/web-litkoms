@extends('layouts.app')

@section('title', 'Новое мероприятие')

@section('content')
    @vite(['resources/css/inputs.css'])
    @vite(['resources/css/editor/create_event.css'])

    <div class="form-container">
        <div class="path-bar">
            <a href="{{ URL::previous() }}" class="text-hint">Назад</a>
            <img src="{{ asset('images/icons/arrow-right.svg') }}"  class="icon-24" alt="icon">
            <p class="text-hint">Новое мероприятие</p>
        </div>

        <div class="info-block">
            <div class="info-header">
                <div class="info-header-title">
                    <img src="{{ asset('images/icons/hw/event-form-icon.svg') }}" alt="icon" class="icon-32">
                    <h3>Новое мероприятие</h3>
                </div>
            </div>

            <div class="h-divider"></div>

            <form method="POST" action="{{ route('editor.store_event') }}" enctype="multipart/form-data" class="lit-form">
                @csrf

                <div class="lit-form-row">
                    <div class="lit-field">
                        <label for="cover">Обложка мероприятия</label>
                        <div class="cover-upload">
                            <input type="file" name="cover" id="cover" accept="image/*" class="{{ $errors->has('cover') ? 'is-invalid' : '' }}">
                            <div class="cover-preview" id="coverPreview"></div>
                        </div>
                        @error('cover')
                        <div class="input-error"><span>{{ $message }}</span></div>
                        @enderror
                    </div>

                    <div class="lit-field">
                        <label for="name">Название мероприятия</label>
                        <input type="text" name="name" id="name" placeholder="Введите название" value="{{ old('name') }}" class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
                        @error('name')
                        <div class="input-error"><span>{{ $message }}</span></div>
                        @enderror
                    </div>
                </div>

                <div class="lit-field">
                    <label for="description">Описание</label>
                    <textarea name="description" id="description" rows="5" placeholder="Подробное описание мероприятия" class="{{ $errors->has('description') ? 'is-invalid' : '' }}">{{ old('description') }}</textarea>
                    @error('description')
                    <div class="input-error"><span>{{ $message }}</span></div>
                    @enderror
                </div>

                <div class="lit-form-row">
                    <div class="lit-field">
                        <label for="start_date">Дата начала</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" class="{{ $errors->has('start_date') ? 'is-invalid' : '' }}">
                        @error('start_date')
                        <div class="input-error"><span>{{ $message }}</span></div>
                        @enderror
                    </div>

                    <div class="lit-field">
                        <label for="start_time">Время начала</label>
                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" class="{{ $errors->has('start_time') ? 'is-invalid' : '' }}">
                        @error('start_time')
                        <div class="input-error"><span>{{ $message }}</span></div>
                        @enderror
                    </div>
                </div>

                <div class="lit-form-row">
                    <div class="lit-field">
                        <label for="end_date">Дата окончания</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" class="{{ $errors->has('end_date') ? 'is-invalid' : '' }}">
                        @error('end_date')
                        <div class="input-error"><span>{{ $message }}</span></div>
                        @enderror
                    </div>

                    <div class="lit-field">
                        <label for="end_time">Время окончания</label>
                        <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" class="{{ $errors->has('end_time') ? 'is-invalid' : '' }}">
                        @error('end_time')
                        <div class="input-error"><span>{{ $message }}</span></div>
                        @enderror
                    </div>
                </div>

                <div class="lit-field">
                    <label for="guests">Список гостей</label>
                    <input type="text" name="guests" id="guests" value="{{ old('guests') }}" placeholder="Имена гостей через запятую" class="{{ $errors->has('guests') ? 'is-invalid' : '' }}">
                    @error('guests')
                    <div class="input-error"><span>{{ $message }}</span></div>
                    @enderror
                </div>

                <div class="lit-field">
                    <label for="tags">Теги</label>
                    <input type="text" name="tags" id="tags" value="{{ old('tags') }}" placeholder="Жанры через запятую" class="{{ $errors->has('genres') ? 'is-invalid' : '' }}">
                    @error('tags')
                    <div class="input-error"><span>{{ $message }}</span></div>
                    @enderror
                </div>

                <div class="h-divider"></div>

                <button type="submit" class="primary-btn">Опубликовать мероприятие</button>
            </form>

        </div>
    </div>
@endsection

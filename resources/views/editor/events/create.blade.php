@extends('layouts.app')

@section('title', 'Новое мероприятие')

@section('content')
    @vite(['resources/css/editor/create_event.css'])
    @vite(['resources/css/inputs.css'])

    <div class="form-container">
{{--        action="{{ route('editor.create_event') }}"--}}
        <form method="POST"  class="lit-form">
            @csrf

            <div class="lit-field">
                <label for="cover">Обложка мероприятия</label>
                <div class="cover-upload">
                    <input type="file" name="cover" id="cover" accept="image/*" required>
                    <div class="cover-preview" id="coverPreview"></div>
                </div>
            </div>

            <div class="lit-field">
                <label for="title">Название мероприятия</label>
                <input type="text" name="title" id="title" placeholder="Введите название" required>
            </div>

            <div class="lit-field">
                <label for="description">Описание</label>
                <textarea name="description" id="description" rows="5" placeholder="Подробное описание мероприятия" required></textarea>
            </div>

            <div class="lit-field-group">
                <div class="lit-field">
                    <label for="date">Дата проведения</label>
                    <input type="date" name="date" id="date" required>
                </div>

                <div class="lit-field">
                    <label for="time">Время начала</label>
                    <input type="time" name="time" id="time" required>
                </div>
            </div>

            <div class="lit-field">
                <label for="guests">Список гостей</label>
                <input type="text" name="guests" id="guests" placeholder="Имена гостей через запятую">
            </div>

            <div class="lit-field">
                <label for="genres">Жанры</label>
                <input type="text" name="genres" id="genres" placeholder="Жанры через запятую" required>
            </div>

            <button type="submit" class="primary-btn">Создать мероприятие</button>
        </form>
    </div>
@endsection

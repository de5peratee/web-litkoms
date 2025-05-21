@extends('layouts.app')

@section('title', 'Список авторских комиксов')

@section('content')
    @vite(['resources/css/inputs.css'])
    @vite(['resources/css/user/author_comics_list.css'])
    @vite(['resources/js/user/author-comics-list-modal.js'])

    <div class="comics-list-container">
        <div class="comics-list-container-header">
            <h2>Мои комиксы</h2>
            <a href="{{ route('user.create_author_comics') }}" class="primary-btn">Создать комикс</a>
        </div>

        <a class="comic-list">
            @foreach ($comics as $comic)

                <a href="{{ route('author_comic', $comic) }}" target="_blank" class="comic-item">
                    <div class="comic-data">
                        <p>{{ $comic->name }}</p>
                        <p>Статус: {{ $comic->status }}</p>
                    </div>
                    <div class="comic-actions">
                        <a href="#" class="list-action-btn edit-comic-btn"
                           data-comic-id="{{ $comic->id }}"
                           data-comic-name="{{ $comic->name }}"
                           data-comic-description="{{ $comic->description }}"
                           data-comic-age_restriction="{{ $comic->age_restriction }}"
                           data-comic-genres="{{ $comic->genres_string }}"
                           data-comic-cover="{{ $comic->cover ? Storage::url($comic->cover) : '' }}"
                           data-comic-file="{{ $comic->comics_file ? Storage::url($comic->comics_file) : '' }}"
                           data-comic-file-name="{{ $comic->comics_file ? basename($comic->comics_file) : '' }}">
                            <img src="{{ asset('images/icons/edit-primary.svg') }}" class="icon-24" alt="edit-icon">
                        </a>

                        <a href="#" class="list-action-btn delete-comic-btn"
                           data-comic-id="{{ $comic->id }}"
                           data-comic-name="{{ $comic->name }}">
                            <img src="{{ asset('images/icons/trash-primary-red.svg') }}" class="icon-24" alt="delete-icon">
                        </a>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal hidden" id="delete-comic-modal">
        <div class="modal-content">
            <div class="modal-close" id="delete-comic-modal-close">
                <img src="{{ asset('images/icons/close-primary.svg') }}" class="icon-24" alt="close">
            </div>
            <h3 id="delete-comic-modal-title">Удаление комикса</h3>
            <div class="h-divider"></div>
            <p id="delete-comic-modal-text"></p>
            <form id="delete-comic-form" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-actions">
                    <button type="button" class="secondary-btn" id="cancel-delete-comic">Отмена</button>
                    <button type="submit" class="primary-btn">Удалить</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal hidden" id="edit-comic-modal">
        <div class="modal-content">
            <div class="modal-close" id="edit-comic-modal-close">
                <img src="{{ asset('images/icons/close-primary.svg') }}" class="icon-24" alt="close">
            </div>
            <h3>Редактирование комикса</h3>
            <div class="h-divider"></div>
            <form method="POST" action="" enctype="multipart/form-data" class="lit-form" id="edit-comic-form">
                @csrf
                @method('PATCH')
                <input type="hidden" name="id" id="edit-comic-id">

                <div class="lit-field">
                    <label for="edit-comic-cover">Обложка комикса</label>
                    <div class="cover-upload">
                        <input type="file" name="cover" id="edit-comic-cover" accept="image/*">
                        <div class="cover-preview" id="edit-comic-cover-preview"></div>
                        <div class="input-error" id="edit-comic-cover-error"></div>
                    </div>
                </div>

                <div class="lit-field">
                    <label for="edit-comic-file">Файл комикса</label>
                    <div class="file-upload">
                        <div class="file-input-wrapper">
                            <input type="file" name="comic_file" id="edit-comic-file" accept=".pdf,.cbr,.cbz">
                            <span class="file-input-label" id="edit-comic-file-label">Выберите файл</span>
                        </div>
                        <div class="input-error" id="edit-comic-file-error"></div>
                    </div>
                </div>

                <div class="lit-field">
                    <label for="edit-comic-name">Название комикса</label>
                    <input type="text" name="title" id="edit-comic-name" placeholder="Введите название" required>
                    <div class="input-error" id="edit-comic-name-error"></div>
                </div>

                <div class="lit-field">
                    <label for="edit-comic-description">Описание</label>
                    <textarea name="description" id="edit-comic-description" rows="5" placeholder="Подробное описание комикса" required></textarea>
                    <div class="input-error" id="edit-comic-description-error"></div>
                </div>

                <div class="lit-field">
                    <label for="edit-comic-age_restriction">Возрастное ограничение</label>
                    <select name="age_restriction" id="edit-comic-age_restriction" required>
                        <option value="0+">0+</option>
                        <option value="6+">6+</option>
                        <option value="12+">12+</option>
                        <option value="16+">16+</option>
                        <option value="18+">18+</option>
                    </select>
                    <div class="input-error" id="edit-comic-age_restriction-error"></div>
                </div>

                <div class="lit-field">
                    <label for="edit-comic-genres">Жанры</label>
                    <input type="text" name="genres" id="edit-comic-genres" placeholder="Жанры через запятую">
                    <div class="input-error" id="edit-comic-genres-error"></div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="secondary-btn" id="cancel-edit-comic">Отмена</button>
                    <button type="submit" class="primary-btn">Сохранить изменения</button>
                </div>
            </form>
        </div>
    </div>
@endsection

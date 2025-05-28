@extends('layouts.app')

@section('title', 'Список каталога')

@section('content')
    @vite(['resources/css/inputs.css'])
    @vite(['resources/css/editor/catalog_list.css'])
    @vite(['resources/js/editor/catalog-list-modal.js'])

    <div class="catalogs-list-container">
        <div class="catalogs-list-container-header">
            <h2>Каталог</h2>
            <a href="{{ route('editor.create_catalog') }}" class="primary-btn">Добавить в каталог</a>
        </div>

        <div class="catalog-list">
            @foreach ($catalogs as $catalog)
                <div class="catalog-item">
                    <div class="catalog-data">
                        <p>{{ $catalog->name }}</p>
{{--                        <p>{{ $catalog->author }}</p>--}}
                    </div>
                    <div class="catalog-actions">
                        <a href="#" class="list-action-btn edit-catalog-btn"
                           data-catalog-id="{{ $catalog->id }}"
                           data-catalog-name="{{ $catalog->name }}"
                           data-catalog-author="{{ $catalog->author }}"
                           data-catalog-description="{{ $catalog->description }}"
                           data-catalog-release_year="{{ $catalog->release_year }}"
                           data-catalog-genres="{{ $catalog->genres->pluck('name')->implode(', ') }}"
                           data-catalog-cover="{{ $catalog->cover ? Storage::url('/' . $catalog->cover) : '' }}">
                            <img src="{{ asset('images/icons/edit-primary.svg') }}" class="icon-24" alt="edit-icon">
                        </a>

                        <a href="#" class="list-action-btn delete-catalog-btn"
                           data-catalog-id="{{ $catalog->id }}"
                           data-catalog-name="{{ $catalog->name }}">
                            <img src="{{ asset('images/icons/trash-primary-red.svg') }}" class="icon-24" alt="delete-icon">
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal hidden" id="delete-catalog-modal">
        <div class="modal-content">
            <div class="modal-close" id="delete-catalog-modal-close">
                <img src="{{ asset('images/icons/close-primary.svg') }}" class="icon-24" alt="close">
            </div>
            <h3 id="delete-catalog-modal-title">Удаление</h3>
            <div class="h-divider"></div>
            <p id="delete-catalog-modal-text"></p>
            <form id="delete-catalog-form" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-actions">
                    <button type="button" class="secondary-btn" id="cancel-delete-catalog">Отмена</button>
                    <button type="submit" class="primary-btn">Удалить</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal hidden" id="edit-catalog-modal">
        <div class="modal-content">
            <div class="modal-close" id="edit-catalog-modal-close">
                <img src="{{ asset('images/icons/close-primary.svg') }}" class="icon-24" alt="close">
            </div>
            <h3>Редактирование</h3>
            <div class="h-divider"></div>
            <form method="POST" action="" enctype="multipart/form-data" class="lit-form" id="edit-catalog-form">
                @csrf
                @method('PATCH')
                <input type="hidden" name="id" id="edit-catalog-id">

                <div class="lit-field">
                    <label for="edit-catalog-name">Название</label>
                    <input type="text" name="name" id="edit-catalog-name" placeholder="Введите название"
                           value="{{ old('name') }}" class="{{ $errors->has('name') ? 'is-invalid' : '' }}" required>
                    <div id="edit-catalog-name-error" class="input-error">
                        @error('name') {{ $message }} @enderror
                    </div>
                </div>

                <div class="lit-field">
                    <label for="edit-catalog-author">Автор</label>
                    <input type="text" name="author" id="edit-catalog-author" placeholder="Введите автора"
                           value="{{ old('author') }}" class="{{ $errors->has('author') ? 'is-invalid' : '' }}" required>
                    <div id="edit-catalog-author-error" class="input-error">
                        @error('author') {{ $message }} @enderror
                    </div>
                </div>

                <div class="lit-field">
                    <label for="edit-catalog-description">Описание</label>
                    <textarea name="description" id="edit-catalog-description" rows="5" placeholder="Подробное описание"
                              class="{{ $errors->has('description') ? 'is-invalid' : '' }}">{{ old('description') }}</textarea>
                    <div id="edit-catalog-description-error" class="input-error">
                        @error('description') {{ $message }} @enderror
                    </div>
                </div>

                <div class="lit-field">
                    <label for="edit-catalog-release_year">Год выпуска</label>
                    <input type="number" name="release_year" id="edit-catalog-release_year" placeholder="Введите год выпуска"
                           value="{{ old('release_year') }}" class="{{ $errors->has('release_year') ? 'is-invalid' : '' }}">
                    <div id="edit-catalog-release_year-error" class="input-error">
                        @error('release_year') {{ $message }} @enderror
                    </div>
                </div>

                <div class="lit-field">
                    <label for="edit-catalog-genres">Жанры</label>
                    <input type="text" name="genres" id="edit-catalog-genres" placeholder="Жанры через запятую"
                           value="{{ old('genres') }}" class="{{ $errors->has('genres') ? 'is-invalid' : '' }}">
                    <div id="edit-catalog-genres-error" class="input-error">
                        @error('genres') {{ $message }} @enderror
                    </div>
                </div>

                <div class="lit-field">
                    <label for="edit-catalog-cover">Обложка</label>
                    <input type="file" name="cover" id="edit-catalog-cover" accept="image/*"
                           class="{{ $errors->has('cover') ? 'is-invalid' : '' }}">
                    <div class="cover-preview" id="edit-catalog-cover-preview"></div>
                    <div id="edit-catalog-cover-error" class="input-error">
                        @error('cover') {{ $message }} @enderror
                    </div>
                </div>


                <div class="modal-actions">
                    <button type="button" class="secondary-btn" id="cancel-edit-catalog">Отмена</button>
                    <button type="submit" class="primary-btn">Сохранить изменения</button>
                </div>
            </form>
        </div>
    </div>
@endsection
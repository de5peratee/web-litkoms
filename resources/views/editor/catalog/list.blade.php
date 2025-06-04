@extends('layouts.app')

@section('title', 'Список каталога')

@section('content')
    @vite(['resources/css/inputs.css'])
    @vite(['resources/css/editor/catalog_list.css'])
    @vite(['resources/js/editor/catalog-list-modal.js'])
    @vite(['resources/js/editor/catalog_items.js'])

    <div class="catalogs-list-container">
        <div class="info-block">
            <div class="info-header">
                <div class="info-header-title">
                    <img src="{{ asset('images/icons/hw/library-form-icon.svg') }}" alt="icon" class="icon-32">
                    <h3>Каталог комиксов Литкомс</h3>
                    <p class="text-medium info-count-text">{{ $total }}</p>
                </div>

                <a href="{{ route('editor.create_catalog') }}" class="primary-btn">
                    Опубликовать
                    <img src="{{ asset('images/icons/plus-icon-white.svg') }}" class="icon-24" alt="icon">
                </a>
            </div>

            <div class="search-container">
                <form id="search-form" action="{{ route('editor.catalogs_index') }}" method="GET" class="search-form">
                    <div class="search-input-wrapper">
                        <input type="text" name="search" id="search-input" placeholder="Поиск по каталогу..." value="{{ $search }}">
                        <div class="clear-search hidden">
                            <img src="{{ asset('images/icons/close-primary.svg') }}" class="icon-20" alt="clear">
                        </div>
                    </div>
                    <button type="submit" class="secondary-btn">Найти</button>
                </form>
            </div>
        </div>

        <div class="catalog-list" id="catalog-list">
            @include('partials.editor_lists.catalog_items', ['catalogs' => $catalogs, 'page' => 0])
        </div>

        @if ($catalogs->count() === 10 && $total > $catalogs->count())
            <div class="load-more-container">
                <button id="load-more" class="primary-btn"
                        data-page="2"
                        data-search="{{ $search }}"
                        data-url="{{ route('editor.catalogs_loadMore') }}">
                    Загрузить еще
                </button>
            </div>
        @endif
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

                <div class="lit-form-row">
                    <div class="lit-field">
                        <label for="edit-catalog-cover">Обложка</label>
                        <input type="file" name="cover" id="edit-catalog-cover" accept="image/*"
                               class="{{ $errors->has('cover') ? 'is-invalid' : '' }}">
                        <div class="cover-preview" id="edit-catalog-cover-preview"></div>
                        <div id="edit-catalog-cover-error" class="input-error">
                            @error('cover') {{ $message }} @enderror
                        </div>
                    </div>

                    <div class="lit-field">
                        <label for="edit-catalog-name">Название</label>
                        <input type="text" name="name" id="edit-catalog-name" placeholder="Введите название"
                               value="{{ old('name') }}" class="{{ $errors->has('name') ? 'is-invalid' : '' }}" required>
                        <div id="edit-catalog-name-error" class="input-error">
                            @error('name') {{ $message }} @enderror
                        </div>
                    </div>
                </div>

                <div class="lit-form-row">
                    <div class="lit-field">
                        <label for="edit-catalog-release_year">Год выпуска</label>
                        <input type="number" name="release_year" id="edit-catalog-release_year" placeholder="Введите год выпуска"
                               value="{{ old('release_year') }}" class="{{ $errors->has('release_year') ? 'is-invalid' : '' }}">
                        <div id="edit-catalog-release_year-error" class="input-error">
                            @error('release_year') {{ $message }} @enderror
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
                    <label for="edit-catalog-genres">Жанры</label>
                    <input type="text" name="genres" id="edit-catalog-genres" placeholder="Жанры через запятую"
                           value="{{ old('genres') }}" class="{{ $errors->has('genres') ? 'is-invalid' : '' }}">
                    <div id="edit-catalog-genres-error" class="input-error">
                        @error('genres') {{ $message }} @enderror
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

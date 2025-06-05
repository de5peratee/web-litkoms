@extends('layouts.app')

@section('title', 'Список авторских комиксов')

@section('content')
    @vite(['resources/css/inputs.css'])
    @vite(['resources/css/user/author_comics_list.css'])
    @vite(['resources/js/user/author-comics-list-modal.js'])
    @vite(['resources/js/user/authors_comics_items.js'])

    <div class="comics-list-container">
        <div class="info-block">
            <div class="info-header">
                <div class="info-header-title">
                    <img src="{{ asset('images/icons/hw/draw-pencil.svg') }}" alt="icon" class="icon-32">
                    <h3>Список авторских комиксов</h3>
                    <p class="text-medium info-count-text">{{ $total }}</p>
                </div>

                <a href="{{ route('user.create_author_comics') }}" class="primary-btn">
                    Опубликовать
                    <img src="{{ asset('images/icons/plus-icon-white.svg') }}" class="icon-24" alt="icon">
                </a>
            </div>

            <div class="search-container">
                <form id="search-form" action="{{ route('user.author_comics') }}" method="GET" class="search-form">
                    <div class="search-input-wrapper">
                        <input type="text" name="search" id="search-input" placeholder="Поиск по авторским комиксам..."
                               value="{{ $search }}">
                        <div class="clear-search hidden">
                            <img src="{{ asset('images/icons/close-primary.svg') }}" class="icon-20" alt="clear">
                        </div>
                    </div>
                    <button type="submit" class="secondary-btn">Найти</button>
                </form>
            </div>
        </div>

        <div class="comic-list" id="comic-list">
            @include('partials.user_lists.authors_comics_items', ['comics' => $comics, 'page' => 0])
        </div>

        @if ($comics->count() === 10 && $total > $comics->count())
            <div class="load-more-container">
                <button id="load-more" class="primary-btn"
                        data-page="2"
                        data-search="{{ $search }}"
                        data-url="{{ route('user.author_comics_loadMore') }}">
                    Загрузить еще
                </button>
            </div>
        @endif
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
            <h3>Редактирование</h3>
            <div class="h-divider"></div>
            <form method="POST" action="" enctype="multipart/form-data" class="lit-form" id="edit-comic-form">
                @csrf
                @method('PATCH')
                <input type="hidden" name="id" id="edit-comic-id">

                <div class="lit-form-row">
                    <div class="lit-field">
                        <label for="edit-comic-name">Название комикса</label>
                        <input type="text" name="name" id="edit-comic-name" placeholder="Введите название" required>
                        <div class="input-error" id="edit-comic-name-error"></div>
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
                </div>

                <div class="lit-form-row">
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
                            <input type="file" name="comics_file" id="edit-comic-file" accept=".pdf,.cbr,.cbz">
                            <div class="input-error" id="edit-comic-file-error"></div>
                        </div>
                    </div>
                </div>

                <div class="lit-field">
                    <label for="edit-comic-description">Описание</label>
                    <textarea name="description" id="edit-comic-description" rows="5"
                              placeholder="Подробное описание комикса" required></textarea>
                    <div class="input-error" id="edit-comic-description-error"></div>
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

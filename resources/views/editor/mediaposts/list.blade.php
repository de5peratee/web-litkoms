@extends('layouts.app')

@section('title', 'Список постов')

@section('content')
    @vite(['resources/css/inputs.css'])
    @vite(['resources/css/editor/multimedia_list.css'])
    @vite(['resources/js/editor/multimedia-list-modal.js'])
    @vite(['resources/js/editor/multimedia_items.js'])

    <div class="multimedia-list-container">
        <div class="info-block">
            <div class="info-header">
                <div class="info-header-title">
                    <img src="{{ asset('images/icons/hw/media-form-icon.svg') }}" alt="icon" class="icon-32">
                    <h3>Новостные посты</h3>
                    <p class="text-medium info-count-text">{{ $total }}</p>
                </div>

                <a href="{{ route('editor.create_mediapost') }}" class="primary-btn">
                    Опубликовать
                    <img src="{{ asset('images/icons/plus-icon-white.svg') }}" class="icon-24" alt="icon">
                </a>
            </div>

            <div class="search-container">
                <form id="search-form" action="{{ route('editor.mediapost_index') }}" method="GET" class="search-form">
                    <div class="search-input-wrapper">
                        <input type="text" name="search" id="search-input" placeholder="Поиск по новостным постам..." value="{{ $search }}">
                        <div class="clear-search hidden">
                            <img src="{{ asset('images/icons/close-primary.svg') }}" class="icon-20" alt="clear">
                        </div>
                    </div>
                    <button type="submit" class="secondary-btn">Найти</button>
                </form>
            </div>
        </div>

        <div class="multimedia-list" id="multimedia-list">
            @include('partials.editor_lists.multimedia_items', ['mediaPosts' => $mediaPosts, 'page' => 0])
        </div>

        @if ($mediaPosts->count() === 10 && $total > $mediaPosts->count())
            <div class="load-more-container">
                <button id="load-more" class="primary-btn"
                        data-page="2"
                        data-search="{{ $search }}"
                        data-url="{{ route('editor.mediapost_loadMore') }}">
                    Загрузить еще
                </button>
            </div>
        @endif
    </div>

    <!-- Модалка удаления -->
    <div class="modal hidden" id="delete-modal">
        <div class="modal-content">
            <div class="modal-close" id="delete-modal-close">
                <img src="{{ asset('images/icons/close-primary.svg') }}" class="icon-24" alt="close">
            </div>
            <h3 id="delete-modal-title">Удаление</h3>
            <div class="h-divider"></div>
            <p id="delete-modal-text"></p>
            <form id="delete-post-form" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-actions">
                    <button type="button" class="secondary-btn" id="cancel-delete-post">Отмена</button>
                    <button type="submit" class="primary-btn">Удалить</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Модалка редактирования -->
    <div class="modal hidden" id="edit-modal">
        <div class="modal-content">
            <div class="modal-close" id="edit-modal-close">
                <img src="{{ asset('images/icons/close-primary.svg') }}" class="icon-24" alt="close">
            </div>
            <h3>Редактирование поста</h3>
            <div class="h-divider"></div>
            <form method="POST" action="" enctype="multipart/form-data" class="lit-form" id="edit-post-form">
                @csrf
                @method('PATCH')
                <input type="hidden" name="id" id="edit-post-id">

                <div class="lit-form-row">
                    <div class="lit-field">
                        <label for="edit-post-media">Фотографии и файлы</label>
                        <div class="media-upload">
                            <input type="file" name="media[]" id="media" accept="image/*,video/*,application/pdf" multiple class="{{ $errors->has('media') ? 'is-invalid' : '' }}">
                            <div class="media-preview" id="edit-post-media-preview"></div>
                        </div>
                        <div class="input-error" id="edit-post-media-error"></div>
                    </div>

                    <div class="lit-field">
                        <label for="edit-post-name">Название поста</label>
                        <input type="text" name="name" id="edit-post-name" placeholder="Введите название" required>
                        <div class="input-error" id="edit-post-name-error"></div>
                    </div>
                </div>

                <div class="lit-field">
                    <label for="edit-post-description">Описание</label>
                    <textarea name="description" id="edit-post-description" rows="5" placeholder="Подробное описание поста" required></textarea>
                    <div class="input-error" id="edit-post-description-error"></div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="secondary-btn" id="cancel-edit-post">Отмена</button>
                    <button type="submit" class="primary-btn">Сохранить изменения</button>
                </div>
            </form>
        </div>
    </div>
@endsection

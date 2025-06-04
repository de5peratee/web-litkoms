@extends('layouts.app')

@section('title', 'Список мероприятий')

@section('content')
    @vite(['resources/css/inputs.css'])
    @vite(['resources/css/editor/events_list.css'])
    @vite(['resources/js/editor/events-list-modal.js'])
    @vite(['resources/js/editor/events_items.js'])

    <div class="events-list-container">
        <div class="info-block">
            <div class="info-header">
                <div class="info-header-title">
                    <img src="{{ asset('images/icons/hw/event-form-icon.svg') }}" alt="icon" class="icon-32">
                    <h3>Список мероприятий</h3>
                    <p class="text-medium info-count-text">{{ $total }}</p>
                </div>

                <a href="{{ route('editor.create_event') }}" class="primary-btn">
                    Опубликовать
                    <img src="{{ asset('images/icons/plus-icon-white.svg') }}" class="icon-24" alt="icon">
                </a>
            </div>

            <div class="search-container">
                <form id="search-form" action="{{ route('editor.events_index') }}" method="GET" class="search-form">
                    <div class="search-input-wrapper">
                        <input type="text" name="search" id="search-input" placeholder="Поиск по мероприятиям..." value="{{ $search }}">
                        <div class="clear-search hidden">
                            <img src="{{ asset('images/icons/close-primary.svg') }}" class="icon-20" alt="clear">
                        </div>
                    </div>
                    <button type="submit" class="secondary-btn">Найти</button>
                </form>
            </div>
        </div>

        <div class="event-list" id="event-list">
            @include('partials.editor_lists.events_items', ['events' => $events, 'page' => 0])
        </div>

        @if ($events->count() === 10 && $total > $events->count())
            <div class="load-more-container">
                <button id="load-more" class="primary-btn"
                        data-page="2"
                        data-search="{{ $search }}"
                        data-url="{{ route('editor.events_loadMore') }}">
                    Загрузить еще
                </button>
            </div>
        @endif
    </div>

    <!-- Модалка удаления -->
    <div class="modal hidden" id="delete-event-modal">
        <div class="modal-content">
            <div class="modal-close" id="delete-event-modal-close">
                <img src="{{ asset('images/icons/close-primary.svg') }}" class="icon-24" alt="close">
            </div>
            <h3 id="delete-event-modal-title">Удаление</h3>
            <div class="h-divider"></div>
            <p id="delete-event-modal-text"></p>
            <form id="delete-event-form" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-actions">
                    <button type="button" class="secondary-btn" id="cancel-delete-event">Отмена</button>
                    <button type="submit" class="primary-btn">Удалить</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Модалка редактирования -->
    <div class="modal hidden" id="edit-event-modal">
        <div class="modal-content">
            <div class="modal-close" id="edit-event-modal-close">
                <img src="{{ asset('images/icons/close-primary.svg') }}" class="icon-24" alt="close">
            </div>
            <h3>Редактирование</h3>
            <div class="h-divider"></div>
            <form method="POST" action="" enctype="multipart/form-data" class="lit-form" id="edit-event-form">
                @csrf
                @method('PATCH')
                <input type="hidden" name="id" id="edit-event-id">

                <div class="lit-form-row">
                    <div class="lit-field">
                        <label for="edit-event-cover">Обложка мероприятия</label>
                        <div class="cover-upload">
                            <input type="file" name="cover" id="edit-event-cover" accept="image/*">
                            <div class="cover-preview" id="edit-event-cover-preview"></div>
                            <div class="input-error" id="edit-event-cover-error"></div>
                        </div>
                    </div>

                    <div class="lit-field">
                        <label for="edit-event-name">Название мероприятия</label>
                        <input type="text" name="name" id="edit-event-name" placeholder="Введите название" required>
                        <div class="input-error" id="edit-event-name-error"></div>
                    </div>
                </div>

                <div class="lit-field">
                    <label for="edit-event-description">Описание</label>
                    <textarea name="description" id="edit-event-description" rows="5" placeholder="Подробное описание мероприятия" required></textarea>
                    <div class="input-error" id="edit-event-description-error"></div>
                </div>

                <div class="lit-form-row">
                    <div class="lit-field">
                        <label for="edit-event-start_date">Дата проведения</label>
                        <input type="date" name="start_date" id="edit-event-start_date" required>
                        <div class="input-error" id="edit-event-start_date-error"></div>
                    </div>

                    <div class="lit-field">
                        <label for="edit-event-time">Время начала</label>
                        <input type="time" name="time" id="edit-event-time" required>
                        <div class="input-error" id="edit-event-time-error"></div>
                    </div>
                </div>

                <div class="lit-field">
                    <label for="edit-event-guests">Список гостей</label>
                    <input type="text" name="guests" id="edit-event-guests" placeholder="Имена гостей через запятую">
                    <div class="input-error" id="edit-event-guests-error"></div>
                </div>

                <div class="lit-field">
                    <label for="edit-event-tags">Теги</label>
                    <input type="text" name="tags" id="edit-event-tags" placeholder="Теги через запятую">
                    <div class="input-error" id="edit-event-tags-error"></div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="secondary-btn" id="cancel-edit-event">Отмена</button>
                    <button type="submit" class="primary-btn">Сохранить изменения</button>
                </div>
            </form>
        </div>
    </div>
@endsection

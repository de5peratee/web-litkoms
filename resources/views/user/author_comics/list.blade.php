@extends('layouts.app')

@section('title', 'Список авторских комиксов')

@section('content')
    @vite(['resources/css/inputs.css'])
    @vite(['resources/css/user/author_comics_list.css'])
{{--    @vite(['resources/js/user/author-comics-list-modal.js'])--}}

    <div class="author-comics-list-container">
        <div class="author-comics-list-container-header">
            <h2>Авторские комиксы</h2>
            <a href="{{ route('user.create_author_comics') }}" class="primary-btn">Новый авторский комикс</a>
        </div>

        <div class="author-comics-list">
{{--            @forelse ($comics as $comic)--}}
{{--                <div class="author-comic-item">--}}
{{--                    <div class="author-comic-data">--}}
{{--                        <p>ID: {{ $comic->id }}</p>--}}
{{--                        <p><strong>{{ $comic->name }}</strong></p>--}}
{{--                        <p>{{ $comic->description ?: 'Нет описания' }}</p>--}}
{{--                        <p>Жанры: {{ $comic->genres ?: 'Не указаны' }}</p>--}}
{{--                        @if($comic->cover)--}}
{{--                            <div class="comic-cover-preview">--}}
{{--                                <img src="{{ Storage::url($comic->cover) }}" alt="{{ $comic->name }} обложка" class="comic-cover-thumb">--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                    <div class="author-comic-actions">--}}
{{--                        <a href="{{ route('user.edit_author_comics', $comic->id) }}" class="list-action-btn edit-comic-btn">--}}
{{--                            <img src="{{ asset('images/icons/edit-primary.svg') }}" class="icon-24" alt="edit-icon">--}}
{{--                        </a>--}}
{{--                        <a href="#" class="list-action-btn delete-comic-btn"--}}
{{--                           data-comic-id="{{ $comic->id }}"--}}
{{--                           data-comic-name="{{ $comic->name }}">--}}
{{--                            <img src="{{ asset('images/icons/trash-primary-red.svg') }}" class="icon-24" alt="delete-icon">--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @empty--}}
{{--                <p>Пока нет загруженных комиксов.</p>--}}
{{--            @endforelse--}}
        </div>
    </div>

    <!-- Модалка удаления -->
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

    <!-- Модальное окно редактирования комикса -->
    <div id="edit-comic-modal" class="modal hidden">
        <div class="modal-content">
            <button id="edit-comic-modal-close" class="modal-close">&times;</button>

            <form id="edit-comic-form" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <input type="hidden" id="edit-comic-id" name="id">

                <div class="modal-section">
                    <label for="edit-comic-name">Название комикса</label>
                    <input type="text" id="edit-comic-name" name="name" placeholder="Введите название">
                    <div id="edit-comic-name-error" class="input-error"></div>
                </div>

                <div class="modal-section">
                    <label for="edit-comic-description">Описание</label>
                    <textarea id="edit-comic-description" name="description" placeholder="Введите описание"></textarea>
                    <div id="edit-comic-description-error" class="input-error"></div>
                </div>

                <div class="modal-section">
                    <label for="edit-comic-genres">Жанры (через запятую)</label>
                    <input type="text" id="edit-comic-genres" name="genres" placeholder="Фантастика, боевик">
                    <div id="edit-comic-genres-error" class="input-error"></div>
                </div>

                <div class="modal-section cover-upload">
                    <label for="edit-comic-cover">Обложка</label>
                    <input type="file" id="edit-comic-cover" name="cover" accept="image/*">
                    <div id="edit-comic-cover-preview"></div>
                    <div id="edit-comic-cover-error" class="input-error"></div>
                </div>

                <div class="modal-section">
                    <label for="edit-comic-file">Файл комикса (PDF или архив)</label>
                    <input type="file" id="edit-comic-file" name="comics_file" accept=".pdf,.zip,.rar">
                    <div id="edit-comic-file-error" class="input-error"></div>
                </div>

                <div class="modal-actions">
                    <button type="button" id="cancel-edit-comic" class="btn btn-secondary">Отмена</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>

@endsection

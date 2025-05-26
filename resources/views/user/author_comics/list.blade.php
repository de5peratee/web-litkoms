@extends('layouts.app')

@section('title', 'Список авторских комиксов')

@section('content')
    @vite(['resources/css/inputs.css'])
    @vite(['resources/css/user/author_comics_list.css'])
    @vite(['resources/js/user/author-comics-list-modal.js'])

    <div class="comics-list-container">
        <div class="comics-list-container-header">
            <div class="left-header">
                <div class="title-container">
                    <h3>Авторские комиксы</h3>
                    <p class="text-big comics-count-text">({{ $comics->count() }})</p>
                </div>

                <a href="{{ route('user.create_author_comics') }}" class="primary-btn">
                    Новый комикс
                    <img src="{{ asset('images/icons/plus-icon-white.svg') }}" class="icon-24" alt="edit-icon">
                </a>
            </div>

            <div class="search-container">
                <form id="search-form" action="" method="GET" class="search-form">

                    <div class="search-input-wrapper">
                        <input type="text" name="search" placeholder="Что желаете найти..." value="{{ request('search') }}">
                        <div class="clear-search hidden">
                            <img src="{{ asset('images/icons/close-primary.svg') }}" class="icon-20" alt="clear">
                        </div>
                    </div>

                    <button type="submit" class="secondary-btn">Искать</button>

                </form>
            </div>
        </div>

        <div class="comic-list">
            @foreach ($comics as $comic)
                <div href="{{ route('author_comic', $comic->slug) }}" class="comic-item">
                    <div class="comic-item-left-part">
                        <div class="item-sell num-cell">{{ $loop->iteration }}</div>

                        <div class="item-cell comic-preview-cell">
                            <div class="comic-cover-wrapper">
                                <img src="{{ $comic->cover ? Storage::url($comic->cover) : asset('images/default_template/comics.svg') }}" class="comic-cover" alt="icon">
                            </div>

                            <div class="comic-preview-text-wrapper">
                                <div class="comic-title-flex">
                                    <p class="text-big">{{ $comic->name }}</p>
                                    @if ($comic->age_restriction >= 18)
                                        <p class="text-hint age-restriction-tag">18+</p>
                                    @endif
                                </div>

                                <p class="text-hint comic-datetime-tag">
                                    {{ $comic->updated_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        <div class="item-cell status-cell">
                            <img src="{{ asset('images/icons/moderation/' . ($comic->is_moderated === 'successful' ? 'success-icon.svg' : ($comic->is_moderated === 'unsuccessful' ? 'reject-icon.svg' : 'hold-on-icon.svg')) ) }}" class="icon-24" alt="icon">
                             <p>{{ $comic->status }}</p>
                        </div>

                        <div class="comic-links">
                            @if ($comic->is_published === true )
                                <a href="{{ route('author_comic', $comic->slug) }}" target="_blank" class="tertiary-btn">
                                    Читать комикс
                                    <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                                </a>
                            @endif

                            @if ($comic->is_moderated !== 'successful' || !$comic->is_published)
                                <a href="{{ route('user.moderation-confirm-comics', $comic->slug) }}" target="_blank" class="tertiary-btn">
                                    Модерация
                                    <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                                </a>
                            @endif
                        </div>

                    </div>



                    <div class="comic-actions">
                        @if($comic->is_published!==true)
                        <a href="#" class="list-action-btn edit-comic-btn"
                           data-comic-id="{{ $comic->id }}"
                           data-comic-name="{{ $comic->name }}"
                           data-comic-description="{{ $comic->description }}"
                           data-comic-age_restriction="{{ $comic->age_restriction ? $comic->age_restriction . '+' : '0+' }}"
                           data-comic-genres="{{ $comic->genres_string }}"
                           data-comic-cover="{{ $comic->cover ? Storage::url($comic->cover) : '' }}"
                           data-comic-file="{{ $comic->comics_file ? Storage::url($comic->comics_file) : '' }}"
                           data-comic-file-name="{{ $comic->comics_file ? basename($comic->comics_file) : '' }}">
                            <img src="{{ asset('images/icons/edit-primary.svg') }}" class="icon-24" alt="edit-icon">
                        </a>
                        @endif

                        <a href="#" class="list-action-btn delete-comic-btn"
                           data-comic-id="{{ $comic->id }}"
                           data-comic-name="{{ $comic->name }}">
                            <img src="{{ asset('images/icons/trash-primary-red.svg') }}" class="icon-24" alt="delete-icon">
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="load-more-container">
            <a href="" class="primary-btn">Загрузить еще</a>
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
                            <input type="file" name="comics_file" id="edit-comic-file" accept=".pdf,.cbr,.cbz">
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

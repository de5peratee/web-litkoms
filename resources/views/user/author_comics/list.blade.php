@extends('layouts.app')

@section('title', 'Список авторских комиксов')

@section('content')
    @vite(['resources/css/inputs.css'])
    @vite(['resources/css/user/author_comics_list.css'])
    @vite(['resources/js/user/author-comics-list-modal.js'])

    <div class="author-comics-list-container">
        <div class="author-comics-list-container-header">
            <h2>Авторские комиксы</h2>
            <a href="{{ route('user.create_author_comics') }}" class="primary-btn">Новый авторский комикс</a>
        </div>

        <div class="author-comics-list">
            @forelse ($comics as $comic)
                <div class="author-comic-item">
                    <div class="author-comic-data">
                        <p><strong>{{ $comic->name }}</strong></p>
                        <p>{{ $comic->description ?: 'Нет описания' }}</p>
                        <p>Жанры: {{ $comic->genres_string }}</p>
                        <p>Статус: {{ $comic->status }}</p>
                        @if ($comic->is_moderated !== 'successful' || !$comic->is_published)
                        <p>
                            <a href="{{ route('user.moderation-confirm-comics', $comic->slug) }}" class="text-hint">
                                Проверить статус модерации
                            </a>
                        </p>
                        @endif
                        @if($comic->cover)
                            <div class="comic-cover-preview">
                                <img src="{{ $comic->cover ? Storage::url('' . $comic->cover) : '' }}" alt="{{ $comic->name }} обложка"
                                     class="comic-cover-thumb">
                            </div>
                        @endif
                    </div>
                    <div class="author-comic-actions">
                        @if($comic->is_moderated !== 'successful' && !$comic->is_published)

                            <a href="#" class="list-action-btn edit-comic-btn"
                               data-comic-slug="{{ $comic->slug }}"
                               data-comic-name="{{ $comic->name }}"
                               data-comic-description="{{ $comic->description ?: '' }}"
                               data-comic-genres="{{ $comic->genres_string }}"
                               data-comic-age-restriction="{{ $comic->age_restriction ?? '0+' }}"
                               data-comic-cover="{{ $comic->cover ? Storage::url($comic->cover) : '' }}"
                               data-action="{{ route('user.update_author_comics', $comic->slug) }}">
                                <img src="{{ asset('images/icons/edit-primary.svg') }}" class="icon-24" alt="edit-icon">
                            </a>
                        @endif
                        <a href="#" class="list-action-btn delete-comic-btn"
                           data-comic-slug="{{ $comic->slug }}"
                           data-comic-name="{{ $comic->name }}"
                           data-action="{{ route('user.delete_author_comics', $comic->slug) }}">
                            <img src="{{ asset('images/icons/trash-primary-red.svg') }}" class="icon-24"
                                 alt="delete-icon">
                        </a>
                    </div>
                </div>
            @empty
                <p>Пока нет загруженных комиксов.</p>
            @endforelse
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
            <button id="edit-comic-modal-close" class="modal-close">×</button>

            <form id="edit-comic-form" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <input type="hidden" id="edit-comic-slug" name="slug">

                <div class="modal-section">
                    <label for="edit-comic-name">Название комикса</label>
                    <input type="text" id="edit-comic-name" name="title" placeholder="Введите название" value="{{ old('title') }}">
                    <div id="edit-comic-name-error" class="input-error">@error('title') {{ $message }} @enderror</div>
                </div>

                <div class="modal-section">
                    <label for="edit-comic-description">Описание</label>
                    <textarea id="edit-comic-description" name="description" placeholder="Введите описание">{{ old('description') }}</textarea>
                    <div id="edit-comic-description-error" class="input-error">@error('description') {{ $message }} @enderror</div>
                </div>

                <div class="modal-section">
                    <label for="edit-comic-genres">Жанры (через запятую)</label>
                    <input type="text" id="edit-comic-genres" name="genres" placeholder="Фантастика, боевик" value="{{ old('genres') }}">
                    <div id="edit-comic-genres-error" class="input-error">@error('genres') {{ $message }} @enderror</div>
                </div>

                <div class="modal-section">
                    <label for="edit-comic-age-restriction">Возрастное ограничение</label>
                    <select id="edit-comic-age-restriction" name="age_restriction">
                        <option value="0+" {{ old('age_restriction') == '0+' ? 'selected' : '' }}>0+</option>
                        <option value="6+" {{ old('age_restriction') == '6+' ? 'selected' : '' }}>6+</option>
                        <option value="12+" {{ old('age_restriction') == '12+' ? 'selected' : '' }}>12+</option>
                        <option value="16+" {{ old('age_restriction') == '16+' ? 'selected' : '' }}>16+</option>
                        <option value="18+" {{ old('age_restriction') == '18+' ? 'selected' : '' }}>18+</option>
                    </select>
                    <div id="edit-comic-age-restriction-error" class="input-error">@error('age_restriction') {{ $message }} @enderror</div>
                </div>

                <div class="modal-section cover-upload">
                    <label for="edit-comic-cover">Обложка</label>
                    <input type="file" id="edit-comic-cover" name="cover" accept="image/*">
                    <div id="edit-comic-cover-preview"></div>
                    <div id="edit-comic-cover-error" class="input-error">@error('cover') {{ $message }} @enderror</div>
                </div>

                <div class="modal-section">
                    <label for="edit-comic-file">Файл комикса (PDF или архив)</label>
                    <input type="file" id="edit-comic-file" name="comic_file" accept=".pdf,.zip,.rar">
                    <div id="edit-comic-file-error" class="input-error">@error('comic_file') {{ $message }} @enderror</div>
                </div>

                <div class="modal-actions">
                    <button type="button" id="cancel-edit-comic" class="secondary-btn">Отмена</button>
                    <button type="submit" class="primary-btn">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
@endsection
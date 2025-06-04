@extends('layouts.app')

@section('title', 'Список постов')

@section('content')
    @vite(['resources/css/inputs.css'])
    @vite(['resources/css/editor/multimedia_list.css'])
    @vite(['resources/js/editor/multimedia-list-modal.js'])

    <div class="multimedia-list-container">
        <div class="multimedia-list-container-header">
            <h3>Посты</h3>
            <a href="{{ route('editor.create_mediapost') }}" class="primary-btn">
                Опубликовать пост
                <img src="{{ asset('images/icons/plus-icon-white.svg') }}" class="icon-24" alt="icon">
            </a>
        </div>

        <div class="media-post-list">
            @foreach ($mediaPosts as $post)
                <div class="media-post-item">
                    <div class="media-post-data">
                        <p>{{ $post->id }}</p>
                        <p>{{ $post->name }}</p>
                        <p>{{ $post->description ?: 'Нет описания' }}</p>
                    </div>
                    <div class="media-post-actions">
                        @php
                            $medias = [];
                            foreach ($post->medias as $media) {
                                $fileUrl = Storage::url($media->file);
                                $fileExtension = pathinfo($media->file, PATHINFO_EXTENSION);

                                if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                                    $type = 'image';
                                } elseif (in_array($fileExtension, ['mp4', 'webm', 'ogg'])) {
                                    $type = 'video';
                                } elseif (in_array($fileExtension, ['mp3', 'wav', 'ogg'])) {
                                    $type = 'audio';
                                } else {
                                    $type = 'unsupported';
                                }

                                $medias[] = [
                                    'url' => $fileUrl,
                                    'type' => $type,
                                    'ext' => $fileExtension
                                ];
                            }
                        @endphp

                        <a href="#" class="list-action-btn edit-post-btn"
                           data-post-id="{{ $post->id }}"
                           data-post-name="{{ $post->name }}"
                           data-post-description="{{ $post->description }}"
                           data-post-medias='@json($medias)'>
                            <img src="{{ asset('images/icons/edit-primary.svg') }}" class="icon-24" alt="edit-icon">
                        </a>

                        <a href="#" class="list-action-btn delete-post-btn"
                           data-post-id="{{ $post->id }}"
                           data-post-name="{{ $post->name }}">
                            <img src="{{ asset('images/icons/trash-primary-red.svg') }}" class="icon-24" alt="delete-icon">
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
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

                <div class="lit-field">
                    <label for="edit-post-name">Название поста</label>
                    <input type="text" name="name" id="edit-post-name" placeholder="Введите название" required>
                    <div class="input-error" id="edit-post-name-error"></div>
                </div>

                <div class="lit-field">
                    <label for="edit-post-description">Описание</label>
                    <textarea name="description" id="edit-post-description" rows="5" placeholder="Подробное описание поста" required></textarea>
                    <div class="input-error" id="edit-post-description-error"></div>
                </div>

                <div class="lit-field">
                    <label for="edit-post-media">Медиафайлы</label>
                    <div class="media-upload">
                        <input type="file" name="media[]" id="media" accept="image/*,video/*,application/pdf" multiple class="{{ $errors->has('media') ? 'is-invalid' : '' }}">
                        <div class="media-preview" id="edit-post-media-preview"></div>
                    </div>
                    <div class="input-error" id="edit-post-media-error"></div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="secondary-btn" id="cancel-edit-post">Отмена</button>
                    <button type="submit" class="primary-btn">Сохранить изменения</button>
                </div>
            </form>
        </div>
    </div>
@endsection

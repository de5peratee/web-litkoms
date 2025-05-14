@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.app')

@section('title', 'Список постов')

@section('content')
    @vite(['resources/css/inputs.css'])
    @vite(['resources/css/editor/multimedia_list.css'])
    @vite(['resources/js/editor/multimedia-list-modal.js'])

    <div class="multimedia-list-container">
        <div class="multimedia-list-container-header">
            <h2>Посты</h2>
            <a href="{{ route('editor.create_mediapost') }}" class="primary-btn">Создать пост</a>
        </div>

        <div class="media-post-list">
            @foreach ($mediaPosts as $post)
                <div class="media-post-item">
                    <div class="media-post-data">
                        <p>{{ $post->id }}</p>
                        <p>{{ $post->name }}</p>
                        <p>{{ $post->description }}</p>
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
            <div class="modal-actions">
                <button class="secondary-btn" id="cancel-delete">Отмена</button>
                <button class="primary-btn">Удалить</button>
            </div>
        </div>
    </div>

    <div class="modal hidden" id="edit-modal">
        <div class="modal-content">
            <div class="modal-close" id="edit-modal-close">
                <img src="{{ asset('images/icons/close-primary.svg') }}" class="icon-24" alt="close">
            </div>
            <h3>Редактирование поста</h3>
            <div class="h-divider"></div>
            <form method="POST" action="" enctype="multipart/form-data" class="lit-form">
                @csrf
                <input type="hidden" name="id" id="edit-post-id">

                <div class="lit-field">
                    <label for="edit-post-media">Медиафайлы</label>
                    <div class="media-upload">
                        <input type="file" name="media[]" id="edit-post-media" accept="image/*,video/*,audio/*" multiple>
                        <div class="media-preview" id="edit-post-media-preview"></div>
                    </div>
                </div>

                <div class="lit-field">
                    <label for="edit-post-name">Название поста</label>
                    <input type="text" name="name" id="edit-post-name" placeholder="Введите название" required>
                </div>

                <div class="lit-field">
                    <label for="edit-post-description">Описание</label>
                    <textarea name="description" id="edit-post-description" rows="5" placeholder="Подробное описание поста" required></textarea>
                </div>

                <div class="modal-actions">
                    <button type="button" class="secondary-btn" id="cancel-edit">Отмена</button>
                    <button type="submit" class="primary-btn">Сохранить</button>
                </div>
            </form>

        </div>
    </div>

@endsection

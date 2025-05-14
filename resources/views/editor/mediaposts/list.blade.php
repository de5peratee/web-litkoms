@extends('layouts.app')

@section('title', 'Список постов')

@section('content')
    @vite(['resources/css/editor/multimedia_list.css'])

    <div class="multimedia-list-container">
        <div class="multimedia-list-container-header">
            <h2>Посты</h2>
            <a href="{{route('editor.create_mediapost')}}"  class="primary-btn">Создать пост</a>
        </div>

        <div class="media-post-list">
            @if ($mediaPosts->isEmpty())
                <p class="no-posts">Пока нет медиапостов.</p>
            @else
                @foreach ($mediaPosts as $post)
                    <div class="media-post-item">
                        <div class="media-post-data">
                            <p>{{ $post->id }}</p>
                            <p>{{ $post->name }}</p>
                            <p>{{ $post->description }}</p>
                        </div>

                        <div class="media-post-actions">
                            <a href="#"  class="list-action-btn">
                                <img src="{{ asset('images/icons/edit-primary.svg') }}" class="icon-24" alt="edit-icon">
                            </a>
                            <a href="#"  class="list-action-btn">
                                <img src="{{ asset('images/icons/trash-primary-red.svg') }}" class="icon-24" alt="delete-icon">
                            </a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

{{--            <ul>--}}
{{--                @foreach ($mediaPosts as $post)--}}
{{--                    <li>--}}
{{--                        <p>{{ $post->name }}</p>--}}
{{--                        <p>{{ $post->description }}</p>--}}

{{--                        @if ($post->medias->isNotEmpty())--}}
{{--                            <div class="media-gallery">--}}
{{--                                @foreach ($post->medias as $media)--}}
{{--                                    @php--}}
{{--                                        $fileUrl = Storage::url($media->file);--}}
{{--                                        $fileExtension = pathinfo($media->file, PATHINFO_EXTENSION);--}}
{{--                                    @endphp--}}

{{--                                    @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']))--}}
{{--                                        <img src="{{ $fileUrl }}" alt="media_image">--}}
{{--                                    @elseif (in_array($fileExtension, ['mp4', 'webm', 'ogg']))--}}
{{--                                        <video controls>--}}
{{--                                            <source src="{{ $fileUrl }}" type="video/{{ $fileExtension }}">--}}
{{--                                            Ваш браузер не поддерживает видео.--}}
{{--                                        </video>--}}
{{--                                    @elseif (in_array($fileExtension, ['mp3', 'wav', 'ogg']))--}}
{{--                                        <audio controls>--}}
{{--                                            <source src="{{ $fileUrl }}" type="audio/{{ $fileExtension }}">--}}
{{--                                            Ваш браузер не поддерживает аудио.--}}
{{--                                        </audio>--}}
{{--                                    @else--}}
{{--                                        <p>Тип файла не поддерживается для отображения.</p>--}}
{{--                                    @endif--}}
{{--                                @endforeach--}}
{{--                            </div>--}}
{{--                        @else--}}
{{--                            <p>Медиафайлов нет.</p>--}}
{{--                        @endif--}}
{{--                    </li>--}}
{{--                @endforeach--}}
{{--            </ul>--}}
    </div>
@endsection

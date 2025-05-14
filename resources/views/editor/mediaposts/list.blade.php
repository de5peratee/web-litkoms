@extends('layouts.app')

@section('title', 'Список постов')

@section('content')
    @vite(['resources/css/editor/multimedia_post.css'])

    <div class="form-container">
        <h2>Список медиапостов</h2>
        @if ($mediaPosts->isEmpty())
            <p class="no-posts">Пока нет медиапостов.</p>
        @else
            <ul>
                @foreach ($mediaPosts as $post)
                    <li>
                        <h3>{{ $post->name }}</h3>
                        <p>{{ $post->description }}</p>

                        @if ($post->medias->isNotEmpty())
                            <div class="media-gallery">
                                @foreach ($post->medias as $media)
                                    @php
                                        $fileUrl = Storage::url($media->file);
                                        $fileExtension = pathinfo($media->file, PATHINFO_EXTENSION);
                                    @endphp

                                    @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']))
                                        <img src="{{ $fileUrl }}" alt="media_image">
                                    @elseif (in_array($fileExtension, ['mp4', 'webm', 'ogg']))
                                        <video controls>
                                            <source src="{{ $fileUrl }}" type="video/{{ $fileExtension }}">
                                            Ваш браузер не поддерживает видео.
                                        </video>
                                    @elseif (in_array($fileExtension, ['mp3', 'wav', 'ogg']))
                                        <audio controls>
                                            <source src="{{ $fileUrl }}" type="audio/{{ $fileExtension }}">
                                            Ваш браузер не поддерживает аудио.
                                        </audio>
                                    @else
                                        <p>Тип файла не поддерживается для отображения.</p>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p>Медиафайлов нет.</p>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection

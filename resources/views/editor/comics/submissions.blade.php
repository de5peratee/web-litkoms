@extends('layouts.app')

@section('title', 'Заявки на модерацию')

@section('content')
    @vite(['resources/css/editor/authors_сomics_submissions_list.css'])

    <div class="submissions-list-container">
        <div class="submissions-list-container-header">
            <h2>Заявки</h2>
            <p class="text-medium">Количество заявок: {{ $comics_count }}</p>
        </div>

        <div class="submissions-list">
            @forelse ($comics as $comic)
                <div class="submission-item">
                    <div class="submission-comic-preview">
                        <div class="submission-comic-cover-wrapper">
                            <img src="{{ $comic->cover ? Storage::url($comic->cover) : asset('images/default_template/comics.svg') }}" class="comic-cover">
                        </div>

                        <div class="submission-comic-preview-text-wrapper">
                            <div class="preview-text-flex">
                                <p class="text-big">{{ $comic->name }}</p>
                                @if ($comic->age_restriction >= 18)
                                    <p class="text-hint age-restriction-tag">18+</p>
                                @endif
                            </div>

                            <div class="submission-author-text-wrapper">
                                <p>{{ $comic->createdBy->nickname ?? 'Неизвестный' }}</p>
                                <p class="submission-author-text">·</p>
                                <p class="submission-author-text">{{ $comic->createdBy->name ?? 'Неизвестный автор' }}</p>
                            </div>

                            <p class="text-hint submission-datetime-tag">
                                {{ $comic->updated_at->diffForHumans() }}
                            </p>
                        </div>

                        <a href="{{ route('editor.comic_moderation', $comic->slug) }}" class="tertiary-btn">
                            Модерация
                            <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                        </a>
                    </div>

                    <div class="submission-actions-tab-wrapper">
                        <div class="submission-action-tab">
                            <img src="{{ asset('images/icons/dislike-primary.svg') }}" class="icon-24" alt="icon">
                        </div>
                        <div class="submission-action-tab active-submission-tab">
                            |
                        </div>
                        <div class="submission-action-tab">
                            <img src="{{ asset('images/icons/like-primary.svg') }}" class="icon-24" alt="icon">
                        </div>
                    </div>
                </div>
            @empty
                <p>Нет комиксов на модерации</p>
            @endforelse
        </div>
    </div>
@endsection
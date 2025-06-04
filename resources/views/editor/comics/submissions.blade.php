@extends('layouts.app')

@section('title', 'Заявки на модерацию')

@section('content')
    @vite(['resources/css/inputs.css'])
    @vite(['resources/css/editor/authors_сomics_submissions_list.css'])
    @vite(['resources/js/editor/authors_сomics_submissions_list.js'])

    <div class="submissions-list-container">

        <div class="info-block">
            <div class="info-header-title">
                <img src="{{ asset('images/icons/hw/submissions-icon.svg') }}" alt="icon" class="icon-32">
                <h3>Заявки на модерацию</h3>
                <p class="text-medium submissions-count-text">{{$comics->count()}}</p>
            </div>

            <div class="search-container">
                <form id="search-form" action="" method="GET" class="search-form">

                    <div class="search-input-wrapper">
                        <input type="text" name="search" placeholder="Поиск по заявкам..." value="{{ request('search') }}">
                        <div class="clear-search hidden">
                            <img src="{{ asset('images/icons/close-primary.svg') }}" class="icon-20" alt="clear">
                        </div>
                    </div>

                    <button type="submit" class="secondary-btn">Найти</button>

                </form>
            </div>

        </div>

        <div class="submissions-filter-tabs-wrapper">
            <div class="submissions-filter-tab active-tab" data-tab="">
                <img src="{{ asset('images/icons/review-clock-white.svg') }}" alt="icon" class="icon-24">
                На рассмотрении
            </div>
            <div class="submissions-filter-tab" data-tab="">
                <img src="{{ asset('images/icons/approves-icon-primary.svg') }}" alt="icon" class="icon-24">
                Принятые
            </div>
            <div class="submissions-filter-tab" data-tab="">
                <img src="{{ asset('images/icons/rejects-icon-primary.svg') }}" alt="icon" class="icon-24">
                Отклоненные
            </div>
        </div>

        <div class="submissions-list">
            @forelse ($comics as $comic)
                <div class="submission-item">

                    <div class="submission-item-left">
                        <div class="item-cell num-cell">
                            {{ $loop->index + 1}}
                        </div>

                        <div class="submission-comic-preview item-cell">
                            <div class="submission-comic-cover-wrapper">
                                <img src="{{ $comic->cover ? Storage::url($comic->cover) : asset('images/default_template/comics.svg') }}" alt="comic-cover">
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
                        </div>

                        <div class="item-cell">
                            <a href="{{ route('editor.comic_moderation', $comic->slug) }}" class="tertiary-btn">
                                Модерация
                                <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                            </a>
                        </div>
                    </div>


                    <div class="submission-actions-tab-wrapper">
                        <div class="submission-action-tab dislike-tab">
                            <img src="{{ asset('images/icons/dislike-primary.svg') }}" class="icon-24" alt="icon">
                            Отклонить
                        </div>

                        <div class="submission-action-divider">
                            |
                        </div>

                        <div class="submission-action-tab like-tab">
                            <img src="{{ asset('images/icons/like-primary.svg') }}" class="icon-24" alt="icon">
                            Принять
                        </div>
                    </div>
                </div>
            @empty
                <p>Нет комиксов на модерации</p>
            @endforelse
        </div>

        <div class="load-more-container">
            <button id="load-more" class="primary-btn"
                    data-page="2"
                    data-search="{{ request('search') ?? '' }}">
                Загрузить еще
            </button>
        </div>

    </div>

    <!-- Review Modal -->
    <div class="modal hidden" id="review-submission-modal">
        <div class="modal-content">
            <div class="modal-close" id="review-submission-modal-close">
                <img src="{{ asset('images/icons/close-primary.svg') }}" class="icon-24" alt="close">
            </div>

            <h3 id="review-submission-modal-title">Итоговое решение</h3>

            <div class="h-divider"></div>

            <form id="review-submission-form" method="POST" action="{{route('editor.comic_moderation', $comic->slug)}}"  class="lit-form">
                @csrf
                @method('PUT')

                <div class="lit-field">
                    <label for="edit-submission-comment">Комментарий</label>
                    <textarea name="description" id="edit-submission-comment" rows="5" placeholder="Напишите причину отказа..."></textarea>
                    <div class="input-error" id="review-submission-error"></div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="secondary-btn" id="cancel-review-modal">Отмена</button>
                    <button type="submit" class="primary-btn"></button>
                </div>
            </form>
        </div>
    </div>
@endsection

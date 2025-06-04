@extends('layouts.app')

@section('title', 'Заявки на модерацию')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/inputs.css'])
    @vite(['resources/css/editor/authors_сomics_submissions_list.css'])
    @vite(['resources/js/editor/authors_сomics_submissions_list.js'])

    <div class="submissions-list-container">
        <div class="submissions-list-container-header">
            <div class="info-header-title">
                <img src="{{ asset('images/icons/hw/submissions-icon.svg') }}" alt="icon" class="icon-32">
                <h3>Заявки на модерацию</h3>
                <p class="text-medium submissions-count-text">{{ $comics_count }}</p>
            </div>

            <div class="search-container">
                <form id="search-form" action="{{ route('editor.comics_submissions_index') }}" method="GET"
                      class="search-form">
                    <div class="search-input-wrapper">
                        <input type="text" name="search" placeholder="Поиск по заявкам..."
                               value="{{ request('search') }}">
                        <div class="clear-search {{ request('search') ? '' : 'hidden' }}">
                            <img src="{{ asset('images/icons/close-primary.svg') }}" class="icon-20" alt="clear">
                        </div>
                    </div>
                    <input type="hidden" name="status" value="{{ $status }}">
                    <button type="submit" class="secondary-btn">Найти</button>
                </form>
            </div>
        </div>

        <div class="submissions-filter-tabs-wrapper">
            <a href="{{ route('editor.comics_submissions_index', ['status' => 'under review', 'search' => request('search')]) }}"
               class="submissions-filter-tab {{ $status === 'under review' ? 'active-tab' : '' }}"
               data-tab="under review">
                <img src="{{ asset('images/icons/review-clock-' . ($status === 'under review' ? 'white' : 'primary') . '.svg') }}"
                     alt="icon" class="icon-24">
                На рассмотрении
            </a>
            <a href="{{ route('editor.comics_submissions_index', ['status' => 'successful', 'search' => request('search')]) }}"
               class="submissions-filter-tab {{ $status === 'successful' ? 'active-tab' : '' }}"
               data-tab="successful">
                <img src="{{ asset('images/icons/approves-icon-' . ($status === 'successful' ? 'white' : 'primary') . '.svg') }}"
                     alt="icon" class="icon-24">
                Принятые
            </a>
            <a href="{{ route('editor.comics_submissions_index', ['status' => 'unsuccessful', 'search' => request('search')]) }}"
               class="submissions-filter-tab {{ $status === 'unsuccessful' ? 'active-tab' : '' }}"
               data-tab="unsuccessful">
                <img src="{{ asset('images/icons/rejects-icon-' . ($status === 'unsuccessful' ? 'white' : 'primary') . '.svg') }}"
                     alt="icon" class="icon-24">
                Отклоненные
            </a>
        </div>

        <div class="submissions-list">
            @include('editor.comics.partials.submissions_list')
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

            <form id="review-submission-form" method="POST" action="">
                @csrf
                @method('PUT')
                <input type="hidden" name="moderation_status" id="moderation-status">
                <input type="hidden" name="age_restriction" id="age-restriction">

                <div class="lit-field" id="feedback-field" style="display: none;">
                    <label for="edit-submission-comment">Комментарий</label>
                    <textarea name="feedback" id="edit-submission-comment" rows="5"
                              placeholder="Напишите причину отказа..."></textarea>
                    <div class="input-error" id="review-submission-error"></div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="secondary-btn" id="cancel-review-btn">Отмена</button>
                    <button type="submit" class="primary-btn" id="submit-review-btn"></button>
                </div>
            </form>
        </div>
    </div>
@endsection
@extends('layouts.app')

@section('title', 'Панель редактора')

@section('content')
    @vite(['resources/css/editor/dashboard.css'])

    <div class="dashboard-container">

        <div class="info-block dashboard-block">
            <div class="info-header">
                <div class="info-header">
                    <div class="info-header-title">
                        <img src="{{ asset('images/icons/hw/panel-icon.svg') }}" alt="icon" class="icon-32">
                        <h3>Панель управления</h3>
                    </div>
                </div>

                <div class="create-actions">
                    <a href="{{route('editor.create_event')}}" class="primary-btn">
                        Создать мероприятие
                        <img src="{{ asset('images/icons/plus-icon-white.svg') }}" class="icon-24" alt="icon">
                    </a>
                    <a href="{{route('editor.create_mediapost')}}" class="primary-btn">
                        Создать пост
                        <img src="{{ asset('images/icons/plus-icon-white.svg') }}" class="icon-24" alt="icon">
                    </a>
                    <a href="{{route('editor.create_catalog')}}" class="primary-btn">
                        Загрузить комикс в каталог
                        <img src="{{ asset('images/icons/plus-icon-white.svg') }}" class="icon-24" alt="icon">
                    </a>
                </div>
            </div>
        </div>

        <div class="info-block">
            <div class="info-header">
                <p class="header-title-text">Инструменты</p>
            </div>

            <div class="h-divider"></div>

            <div class="cta-actions-list">
                <a href="{{ route('editor.events_index') }}" class="cta-block-redirect">
                    <h2 class="cta-count-text">{{ $eventsCount }}</h2>
                    <div class="cta-title-flex">
                        <p class="text-big">Мероприятия</p>
                        <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                    </div>
                </a>
                <a href="{{ route('editor.mediapost_index') }}" class="cta-block-redirect">
                    <h2 class="cta-count-text">{{ $mediaPostsCount }}</h2>
                    <div class="cta-title-flex">
                        <p class="text-big">Новостные посты</p>
                        <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                    </div>
                </a>
                <a href="{{ route('editor.catalogs_index') }}" class="cta-block-redirect">
                    <h2 class="cta-count-text">{{ $catalogsCount }}</h2>
                    <div class="cta-title-flex">
                        <p class="text-big">Каталог комиксов</p>
                        <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                    </div>
                </a>
                <a href="{{ route('editor.comics_submissions_index') }}" class="cta-block-redirect">
                    <h2 class="cta-count-text">{{ $submissionsCount }}</h2>
                    <div class="cta-title-flex">
                        <p class="text-big">Заявки на модерацию</p>
                        <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                    </div>
                </a>
            </div>

            <div class="info-header">
                <p class="header-title-text">Редакторы</p>
            </div>

            <div class="h-divider"></div>

            <div class="editor-list-wrapper">
                @foreach($editors as $editor)
                    <a href="{{ route('profile.index', ['nickname' => $editor->nickname]) }}" class="editor-block-info">
                        <div class="editor-avatar-wrapper">
                            @if($editor->icon && Storage::disk('public')->exists($editor->icon))
                                <img src="{{ Storage::url($editor->icon) }}" alt="avatar">
                            @else
                                <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="default avatar">
                            @endif
                        </div>
                        <div class="editor-text-wrapper">
                            <p class="editor-name-text">{{ $editor->name }}</p>
                            <p class="editor-nick-text">{{ '@' . $editor->nickname }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

        </div>
    </div>
@endsection

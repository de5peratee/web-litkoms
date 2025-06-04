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
                        Опубликовать мероприятие
                        <img src="{{ asset('images/icons/plus-icon-white.svg') }}" class="icon-24" alt="icon">
                    </a>
                    <a href="{{route('editor.create_mediapost')}}" class="primary-btn">
                        Опубликовать пост
                        <img src="{{ asset('images/icons/plus-icon-white.svg') }}" class="icon-24" alt="icon">
                    </a>
                    <a href="{{route('editor.create_catalog')}}" class="primary-btn">
                        Опубликовать комикс
                        <img src="{{ asset('images/icons/plus-icon-white.svg') }}" class="icon-24" alt="icon">
                    </a>
                </div>
            </div>
        </div>

        <div class="info-block">
            <div class="info-header">
                <p class="header-title-text">Инструменты</p>
            </div>

            <div class="cta-actions-list">
                <a href="{{ route('editor.events_index') }}" class="cta-block-redirect">
                    <h2 class="cta-count-text">35</h2>

                    <div class="cta-title-flex">
                        <p class="text-big">Мероприятия</p>
                        <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                    </div>
                </a>
                <a href="{{ route('editor.mediapost_index') }}" class="cta-block-redirect">
                    <h2 class="cta-count-text">24</h2>

                    <div class="cta-title-flex">
                        <p class="text-big">Новостные посты</p>
                        <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                    </div>
                </a>
                <a href="{{ route('editor.catalogs_index') }}" class="cta-block-redirect">
                    <h2 class="cta-count-text">726</h2>

                    <div class="cta-title-flex">
                        <p class="text-big">Каталог комиксов</p>
                        <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                    </div>
                </a>
                <a href="{{ route('editor.comics_submissions_index') }}" class="cta-block-redirect">
                    <h2 class="cta-count-text">4</h2>

                    <div class="cta-title-flex">
                        <p class="text-big">Заявки на модерацию</p>
                        <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                    </div>
                </a>
            </div>

            <div class="h-divider"></div>

            <div class="info-header">
                <p class="header-title-text">Редакторы</p>
            </div>


            <div class="editor-list-wrapper">
{{--                <a href="{{ route('profile.index', ['nickname' => $author->nickname]) }}" class="author-item">--}}
                <a href="" class="editor-block-info">
                    <div class="editor-avatar-wrapper">
{{--                        @if($author->icon && Storage::disk('public')->exists($author->icon))--}}
{{--                            <img src="{{ Storage::url($author->icon) }}" alt="avatar">--}}
{{--                        @else--}}
{{--                            <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="default avatar">--}}
{{--                        @endif--}}
                        <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="default avatar">--}}
                    </div>

                    <div class="editor-text-wrapper">
                        <p class="editor-name-text">Владислав М.</p>
                        <p class="editor-nick-text" >@Ник</p>
                    </div>
                </a>
                <a href="" class="editor-block-info">
                    <div class="editor-avatar-wrapper">
                        {{--                        @if($author->icon && Storage::disk('public')->exists($author->icon))--}}
                        {{--                            <img src="{{ Storage::url($author->icon) }}" alt="avatar">--}}
                        {{--                        @else--}}
                        {{--                            <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="default avatar">--}}
                        {{--                        @endif--}}
                        <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="default avatar">--}}
                    </div>

                    <div class="editor-text-wrapper">
                        <p class="editor-name-text">Владислав М.</p>
                        <p class="editor-nick-text" >@Ник</p>
                    </div>
                </a>
                <a href="" class="editor-block-info">
                    <div class="editor-avatar-wrapper">
                        {{--                        @if($author->icon && Storage::disk('public')->exists($author->icon))--}}
                        {{--                            <img src="{{ Storage::url($author->icon) }}" alt="avatar">--}}
                        {{--                        @else--}}
                        {{--                            <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="default avatar">--}}
                        {{--                        @endif--}}
                        <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="default avatar">--}}
                    </div>

                    <div class="editor-text-wrapper">
                        <p class="editor-name-text">Владислав М.</p>
                        <p class="editor-nick-text" >@Ник</p>
                    </div>
                </a>
                <a href="" class="editor-block-info">
                    <div class="editor-avatar-wrapper">
                        {{--                        @if($author->icon && Storage::disk('public')->exists($author->icon))--}}
                        {{--                            <img src="{{ Storage::url($author->icon) }}" alt="avatar">--}}
                        {{--                        @else--}}
                        {{--                            <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="default avatar">--}}
                        {{--                        @endif--}}
                        <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="default avatar">--}}
                    </div>

                    <div class="editor-text-wrapper">
                        <p class="editor-name-text">Владислав М.</p>
                        <p class="editor-nick-text" >@Ник</p>
                    </div>
                </a>
            </div>

        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Панель редактора')

@section('content')
    @vite(['resources/css/editor/dashboard.css'])

    <div class="dashboard-container">
        <div class="info-block">
            <div class="info-header">
                <h3>Панель управления</h3>
                <div class="create-actions">
                    <a href="{{route('editor.create_event')}}" class="primary-btn">Создать мероприятие</a>
                    <a href="{{route('editor.create_mediapost')}}" class="primary-btn">Создать пост</a>
                    <a href="{{route('editor.create_catalog')}}" class="primary-btn">Добавить в каталог</a>
                </div>
            </div>
        </div>

        <div class="info-block">
            <div class="cta-actions-list">
                <a href="{{ route('editor.events_index') }}" class="cta-block-redirect">
                    <p class="text-big">Мероприятия</p>
                </a>
                <a href="{{ route('editor.mediapost_index') }}" class="cta-block-redirect">
                    <p class="text-big">Новостные посты</p>
                </a>
                <a href="{{ route('editor.catalogs_index') }}" class="cta-block-redirect">
                    <p class="text-big">Каталог</p>
                </a>
                <a href="{{ route('editor.comics_submissions_index') }}" class="cta-block-redirect">
                    <p class="text-big">Заявки на модерацию</p>
                </a>
            </div>
        </div>
    </div>
@endsection

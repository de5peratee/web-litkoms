@extends('layouts.app')

@section('title', 'Панель редактора')

@section('content')
    @vite(['resources/css/editor/dashboard.css'])

    <div class="dashboard-container">

        <div class="info-block">
            <div class="info-header">
                <h3>Панель управления</h3>
                <div class="create-actions">
                    <a href="{{route('editor.create_event_form')}}" class="primary-btn">Создать мероприятие</a>
                    <a href="{{route('editor.create_post_form')}}"  class="primary-btn">Создать пост</a>
                </div>
            </div>
        </div>

        <div class="info-block">
            <div class="cta-actions-list">
                <a href="{{ route('editor.events_list') }}" class="cta-block-redirect">
                    <p class="text-big">Мероприятия</p>
                </a>
                <a href="{{ route('editor.news_list') }}" class="cta-block-redirect">
                    <p class="text-big">Новостные посты</p>
                </a>
                <a href="" class="cta-block-redirect disable-cta">
                    <p class="text-big">Заявки на модерацию</p>
                </a>
            </div>
        </div>

    </div>
@endsection

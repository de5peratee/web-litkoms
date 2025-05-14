@extends('layouts.app')

@section('title', 'Список мероприятий')

@section('content')
    @vite(['resources/css/editor/events_list.css'])

    <div class="events-container">
        <h2>Список мероприятий</h2>
        @if ($events->isEmpty())
            <div>Мероприятия отсутствуют.</div>
        @else
            @foreach ($events as $event)
                <div class="event-item">
                    <div>ID: {{ $event->id }}</div>
                    <div>Название: {{ $event->name }}</div>
{{--                    <div>Дата начала: {{ $event->start_date ? $event->start_date->translatedFormat('j F Y H:i', 'ru') : 'Не указана' }}</div>--}}
{{--                    <div>Дата окончания: {{ $event->end_date ? $event->end_date->translatedFormat('j F Y H:i', 'ru') : 'Не указана' }}</div>--}}
                    <div>Описание: {{ $event->description ?: 'Нет описания' }}</div>
                    <div>Обложка: {{ $event->cover ?: 'Нет обложки' }}</div>
                    <div>Создано редактором: {{ $event->created_by }}</div>
                    <div>Дата создания: {{ $event->created_at->translatedFormat('j F Y H:i', 'ru') }}</div>
                    <div>Дата обновления: {{ $event->updated_at ? $event->updated_at->translatedFormat('j F Y H:i', 'ru') : 'Не обновлялось' }}</div>
                    <div>Дата удаления: {{ $event->deleted_at ? $event->deleted_at->translatedFormat('j F Y H:i', 'ru') : 'Не удалено' }}</div>
                </div>
            @endforeach
        @endif
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Модерация и подтверждение')

@section('content')
    @vite(['resources/css/user/moderation-confirm-comics.css'])

    <div class="moderation-confirm-container">
        <div class="moderation-confirm-container-header">
            <h3>Модерация</h3>
            <div class="progress-bar">
                <div class="progress-bar-endpoint {{ $comic->is_moderated !== 'under review' ? 'completed-endpoint' : '' }}">
                    <img src="{{ asset('images/icons/moderation/success-icon.svg') }}" class="icon-24" alt="icon">
                    <p>Загрузка комикса</p>
                </div>
                <div class="h-divider {{ $comic->is_moderated !== 'under review' ? 'completed-divider' : '' }}"></div>
                <div class="progress-bar-endpoint {{ $comic->is_moderated === 'under review' ? 'active-endpoint' : 'completed-endpoint' }}">
                    <img src="{{ asset('images/icons/moderation/' . ($comic->is_moderated === 'under review' ? 'hold-on-icon.svg' : ($comic->is_moderated === 'successful' ? 'success-icon.svg' : 'reject-icon.svg'))) }}" class="icon-24" alt="icon">
                    <p>Модерация</p>
                </div>
                <div class="h-divider {{ $comic->is_moderated === 'successful' ? 'completed-divider' : '' }}"></div>
                <div class="progress-bar-endpoint {{ $comic->is_moderated === 'successful' ? 'active-endpoint' : '' }}">
                    <p>Подтверждение</p>
                </div>
            </div>
        </div>

        <div class="h-divider"></div>

        <div class="moderation-confirm-message-wrapper">
            <div class="icon-wrapper {{ $comic->is_moderated === 'successful' ? 'success-wrapper' : ($comic->is_moderated === 'unsuccessful' ? 'error-wrapper' : 'pending-wrapper') }}">
                <img src="{{ asset('images/icons/moderation/' . ($comic->is_moderated === 'successful' ? 'success-icon.svg' : ($comic->is_moderated === 'unsuccessful' ? 'reject-icon.svg' : 'hold-on-icon.svg')) ) }}" class="icon-32" alt="icon">
            </div>

            <div class="message-text">
                @if($comic->is_moderated === 'under review')
                    <p class="message-title text-big">Комикс на модерации</p>
                    <p class="message-description text-hint">Ваш комикс находится на проверке. <br>Пожалуйста, дождитесь завершения модерации.</p>
                @elseif($comic->is_moderated === 'successful' && !$comic->is_published)
                    <p class="message-title text-big">Модерация успешно пройдена</p>
                    <p class="message-description text-hint">Ваш комикс полностью соответствует авторским правам. <br>Теперь вы можете его опубликовать!</p>
                @elseif($comic->is_moderated === 'unsuccessful')
                    <p class="message-title text-big">Комикс не принят</p>
                    <p class="message-description text-hint">Ваш комикс не прошел модерацию. <br>Пожалуйста, проверьте замечания модератора и внесите изменения.</p>
                    @if($comic->feedback)
                        <p class="message-feedback text-hint"><strong>Замечания модератора:</strong> {{ $comic->feedback }}</p>
                    @endif
                @endif
            </div>
        </div>

        <div class="author-comics-preview">
            <div class="comics-preview-wrapper">
                <img src="{{ Storage::url($comic->cover) }}" alt="comics_cover">
            </div>

            <div class="comics-preview-text">
                <div class="author-wrapper">
                    @if(Auth::user()->icon && Storage::exists(Auth::user()->icon))
                        <div class="comics-author-avatar-wrapper">
                            <img src="{{ Storage::url(Auth::user()->icon) }}" alt="{{ Auth::user()->icon }}">
                        </div>
                    @else
                        <div class="comics-author-avatar-wrapper">
                            <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="ava_cover">
                        </div>
                    @endif

                    <p class="author-nickname-text">{{ '@' . Auth::user()->nickname }}</p>
                </div>

                <h3>{{ $comic->name }}</h3>

                <p class="text-small">{{ Str::limit($comic->description, 100) }}</p>

                <div class="comics-genres">
                    @foreach ($comic->genres as $genre)
                        <span class="comics-genre-tag text-hint">{{ $genre->name }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="h-divider"></div>

        <div class="moderation-confirm-actions">
            <a href="{{ route('user.author_comics') }}" class="secondary-btn">Вернуться к списку</a>
            @if($comic->is_moderated === 'successful' && !$comic->is_published)
                <form action="{{ route('user.publish_comic', $comic->slug) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="primary-btn">Опубликовать</button>
                </form>
            @endif
        </div>
    </div>
@endsection

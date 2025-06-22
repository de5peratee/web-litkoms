@extends('layouts.app')

@section('title', $user->nickname)

@section('content')
    @vite(['resources/css/profile.css'])

    @vite(['resources/js/toggleSubscription.js'])
    @vite(['resources/js/profile-tabs.js'])
    @vite(['resources/js/comics-list-wrapper-fix.js'])

    <div class="profile-container">
        <div class="profile-header">
            <img src="{{ asset('images/icons/hw/hw_brush.svg') }}" alt="icon" class="hw-abstract brush-icon">
            <img src="{{ asset('images/icons/hw/hw_draw_pen.svg') }}" alt="icon" class="hw-abstract draw_pen-icon">
            <img src="{{ asset('images/icons/hw/hw_ruler.svg') }}" alt="icon" class="hw-abstract ruler-icon">

            <div class="profile-user-data">
                <div class="profile-user-avatar-wrapper">
                    @if($user->icon && Storage::disk('public')->exists($user->icon))
                        <img src="{{ Storage::url($user->icon) }}" alt="avatar">
                    @else
                        <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="default avatar">
                    @endif
                </div>

                <div class="profile-text-data">
                    <div class="profile-nearby-text-wrapper">
                        @if ($user->role === 'editor')
                            <div class="editor-role-tag">
                                Редактор
                            </div>
                        @endif


                        <div class="profile-title-flex">
                            <h3>{{ $user->name }} {{ $user->last_name }}</h3>

                            @auth
                                @if (Auth::id() == $user->id)
                                    <a href="{{ route('settings.show') }}" class="profile-settings-btn">
                                        <img src="{{ asset('images/icons/edit-primary.svg') }}" class="icon-24" alt="icon">
                                    </a>
                                @endif
                            @endauth
                        </div>

                        <div class="profile-secondary-text-data">
                            <p class="text-medium">{{ '@' }}{{ $user->nickname }}</p>
                            <p class="text-medium dot">·</p>
                            <p class="text-medium profile-email">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="user-status-bar">

                        <p class="user-subscribers text-small">Подписчиков: {{ $user->subscribers()->count() }}</p>
                        <div class="v-divider"></div>
                        <div class="user-avg-grade">
                            <p class="text-small">Средняя оценка: </p>
                            <img src="{{ asset('images/icons/grade_star_fill.svg') }}" alt="icon" class="icon-24">
                            <p class="text-small">{{ number_format($averageRating, 1) }}</p>
                        </div>

                    </div>
                </div>
            </div>

            <div class="profile-sub-action">
                @auth
                    @if (Auth::id() !== $user->id)
                        <button class="primary-btn {{ $isSub ? 'subscribed-btn' : '' }}"
                                id="subscribeBtn"
                                data-nickname="{{ $user->nickname }}"
                                data-issub="{{ $isSub ? 'true' : 'false' }}">
                            @if($isSub)
                                <p>Вы подписаны</p>
                                <img src="{{ asset('images/icons/check-gray.svg') }}" alt="✓" class="icon-24">
                            @else
                                <p>Подписаться</p>
                            @endif
                        </button>
                    @endif
                    @if (auth()->user()->role === 'editor' && auth()->id() === $user->id)
                        <a href="{{ route('editor.dashboard') }}" class="secondary-btn">Панель редактора</a>
                    @endif
                @endauth
            </div>
        </div>

        @if(!empty($user->about))
            <div class="info-block profile-description-wrapper">
                <div class="info-header">
                    <h3>Об авторе</h3>
                </div>

                <div class="h-divider"></div>

                <p>{{ $user->about }}</p>
            </div>
        @endif

        <div class="profile-tabs-wrapper">
            <div class="profile-tab active-tab author-comics-tab" data-tab="author-comics">
                <img src="{{ asset('images/icons/comics-icon-primary.svg') }}" alt="icon" class="icon-24">
                Авторские комиксы
            </div>
            <div class="profile-tab subscriptions-tab" data-tab="subscriptions">
                <img src="{{ asset('images/icons/subs-icon-primary.svg') }}" alt="icon" class="icon-24">
                Подписки
            </div>
        </div>

        <div class="info-block tab-content" data-content="subscriptions">

            <div class="info-header">
                <div class="info-header-title">
                    <h3>Подписки</h3>
                    <p class="text-big subscriptions-count">{{ $user->subscriptions()->count() }}</p>
                </div>
            </div>

            <div class="h-divider"></div>

            @if($user->subscriptions->isEmpty())
                <p>Нет подписок</p>
            @else
                <div class="subscriptions-list">
                    @foreach($user->subscriptions as $subscription)
                        <a href="{{ route('profile.index', $subscription->nickname) }}" class="subscription-item">

                            <div class="subscription-left-data">
                                <div class="subscription-avatar-wrapper">
                                    @if($subscription->icon && Storage::disk('public')->exists($subscription->icon))
                                        <img src="{{ Storage::url($subscription->icon) }}" alt="avatar">
                                    @else
                                        <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="default avatar">
                                    @endif
                                </div>

                                <div class="subscription-username-data">
                                    <p class="text-medium">{{ '@' . ($subscription->nickname) }}</p>
                                    <p class="text-hint subscription-name">{{ $subscription->name }} {{ $subscription->last_name }}</p>
                                </div>
                            </div>

                            <div class="subscription-right-data">
                                <div class="subscription-comics-count subscription-wrapper-flex">
                                    <p class="text-small">Комиксов: {{ $subscription->comics_count }}</p>
                                </div>

                                <div class="subscription-subs-count subscription-wrapper-flex">
                                    <p class="text-small">Подписчиков: {{$subscription->subscribers()->count()}}</p>
                                </div>

                                <div class="subscription-avg-grade subscription-wrapper-flex">
                                    <img src="{{ asset('images/icons/grade_star_fill.svg') }}" alt="icon" class="icon-24">
                                    <p class="text-small">{{ number_format($subscription->average_rating, 1) }}</p>
                                </div>
                            </div>

                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="info-block tab-content" data-content="author-comics">

            <div class="info-header">
                <div class="info-header-title">
                    <h3>Авторские комиксы</h3>
                    <p class="text-big author-comics-count">{{$comics->count()}}</p>
                </div>

                <div class="author-comics-action">
                    @auth
                        @if (Auth::id() == $user->id)
                            <a href="{{ route('user.author_comics', Auth::user()->nickname) }}" class="tertiary-btn">
                                Мои комиксы
                                <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                            </a>

                            <a href="{{ route('user.create_author_comics') }}" class="primary-btn">
                                Новый комикс
                                <img src="{{ asset('images/icons/plus-icon-white.svg') }}" class="icon-24" alt="edit-icon">
                            </a>
                        @else
{{--                            Все комиксы автора!!!--}}
{{--                            <a href="{{ route('', Auth::user()->nickname) }}" class="tertiary-btn">--}}
{{--                                Все комиксы--}}
{{--                                <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">--}}
{{--                            </a>--}}
                        @endif
                    @endauth
                </div>
            </div>

            <div class="h-divider"></div>

            <div class="comics-list-wrapper">
                <button class="scroll-btn scroll-left" style="display: none;">
                    <img src="{{ asset('images/icons/arrow-full-left-white.svg') }}" alt="left" class="scroll-icon">
                </button>
                <div class="comics-scroll-container">
                    @forelse ($comics as $comic)
                        <div class="comic">
                            <a href="{{ route('author_comic', $comic->slug) }}">
                                <div class="comic-cover-wrapper">
                                    <img src="{{ $comic->cover ? Storage::url($comic->cover) : asset('images/default_template/comics.svg') }}"
                                         alt="{{ $comic->name }}" class="comic-cover">
                                </div>
                            </a>

                            <div class="comic-tags-wrapper" data-genres="{{ $comic->genres->pluck('name')->join(',') }}">
                                @foreach ($comic->genres as $genre)
                                    <p class="text-hint comic-tag">{{ $genre->name }}</p>
                                @endforeach
                            </div>

                            <div class="comic-title">
                                <p class="text-big">{{ $comic->name }}</p>
                                <p class="comic-author-text text-small">{{ '@' }}{{ $comic->createdBy->nickname }}</p>
                            </div>

                            <div class="comic-avg-grade">
                                <img src="{{ asset('images/icons/grade_star_fill.svg') }}" alt="icon" class="icon-24">
                                <p class="text-small">{{ number_format($comic->average_assessment ?? 0, 1) }}</p>
                            </div>
                        </div>
                    @empty
                        <p>Упс, кажется ничего нет...</p>
                    @endforelse
                </div>
                <button class="scroll-btn scroll-right" style="display: none;">
                    <img src="{{ asset('images/icons/arrow-full-right-white.svg') }}" alt="right" class="icon-24">
                </button>
            </div>
        </div>
    </div>

@endsection

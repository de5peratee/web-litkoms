@extends('layouts.app')

@section('title', $user->nickname)

@section('content')
    @vite(['resources/css/profile.css'])
    @vite(['resources/js/toggleSubscription.js'])


    <div class="profile-container">
        <div class="profile-header">
            <img src="{{ asset('images/icons/hw/hw_brush.svg') }}" alt="icon" class="hw-abstract brush-icon">
            <img src="{{ asset('images/icons/hw/hw_draw_pen.svg') }}" alt="icon" class="hw-abstract draw_pen-icon">
            <img src="{{ asset('images/icons/hw/hw_ruler.svg') }}" alt="icon" class="hw-abstract ruler-icon">

            <div class="profile-user-data">
                @if($user->icon && Storage::exists($user->icon))
                    <div class="profile-user-avatar-wrapper">
                        <img src="{{ Storage::url($user->icon) }}" alt="{{ $user->icon }}">
                    </div>
                @else
                    <div class="profile-user-avatar-wrapper">
                        <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="ava_cover">
                    </div>
                @endif

                <div class="profile-text-data">
                    <h3>{{ $user->name }} {{ $user->last_name }}</h3>

                    <div class="profile-secondary-text-data">
                        <p class="text-medium">{{ '@' }}{{ $user->nickname }}</p>
                        <p class="text-medium dot">·</p>
                        <p class="text-medium profile-email">{{ $user->email }}</p>
                    </div>

                    <div class="user-status-bar">

                        <p class="user-subscribers">Подписчиков: {{ $user->subscribers()->count() }}</p>
                        <div class="v-divider"></div>
                        <div class="user-avg-grade">
                            <p>Средняя оценка: </p>
                            <img src="{{ asset('images/icons/star.svg') }}" alt="icon" class="icon-24">
                            <p>x.x</p>
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
                                <img src="{{ asset('images/icons/check-gray.svg') }}" alt="✓" class="icon-24">
                                <span>Вы подписаны</span>
                            @else
                                <span>Подписаться</span>
                            @endif
                        </button>
                    @endif
                    @if (auth()->user()->role === 'editor')
                        <a href="{{ route('editor.dashboard')}}" class="secondary-btn">Панель редактора</a>
                    @endif
                @endauth
            </div>
        </div>

        <div class="profile-tabs-wrapper">
            <div class="profile-tab active-tab author-comics-tab">
                <img src="{{ asset('images/icons/comics-icon-primary.svg') }}" alt="icon" class="icon-24">
                Авторские комиксы
            </div>
            <div class="profile-tab subscriptions-tab">
                <img src="{{ asset('images/icons/subs-icon-primary.svg') }}" alt="icon" class="icon-24">
                Подписки
            </div>
        </div>

        <div class="info-block">

            <div class="info-header">
                <p class="text-big">Подписки</p>
                <p class="text-small subscriptions-count">{{ $user->subscriptions()->count() }}</p>
            </div>

            <div class="h-divider"></div>

            @if($user->subscriptions->isEmpty())
                <h3>Нет подписок</h3>
            @else
                <div class="subscriptions-list">
                    @foreach($user->subscriptions as $subscription)
                        <a href="{{ route('profile.index', $subscription->nickname) }}" class="subscription-item">

                            <div class="subscription-left-data">
                                @if($user->icon && Storage::exists($user->icon))
                                    <div class="subscription-avatar-wrapper">
                                        <img src="{{ Storage::url($user->icon) }}" alt="{{ $user->icon }}">
                                    </div>
                                @else
                                    <div class="subscription-avatar-wrapper">
                                        <img src="{{ asset('images/default_template/ava_cover.png') }}"
                                             alt="subscription_ava_cover">
                                    </div>
                                @endif

                                <div class="subscription-username-data">
                                    <p class="text-medium">{{ '@' . ($subscription->nickname) }}</p>
                                    <p class="text-hint subscription-name">{{ $subscription->name }} {{ $subscription->last_name }}</p>
                                </div>
                            </div>

                            <div class="subscription-right-data primary-btn">
                                Профиль
                            </div>

                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="info-block">

            <div class="info-header">
                <p class="text-big">Авторские комиксы</p>
                <p class="text-small author-comics-count">0</p>
            </div>

            <div class="h-divider"></div>

            <h3>Нет комиксов</h3>

            <div class="author-comics-list">
                <a href="{{ route('user.author_comics')}}" class="primary-btn">Все комиксы</a>
            </div>
        </div>

    </div>

@endsection

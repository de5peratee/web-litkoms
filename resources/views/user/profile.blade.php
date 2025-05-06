@extends('layouts.app')

@section('title')
    {{ '@' . ($user->nickname) }}
@endsection
@section('content')
    @vite(['resources/css/profile.css'])

    @auth
    @endauth

    <div class="profile-container">
        <div class="profile-header">
            <img src="{{ asset('images/icons/hw/hw_brush.svg') }}" alt="icon" class="hw-abstract brush-icon">
            <img src="{{ asset('images/icons/hw/hw_draw_pen.svg') }}" alt="icon" class="hw-abstract draw_pen-icon">
            <img src="{{ asset('images/icons/hw/hw_ruler.svg') }}" alt="icon" class="hw-abstract ruler-icon">

            <div class="profile-user-data">
                <div class="avatar_wrapper">
                    <img src="{{ asset('images/nigga.png') }}" alt="avatar">
                </div>

                <div class="profile-text-data">
                    <h3>{{ $user->name }} {{ $user->last_name }}</h3>

                    <div class="profile-secondary-text-data">
                        <p class="text-medium">{{ '@' }}{{ $user->nickname }}</p>
                        <p class="text-medium">·</p>
                        <p class="text-medium profile-email">{{ $user->email }}</p>
                    </div>

                    <div class="user-status-bar">
                        <p>Подписчиков: {{ $user->subscribers()->count() }}</p>

                        <div class="user-avg-grade">
                            <p>Средняя оценка: </p>
                            <img src="{{ asset('images/icons/star.svg') }}" alt="avatar" class="icon-24">
                            <p>x.x</p>
                        </div>
                    </div>

                    @auth
                        @if (Auth::id() !== $user->id)
                            @if ($isSub)
                                <a href="#" class="primary-btn" style="background: lightgray" disabled>
                                    <span>Вы подписаны</span>
                                </a>
                            @else
                                <a href="#" class="primary-btn" onclick="subscribe({{ $user->id }})">
                                    <span>Подписаться</span>
                                </a>
                            @endif
                        @endif
                    @endauth

                    <div>
                        <h4>Подписки ({{ $user->subscriptions()->count() }})</h4>
                        @if($user->subscriptions->isEmpty())
                            <p>Нет подписок</p>
                        @else
                            <div>
                                @foreach($user->subscriptions as $subscription)
                                    <div>
                                        <a href="{{ route('profile.index', $subscription->nickname) }}">
                                            <img src="{{ asset('images/nigga.png') }}" alt="avatar" class="subscription-avatar">
                                            <div>
                                                <p>{{ $subscription->name }} {{ $subscription->last_name }}</p>
                                                <p>{{ '@' . ($subscription->nickname) }}</p>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
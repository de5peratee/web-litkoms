
@extends('layouts.app')

@section('title', 'Имя Фамилия | @ник')

@section('content')
    @vite(['resources/css/profile.css'])

    @auth
{{--        data--}}
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
                    <h3>{{ Auth::user()->name }} {{ Auth::user()->last_name }}</h3>

                    <div class="profile-secondary-text-data">
                        <p class="text-medium">{{ '@' }}{{ Auth::user()->nickname }}</p>
                        <p class="text-medium">·</p>
                        <p class="text-medium profile-email">{{ Auth::user()->email }}</p>
                    </div>

                    <div class="user-status-bar">

                        <p>Подписчиков: n</p>

                        <div class="user-avg-grade">
                            <p>Средняя оценка: </p>
                            <img src="{{ asset('images/icons/star.svg') }}" alt="avatar" class="icon-24">
                            <p>x.x</p>
                        </div>

                    </div>

                    <a href="" class="primary-btn">Подписаться</a>

                </div>
            </div>


        </div>
    </div>
@endsection

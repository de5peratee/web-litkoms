@extends('layouts.app')

@section('title', 'Настройки профиля')

@section('content')
    @vite(['resources/css/inputs.css'])
    @vite(['resources/css/user/settings.css'])
    @vite(['resources/js/user/settings.js'])

    <div class="user-settings-container">

        <div class="info-block header-block">
            <div class="info-header">
                <div class="info-header-title">
                    <img src="{{ asset('images/icons/hw/user-settings-icon.svg') }}" alt="icon" class="icon-32">
                    <h3>Настройки профиля</h3>
                </div>
                <a href="{{ route('profile.index', Auth::user()->nickname) }}" class="primary-btn">Перейти в профиль</a>
            </div>
        </div>

        <div class="info-block">
            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="lit-form">
                @csrf
                @method('PUT')

                <div class="lit-field-group-flex">
                    <div class="lit-field-group">
                        <div class="lit-field">
                            <div class="user-settings-avatar-wrapper" data-default-avatar="{{ $user->icon ? 'custom' : 'default' }}">
                                <img src="{{ asset('images/icons/load-white-icon.svg') }}" class="icon-24 hover-icon" alt="change cover icon">

                                @if($user->icon && Storage::disk('public')->exists($user->icon))
                                    <img src="{{ Storage::url($user->icon) }}" alt="avatar" class="avatar-image">
                                @else
                                    <img src="{{ asset('images/default_template/ava_cover.png') }}" alt="default avatar" class="avatar-image">
                                @endif
                            </div>

                            @error('icon')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror

                            <input type="file" name="icon" id="custom-icon" class="custom-icon-input" style="display: none;" accept="image/*">
                            <input type="hidden" name="selected_avatar" id="selected-avatar" value="">
                        </div>

                        <div class="avatar-picker-container">
                            <div class="h-divider"></div>
                            <div class="avatar-picker-title">
                                <p class="text-big">Доступные аватарки</p>
                            </div>

                            @php
                                $defaultAvatars = [
                                    'lit_avatars/lit-ava-1.png',
                                    'lit_avatars/lit-ava-2.png',
                                    'lit_avatars/lit-ava-3.png',
                                    'lit_avatars/lit-ava-4.png',
                                    'lit_avatars/lit-ava-5.png',
                                ];
                            @endphp

                            <div class="avatar-picker-list-flex">
                                @foreach ($defaultAvatars as $avatar)
                                    <div class="default-avatar-pick-wrapper-flex" data-avatar="{{ $avatar }}">
                                        <div class="default-avatar-pick-wrapper">
                                            <img src="{{ asset('images/' . $avatar) }}" alt="default-avatar">
                                        </div>
                                        <div class="active-avatar-text-tag hidden">
                                            <p class="text-hint">Выбрано</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>

                    <div class="lit-field-group">
                        <div class="lit-form-row">
                            <div class="lit-field">
                                <label for="nickname">Никнейм</label>
                                <input type="text" name="nickname" id="nickname" value="{{ old('nickname', $user->nickname) }}" class="form-control">
                                @error('nickname')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="lit-field">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control">
                                @error('email')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="lit-form-row">
                            <div class="lit-field">
                                <label for="name">Имя</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-control">
                                @error('name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="lit-field">
                                <label for="last_name">Фамилия</label>
                                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" class="form-control">
                                @error('last_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="lit-field">
                            <label for="birth_date">Дата рождения</label>
                            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $user->birth_date) }}" class="form-control">
                            @error('birth_date')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="lit-field">
                            <label for="about">О себе</label>
                            <textarea name="about" id="about" class="form-control" placeholder="Напишите информацию о себе...">{{ old('about', $user->about) }}</textarea>
                            @error('about')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="lit-form-row">
                            <div class="lit-field">
                                <label for="password">Новый пароль</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Введите новый пароль..." autocomplete="new-password">
                                @error('password')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="lit-field">
                                <label for="password_confirmation">Подтверждение пароля</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Введите новый пароль..." autocomplete="new-password">
                                @error('password_confirmation')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="secondary-btn">Сохранить изменения</button>
                    </div>
                </div>

            </form>
        </div>

        <div class="toaster-message" id="toaster">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        </div>

    </div>

    <script>
        window.assetBasePath = "{{ asset('images') }}";
    </script>
@endsection

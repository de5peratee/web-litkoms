@extends('layouts.app')

@section('title', 'Настройки профиля')

@section('content')
    @vite(['resources/css/user/settings.css'])

    <div class="user-settings-container">
        <h2>Настройки профиля</h2>

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

        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nickname">Никнейм</label>
                <input type="text" name="nickname" id="nickname" value="{{ old('nickname', $user->nickname) }}" class="form-control">
                @error('nickname')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control">
                @error('email')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="name">Имя</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-control">
                @error('name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="last_name">Фамилия</label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" class="form-control">
                @error('last_name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="birth_date">Дата рождения</label>
                <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $user->birth_date) }}" class="form-control">
                @error('birth_date')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="about">О себе</label>
                <textarea name="about" id="about" class="form-control">{{ old('about', $user->about) }}</textarea>
                @error('about')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="icon">Иконка профиля</label>
                @if ($user->icon)
                    <div>
                        <img src="{{ Storage::url($user->icon) }}" alt="Иконка профиля" class="profile-icon">
                    </div>
                @endif
                <input type="file" name="icon" id="icon" class="form-control">
                @error('icon')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="head_profile">Обложка профиля</label>
                @if ($user->head_profile)
                    <div>
                        <img src="{{ Storage::url($user->head_profile) }}" alt="Обложка профиля" class="profile-header">
                    </div>
                @endif
                <input type="file" name="head_profile" id="head_profile" class="form-control">
                @error('head_profile')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Новый пароль</label>
                <input type="password" name="password" id="password" class="form-control">
                @error('password')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Подтверждение пароля</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                @error('password_confirmation')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
        </form>
    </div>
@endsection
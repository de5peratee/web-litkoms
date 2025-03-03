<!-- resources/views/home.blade.php -->
@extends('layouts.app')  <!-- Используем главный шаблон -->

@section('title', 'Профиль')  <!-- Устанавливаем название страницы -->

@section('content')
    <div class="profile-container">
        <div class="profile-header">

            <div class="profile-data">
                <div class="avatar">
                    <img src="" alt="">
                </div>

                <div class="text-data">
                    <h3>Владислав М.В.</h3>
                    <p>melnichuk1712@mail.ru</p>
                </div>
            </div>

            <div class="about">
                <div class="subs">
                    Подписчики: 11
                </div>

                <div class="avg-grade">
                    Средняя оценка: 4.5
                </div>

                <a href="">Подписаться</a>
            </div>

        </div>

        <div class="profile-tabs">
            <div class="tab">Таб1</div>
            <div class="tab">Таб2</div>
        </div>

        <div class="profile-data-container">
            Контенер
        </div>
    </div>
@endsection

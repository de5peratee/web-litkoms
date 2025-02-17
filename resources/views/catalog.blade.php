<!-- resources/views/home.blade.php -->
@extends('layouts.app')  <!-- Используем главный шаблон -->

@section('title', 'Каталог')  <!-- Устанавливаем название страницы -->

@section('content')
    <section class="catalog">
        <div class="container">

            <!-- Иконка перед заголовком -->
            <div class="catalog-icon">
                <img src="{{ asset('images/icons/library.svg') }}" alt="Catalog Icon">
            </div>

            <!-- Заголовок каталога -->
            <header class="catalog-header">
                <h1 class="catalog-title">Каталог</h1>
            </header>

            <!-- Блок поиска -->
            <div class="catalog-search">
                <input type="text" class="search-input" placeholder="Поиск по каталогу...">
{{--                <button class="search-button">Поиск</button>--}}
            </div>

            <!-- Чекбоксы для сортировки -->
            <div class="catalog-sort">
                <label><input type="checkbox" class="sort-checkbox" /> Книги</label>
                <label><input type="checkbox" class="sort-checkbox" /> Комиксы</label>
            </div>

            <!-- Блок фильтров по категориям -->
            <div class="catalog-filters">
                <div class="filter-tags">
                    <span class="filter-tag">Приключения</span>
                    <span class="filter-tag">Научная фантастика</span>
                    <span class="filter-tag">Фэнтези</span>
                    <span class="filter-tag">Мистика</span>
                </div>
            </div>

            <!-- Основной контент каталога -->
            <div class="catalog-content">
                <p>В скором времени мы добавим товары и другие элементы каталога.</p>
            </div>

        </div>
    </section>
@endsection

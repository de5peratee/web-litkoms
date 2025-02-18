<!-- resources/views/home.blade.php -->
@extends('layouts.app')  <!-- Используем главный шаблон -->

@section('title', 'Каталог')  <!-- Устанавливаем название страницы -->

@section('content')
    <section class="catalog">
        <div class="container">

            <div class="library-header">

                <div class="search-controller">
                    <div class="catalog-icon">
                        <img src="{{ asset('images/icons/library.svg') }}" alt="Catalog Icon">
                    </div>

                    <h2>Каталог</h2>

                    <div class="library-search">
                        <input type="text" class="search-input" placeholder="Поиск по каталогу...">
                        {{--                <button class="search-button">Поиск</button>--}}
                    </div>

                    <!-- Чекбоксы для сортировки -->
                    <div class="catalog-sort">
                        <label><input type="checkbox" class="sort-checkbox" /> Книги</label>
                        <label><input type="checkbox" class="sort-checkbox" /> Комиксы</label>
                    </div>
                </div>

                <div class="category-controller">
                    <!-- Блок фильтров по категориям -->
                    <div class="filters">
                        <span class="filter-tag">Приключения</span>
                        <span class="filter-tag">Научная фантастика</span>
                        <span class="filter-tag">Фэнтези</span>
                        <span class="filter-tag">Мистика</span>
                    </div>
{{--                    <div class="controll-category">--}}
{{--                        --}}
{{--                    </div>--}}
                </div>

            </div>

            <div class="library-content">
                <p>В скором времени мы добавим товары и другие элементы каталога.</p>
            </div>

        </div>
    </section>
@endsection

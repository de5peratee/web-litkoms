<!-- resources/views/home.blade.php -->
@extends('layouts.app')  <!-- Используем главный шаблон -->

@section('title', 'Каталог')  <!-- Устанавливаем название страницы -->

@section('content')
    <section class="catalog">
        <div class="library-header">

            <div class="search-controller">
                <div class="catalog-icon">
                    <img src="{{ asset('images/icons/library.svg') }}" alt="Catalog Icon">
                </div>

                <h2>Каталог</h2>

                <div class="library-search">
                    <input type="text" class="search-input" placeholder="Поиск по каталогу...">
                </div>

                <!-- Чекбоксы для сортировки -->
                <div class="catalog-sort">
                    <label><input type="checkbox" class="sort-checkbox text-small" /> Книги</label>
                    <label><input type="checkbox" class="sort-checkbox text-small" /> Комиксы</label>
                </div>
            </div>

            <div class="filters">
                <span class="filter-tag text-medium choice">Приключения</span>
                <span class="filter-tag text-medium">Научная фантастика</span>
                <span class="filter-tag text-medium">Фэнтези</span>
                <span class="filter-tag text-medium">Мистика</span>
            </div>

        </div>

        <div class="library-content">
            <div class="book">
                <img src="{{ asset('images/comics_cover.png') }}" alt="" class="comics_cover">
                <div class="description">
                    <div class="categories">
                        <div class="category">Хоррор</div>
                        <div class="category">Ужасы</div>
                        <div class="category">Триллер</div>
                    </div>
                    <div class="about">
                        <p class="title text-big">BLACK MIRROR</p>
                        <p class="author text-small">Мухва</p>
                    </div>
                </div>
            </div>
            <div class="book">
                <img src="{{ asset('images/comics_cover.png') }}" alt="" class="comics_cover">
                <div class="description">
                    <div class="categories">
                        <div class="category">Хоррор</div>
                        <div class="category">Ужасы</div>
                        <div class="category">Триллер</div>
                    </div>
                    <div class="about">
                        <p class="title text-big">BLACK MIRROR</p>
                        <p class="author text-small">Мухва</p>
                    </div>
                </div>
            </div>
            <div class="book">
                <img src="{{ asset('images/comics_cover.png') }}" alt="" class="comics_cover">
                <div class="description">
                    <div class="categories">
                        <div class="category">Хоррор</div>
                        <div class="category">Ужасы</div>
                        <div class="category">Триллер</div>
                    </div>
                    <div class="about">
                        <p class="title text-big">BLACK MIRROR</p>
                        <p class="author text-small">Мухва</p>
                    </div>
                </div>
            </div>
            <div class="book">
                <img src="{{ asset('images/comics_cover.png') }}" alt="" class="comics_cover">
                <div class="description">
                    <div class="categories">
                        <div class="category">Хоррор</div>
                        <div class="category">Ужасы</div>
                        <div class="category">Триллер</div>
                    </div>
                    <div class="about">
                        <p class="title text-big">BLACK MIRROR</p>
                        <p class="author text-small">Мухва</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

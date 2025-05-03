<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CatalogController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//Главная
Route::get('/', function () {
    return view('home');
})->name('home');

// Основная страница библиотеки
Route::get('/library', [CatalogController::class, 'index'])
    ->name('library.index');

// Страница книги
Route::get('/library/{id}', [CatalogController::class, 'get_book'])
    ->name('library.get_book');

Route::get('/events', function () {
    return view('events.index');
})->name('events.index');

Route::get('/event', function () {
    return view('events.event');
})->name('events.get_event');


// Мероприятия
//Route::get('/events', [EventsController::class, 'index'])
//    ->name('events.index');
//
//Route::get('/events/{id}', [EventsController::class, 'get_event'])
//    ->name('events.get_event');


Route::get('/news', function () {
    return view('news');
})->name('news');

Route::get('/authors_comics_landing', function () {
    return view('authors_comics_landing');
})->name('authors_comics_landing');

Route::get('/litar_landing', function () {
    return view('litar_landing');
})->name('litar_landing');

//Route::get('/auth', function () {
//    return view('auth');
//})->name('auth');



Route::get('/auth', [RegisterController::class, 'index'])
    ->name('auth.index')
    ->middleware('guest');

Route::post('/auth/register', [RegisterController::class, 'store'])->name('register.store');

Route::post('/auth/login', [LoginController::class, 'loginUser'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/profile', function () {
    return view('user.profile');
})->name('profile');

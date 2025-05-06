<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;

use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
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


Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');



// Форма авторизации
Route::get('/auth', [AuthController::class, 'index'])->name('auth')->middleware('guest');;

// Обработка форм
Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


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


//Route::get('/profile', function () {
//    return view('user.profile');
//})->name('profile');

Route::get('/profile/{nickname}', [ProfileController::class, 'index'])->name('profile.index');